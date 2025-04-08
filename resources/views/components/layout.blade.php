<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
<header>
    <ul>
    <li><a href="/dashboard">Dashboard</a></li>
    <li><a href="/attendance">Attendance</a></li>
    <li><a href="/employee">Employee</a></li>
    <li><a href="/shift">Shifts</a></li>
    <li><a href="/payroll">Payroll</a></li>
    </ul>
</header>
<main class="container">
    {{ $slot }}
</main>
</body>
</html>


