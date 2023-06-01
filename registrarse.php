<?php

require 'database.php';

$message = ''; // Variable global para los mensajes

// Si los campos que recibo a través del método POST no están vacíos, se pueden agregar a la base de datos
if (!empty($_POST['email']) && !empty($_POST['password'])) {

    // Verificar si el email ya existe en la base de datos
    $email = $_POST['email'];
    $checkEmail = "SELECT COUNT(*) FROM users WHERE email = :email";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $emailCount = $stmt->fetchColumn();

    if ($emailCount > 0) {
        // El email ya existe en la base de datos
        $message = 'El email ya está registrado';
    } else {

        // El email no existe en la base de datos, procedemos con la inserción
        $sql = "INSERT INTO users (apodo, email, password) VALUES (:apodo, :email, :password)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':apodo', $_POST['apodo']);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $_POST['password']);

        if ($stmt->execute()) {
            $message = 'Usuario creado con éxito';
        } else {
            $message = 'Lo sentimos, no se ha podido crear su usuario';
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <link rel="icon" type="image/png" href="/proyecto_jeanine/images/logo.png">
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        input[type="text"],
        input[type="submit"],
        input[type="password"] {
            border: none;
        }
        body{
            background: url('./images/background.jpg');
        }

        form {
            padding-top: 3rem;
        }
        a:hover {
            text-shadow: 0 0 8px #fff, 0 0 12px #fff, 0 0 16px #fff;
        }
    </style>
</head>

<body>
<?php require 'partials/header.php' ?>

<form action="registrarse.php" method="post">
    <h1>Registrarse</h1>
    <input type="text" name="apodo" placeholder="Escribe un apodo" pattern="^[^&quot;]*$" required>
    <input type="email" name="email" placeholder="Ingresa el email" required>
    <input type="password" name="password" placeholder="Escribe una contraseña" pattern="^[^&quot;]*$" required>

    <input type="submit" class="boton_style" value="Confirmar">
    <?php if (isset($message)): ?>
        <p style="color: white;"><?php echo $message; ?></p>
    <?php endif; ?>
</form>
<br>
<div class="form-opt">
    <a href="index.php">Volver</a>
</div>
</html>