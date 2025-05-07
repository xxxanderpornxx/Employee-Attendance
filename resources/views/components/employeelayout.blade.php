<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/employeelayout.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Dashboard</title>
</head>

<body>
    <div>
        <header>
            <div class="logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Company Logo" class="logo-img">
            </div>
            <h1>Employee Attendance System</h1>
            <nav>
                <ul class="navlinks">
                    <li><a href="{{ route('employee.profile') }}">Profile</a></li>
                    <li><a href="{{ route('employee.attendancerecords') }}">Attendance Logs</a></li>
                    <li><a href="{{ route('employee.employeeleaverequest') }}">Request</a></li>
                    {{-- <!-- <li><a href="{{ route('employee.payroll') }}">Payroll Logs</a></li> --> --}}
                </ul>
            </nav>
        </header>
        <div class="main-layout">

            <aside class="left-column">
                <div class="user-info">
                    <p>Logged in as:</p>
                    <p><strong>{{ Auth::user()->name }}</strong></p>
                    <p>Role: <strong>{{ Auth::user()->role }}</strong></p>
                    <!-- Logout Form -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="logout-button">Log Out</button>
                    </form>
                </div>
            </aside>

            <!-- Main content -->
            <main class="   main-content">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>

</html>
