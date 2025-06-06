<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attendance</title>

    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <style>
.swal2-popup {
    font-size: 1.2rem;
    border-radius: 15px;
}

.swal2-title {
    font-size: 1.8rem;
    color: #333;
}

.swal2-html-container {
    font-size: 1.1rem;
}

.swal2-success {
    border: none;
    box-shadow: 0 0 15px rgba(0,123,255,0.2);
}

.swal2-error {
    border: none;
    box-shadow: 0 0 15px rgba(255,0,0,0.2);
}
</style>

</head>
<body>
    <x-layout>

    <div class="container mt-4" >
        <div class="row">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h1 class="mb-0">Attendance</h1>
                            <h3 id="currentTime" class="mb-0"></h3>
                        </div>
                    </div>
                </div>

            <script>
            function updateTime() {
                const now = new Date();
                const options = {
                    timeZone: 'Asia/Manila',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                };
                document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', options);
            }

            setInterval(updateTime, 1000);
            updateTime();
            </script>
            </div>
            <!-- Camera Card -->
            <div class="col-md-4">
                <div class="card h-90   ">
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
                        <th>Status</th>
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
                                <td>{{$attendance ->Status}}</td>
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
                                infoFiltered: "(filtered from _MAX_ total entries)",
                                paginate: {
                                    first: "First",
                                    last: "Last",
                                    next: "Next",
                                    previous: "Previous"
                                }
                            }
                        });

                        // Initialize the QR code scanner
                        const scanner = new Instascan.Scanner({
                            video: document.getElementById('interactive')
                        });

                        scanner.addListener('scan', function (content) {
    // Show scanning animation
    Swal.fire({
        title: 'Scanning...',
        html: 'Processing your attendance',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

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
        console.log('Server Response:', data);
        if (data.attendance) {
            // Success Alert
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 3000,
                showConfirmButton: false,
                backdrop: `
                    rgba(0,123,255,0.4)
                    url("/images/check-animation.gif")
                    center top
                    no-repeat
                `
            });

            // Update the DataTable
            table.row.add([
                data.attendance.id,
                data.employee.id,
                `${data.employee.FirstName} ${data.employee.LastName}`,
                data.attendance.Type,
                data.attendance.Status,
                new Date(data.attendance.DateTime).toLocaleString('en-US', { timeZone: 'Asia/Manila' })
            ]).draw(false);

            // Update employee info
            document.getElementById('employee-id').textContent = `Employee ID: ${data.employee.id}`;
            document.getElementById('employee-fullname').textContent = `Name: ${data.employee.FirstName} ${data.employee.LastName}`;
        } else {
            // Error Alert
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
                showConfirmButton: true,
                confirmButtonColor: '#3085d6'
            });

            // Clear employee info
            document.getElementById('employee-id').textContent = '';
            document.getElementById('employee-fullname').textContent = '';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Network Error Alert
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Failed to process the QR code. Please try again.',
            showConfirmButton: true,
            confirmButtonColor: '#3085d6'
        });

        // Clear employee info
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
</body>
</html>
