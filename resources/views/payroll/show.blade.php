<x-app-layout>
    <div class="print-container max-w-7xl mx-auto bg-white dark:bg-gray-900 shadow-xl rounded-2xl p-8 my-10 border border-gray-200 dark:border-gray-700"
        style="margin-top: 100px;">
        {{-- Payroll content --}}
        {{-- Header --}}
        <div class="flex justify-between items-start border-b pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Payroll Slip</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Month:
                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ \Carbon\Carbon::parse($payrollData->payroll_month)->format('F Y') }}
                    </span>
                </p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ config('app.name') }}</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Payroll Date: {{ \Carbon\Carbon::parse($payrollData->payroll_date)->format('d M, Y') }}</p>
            </div>
        </div>

        {{-- Employee & Payment Info --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 shadow-inner">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Employee Details</h3>
                <div class="space-y-1 text-gray-700 dark:text-gray-300">
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Name:</span> {{ $payrollData->employee->name }}</p>
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Employee ID:</span> {{ $payrollData->employee->employee_id }}</p>
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Department:</span> {{ $payrollData->employee->department ?? '—' }}</p>
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Position:</span> {{ $payrollData->employee->position ?? '—' }}</p>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-5 shadow-inner">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Payment Info</h3>
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $payrollData->payroll_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($payrollData->payroll_status) }}
                        </span>
                    </p>
                    <p><span class="font-medium text-gray-600 dark:text-gray-400">Payment Date:</span> {{ \Carbon\Carbon::parse($payrollData->payroll_date)->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Earnings & Deductions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
            <div class="rounded-lg shadow p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Earnings</h3>
                <div class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                    <div class="flex justify-between py-2"><span>Basic Salary</span><span>{{ number_format($payrollData->basic_salary, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Taxable Transport</span><span>{{ number_format($payrollData->taxable_transport, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Overtime</span><span>{{ number_format($payrollData->overtime, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Department Allowance</span><span>{{ number_format($payrollData->department_allowance, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Position Allowance</span><span>{{ number_format($payrollData->position_allowance, 2) }}</span></div>
                    <div class="flex justify-between py-2 font-semibold text-gray-900 dark:text-white"><span>Gross Earning</span><span>{{ number_format($payrollData->gross_earning, 2) }}</span></div>
                </div>
            </div>
            <div class="rounded-lg shadow p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-4">Deductions</h3>
                <div class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300">
                    <div class="flex justify-between py-2"><span>Income Tax</span><span>{{ number_format($payrollData->income_tax, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Pension (School)</span><span>{{ number_format($payrollData->pension_school, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Staff Pension</span><span>{{ number_format($payrollData->staff_pension, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Advance Loan</span><span>{{ number_format($payrollData->advance_loan, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Labor Association</span><span>{{ number_format($payrollData->labor_association, 2) }}</span></div>
                    <div class="flex justify-between py-2"><span>Social Committee</span><span>{{ number_format($payrollData->social_committee, 2) }}</span></div>
                </div>
            </div>
        </div>

        {{-- Net Pay --}}
        <div class="rounded-lg shadow p-5 bg-gradient-to-r from-blue-100 to-blue-200 dark:from-gray-800 dark:to-gray-700 border border-gray-200 dark:border-gray-700 text-right mb-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Net Pay: ህፍህግፍግህፍፍ
                <span class="text-blue-700 dark:text-blue-400">{{ number_format($payrollData->net_pay, 2) }}</span>
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 italic mt-1">Amount in words: <span class="capitalize">{{ $payrollData->net_pay }} Birr</span></p>
        </div>

        {{-- Footer --}}
        <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 border-t pt-4">
            <p>Generated by {{ config('app.name') }} Payroll System</p>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg print:hidden flex items-center">
                <i class="fas fa-print mr-2"></i> Print / Download PDF
            </button>
        </div>
    </div>
</x-app-layout>

{{-- Print Styling --}}
<style>
@media print {
    body * {
        visibility: hidden;
    }
    .print-container, .print-container * {
        visibility: visible;
    }
    .print-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important; /* remove shadow on print */
        border: none !important; /* remove border on print */
        margin-top:-20px !important;
    }
}

</style>
