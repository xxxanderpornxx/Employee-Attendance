<x-layout>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Attendance Records</title>
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
            <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
            <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        </head>
    <body>

        <div class="container mt-5">
            <div class="card"           style="height: 600  px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1>Attendance Records</h1>
                    <a href="/attendance" class="btn btn-secondary"> Back to Attendance</a>
                </div>
                <div class="card-body " style="height: 450px; overflow-y: auto;">
                    <!-- Row for Export Buttons and Filters -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <!-- Export Buttons -->
                        <div id="exportButtons"></div>

                        <!-- Filter Form -->
                        <form method="GET" action="{{ route('attendance.records') }}" class="d-flex align-items-center">
                            <!-- Filter by Employee -->
                            <label for="employeeFilter" class="form-label me-2">Filter by Employee:</label>
                            <select class="form-select me-2" id="employeeFilter" name="EmployeeID" style="width: 300px; max-height: 200px; overflow-y: auto; margin-right: 10px;">
                                <option value="" {{ is_null($selectedEmployeeId) ? 'selected' : '' }}>All Employees</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ $selectedEmployeeId == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->id }} - {{ $employee->FirstName }} {{ $employee->LastName }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Filter by Date -->
                            <button type="button" id="dateFilterButton" class="btn btn-outline-secondary ml-2" style="margin-left: 10px;">
                                <i class="bi bi-calendar"></i>
                                <span id="dateFilterText"></span>
                            </button>
                            <input type="hidden" id="dateFilter" name="date_range">
                            <button type="submit" class="btn btn-primary me-2">Apply</button>
                        </form>
                    </div>

                    <!-- DataTable -->
                    <table class="table table-striped table-bordered table-hover w-100" id="attendanceTable">
                       <colgroup>
                            <col style="width: 3%;">
                            <col style="width: 5%;">
                            <col style="width: 20%;">
                            <col style="width: 10%;">
                            <col style="width: 10%;">
                            <col style="width: 20%;">
                            <col style="width: 25%;">
                    </colgroup>
                        <thead class="table-primary">

                            <tr>
                                <th>#</th>
                                <th class="text-center">ID</th>
                                <th>Employee Name</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                            <tr>
                                <td class="text-center">{{ $attendance['id'] }}</td>
                                <td class="text-center">{{ $attendance['EmployeeID'] }}</td>
                                <td>{{ $attendance['employee_name'] }}</td>
                                <td>{{ $attendance['type'] }}</td>
                                <td>{{ $attendance['status'] }}</td>
                                <td>{{ $attendance['remarks'] }}</td>
                                <td>{{ $attendance['date_time'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const table = $('#attendanceTable').DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    scrollY: '400px',
                    scrollCollapse: true,
                    language: {
                        search: "Search:",
                        lengthMenu: "Display _MENU_ records per page",
                        zeroRecords: "No matching records found",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "No entries available",
                        infoFiltered: "(filtered from _MAX_ total entries)"
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            title: 'Attendance Records',
                            text: 'Export to Excel',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'pdfHtml5',
                            title: 'Attendance Records',
                            text: 'Export to PDF',
                            className: 'btn btn-danger'
                        },
                        {
                            extend: 'print',
                            title: 'Attendance Records',
                            text: 'Print',
                            className: 'btn btn-primary'
                        }
                    ],
                    initComplete: function () {
                        // Move the export buttons to the custom container
                        this.api().buttons().container().appendTo('#exportButtons');
                    }
                });

                // Initialize Select2 for Employee Filter
                $('#employeeFilter').select2({
                    placeholder: "Search or select an employee",
                    allowClear: true,
                    dropdownCssClass: 'select2-scrollable'
                });

                // Initialize Date Range Picker
                $('#dateFilter').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                });

                // Update the input field when a date range is selected
                $('#dateFilter').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
                });

                // Clear the input field when the date range picker is canceled
                $('#dateFilter').on('cancel.daterangepicker', function () {
                    $(this).val('');
                });
       // Initialize Date Range Picker
       $('#dateFilterButton').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                applyLabel: 'Apply',
                format: 'YYYY-MM-DD'
            }
        }, function (start, end) {
            // Update the hidden input and display text when a date range is selected
            $('#dateFilter').val(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            $('#dateFilterText').text(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
        });

        // Handle Clear Button
        $('#dateFilterButton').on('cancel.daterangepicker', function () {
            $('#dateFilter').val(''); // Clear the hidden input
            $('#dateFilterText').text('Select Date Range'); // Reset the display text
        });

        // Retain the selected date range after page reload
        const selectedDateRange = "{{ request('date_range') }}"; // Get the date range from the request
        if (selectedDateRange) {
            const dates = selectedDateRange.split(' to ');
            $('#dateFilter').val(selectedDateRange); // Set the hidden input value
            $('#dateFilterText').text(selectedDateRange); // Set the display text
            $('#dateFilterButton').data('daterangepicker').setStartDate(dates[0]); // Set the start date
            $('#dateFilterButton').data('daterangepicker').setEndDate(dates[1]); // Set the end date
        }
    });
        </script>

    </body>
    </html>
</x-layout>
