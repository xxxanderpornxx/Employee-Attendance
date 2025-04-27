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
        </head>
    <body>

        <div class="container mt-5">
            <div class="card" style="max-height: 500px; overflow-y: auto;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1>Attendance Records</h1>
                    <a href="/attendance" class="btn btn-secondary">Back to Attendance</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div id="exportButtons"></div>
                        <button id="dateRangePickerButton" class="btn btn-primary">
                            <i class="bi bi-calendar"></i> Filter by Date Range
                        </button>
                    </div>


                    <div style="overflow: hidden;">
                        <table class="table table-striped table-bordered table-hover w-100" id="attendanceTable">
                            <thead class="table-primary">
                                <tr>
                                    <th>EmployeeID</th>
                                    <th>Employee Name</th>
                                    <th>Type</th>
                                    <th>Date & Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance['id'] }}</td>
                                    <td>{{ $attendance['employee_name'] }}</td>
                                    <td>{{ $attendance['type'] }}</td>
                                    <td>{{ $attendance['date_time'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize the DataTable
                const table = $('#attendanceTable').DataTable({
                    responsive: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    scrollY: '400px', // Set the height of the table
                    scrollCollapse: true, // Allow the table to collapse if fewer rows
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

                // Initialize the Date Range Picker on the button
                $('#dateRangePickerButton').daterangepicker({
                    opens: 'left',
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: 'Clear'
                    }
                }, function (start, end) {
                    const startDate = start.format('YYYY-MM-DD');
                    const endDate = end.format('YYYY-MM-DD');

                    // Filter the DataTable
                    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                        const date = data[3]; // Assuming the 4th column contains the date
                        const formattedDate = moment(date, 'YYYY-MM-DD | hh:mm A').format('YYYY-MM-DD');
                        return formattedDate >= startDate && formattedDate <= endDate;
                    });

                    table.draw();
                });

                // Clear the date range filter when the picker is canceled
                $('#dateRangePickerButton').on('cancel.daterangepicker', function () {
                    $.fn.dataTable.ext.search.pop();
                    table.draw();
                });
            });
        </script>
    </body>
    </html>
</x-layout>
