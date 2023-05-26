<?php

session_start();

if (isset($_SESSION['user_id'])) {
    $id_user= $_SESSION['user_id'];
    header('Location: partida.php');
}
require 'database.php';

if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, email, password FROM users WHERE email = :email');
    $records->bindParam(':email', $_POST['email']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    $message = '';

    if (count($results) > 0 && $_POST['password'] === $results['password']) {
        $_SESSION['user_id'] = $results['id'];
        header("Location: partida.php");
    } else {
        $message = 'Sorry, those credentials do not match';
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
</head>
<body>
<?php require 'partials/header.php'?>
<h1>Iniciar sesión</h1>
<span><a href="registrarse.php">Registrarse</a></span>

<?php if(!empty($user)): ?>
    <p> <?= $message ?></p>
<?php endif; ?>

<form action="iniciar_sesion.php" method="post">
    <input type="text" name="email" placeholder="Escribe tu email" class="caja">
    <input type="password" name="password" placeholder="Escribe tu contraseña">
    <input type="submit" value="Enviar">
</form>
</body>
</html>