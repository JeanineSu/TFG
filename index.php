<?php

    //iniciar sesion
    session_start();

    //comprobar si el id_user está en la bbdd
    require 'database.php';

    if (isset($_SESSION['user_id'])){
        $records=$conn->prepare('SELECT id, email, password FROM users WHERE id =:id');
        $records->bindParam(':id', $_SESSION['user_id']);
        $records->execute();
        $results=$records->fetch(PDO::FETCH_ASSOC);

        $user=null;

        if (count($results) > 0) {
            $user = $results;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
    <body>
    <?php require 'partials/header.php'?>
    <?php if(!empty($user)): ?>
        <br> Bienvenido. <?= $user['email']; ?>
        <br>oleee te has registrado correctamente
        <a href="logout.php">
            Logout
        </a>
    <?php else: ?>
        <h1>inicia sesion o registrate porfa</h1>

        <a href="iniciar_sesion.php">Login</a> or
        <a href="registrarse.php">SignUp</a>
        <a href="encuesta.php">Encuesta de satisfacción</a>
        <a href="partida.php">Partida</a>
    <?php endif; ?>
    </body>
</html>
