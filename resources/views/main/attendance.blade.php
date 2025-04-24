<x-layout>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-12">
        <div class="row">
            <h1>Attendance Records</h1>

            <!-- Camera Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        QR Code Scanner
                    </div>
                    <div class="card-body">
                        <video id="interactive" style="width: 100%;"></video>
                        <button id="toggle-camera" class="btn btn-primary mt-3">Turn Off Camera</button>
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
                                        <td>{{ $attendance->employees->id }}</td>
                                        <td>{{ $attendance->employees->FirstName . ' ' . substr($attendance->employees->MiddleName, 0, 1) . '. ' . $attendance->employees->LastName }}</td>
                                        <td>{{ $attendance->Type }}</td>
                                        <td>{{ $attendance->DateTime }}</td>
                                    </tr>
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
    let scanner = new Instascan.Scanner({
        video: document.getElementById('interactive')
    });

    scanner.addListener('scan', function(content) {
        console.log("QR code detected:", content); // Debug log
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
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            alert(data.message); // Display a success message

            if (data.attendance) {
                // Dynamically add the new attendance record to the table
                const table = document.getElementById('attendanceTable').getElementsByTagName('tbody')[0];
                const newRow = table.insertRow();
                newRow.innerHTML = `
                    <td>${data.attendance.EmployeeID}</td>
                    <td>${data.employee.FirstName} ${data.employee.LastName}</td>
                    <td>${data.attendance.Type}</td>
                    <td>${data.attendance.DateTime}</td>
                `;
            }
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
                            scanner.start(cameras[0]); // Restart the first available camera
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
