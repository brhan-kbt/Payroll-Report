<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\OrderStatusNotification;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SmsService
{
    protected $apiUrl;
    protected $token;
    protected $identifierId;
    protected $senderName;
    protected $callbackUrl;

    public function __construct()
    {
        // Load AFRO SMS settings from .env
        $this->apiUrl = env('AFRO_BASE_URL') . '/send';
        $this->token = env('AFRO_API_KEY');
        $this->identifierId = env('AFRO_IDENTIFIER_ID');
        $this->senderName = env('AFRO_SENDER_NAME');
        $this->callbackUrl = env('AFRO_CALLBACK_URL', ''); // optional
    }

    public function sendSms($to, $message, $templateId = null)
    {
        if ($this->token && $this->apiUrl) {
            $response = $this->afroSendSingleSms($to, $message);
            // Log::info('SMS Response: ' . json_encode($response));

            if ($this->isSuccess($response)) {
                return [
                    'status' => 'success',
                    'message' => 'SMS sent successfully',
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Failed to send SMS',
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'SMS configuration is not active.',
            ];
        }
    }

    public function sendSalarySms($employee, $payroll)
    {
        $monthYear = Carbon::parse($payroll->payroll_month)->format('F Y');

        $message = "Hello {$employee->name},\n\n";
        $message .= "Here are your salary details for {$monthYear}:\n\n";
        $message .= '• Basic Salary: ' . number_format($payroll->basic_salary, 2) . " ETB\n";
        $message .= '• Transport Allowance: ' . number_format($payroll->taxable_transport, 2) . " ETB\n";
        $message .= '• Overtime: ' . number_format($payroll->overtime, 2) . " ETB\n";
        $message .= '• Department Allowance: ' . number_format($payroll->department_allowance, 2) . " ETB\n";
        $message .= '• Position Allowance: ' . number_format($payroll->position_allowance, 2) . " ETB\n";
        $message .= '• Gross Earnings: ' . number_format($payroll->gross_earning, 2) . " ETB\n\n";

        $message .= "Deductions:\n";
        $message .= '• Pension (School): ' . number_format($payroll->pension_school, 2) . " ETB\n";
        $message .= '• Staff Pension: ' . number_format($payroll->staff_pension, 2) . " ETB\n";
        $message .= '• Income Tax: ' . number_format($payroll->income_tax, 2) . " ETB\n";
        $message .= '• Labor Association: ' . number_format($payroll->labor_association, 2) . " ETB\n";
        $message .= '• Social Committee: ' . number_format($payroll->social_committee, 2) . " ETB\n";
        $message .= '• Advance Loan: ' . number_format($payroll->advance_loan, 2) . " ETB\n\n";

        $message .= 'Additional Allowances: ' . number_format($payroll->allowance, 2) . " ETB\n\n";
        $message .= 'Net Pay: ' . number_format($payroll->net_pay, 2) . " ETB\n\n";

        $message .= "We appreciate your hard work and dedication. Thank you for being an important part of our team.\n\n";
        $message .= '— Finance Department';

        // Format phone number
        $phone = $this->formatPhoneNumber($employee->phone);

        // Send SMS and update payroll SMS status
        $smsResponse = $this->sendSms($phone, $message);

        SmsLog::create([
            'payroll_id' => $payroll->id,
            'phone' => $phone,
            'message' => $message,
            'status' => $smsResponse['status'],
            'response' => json_encode($smsResponse),
        ]);

        return $smsResponse;
    }

    public function sendBulkSms($allDestinations, $text)
    {
        if ($this->token && $this->apiUrl) {
            $responses = [];
            foreach ($allDestinations as $destination) {
                $responses[] = $this->afroSendSingleSms($destination, $text);
            }
            return $responses;
        } else {
            return [
                'status' => 'error',
                'message' => 'SMS configuration is not active.',
            ];
        }
    }

    private function isSuccess($response)
    {
        if (is_string($response) && strpos($response, 'Error') === false) {
            return true;
        }
        if (is_array($response) && isset($response['acknowledge']) && $response['acknowledge'] == 'success') {
            return true;
        }
        return false;
    }

    public function afroSendSingleSms($to, $message)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->timeout(60)->post($this->apiUrl, [
            'from' => $this->identifierId,
            'sender' => $this->senderName,
            'to' => $to,
            'message' => $message,
            'callback' => $this->callbackUrl,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return [
                'status' => 'error',
                'message' => 'HTTP Error: ' . $response->status(),
            ];
        }
    }

    public function afroSendBulkSms($to, $message)
    {
        $responses = [];
        foreach ($to as $destination) {
            $responses[] = $this->afroSendSingleSms($destination, $message);
        }
        return $responses;
    }

    public function formatPhoneNumber($number, $countryCode = '251')
    {
        $number = preg_replace('/\D/', '', $number);
        $number = ltrim($number, '0');

        if (strpos($number, $countryCode) !== 0) {
            $number = $countryCode . $number;
        }

        return '+' . $number;
    }
}
