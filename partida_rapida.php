<?php
//Guardar aqui, en la tabla partida, el id de la partida, el id del usuario, cantidad de preguntas, dinero ganado,
// fecha en la que jugo


require 'database.php';
include 'index.php';

$user_id=$user['id'];
// Obtener la fecha y hora actual
$fecha = date('Y-m-d H:i:s');


$sql = "INSERT INTO partida (user_id, cantidad_preguntas, dinero_ganado, fecha) VALUES (:$user_id, :cantidad_preguntas, 
                                                                             :dinero_ganado, :fecha)";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $_POST['id']);
$stmt->bindParam(':nivel_preguntas', $_POST['nivel_preguntas']);
$stmt->bindParam(':dinero_ganado', $_POST['dinero_ganado']);
$stmt->bindParam(':fecha', $fecha );
$stmt->execute();


?>


<!doctype html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="/proyecto_jeanine/images/logo.png">
    <meta charset="UTF-8">
    <title>Datos de la partida</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require 'partials/header.php' ?>

<?php  if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>
<h1>ENCUESTA</h1>

</body>
</html>