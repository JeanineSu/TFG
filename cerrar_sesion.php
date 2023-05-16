<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['partida_rapida'])) {
        // Redirigir a la página deseada
        header('Location: partida_rapida.php');
        exit; // Asegurar que no se procese más código después de la redirección
    }

    if (isset($_POST['iniciar_sesion'])) {
        header('Location: iniciar_sesion.php');
        exit;
        // Acción de "Iniciar sesión" seleccionada
        // Puedes redirigir a otra página o ejecutar una función específica en PHP
    }

    if (isset($_POST['registrarse'])) {
        header('Location: registrarse.php');
        exit;
        // Acción de "Registrarse" seleccionada
        // Puedes redirigir a otra página o ejecutar una función específica en PHP
    }
}

$mensaje = '';
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bienvenido</title>
</head>
<body>
<h1>Bienvenido</h1>

<?php if (!empty($mensaje)) : ?>
    <p><?php echo $mensaje; ?></p>
<?php endif; ?>

<form method="POST">
    <button type="submit" name="partida_rapida">Partida rápida</button>
    <button type="submit" name="iniciar_sesion">Iniciar sesión</button>
    <button type="submit" name="registrarse">Registrarse</button>
</form>
</body>
</html>
