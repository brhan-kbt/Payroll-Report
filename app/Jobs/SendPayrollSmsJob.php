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
            Log::info('SendPayrollSmsJob started', [
                'employee_id' => $this->employeeId,
                'payroll_id' => $this->payrollId,
            ]);

            $employee = Employee::find($this->employeeId);
            $payroll = Payroll::find($this->payrollId);

            if (!$employee) {
                Log::error('SendPayrollSmsJob: Employee not found', ['employee_id' => $this->employeeId]);
                DB::rollBack();
                return;
            }

            if (!$payroll) {
                Log::error('SendPayrollSmsJob: Payroll not found', ['payroll_id' => $this->payrollId]);
                DB::rollBack();
                return;
            }

            Log::info('SendPayrollSmsJob: Sending SMS to employee' . $employee->name);
            $smsService = new SmsService();

            $monthYear = \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y');
            // Log::info("SendPayrollSmsJob: Payroll month is {$monthYear}");

            $message = "Hello {$employee->name},\n\n";
            $message .= "Here are your salary details for {$monthYear}:\n\n";

            if (!empty($payroll->basic_salary) && $payroll->basic_salary > 0) {
                $message .= '• Basic Salary: ' . number_format($payroll->basic_salary, 2) . " ETB\n";
            }

            if (!empty($payroll->taxable_transport) && $payroll->taxable_transport > 0) {
                $message .= '• Taxable Transport Allowance: ' . number_format($payroll->taxable_transport, 2) . " ETB\n";
            }

            if (!empty($payroll->overtime) && $payroll->overtime > 0) {
                $message .= '• Total Overtime: ' . number_format($payroll->overtime, 2) . " ETB\n";
            }

            if (!empty($payroll->department_allowance) && $payroll->department_allowance > 0) {
                $message .= '• Department Head / Homeroom Allowance: ' . number_format($payroll->department_allowance, 2) . " ETB\n";
            }

            if (!empty($payroll->position_allowance) && $payroll->position_allowance > 0) {
                $message .= '• Position & Fuel Allowance: ' . number_format($payroll->position_allowance, 2) . " ETB\n";
            }

            if (!empty($payroll->gross_earning) && $payroll->gross_earning > 0) {
                $message .= '• Gross Earnings: ' . number_format($payroll->gross_earning, 2) . " ETB\n\n";
            }

            if (!empty($payroll->pension_school) && $payroll->pension_school > 0) {
                $message .= '• Pension (11%) (School Cont): ' . number_format($payroll->pension_school, 2) . " ETB\n\n";
            }

            $deductions = false;

            if (!empty($payroll->income_tax) && $payroll->income_tax > 0) {
                $message .= "Deductions:\n";
                $message .= '• Income Tax: ' . number_format($payroll->income_tax, 2) . " ETB\n";
            }

            if (!empty($payroll->staff_pension) && $payroll->staff_pension > 0) {
                $message .= '• Staff Cont. (7% Pension) : ' . number_format($payroll->staff_pension, 2) . " ETB\n";
            }

            if (!empty($payroll->advance_loan) && $payroll->advance_loan > 0) {
                $message .= '• Advance & Loan: ' . number_format($payroll->advance_loan, 2) . " ETB\n\n";
            }

            if (!empty($payroll->labor_association) && $payroll->labor_association > 0) {
                $message .= '• 1% Labor Ass. Cont. : ' . number_format($payroll->labor_association, 2) . " ETB\n";
            }

            if (!empty($payroll->social_committee) && $payroll->social_committee > 0) {
                $message .= '• Contribution For Social Committee: ' . number_format($payroll->social_committee, 2) . " ETB\n";
            }

            if (!empty($payroll->allowance) && $payroll->allowance > 0) {
                $message .= '• Allowance: ' . number_format($payroll->allowance, 2) . " ETB\n\n";
            }

            if (!empty($payroll->net_pay) && $payroll->net_pay > 0) {
                $message .= 'Net Pay: ' . number_format($payroll->net_pay, 2) . " ETB\n\n";
            }

            $message .= "We appreciate your hard work and dedication. Thank you for being an important part of our team.\n\n";
            $message .= '— Finance Department';

            // Log::info("SendPayrollSmsJob: SMS message: " . $message);
            // Check if phone number exists and is valid
            if (empty($employee->phone)) {
                Log::error('SendPayrollSmsJob: Phone number missing for employee', [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                ]);
                DB::rollBack();
                return;
            }

            $phone = $smsService->formatPhoneNumber($employee->phone);

            $smsResponse = $smsService->sendSms($phone, $message);

            // Log::info("SendPayrollSmsJob: SMS response: " . json_encode($smsResponse));
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
                Log::error('Failed to create SmsLog: ' . $dbException->getMessage());
                // Don't fail the entire job just because logging failed
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SendPayrollSmsJob failed: ' . $e->getMessage(), [
                'employee_id' => $this->employeeId,
                'payroll_id' => $this->payrollId,
                'exception' => $e->getTraceAsString(),
            ]);

            // Re-throw the exception to mark job as failed
            throw $e;
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('SendPayrollSmsJob failed completely: ' . $exception->getMessage(), [
            'employee_id' => $this->employeeId,
            'payroll_id' => $this->payrollId,
            'exception_trace' => $exception->getTraceAsString(),
        ]);
    }
}
