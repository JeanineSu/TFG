<?php

require 'database.php';


$message = ''; //variable global para los mensajes

//si los campos que recibo a traves del metodo POST no estan vacios se pueden agregar a la bbdd
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $sql = "INSERT INTO users (apodo, email, password) VALUES (:apodo, :email, :password)"; //variable de sql

    $stmt = $conn->prepare($sql);//crear variable para ejecutar el metodo prepare (que ejecuta la consulta sql)

    $stmt->bindParam(':apodo', $_POST['apodo']);//vincular parametros
    $stmt->bindParam(':email', $_POST['email']);//vincular parametros
    $stmt->bindParam(':password', $_POST['password']);
//    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);//almacenar la contraseña en una variable y cifrarla
//    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) { //si esta variable se ejecuta se envía un mensaje de éxito
        $message = 'oleee usuario creado';
    } else { //si no se envía un mensaje de error
        $message = 'Sorry there must have been an issue creating your account';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<?php require 'partials/header.php' ?>

<?php  if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>
<h1>Registrarse</h1>
<span><a href="iniciar_sesion.php">Iniciar sesión</a></span>
<form action="registrarse.php" method="post">


    <input type="text" name="apodo" placeholder="Escribe un apodo">
    <input type="text" name="email" placeholder="Ingresa el email">
    <input type="password" name="password" placeholder="Escribe una contraseña">

    <input type="submit" value="enviar">
</form>
</body>
</html>