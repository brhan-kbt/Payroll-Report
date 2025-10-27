<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(20);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id',
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:50|unique:employees,employee_id,'.$employee->id,
            'department' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_joining' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}
