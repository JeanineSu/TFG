<?php

//iniciar sesion
session_start();

//comprobar si el id_user está en la bbdd
require 'database.php';

if (isset($_SESSION['user_id'])) {
    $records = $conn->prepare('SELECT id, apodo, email, password FROM users WHERE id =:id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $user = null;

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
    <link rel="icon" type="image/x-icon" href="/images/logo.png">
    <style>
        body {
            background: url('./images/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            max-height: 100vh;
        }

        h1 {
            font-family: Arial, sans-serif;
            font-size: 4rem;
            font-weight: bold;
            color: white;
        }

        a {
            text-decoration: none;
            color: white;
            cursor: pointer;
            display: inline-block;
            margin: 20px;
            padding: 15px;
            border: 2px solid white;
            border-radius: 50px;
            font-size: 20px;
            background: #2d0e53;
            box-shadow: -1px -1px 5px #fff, 2px 2px 20px #1f1e1e, inset 0px 0px 0px #fff, inset 0px 0px 0px #1f1e1e;
            /*transition: all 300ms ease;*/
        }

        a:hover {
            box-shadow: -1px -1px 25px #fff, 2px 2px 20px #1f1e1e, inset 0px 0px 0px #fff, inset 0px 0px 0px #1f1e1e;

        }

        /*.barra-superior,*/
        /*.barra-inferior {*/
        /*    background: linear-gradient(90deg, rgb(255, 255, 0), rgb(255, 201, 14));*/
        /*    position: absolute;*/
        /*    height: 10px;*/
        /*    width: 100%;*/
        /*}*/

        .barra-superior {

            top: 0;
        }

    </style>
</head>

<body>
<div class="barra-superior"></div>
<?php if (!empty($user)) : ?>
<!--    <br> Bienvenido. --><?php //= $user['apodo'], ' ', $user['id'];  ?>
<!--    <br>oleee TE HAS LOGEADO-->
    <a href="logout.php">
        Logout
    </a>
<?php else : ?>

    <img src="./images/logo.png" alt="Quien quiere ser millonario" width="300px">
    <h1>Bienvenido</h1>

    <h2>¿Qué quieres hacer?</h2>
    <a href="iniciar_sesion.php">Iniciar sesión</a>
    <a href="registrarse.php">Registrarse</a>
    <a href="partida.php">Partida Rápida</a>
<?php endif; ?>
</body>

</html>