<?php

namespace App\Imports;

use App\Jobs\SendPayrollSmsJob;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\SmsLog;
use App\Services\SmsService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class PayrollImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $errors = [];
    private $rowCount = 0;
    private $successCount = 0;
    protected $payrollMonth;
    protected $smsService;

    public function __construct($payrollMonth)
    {
        $this->payrollMonth = $payrollMonth;
        $this->smsService = new SmsService(); // Initialize SMS service
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Find employee by ID
        $employee = Employee::where('employee_id', $row['employee_id'])->first();
        if (!$employee) {
            $this->errors[] = [
                'row' => $this->rowCount + 1,
                'employee_id' => $row['employee_id'] ?? null,
                'message' => 'Employee not found',
            ];
            return null;
        }

        // Prevent duplicate for same month
        if (
            Payroll::where('employee_id', $employee->id)
                ->where('payroll_month', $this->payrollMonth . '-01')
                ->exists()
        ) {
            $this->errors[] = [
                'row' => $this->rowCount + 1,
                'employee_id' => $employee->employee_id,
                'message' => 'Payroll already exists for this month',
            ];
            return null;
        }

        $this->successCount++;

        // Create Payroll record
        $payroll = new Payroll([
            'employee_id' => $employee->id,
            'basic_salary' => $row['basic_salary'] ?? 0,
            'taxable_transport' => $row['taxable_transport'] ?? 0,
            'overtime' => $row['overtime'] ?? 0,
            'department_allowance' => $row['department_allowance'] ?? 0,
            'position_allowance' => $row['position_allowance'] ?? 0,
            'gross_earning' => $row['gross_earning'] ?? 0,
            'pension_school' => $row['pension_school'] ?? 0,
            'income_tax' => $row['income_tax'] ?? 0,
            'staff_pension' => $row['staff_pension'] ?? 0,
            'advance_loan' => $row['advance_loan'] ?? 0,
            'net_pay' => $row['net_pay'] ?? 0,
            'labor_association' => $row['labor_association'] ?? 0,
            'social_committee' => $row['social_committee'] ?? 0,
            'allowance' => $row['allowance'] ?? 0,
            'payroll_month' => $this->payrollMonth . '-01',
            'payroll_date' => now(),
            'payroll_status' => 'pending',
        ]);

        $payroll->save();

        // Send detailed SMS after payroll record creation
        // $this->sendSalarySms($employee, $payroll);
        SendPayrollSmsJob::dispatch($employee->id, $payroll->id);

        return $payroll;
    }

    protected function sendSalarySms($employee, $payroll)
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
        $phone = $this->smsService->formatPhoneNumber($employee->phone);

        // Send SMS and update payroll SMS status
        $smsResponse = $this->smsService->sendSms($phone, $message);

        SmsLog::create([
            'payroll_id' => $payroll->id,
            'phone' => $phone,
            'message' => $message,
            'status' => $smsResponse['status'],
            'response' => json_encode($smsResponse),
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required',
            'basic_salary' => 'required|numeric',
            'net_pay' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'employee_id.required' => 'Employee ID is required on row :attribute',
            'basic_salary.required' => 'Basic salary is required on row :attribute',
            'net_pay.required' => 'Net pay is required on row :attribute',
        ];
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getTotalCount()
    {
        return $this->rowCount;
    }
}
