<?php
session_start();
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

  <title>Quien quiere ser millonario</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="module" src="codigo.js"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php if(!empty($user)): ?>
    <br> Bienvenido <span id="usuario"> <?= $user['apodo'];  ?></span>
    <a href="editar_perfil.php">Editar perfil</a>
    <br>Comienza el juego
    <a href="logout.php">
        Logout
    </a>
<?php endif; ?>
<br><br><br>
<body>
<h1>Quien quiere ser millonario</h1>
<audio src="./medios/respuesta_incorrecta.mp3" id="incorrecto" ></audio>
<audio src="./medios/intro.mp3" id="intro"></audio>

<div style="display:none;" class="musica">
    <button id="play-button">
        <img class="img_musica" src="images/sonido.png" alt="Texto alternativo">
        Musica</button>
    <div id="player"></div>
</div>

<p id="temporizador">Temporizador: <span id="tiempo"></span></p>

<button id="start">Comenzar juego</button>

<form action="perder.php" method="post" id="quizForm">
    <p style="display:none;" id="nivel_pregunta">Pregunta: <span id="nivel_pregunta_actual"></span></p>
    <p id="aciertos">Aciertos: <span name="aciertos_acumulados" id="aciertos_acumulados"></span></p>
    <div id="quizContainer"></div>
    <div style="display:none;" id="botones_juego">
        <button id="comodin_publico"><img class="img_publico" src="images/publico.png" alt=""> </button>
        <button id="comodin_llamada"><img class="img_llamada" src="images/llamada.png" alt=""> </button>
        <button id="comodin_50">50%</button>
        <button id="plantarse">Me planto</button>
    </div>

    <button id="submit" style="display:none;" type="submit">Enviar respuestas</button>
</form>
</body>
</html>
