<?php

require 'database.php';


$message = ''; //variable global para los mensajes

//si los campos que recibo a traves del metodo POST no estan vacios se pueden agregar a la bbdd
if (!empty($_POST['edad']) && !empty($_POST['ciudad']) && !empty($_POST['sexo']) && !empty($_POST['nota']) ) {
    $sql = "INSERT INTO encuesta (edad, ciudad, sexo, nota) VALUES (:edad, :ciudad, :sexo, :nota)"; //variable de sql

    $stmt = $conn->prepare($sql);//crear variable para ejecutar el metodo prepare (que ejecuta la consulta sql)

    $stmt->bindParam(':edad', $_POST['edad']);//vincular parametros
    $stmt->bindParam(':ciudad', $_POST['ciudad']);//vincular parametros
    $stmt->bindParam(':sexo', $_POST['sexo']);//vincular parametros
    $stmt->bindParam(':nota', $_POST['nota']);//vincular parametros


    if ($stmt->execute()) { //si esta variable se ejecuta se envía un mensaje de éxito
        $message = 'Gracias por tus respuestas :)';
    } else { //si no se envía un mensaje de error
        $message = 'Ups! algo salió mal';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Encuesta</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require 'partials/header.php' ?>

<?php  if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>
<h1>ENCUESTA</h1>
<form action="encuesta.php" method="post">

    Edad:<input type="text" name="edad" pattern="[0-9]+" required>
    Ciudad:<input type="text" name="ciudad">
    Sexo:<input type="text" name="sexo" placeholder="">
    Valora del 1 al 10:<input type="text"  name="nota" pattern="[0-9]+" required>
    <input type="submit" value="enviar">
</form>
</body>
</html>