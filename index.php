<?php

    //iniciar sesion
    session_start();

    //comprobar si el id_user está en la bbdd
    require 'database.php';

    if (isset($_SESSION['user_id'])){
        $records=$conn->prepare('SELECT id, apodo, email, password FROM users WHERE id =:id');
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
    <link rel="e" href="images/logo.png">

</head>
    <body>

    <?php if(!empty($user)): ?>
        <br> Bienvenido. <?= $user['apodo'],' ', $user['id'];  ?>
        <br>oleee TE HAS LOGEADO
        <a href="logout.php">
            Logout
        </a>
    <?php else: ?>

        <img src="logo/logo.png" alt="logo">
        <h1>Bienvenido al Concursillo</h1>

        <h2>¿Qué quieres hacer?</h2>
        <a href="iniciar_sesion.php">Iniciar sesión</a>
        <a href="registrarse.php">Registrarse</a>
        <a href="partida.php">Partida Rápida</a>
        <a href="encuesta.php">Encuesta de satisfacción</a>
    <?php endif; ?>
    </body>
</html>
