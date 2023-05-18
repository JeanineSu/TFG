<?php
session_start();
require 'database.php';
$message='';
?>
<!DOCTYPE html>
<html>
<head>

  <title>Quien quiere ser millonario</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="module" src="codigo.js"></script>
</head>
<body>

<!--    POner hola nombre de usuario-->
  <h1>Quien quiere ser millonario</h1>
  <button>Musica</button>

<?php if(!empty($message)): ?>
    <p> <?= $message ?></p>
<?php endif; ?>

  <p id="temporizador">Temporizador: <span id="tiempo"></span></p>
  <p id="aciertos">Aciertos: <span id="aciertos_acumulados"></span></p>
  <p id="dinero">Dinero que llevas ganado: <span id="dinero_acumulado"></span></p>

  <button called="false" id="start">Comenzar juego</button>
  <p style="display:none;" id="nivel_pregunta">Pregunta: <span id="nivel_pregunta_actual"></span></p>

  <form post="user.php" id="quizForm">
    <div id="quizContainer"></div>
    <span style="display:none;" name="dinero_ganado" id="dinero_ganado">23</span>
    <div style="display:none;" id="botones_juego">
      <button id="comodin_publico">Comodin del publico</button>
      <button id="comodin_llamada">Comodin de la llamada</button>
      <button id="comodin_50">50%</button>
      <button id="plantarse">Me planto</button>
    </div>

    <button style="display:none;" type="submit">Enviar respuestas</button>
  </form>
</body>
</html>
