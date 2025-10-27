<?php

namespace App\Exports;

use App\Models\Payroll;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class PayrollExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $month;

    public function __construct($search = '', $month = '')
    {
        $this->search = $search;
        $this->month = $month;
    }

    public function collection()
    {
        $query = Payroll::with('employee');

        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('employee_id', 'like', "%{$this->search}%");
            });
        }

        if ($this->month) {
            $query->whereMonth('payroll_month', Carbon::parse($this->month)->month)
                  ->whereYear('payroll_month', Carbon::parse($this->month)->year);
        }

        return $query->get()->map(function ($payroll) {
            return [
                'Employee Name' => $payroll->employee->name,
                'Employee ID' => $payroll->employee->employee_id,
                'Month' => Carbon::parse($payroll->payroll_month)->format('F Y'),
                'Gross Salary' => $payroll->gross_earning,
                'Net Salary' => $payroll->net_pay,
            ];
        });
    }

    public function headings(): array
    {
        return ['Employee Name', 'Employee ID', 'Month', 'Gross Salary', 'Net Salary'];
    }
}
