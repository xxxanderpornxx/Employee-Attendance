<x-employeelayout>
    {{-- Add required CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet">

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Attendance Records</h2>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    {{-- Export Buttons --}}
                    <div id="exportButtons" class="d-flex">
                        <button id="exportExcel" class="btn btn-success me-2">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </button>
                        <button id="exportPDF" class="btn btn-danger me-2">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </button>
                        <button id="exportPrint" class="btn btn-primary">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>

                    {{-- Date Range Picker --}}
                    <div class="d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                            <input type="text" id="daterange" class="form-control" placeholder="Select Date Range">
                            <button id="applyDateFilter" class="btn btn-primary ms-2">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered table-hover w-100" id="attendanceTable">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mappedLogs as $log)
                            <tr>
                                <td>{{ $log['id'] }}</td>
                                <td>{{ $log['type'] }}</td>
                                <td>{{ $log['status'] }}</td>
                                <td>{{ $log['remarks'] }}</td>
                                <td>{{ $log['date_time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add required JavaScript --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{-- Add DataTable Buttons JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize the date range picker
            $('#daterange').daterangepicker({
                startDate: moment().subtract(29, 'days'),
                endDate: moment(),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });

            // Initialize DataTable with buttons
            var table = $('#attendanceTable').DataTable({
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
                        text: 'Export to Excel',
                        className: 'd-none buttons-excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export to PDF',
                        className: 'd-none buttons-pdf'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'd-none buttons-print'
                    }
                ]
            });

            // Export Buttons
            $('#exportExcel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });

            $('#exportPDF').on('click', function() {
                table.button('.buttons-pdf').trigger();
            });

            $('#exportPrint').on('click', function() {
                table.button('.buttons-print').trigger();
            });

            // Apply date range filter on button click
            $('#applyDateFilter').on('click', function() {
                var startDate = $('#daterange').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var endDate = $('#daterange').data('daterangepicker').endDate.format('YYYY-MM-DD');

                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var date = moment(data[4], 'YYYY-MM-DD | h:mm A').format('YYYY-MM-DD'); // Adjust column index if needed
                    return date >= startDate && date <= endDate;
                });

                table.draw();
                $.fn.dataTable.ext.search.pop(); // Remove the filter after applying
            });
        });
    </script>
    </script>
</x-employeelayout>
