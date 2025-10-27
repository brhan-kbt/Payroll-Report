<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        return view('payroll.index');
    }

    public function create()
    {
        return view('payroll.create');
    }

    public function show(Payroll $payroll)
    {
        $payrollData = $payroll->load('employee');
        return view('payroll.show', compact('payrollData'));
    }

    public function edit(Payroll $payroll)
    {
        //
    }

    public function destroy(Payroll $payroll)
    {
        //
    }
}
