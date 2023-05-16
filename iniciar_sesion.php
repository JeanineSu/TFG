<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require 'partials/header.php'?>
<h1>Login</h1>
<span>or <a href="registrarse.php">registrate</a></span>
<form action="iniciar_sesion.php" method="post">
    <input type="text" name="email" placeholder="Ingresa el email">
    <input type="password" name="password" placeholder="escribe tu contraseña">
    <input type="submit" value="enviar">
</form>
</body>
</html>