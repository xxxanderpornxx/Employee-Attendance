<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" href="{{ asset('css/loginlayout.css') }}">
</head>
<body>
<header class="navbar">
    <div class="logo">
        <img src="{{ asset('images/logo.jpg') }}" alt="Company Logo" class="logo-img">
    </div>
    <div>
        <h1>
            Employee Attendance System
        </h1>
    </div>
    <ul>
        <li class="right"><a href="/register">Register</a></li>
        <li class="right"><a href="/login">Log in</a></li>
    </ul>
</header>

<main class="">
    {{ $slot }}
</main>
</body>
</html>


