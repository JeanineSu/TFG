<?php
session_start();
require 'database.php';

if (isset($_SESSION['user_id'])) {
    // Verificar si el perfil ya ha sido editado
    // if (isset($_SESSION['profile_edited'])) {
    //     echo "El perfil ya ha sido editado.";
    //     exit;
    // }

    // Verificar si se ha enviado un formulario para actualizar los datos
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener los datos enviados por el formulario
        $newApodo = $_POST['new_apodo'];
        $newEmail = $_POST['new_email'];
        $newPassword = $_POST['new_password'];

        // Validar y actualizar los datos en la base de datos
        $stmt = $conn->prepare('UPDATE users SET apodo = :apodo, email = :email, password = :password WHERE id = :id');
        $stmt->bindParam(':apodo', $newApodo);
        $stmt->bindParam(':email', $newEmail);
        $stmt->bindParam(':password', $newPassword);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->execute()) { //si esta variable se ejecuta se envía un mensaje de éxito
            $message = 'Gracias por tus respuestas :)';
        } else { //si no se envía un mensaje de error
            $message = 'Ups! algo salió mal';
        }
        // Marcar el perfil como editado
        // $_SESSION['profile_edited'] = true;

        // Obtener los datos del usuario actualizados
        $stmt = $conn->prepare('SELECT id, apodo, email, password FROM users WHERE id = :id');
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // Obtener los datos del usuario
        $stmt = $conn->prepare('SELECT id, apodo, email, password FROM users WHERE id = :id');
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Editar Perfil</title>


    <style>
        body{
            background: url('./images/background.jpg');
        }
    </style>
</head>

<body>
<h1>Editar perfil</h1>
<form action="editar_perfil.php" method="POST">
    <div>
        <label for="new_apodo">Nuevo Apodo:</label>
        <input type="text" id="new_apodo" name="new_apodo" required value="<?php echo $user['apodo']; ?>">
    </div>
    <div>
        <label for="new_email">Nuevo Email:</label><br><br>
        <input type="email" id="new_email" name="new_email" required value="<?php echo $user['email']; ?>"><br><br>
    </div>
    <div>
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required value="<?php echo $user['password']; ?>">
    </div>
    <div>
        <input type="submit" value="Guardar Cambios" formaction="partida.php">
    </div>
</form>
</body>

</html>