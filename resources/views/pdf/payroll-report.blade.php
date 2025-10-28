<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payroll Report</title>
    <style>
        @page {
            margin: 30px 40px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .filters {
            text-align: center;
            font-size: 13px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <h2>Payroll Report</h2>
    <div class="filters">
        <strong>Month:</strong> {{ $month }}
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Gross Pay</th>
                <th>Net Pay</th>
                <th>Month</th>
                <th>SMS Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payrolls as $index => $p)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->employee->employee_id }}</td>
                    <td>{{ $p->employee->name }}</td>
                    <td>{{ number_format($p->gross_earning, 2) }}</td>

                    <td>{{ number_format($p->net_pay, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->payroll_month)->format('F Y') }}</td>
                    <td>{{ ucfirst($p->smsLogs->sortByDesc('created_at')->first()?->status ?? 'Not Sent') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }}
    </div>
</body>

</html>
