<?php

namespace App\Imports;

use App\Models\Payroll;
use App\Models\Employee;
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

    public function __construct($payrollMonth)
    {
        $this->payrollMonth = $payrollMonth;
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

        // $payrollMonth = isset($row['payroll_month']) ? Carbon::parse($row['payroll_month'])->format('Y-m-d') : Carbon::now()->startOfMonth();

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

        return new Payroll([
            'employee_id' => $employee->id,
            'basic_salary' => $row['basic_salary'] ?? 0,
            'taxable_transport' => $row['taxable_transport_allowance'] ?? 0,
            'overtime' => $row['total_overtime'] ?? 0,
            'department_allowance' => $row['department_head_homeroom_allowance'] ?? 0,
            'position_allowance' => $row['position_fuel_allowance'] ?? 0,
            'gross_earning' => $row['gross_earning'] ?? 0,
            'pension_school' => $row['pension_11_school_cont'] ?? 0,
            'income_tax' => $row['income_tax'] ?? 0,
            'staff_pension' => $row['staff_cont_7_pension'] ?? 0,
            'advance_loan' => $row['advance_loan'] ?? 0,
            'net_pay' => $row['net_pay'] ?? 0,
            'labor_association' => $row['labor_association_cont'] ?? 0,
            'social_committee' => $row['contribution_for_social_committee'] ?? 0,
            'allowance' => $row['allowance'] ?? 0,
            'payroll_month' => $this->payrollMonth . '-01',
            'payroll_date' => now(),
            'payroll_status' => 'pending',
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
