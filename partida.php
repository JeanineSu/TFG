<?php
session_start();
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
    <link rel="icon" type="image/png" href="/proyecto_jeanine/images/logo.png">
    <title>Quien quiere ser millonario</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="module" src="codigo.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background: url('./images/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            padding-top: 0em;
            max-height: 100vh;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
<?php require 'partials/header.php' ?>
<?php if (!empty($user)): ?>
    <br> <span style="color: white; font-family: Arial, sans-serif;font-size: 2rem; font-weight: bold">Hola</span>
    <span id="usuario"
          style="color: white; font-family: Arial, sans-serif; font-size: 2rem; font-weight: bold"> <?= $user['apodo']; ?></span>
    <br><br>
    <a href="editar_perfil.php">Editar perfil</a>
    <br><br>
    <a href="logout.php">
        Logout
    </a>
<?php endif; ?>
<br><br><br>
<body>
<h1>Quien quiere ser millonario</h1>

<p id="temporizador"> <!--Temporizador:--> <span id="tiempo"></span></p>

<button id="start">Comenzar juego</button>

<form action="perder.php" method="post" id="quizForm">
    <p style="display:none;" id="nivel_pregunta">Pregunta: <span id="nivel_pregunta_actual"></span></p>
    <p id="aciertos">Aciertos: <span name="aciertos_acumulados" id="aciertos_acumulados"></span></p>
    <div id="quizContainer"></div>
    <div style="display:none;" id="botones_juego">
        <button id="comodin_publico"><img class="img_publico" src="images/publico.png" alt=""></button>
        <button id="comodin_llamada"><img class="img_llamada" src="images/llamada.png" alt=""></button>
        <button id="comodin_50">50%</button>
        <button id="plantarse">Me planto</button>
    </div>

    <button id="submit" style="display:none;" type="submit">Enviar respuestas</button>
</form>
</body>
</html>