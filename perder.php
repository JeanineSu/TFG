<!DOCTYPE html>
<html>
<head>
    <title>FinPartida</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="e" href="images/logo.png">

</head>
<body>

<?php if(!empty($user)): ?>
    <br> Bienvenido. <?= $user['apodo'],' ', $user['id'];  ?>
    <br>oleee TE HAS LOGEADO
    <a href="logout.php">
        Logout
    </a>
<?php else: ?>

    <img src="logo/logo.png" alt="logo">
    <h1>¡Oh no! Has perdido :(</h1>

    <h2>Pero no te rindas aún ¿Quieres volver a intentarlo?</h2>

    <a href="partida.php">Volver a intentarlo</a>
    <a href="logout.php">Salir</a>
<?php endif; ?>
</body>
</html>
