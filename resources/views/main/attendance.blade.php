<x-layout>
    <header>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    <meta name="csrf-token" content="{{ csrf_token() }}">
</header>
<div class="container mt-4">
    <div class="row">
        <h1 class="mb-4">Attendance</h1>

        <!-- Camera Card -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header text-center">
                    QR Code Scanner
                </div>
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <video id="interactive" style="width: 100%; height: auto; max-height: 200px;"></video>
                    <button id="toggle-camera" class="btn btn-primary mt-3" onclick="toggleCamera()">Turn Off Camera</button>
                    <p id="detected-qr-code" class="mt-3 text-success text-center"></p>
                    <p id="employee-name" class="mt-3 text-info text-center"></p>
                    <!-- Placeholder for Employee Information -->
                    <div id="employee-info" class="mt-3 text-center">
                        <p id="employee-id" class="text-secondary"></p>
                        <p id="employee-fullname" class="text-secondary"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Logs Card -->
        <div class="col-md-8">
            <div class="card" style="height: 501px;">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Attendance Logs</span>
                <a href="/attendance-records" class="btn btn-secondary">Attendance Records</a>
            </div>
            <div class="card-body" style="overflow-y: auto; height: calc(100% - 56px);">
                <table class="table table-striped table-bordered table-hover w-100" id="attendanceTable">
                <thead class="table-primary">
                    <tr>
                    <th>#</th>
                    <th>EmployeeID</th>
                    <th>Employee Name</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr @if ($loop->first)  @endif>
                            <td>{{ $attendance->id }}</td>
                            <td>{{ $attendance->employee->id }}</td>
                            <td>{{ $attendance->employee->FirstName }} {{ $attendance->employee->LastName }}</td>
                            <td>{{ $attendance->Type }}</td>
                            <td>{{ $attendance->DateTime->timezone('Asia/Manila')->format('Y-m-d | h:i A') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

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
                        scrollY: '300px',
                        scrollCollapse: true,
                        order: [[0, 'desc']], // Default sorting by the first column in descending order
                        language: {
                            search: "Search:",
                            lengthMenu: "Display _MENU_ records per page",
                            zeroRecords: "No matching records found",
                            info: "Showing _START_ to _END_ of _TOTAL_ entries",
                            infoEmpty: "No entries available",
                            infoFiltered: "(filtered from _MAX_ total entries)"
                        }
                    });

                    // Initialize the QR code scanner
                    const scanner = new Instascan.Scanner({
                        video: document.getElementById('interactive')
                    });

                    scanner.addListener('scan', function (content) {
    // Send the QR code to the server
    fetch('/attendance/process-qr-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ qrCode: content })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Server Response:', data); // Log the response for debugging
            if (data.attendance) {
                // Show a success notification
                toastr.success(data.message, 'Success', {
                    positionClass: 'toast-top-right',
                    timeOut: 3000, // 3 seconds
                    progressBar: true
                });

                // Dynamically add the new attendance record to the DataTable
                table.row.add([
                    data.attendance.id,
                    data.employee.id,
                    `${data.employee.FirstName} ${data.employee.LastName}`,
                    data.attendance.Type,
                    new Date(data.attendance.DateTime).toLocaleString('en-US', { timeZone: 'Asia/Manila' })
                ]).draw(false); // Redraw the table without resetting pagination

                // Update the employee information
                document.getElementById('employee-id').textContent = `Employee ID: ${data.employee.id}`;
                document.getElementById('employee-fullname').textContent = `Name: ${data.employee.FirstName} ${data.employee.LastName}`;
            } else {
                // Show an error notification
                toastr.error(data.message, 'Error', {
                    positionClass: 'toast-top-right',
                    timeOut: 3000,
                    progressBar: true
                });

                // Clear the employee information if there's an error
                document.getElementById('employee-id').textContent = '';
                document.getElementById('employee-fullname').textContent = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Failed to process the QR code. Please try again.', 'Error', {
                positionClass: 'toast-top-right',
                timeOut: 3000,
                progressBar: true
            });

            // Clear the employee information if there's an error
            document.getElementById('employee-id').textContent = '';
            document.getElementById('employee-fullname').textContent = '';
        });
});

                    let isCameraOn = true;

                    // Start the camera
                    Instascan.Camera.getCameras()
                        .then(function (cameras) {
                            if (cameras.length > 0) {
                                scanner.start(cameras[0]);
                            } else {
                                console.error('No cameras found.');
                                alert('No cameras found.');
                            }
                        })
                        .catch(function (err) {
                            console.error('Camera access error:', err);
                            alert('Camera access error: ' + err);
                        });

                    // Toggle camera functionality
                    document.getElementById('toggle-camera').addEventListener('click', function () {
                        if (isCameraOn) {
                            scanner.stop();
                            this.textContent = 'Turn On Camera';
                        } else {
                            Instascan.Camera.getCameras()
                                .then(function (cameras) {
                                    if (cameras.length > 0) {
                                        scanner.start(cameras[0]);
                                    } else {
                                        console.error('No cameras found.');
                                        alert('No cameras found.');
                                    }
                                })
                                .catch(function (err) {
                                    console.error('Camera access error:', err);
                                    alert('Camera access error: ' + err);
                                });
                            this.textContent = 'Turn Off Camera';
                        }
                        isCameraOn = !isCameraOn;
                    });
                });
            </script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="bootstrap/js/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
