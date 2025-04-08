<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
<style>
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #34abc7;

  width: 100%;
}

body, html {
  margin: 0;
  padding: 0;
  background-color: #f5f5f5;
}

li {
  float: left;
}

li.right {
  float: right;
}

li a {
  display: block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 20px;
}

li a:hover:not(.active) {
  background-color: #098eaf;
}

.active {
  background-color: #04AA6D;
}
</style>
</head>
<body>
<header>
    <ul>
        <li class="right"><a href="/register">Register</a></li>
        <li class="right"><a href="/login">Log in</a></li>
    </ul>
</header>
<main class="container">
    {{ $slot }}
</main>
</body>
</html>


