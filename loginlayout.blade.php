<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: rgb(200, 237, 232) url('{{ asset('images/logo1.png') }}') no-repeat center center;
            background-size: 100%;
            background-blend-mode: overlay;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <main>
        {{ $slot }}
    </main>
</body>
</html>


