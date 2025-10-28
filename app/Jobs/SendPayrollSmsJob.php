<?php

namespace App\Jobs;

use App\Models\Employee;
use App\Models\Payroll;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendPayrollSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $employeeId;
    protected $payrollId;

    public function __construct($employeeId, $payrollId)
    {
        $this->employeeId = $employeeId;
        $this->payrollId = $payrollId;
    }

    public function handle()
    {
        DB::beginTransaction();

        try {
            Log::info("SendPayrollSmsJob started", [
                'employee_id' => $this->employeeId,
                'payroll_id' => $this->payrollId
            ]);

            $employee = Employee::find($this->employeeId);
            $payroll = Payroll::find($this->payrollId);

            if (!$employee) {
                Log::error("SendPayrollSmsJob: Employee not found", ['employee_id' => $this->employeeId]);
                DB::rollBack();
                return;
            }

            if (!$payroll) {
                Log::error("SendPayrollSmsJob: Payroll not found", ['payroll_id' => $this->payrollId]);
                DB::rollBack();
                return;
            }

            $smsService = new SmsService();

            $monthYear = \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y');

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
            $message .= '— HR Department';

            // Check if phone number exists and is valid
            if (empty($employee->phone)) {
                Log::error("SendPayrollSmsJob: Phone number missing for employee", [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name
                ]);
                DB::rollBack();
                return;
            }

            $phone = $smsService->formatPhoneNumber($employee->phone);

            $smsResponse = $smsService->sendSms($phone, $message);


            // Create SmsLog with error handling
            try {
                SmsLog::create([
                    'payroll_id' => $payroll->id,
                    'phone' => $phone,
                    'message' => $message,
                    'status' => $smsResponse['status'] ?? 'failed',
                    'response' => json_encode($smsResponse),
                ]);


                // Update payroll status to mark SMS as sent

            } catch (\Exception $dbException) {
                Log::error("Failed to create SmsLog: " . $dbException->getMessage());
                // Don't fail the entire job just because logging failed
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("SendPayrollSmsJob failed: " . $e->getMessage(), [
                'employee_id' => $this->employeeId,
                'payroll_id' => $this->payrollId,
                'exception' => $e->getTraceAsString()
            ]);

            // Re-throw the exception to mark job as failed
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error("SendPayrollSmsJob failed completely: " . $exception->getMessage(), [
            'employee_id' => $this->employeeId,
            'payroll_id' => $this->payrollId,
            'exception_trace' => $exception->getTraceAsString()
        ]);
    }
}
