<?php

namespace App\Livewire\Payroll;

use App\Exports\PayrollReportExport;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payroll;
use App\Models\SmsLog; // Optional if you track SMS logs
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class Report extends Component
{
    use WithPagination;

    public $month;
    public $department;
    public $status;
    public $search;
    public $perPage = 6;

    protected $queryString = ['month', 'department', 'status', 'search', 'perPage' => ['except' => 10]];

    public function updating($property)
    {
        if (in_array($property, ['month', 'department', 'status', 'search'])) {
            $this->resetPage();
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function exportPdf()
    {
        $query = Payroll::with(['employee','smsLogs']);

        if ($this->month) {
            $query->whereMonth('payroll_month', '=', $this->month);
        }

        if ($this->department) {
            $query->whereHas('employee', fn($q) => $q->where('department', 'like', "%{$this->department}%"));
        }

        if ($this->status) {
            $query->where('payroll_status', $this->status);
        }

        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")->orWhere('employee_id', 'like', "%{$this->search}%");
            });
        }

        $payrolls = $query->latest()->get();

        $pdf = Pdf::loadView('pdf.payroll-report', [
            'payrolls' => $payrolls,
            'month' => $this->month ? date('F', mktime(0, 0, 0, $this->month, 1)) : 'All Months',
            'department' => $this->department,
            'status' => $this->status,
        ])->setPaper('a4', 'landscape');

        $filename = 'Payroll_Report_' . now()->format('Y_m_d_His') . '.pdf';
        return response()->streamDownload(fn() => print $pdf->output(), $filename);
    }

    public function export()
    {
        $filters = [
            'month' => $this->month,
            'department' => $this->department,
            'status' => $this->status,
            'search' => $this->search,
        ];

        $fileName = 'Payroll_Report_' . now()->format('Y_m_d_His') . '.xlsx';
        return Excel::download(new PayrollReportExport($filters), $fileName);
    }

    public function render()
    {
        $query = Payroll::with(['employee', 'smsLogs']);

        $countPayrollWithSuccessSms = Payroll::whereHas('smsLogs', fn(Builder $q) => $q->where('status', 'success'))->count();

        if ($this->month) {
            $query->whereMonth('payroll_month', '=', $this->month);
        }

        if ($this->department) {
            $query->whereHas('employee', fn($q) => $q->where('department', 'like', "%{$this->department}%"));
        }

        if ($this->status) {
            $query->where('payroll_status', $this->status);
        }

        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")->orWhere('employee_id', 'like', "%{$this->search}%");
            });
        }

        $newQuery = clone $query; // clone original filtered query

        $successSm = (clone $newQuery)->whereHas('smsLogs', fn(Builder $q) => $q->where('status', 'success'))->count();

        $failedSm = (clone $newQuery)->whereHas('smsLogs', fn(Builder $q) => $q->where('status', '!=', 'success'))->count();

        $totalFiltered = $newQuery->count();

        $smsNotSent = $totalFiltered - $successSm - $failedSm;

        $payrolls = $query->latest()->paginate($this->perPage);

        $totalPaid = Payroll::where('payroll_status', 'paid')->sum('net_pay');
        $totalUnpaid = Payroll::where('payroll_status', '!=', 'paid')->sum('net_pay');
        $totalEmployees = Payroll::distinct('employee_id')->count('employee_id');
        $totalSmsSent = SmsLog::count(); // All-time total, not filtered

        return view('livewire.payroll.report', [
            'payrolls' => $payrolls,
            'totalPaid' => $totalPaid,
            'totalUnpaid' => $totalUnpaid,
            'totalEmployees' => $totalEmployees,
            'totalSmsSent' => $totalSmsSent,
            'successSms' => $successSm,
            'smsNotSent' => $smsNotSent,
            'failedSms' => $failedSm,
        ]);
    }
}
