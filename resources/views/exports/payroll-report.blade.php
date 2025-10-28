<table>
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Gross Pay</th>
            <th>Net Pay</th>
            <th>Month</th>
            <th>SMS Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($payrolls as $payroll)
            <tr>
                <td>{{ $payroll->employee->employee_id }}</td>
                <td>{{ $payroll->employee->name }}</td>
                <td>{{ number_format($payroll->gross_pay, 2) }}</td>
                <td>{{ number_format($payroll->net_pay, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y') }}</td>
                <td>{{ ucfirst($payroll->smsLogs->sortByDesc('created_at')->first()?->status ?? 'Not Sent') }}</td>

            </tr>
        @endforeach
    </tbody>
</table>
