<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Validation\Rule;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $errors = [];
    private $rowCount = 0;
    private $successCount = 0;

    public function model(array $row)
    {
        $this->rowCount++;

        // Check if employee_id already exists
        if (Employee::where('employee_id', $row['employee_id'])->exists()) {
            $this->errors[] = [
                'row' => $this->rowCount + 1, // +1 for header row
                'employee_id' => $row['employee_id'],
                'message' => 'Duplicate employee ID'
            ];
            return null;
        }

        // Check if email already exists
        if (isset($row['email']) && Employee::where('email', $row['email'])->exists()) {
            $this->errors[] = [
                'row' => $this->rowCount + 1,
                'employee_id' => $row['employee_id'],
                'message' => 'Duplicate email address: ' . $row['email']
            ];
            return null;
        }

        $this->successCount++;

        return new Employee([
            'name' => $row['name'],
            'employee_id' => $row['employee_id'],
            'department' => $row['department'] ?? null,
            'position' => $row['position'] ?? null,
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'] ?? null,
            'date_of_joining' => $this->parseDate($row['date_of_joining'] ?? null),
            'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
            'gender' => $row['gender'] ?? null,
            'address' => $row['address'] ?? null,
            'is_active' => filter_var($row['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required on row :attribute',
            'employee_id.required' => 'Employee ID is required on row :attribute',
            'email.email' => 'Invalid email format on row :attribute',
        ];
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                // Handle Excel serial dates
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
            }

            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
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
