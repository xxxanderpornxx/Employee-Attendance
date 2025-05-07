<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Document</title>

</head>
<body>
<header class="">

    <div class="logo">
        <img src="{{ asset('images/logo.jpg') }}" alt="Company Logo" class="logo-img">
    </div>
    <div>
        <h1 style="color: white; font-size: 24px; margin: 0;">
            Employee Attendance System
        </h1>
    </div>
    <div>
    <ul class="navlinks">
        <li><a href="/dashboard">Dashboard</a></li>
        <li><a href="/attendance">Attendance</a></li>
        <li><a href="/employee">Employee</a></li>
        <li><a href="/leaverequests">Leave Request</a></li>
        <li><a href="/overtimerequests">Overtime Request</a></li>
        <li><a href="/positions">Roles</a></li>
        <li><a href="/shift">Shifts</a></li>
        <li><a href="/payroll">Payroll</a></li>
    </ul>
    </div>
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
    <main class="main-content">
        {{ $slot }}
    </main>
</div>
</body>
</html>


