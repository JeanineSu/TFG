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
    <br>Comienza el juego
    <a href="logout.php">
        Logout
    </a>
<?php endif; ?>
<br><br><br>
<body>
<h1>Quien quiere ser millonario</h1>
<button>Musica</button>
<p id="temporizador">Temporizador: <span id="tiempo"></span></p>

<button id="start">Comenzar juego</button>

<form action="datos_partida.php" method="post" id="quizForm">
    <p style="display:none;" id="nivel_pregunta">Pregunta: <input name="nivel_pregunta_actual" id="nivel_pregunta_actual"></p>
    <p id="aciertos">Aciertos: <span name="aciertos_acumulados" id="aciertos_acumulados"></span></p>
    <div id="quizContainer"></div>
    <div style="display:none;" id="botones_juego">
        <button id="comodin_publico">Comodin del publico</button>
        <button id="comodin_llamada">Comodin de la llamada</button>
        <button id="comodin_50">50%</button>
        <button id="plantarse">Me planto</button>
    </div>

    <button id="submit" style="display:none;" type="submit">Enviar respuestas</button>
</form>
</body>
</html>
