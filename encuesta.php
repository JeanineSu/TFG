<?php

require 'database.php';


$message = ''; //variable global para los mensajes

//si los campos que recibo a traves del metodo POST no estan vacios se pueden agregar a la bbdd
if (!empty($_POST['edad']) && !empty($_POST['ciudad']) && !empty($_POST['sexo']) && !empty($_POST['nota'])) {
    $sql = "INSERT INTO encuesta (edad, ciudad, sexo, nota) VALUES (:edad, :ciudad, :sexo, :nota)"; //variable de sql

    $stmt = $conn->prepare($sql); //crear variable para ejecutar el metodo prepare (que ejecuta la consulta sql)
    $stmt->bindParam(':edad', $_POST['edad']);
    $stmt->bindParam(':ciudad', $_POST['ciudad']);
    $stmt->bindParam(':sexo', $_POST['sexo']);
    $stmt->bindParam(':nota', $_POST['nota']);
    $stmt->execute();

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

<?php if (!empty($message)) : ?>
    <p style="color: white"><?= $message ?></p>
<?php endif; ?>
<h1>Encuesta</h1>
<form action="encuesta.php" method="post">

    <label for="edad">Edad:</label>
    <input type="number" name="edad" id="edad" inputmode="numeric" min="10" max="100" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Ingrese solo números" required>
    <label for="ciudad">Ciudad</label>
    <input type="text" id="ciudad" name="ciudad" required>
    <label for="sexo">Sexo</label>
    <input type="text" id="sexo" name="sexo" required>
    <label for="nota">Valora del 1 al 10</label>
    <input type="number" id="nota" name="nota" inputmode="numeric" min="1" max="10" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Ingrese solo números" required>
    <input type="submit" value="Enviar">
</form>
</body>

</html>