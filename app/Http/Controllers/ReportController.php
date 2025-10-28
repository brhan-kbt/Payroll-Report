<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PayrollReportExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        return view('reports.index');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PayrollReportExport($request), 'payroll_report.xlsx');
    }

 
}
