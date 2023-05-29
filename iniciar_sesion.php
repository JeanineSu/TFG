<?php

session_start();

if (isset($_SESSION['user_id'])) {
    $id_user = $_SESSION['user_id'];
    header('Location: partida.php');
}
require 'database.php';

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (is_array($results) > 0 && $_POST['password'] === $results['password']) {
        $_SESSION['user_id'] = $results['id'];
        header("Location: partida.php");
    } else {
        $message = 'Lo siento, las credenciales no coinciden';
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LOGIN</title>
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

    </style>
</head>

<body>
<?php require 'partials/header.php' ?>


<form action="iniciar_sesion.php" method="post">
    <h1>Iniciar sesión</h1>
    <div class="input_fields">

        <input type="text" name="email" placeholder="Escribe tu email" class="caja">
        <input type="password" name="password" placeholder="Escribe tu contraseña">
        <input type="submit" class="boton_style" value="Confirmar">
    </div>
</form>
<br><br>
<a href="registrarse.php">Registrarse</a>
<a href="index.php">Volver</a>

<?php if (isset($message)): ?>
    <p style="color: white;"><?php echo $message; ?></p>
<?php endif; ?>
</body>

</html>