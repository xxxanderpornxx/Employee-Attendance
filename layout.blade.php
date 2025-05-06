<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Attendance System</title>
</head>
<body>
<header>
    <div class="logo">
        <img src="{{ asset('images/logo1.jpg') }}" alt="EAS" class="logo-img">
    </div>
    <div>
        <h1>WELCOME</h1>
    </div>
    <div class="user-info">
        <p></p>
        <p><strong>{{ Auth::user()->name }}</strong></p>
        <p><strong>{{ Auth::user()->role }}</strong></p>
        <!-- Logout Form -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Log Out</button>
        </form>
    </div>
</header>

<div class="main-layout">
    <aside class="left-column">
        <div class="sidebar-header"></div>
        <ul class="navlinks">
            <br>
            <br>
            <li><a href="/dashboard"><i class="bi bi-house " style="margin-right: 8px;"></i>   Dashboard</a></li>
            <br>
            <li><a href="/attendance"><i class="bi bi-calendar-check " style="margin-right: 8px;"></i>   Attendance</a></li>
            <br>
            <li><a href="/employee"><i class="bi bi-people" style="margin-right: 8px;"></i>   Employee</a></li>
            <br>
            <li><a href="/positions"><i class="bi bi-person-badge" style="margin-right: 8px;"></i>   Roles</a></li>
            <br>
            <li><a href="/shift"><i class="bi bi-clock-history" style="margin-right: 8px;"></i>   Shifts</a></li>
            <br>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropdown-toggle" style="margin-right: 8px;"><i class="bi bi-bar-chart"></i>   Report</a>
                <ul class="dropdown-menu">
                    <li><a href="/payroll"><i class="bi bi-receipt" style="margin-right: 8px;"></i>   Payroll</a></li>
                </ul>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        {{ $slot }}
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const parent = this.parentElement;
            parent.classList.toggle('active');
        });
    });
});
</script>

</body>
</html>

