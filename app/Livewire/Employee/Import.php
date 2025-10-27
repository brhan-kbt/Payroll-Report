<?php

namespace App\Livewire\Employee;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;

class Import extends Component
{
    use WithFileUploads;

    public $file;
    public $showModal = false;
    public $importing = false;
    public $importErrors = [];
    public $importStats = [];

    protected $rules = [
        'file' => 'required|file|mimes:xlsx,csv,xls',
    ];

    protected $messages = [
        'file.required' => 'Please select a file to import.',
        'file.file' => 'The selected file is not valid.',
        'file.mimes' => 'The file must be a valid Excel file (xlsx, csv, xls).',
    ];

    public function openImportModal()
    {
        $this->resetValidation();
        $this->reset(['file', 'importErrors', 'importStats']);
        $this->showModal = true;
    }

    public function closeImportModal()
    {
        $this->showModal = false;
        $this->resetValidation();
        $this->reset(['file', 'importErrors', 'importStats']);
    }

    public function import()
    {
        $this->importing = true;
        $this->validate();
        $this->importErrors = [];
        $this->importStats = [];

        try {
            $import = new EmployeesImport();
            Excel::import($import, $this->file);

            // Get import results
            $errors = $import->getErrors();
            $failures = $import->failures();

            // Combine all errors
            $allErrors = [];

            // Add validation failures
            foreach ($failures as $failure) {
                $allErrors[] = [
                    'row' => $failure->row(),
                    'employee_id' => $failure->values()['employee_id'] ?? 'N/A',
                    'message' => implode(', ', $failure->errors())
                ];
            }

            // Add duplicate errors
            foreach ($errors as $error) {
                $allErrors[] = $error;
            }

            $this->importErrors = $allErrors;
            $this->importStats = [
                'total' => $import->getTotalCount(),
                'success' => $import->getSuccessCount(),
                'errors' => count($allErrors)
            ];

            if (empty($allErrors)) {
                session()->flash('message', 'Employees imported successfully!');
                $this->dispatch('employeeImported');
                // $this->closeImportModal();
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Error importing file: ' . $e->getMessage());
        } finally {
            $this->importing = false;
        }
    }

    public function updatedFile()
    {
        $this->validateOnly('file');
        $this->reset(['importErrors', 'importStats']);
    }

    public function render()
    {
        return view('livewire.employee.import');
    }
}
