<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .card {
            margin: 15px;
        }
        .card-icon {
            font-size: 3rem;
            margin-bottom: 10px;
            color: #009688;
        }
    </style>
</head>
<body>
    <x-layout>
        <div class="dashboard-container">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row m-5">
                <!-- Total Employees Card -->
                <div class="col-md-3">
                    <div class="card employee-card" style="width: 100%;">
                        <a href="/employee" class="text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-people card-icon" style="color: #009688"></i>
                                <h5 class="card-title">Total Employees</h5>
                                <hr>
                                <p class="card-text display-4">{{$totalEmployees}}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Attendance Card -->
                <div class="col-md-3">
                    <div class="card attendance-card" style="width: 100%;">
                        <a href="/attendance" class="text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check card-icon" style="color: #009688"></i> <!-- Bootstrap Icon -->
                                <h5 class="card-title">Attendance</h5>
                                <hr>
                                <p class="card-text display-4">{{$totalAttendance}} / {{ $totalEmployees }}</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Pending Requests Card -->
                <div class="col-md-3">
                    <div class="card requests-card" style="width: 100%;">
                        <div class="card-body text-center">
                            <i class="bi bi-envelope card-icon" style="color: #009688"></i> <!-- Bootstrap Icon -->
                            <h5 class="card-title">Pending Requests</h5>
                            <hr>
                            <a href="/leaverequests" class="text-decoration-none text-dark">
                                <p class="card-text display-6"><strong>Leave: {{ $pendingLeaveRequests }}</strong></p>
                            </a>
                            <br>
                            <a href="/overtimerequests" class="text-decoration-none text-dark">
                                <p class="card-text display-6"><strong>Overtime: {{ $pendingOvertimeRequests }}</strong></p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Days Until Next Payroll Card -->
                <div class="col-md-3">
                    <div class="card payroll-card" style="width: 100%;">
                        <a href="/payroll" class="text-decoration-none text-dark">
                            <div class="card-body text-center">
                                <i class="bi bi-cash-coin card-icon" style="color: #009688"></i> <!-- Bootstrap Icon -->
                                <h5 class="card-title">Days Until Next Payroll</h5>
                                <hr>
                                <p class="card-text display-4">{{ $daysUntilNextPayroll }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="chart-container">
                <div class="col-md-12">
                    <div class="card trends-card">
                        <a href="/attendance-records" class="text-decoration-none text-dark">
                            <div class="card-body">
                                <h5 class="card-title">30-Day Attendance Trends</h5>
                                <canvas id="attendanceTrendChart"></canvas>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const attendanceTrend = @json($attendanceTrend);

            // Extract dates and attendance counts
            const labels = attendanceTrend.map(item => item.date);
            const data = attendanceTrend.map(item => item.count);

            // Render the line chart
            const ctx = document.getElementById('attendanceTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Attendance',
                        data: data,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0, 123, 255, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Total Employees'
                            },
                            beginAtZero: true,
                            suggestedMax: Math.max(...data) + 2 // Optional: add some headroom above max
                        }
                    }
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
