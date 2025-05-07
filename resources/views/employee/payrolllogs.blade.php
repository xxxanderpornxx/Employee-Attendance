<x-employeelayout>
    <div class="container mt-5">
        <h1>Payroll Logs</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pay Period</th>
                    <th>Gross Pay</th>
                    <th>Net Pay</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrollLogs as $log)
                    <tr>
                        <td>{{ $log->PayPeriod }}</td>
                        <td>{{ $log->GrossPay }}</td>
                        <td>{{ $log->NetPay }}</td>
                        <td>{{ $log->Remarks }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-employeelayout>
