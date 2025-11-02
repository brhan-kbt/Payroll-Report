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

    public function deletePayroll($payroll)
    {
        $payroll = Payroll::find($payroll);
        if (!$payroll) {
            return redirect()->route('payrolls.index')->with('error', 'Payroll not found.');
        }
        if ($payroll->smsLogs()->count() > 0) {
            $payroll->smsLogs()->delete();
        }
        $payroll->delete();

        return redirect()->route('payrolls.index')->with('success', 'Payroll deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:payrolls,id',
        ]);

        Payroll::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Selected payrolls deleted successfully.']);
    }

    public function deletePayrolls($payroll)
    {
        $payrolls = Payroll::all();
        foreach ($payrolls as $payroll) {
            if ($payroll->smsLogs()->count() > 0) {
                //delete sms logs
                $payroll->smsLogs()->delete();
            }
            $payroll->delete();
        }
        return redirect()->route('payrolls.index')->with('success', 'Payrolls deleted successfully.');
    }
}
