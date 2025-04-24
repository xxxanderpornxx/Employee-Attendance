<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

<header> <meta name="csrf-token" content="{{ csrf_token() }}"></header>
    <div class="col-12">
        <div class="row">
            <h1>Attendance Records</h1>

            <!-- Camera Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        QR Code Scanner
                    </div>
                    <div class="card-body">
                        <video id="interactive" style="width: 100%; height: 200px;"></video>
                        <button id="toggle-camera" class="btn btn-primary mt-3" onclick="toggleCamera()">Turn Off Camera</button>
                        <p id="detected-qr-code" class="mt-3 text-success"></p>
                    </div>
                </div>
            </div>

            <!-- Attendance Logs Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Attendance Logs
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="attendanceTable">
                            <thead>
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
                                    <td>{{ $attendance->employee->id }}</td>
                                    <td>{{ $attendance->employee->FirstName }} {{ $attendance->employee->LastName }}</td>
                                    <td>{{ $attendance->Type }}</td>
                                    <td>{{ $attendance->DateTime->timezone('Asia/Manila')->format('Y-m-d | h:i A') }}</td>                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let scanner;
        let isCameraOn = true;

        document.addEventListener('DOMContentLoaded', startScanner);

        function startScanner() {
            scanner = new Instascan.Scanner({
                video: document.getElementById('interactive')
            });

            scanner.addListener('scan', function(content) {
                console.log("QR code detected:", content); // Log the scanned QR code
                document.getElementById("detected-qr-code").textContent = `QR Code: ${content}`;

                // Send the scanned QR code to Laravel backend
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
                    if (data.attendance) {
                       // Convert the DateTime to the local timezone (Asia/Manila)
                        const dateTime = new Date(data.attendance.DateTime);
                        const options = {
                            timeZone: 'Asia/Manila',
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        };
                        const formattedDateTime = new Intl.DateTimeFormat('en-US', options).format(dateTime);

                       // Dynamically add the new attendance record to the table
                        const tableBody = document.getElementById('attendanceTable').getElementsByTagName('tbody')[0];
                        const newRow = tableBody.insertRow();
                        newRow.innerHTML = `
                            <td>${data.attendance.EmployeeID}</td>
                            <td>${data.employee.FirstName} ${data.employee.LastName}</td>
                            <td>${data.attendance.Type}</td>
                            <td>${formattedDateTime}</td>
`;
                    }
                    alert(data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to process the QR code.');
                });
            });

            Instascan.Camera.getCameras()
                .then(function(cameras) {
                    if (cameras.length > 0) {
                        scanner.start(cameras[0]); // Start the first available camera
                    } else {
                        console.error('No cameras found.');
                        alert('No cameras found.');
                    }
                })
                .catch(function(err) {
                    console.error('Camera access error:', err);
                    alert('Camera access error: ' + err);
                });
        }

        function toggleCamera() {
            if (isCameraOn) {
                scanner.stop();
                document.getElementById('toggle-camera').textContent = 'Turn On Camera';
            } else {
                Instascan.Camera.getCameras()
                    .then(function(cameras) {
                        if (cameras.length > 0) {
                            scanner.start(cameras[0]);
                        } else {
                            console.error('No cameras found.');
                            alert('No cameras found.');
                        }
                    })
                    .catch(function(err) {
                        console.error('Camera access error:', err);
                        alert('Camera access error: ' + err);
                    });
                document.getElementById('toggle-camera').textContent = 'Turn Off Camera';
            }
            isCameraOn = !isCameraOn;
        }
    </script>
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</x-layout>
