<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/employeelayout.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Employee Dashboard</title>
</head>
<body>
    <header>
        <div class="logo">
            <span class="logo-text">E<span class="logo-accent">A</span>S</span>
        </div>

        <div class="header-right">
            <div class="user-dropdown">
                <button onclick="toggleDropdown()" class="dropdown-toggle">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div id="userDropdown" class="dropdown-menu">
                    <div class="user-info">
                        <p><strong>{{ Auth::user()->name }}</strong></p>
                        <p class="text-muted">{{ Auth::user()->role }}</p>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="button" onclick="confirmLogout()" class="logout-button" style="display: block; margin: 0 auto;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="main-layout">
        <aside class="left-column">
            <ul class="navlinks">
                <li>
                    <a href="{{ route('employee.profile') }}">
                        <i class="fas fa-user"></i> Profile
                    </a>
                </li>
                <li>
                    <a href="{{ route('employee.employeeleaverequest') }}">
                        <i class="fas fa-calendar-alt"></i> Request
                    </a>
                </li>
                <li>
                    <a href="{{ route('employee.attendancerecords') }}">
                        <i class="fas fa-clock"></i> Attendance Logs
                    </a>
                </li>
            </ul>
        </aside>

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
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'swal2-popup',
                title: 'swal2-title',
                htmlContainer: 'swal2-html-container',
                confirmButton: 'swal2-confirm',
                cancelButton: 'swal2-cancel',
                icon: 'swal2-icon'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Logging out...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                        document.getElementById('logout-form').submit();
                    }
                });
            }
        });
    }

    function toggleDropdown() {
        document.getElementById('userDropdown').classList.toggle('show');
    }

    // Close dropdown when clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-toggle') && !event.target.matches('.fa-user-circle')) {
            var dropdowns = document.getElementsByClassName('dropdown-menu');
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
    </script>
</body>
</html>
