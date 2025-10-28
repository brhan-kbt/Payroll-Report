<?php

namespace App\Exports;

use App\Models\Payroll;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PayrollReportExport implements FromView
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Payroll::with(['employee','smsLogs']);

        if ($this->filters['month']) {
            $query->whereMonth('payroll_month', $this->filters['month']);
        }

        if ($this->filters['department']) {
            $query->whereHas('employee', fn($q) =>
                $q->where('department', 'like', "%{$this->filters['department']}%"));
        }

        if ($this->filters['status']) {
            $query->where('payroll_status', $this->filters['status']);
        }

        if ($this->filters['search']) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->filters['search']}%")
                    ->orWhere('employee_id', 'like', "%{$this->filters['search']}%");
            });
        }

        $payrolls = $query->latest()->get();

        return view('exports.payroll-report', [
            'payrolls' => $payrolls
        ]);
    }
}
