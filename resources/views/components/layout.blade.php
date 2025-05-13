<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="button" onclick="confirmLogout()" class="logout-button">Log Out</button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        {{ $slot }}
    </main>
</div>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Ready to Leave?',
        text: "Are you sure you want to logout?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Logging out...',
                text: 'Please wait...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                    document.getElementById('logout-form').submit();
                }
            });
        }
    });
}

// Add custom styling for SweetAlert
const style = document.createElement('style');
style.textContent = `
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
    .swal2-confirm, .swal2-cancel {
        padding: 12px 24px !important;
        font-size: 1.1rem !important;
    }
    .swal2-icon {
        width: 5em !important;
        height: 5em !important;
    }
`;
document.head.appendChild(style);
</script>
</body>
</html>


