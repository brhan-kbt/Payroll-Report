<?php

namespace App\Livewire\Payroll;

use App\Models\Payroll;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollExport;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $month = '';
    public $perPage = 10;
    public $smsMessage = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'month' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = ['payrollImported' => '$refresh'];

    protected $smsService;

    public function __construct()
    {
        $this->smsService = new SmsService(); // Initialize SMS service
    }

    public function mount()
    {
        // Default to last month
        $this->month = Carbon::now()->subMonth()->format('Y-m');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingMonth()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function export()
    {
        $month = $this->month ?: Carbon::now()->format('Y-m');
        return Excel::download(new PayrollExport($this->search, $month), "payroll-{$month}.xlsx");
    }

    public function sendSms($payrollId)
    {
        $payroll = Payroll::find($payrollId);

        $response = $this->smsService->sendSalarySms($payroll->employee, $payroll);
        $this->smsMessage = $response['status'] === 'success' ? 'SMS sent successfully!' : 'Failed to send SMS.';

        $this->dispatch('payrollImported');
        $this->dispatch('payrollSent');
    }
    public function render()
    {
        $query = Payroll::with('employee');

        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('name', 'like', "%{$this->search}%")->orWhere('employee_id', 'like', "%{$this->search}%");
            });
        }

        if ($this->month) {
            $query->whereMonth('payroll_month', Carbon::parse($this->month)->month)->whereYear('payroll_month', Carbon::parse($this->month)->year);
        }

        $payrolls = $query->orderBy('payroll_month', 'desc')->paginate($this->perPage);

        return view('livewire.payroll.index', compact('payrolls'));
    }
}
