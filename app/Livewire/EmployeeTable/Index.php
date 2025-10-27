<?php

namespace App\Livewire\EmployeeTable;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $department = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'department' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    protected $listeners = ['employeeImported' => '$refresh'];

    
    public function refreshTable()
    {
        // This will force a re-render
        $this->render();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDepartment()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Employee::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('employee_id', 'like', "%{$this->search}%")
                    ->orWhere('position', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->department)) {
            $query->where('department', $this->department);
        }

        $employees = $query->orderBy('id', 'asc')->paginate($this->perPage);
        $departments = Employee::select('department')->distinct()->pluck('department');

        return view('livewire.employee-table.index', compact('employees', 'departments'));
    }
}
