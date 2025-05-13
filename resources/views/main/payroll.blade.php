<!-- filepath: c:\IT9aL\Project\EmployeeAttendance\resources\views\main\payroll.blade.php -->
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payroll</title>
    <link rel="stylesheet" href="{{ asset('css/position.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM8e+z5l5c5e5f5e5f5e5f5e5f5e5f5e5f5e" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
  <body>
    <x-layout>
      <div class="container mt-5">
        <div class="card p-3">
            <h1 class="">Payroll</h1>
            <hr>
            <div>
                <div class="mb-3">
                    <button id="exportExcel" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                    <button id="exportPDF" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i>  PDF
                    </button>
                    <button id="printTable" class="btn btn-secondary">
                        <i class="bi bi-printer"></i> Print
                    </button>

                    <button class="align-start end float-end btn btn-primary text-white " id="generatePayrollButton">Generate</button>

                </div>
                <div class="mb-3">
                    <form id="generatePayrollForm">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="employeeSelect" class="form-label">Select Employee</label>
                                <select id="employeeSelect" name="employee_id" class="form-select">
                                    <option value="all"> All Employees </option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->FirstName }} {{ $employee->LastName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" id="startDate" name="start_date" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}">
                            </div>
                            <div class="col-md-4">
                                <label for="endDate" class="form-label">End Date</label>
                                <input type="date" id="endDate" name="end_date" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table id="payrollTable" class="table table-striped table-bordered">
            <colgroup>
                <col style="width: 5%;">
                <col style="width: 15%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
                <col style="width: 14%;">
            </colgroup>
            <thead>
                <tr class="table-primary">
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Period Start</th>
                <th>Period End</th>
                <th>Base Salary</th>
                <th>Days Worked</th>
                <th>Overtime Hours</th>
                <th>Leave Days</th>
                <th>Absent Days</th>
                <th>Late Minutes</th>
                <th>Gross Pay</th>
                <th>Net Pay</th>
                <th>Export Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrollExports as $export)
                    <tr>
                        <td>{{ $export->EmployeeID }}</td>
                        <td>{{ $export->employee->FirstName }} {{ $export->employee->MiddleName ? substr($export->employee->MiddleName, 0, 1) . '.' : '' }} {{ $export->employee->LastName }}</td>
                        <td>{{ $export->PayPeriodStart->format('Y-m-d') }}</td>
                        <td>{{ $export->PayPeriodEnd->format('Y-m-d') }}</td>
                        <td>{{ $export->employee->BaseSalary }}</td>
                        <td>{{ $export->DaysWorked }}</td>
                        <td>{{ $export->OvertimeHours }}</td>
                        <td>{{ $export->LeaveDays }}</td>
                        <td>{{ $export->AbsentDays }}</td>
                        <td>{{ $export->LateMinutes }}</td>
                        <td>{{ $export->GrossPay }}</td>
                        <td>{{ $export->NetPay }}</td>
                        <td>{{ $export->ExportDate->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
            </table>
        </div>
      </div>
    </x-layout>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
         document.addEventListener('DOMContentLoaded', function () {
    // Export to Excel
    document.getElementById('exportExcel').addEventListener('click', function () {
      const table = document.getElementById('payrollTable');
      const workbook = XLSX.utils.table_to_book(table, { sheet: "Payroll Data" });
      XLSX.writeFile(workbook, 'PayrollData.xlsx');
    });

    // Export to PDF
    document.getElementById('exportPDF').addEventListener('click', async function () {
      const { jsPDF } = window.jspdf;
      const pdf = new jsPDF();
      const table = document.getElementById('payrollTable');
      const rows = Array.from(table.rows);

      let y = 10; // Starting Y position
      rows.forEach((row, rowIndex) => {
        const cells = Array.from(row.cells);
        let x = 10; // Starting X position
        cells.forEach((cell) => {
          pdf.text(cell.innerText, x, y);
          x += 40; // Adjust column width
        });
        y += 10; // Adjust row height
        if (y > 280) { // Create a new page if content exceeds page height
          pdf.addPage();
          y = 10;
        }
      });

      pdf.save('PayrollData.pdf');
    });

    // Print Table
    document.getElementById('printTable').addEventListener('click', function () {
      const printContents = document.getElementById('payrollTable').outerHTML;
      const originalContents = document.body.innerHTML;
      document.body.innerHTML = printContents;
      window.print();
      document.body.innerHTML = originalContents;
      location.reload(); // Reload to restore the page
    });

    // Generate Payroll
    document.getElementById('generatePayrollButton').addEventListener('click', function () {
        const form = document.getElementById('generatePayrollForm');
        const formData = new FormData(form);

        fetch('{{ route('payroll.generate') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })



    });
  });
  $(document).ready(function () {
    $('#payrollTable').DataTable({
        autoWidth: false, // Disable automatic width calculation

    });
});
document.getElementById('generatePayrollButton').addEventListener('click', function () {
    const form = document.getElementById('generatePayrollForm');
    const formData = new FormData(form);

    // Show loading state
    Swal.fire({
        title: 'Generating Payroll...',
        text: 'Please wait while we process the data.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route('payroll.generate') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to generate payroll.'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while generating payroll.'
        });
    });
});
    </script>
  </body>
</html>
