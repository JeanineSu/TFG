<?php

require 'database.php';


$message = ''; //variable global para los mensajes

//si los campos que recibo a traves del metodo POST no estan vacios se pueden agregar a la bbdd
if (!empty($_POST['email']) && !empty($_POST['password'])) {
    $sql = "INSERT INTO users (apodo, email, password) VALUES (:apodo, :email, :password)"; //variable de sql

    $stmt = $conn->prepare($sql); //crear variable para ejecutar el metodo prepare (que ejecuta la consulta sql)

    $stmt->bindParam(':apodo', $_POST['apodo']); //vincular parametros
    $stmt->bindParam(':email', $_POST['email']); //vincular parametros
    $stmt->bindParam(':password', $_POST['password']);
    //    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);//almacenar la contraseña en una variable y cifrarla
    //    $stmt->bindParam(':password', $password);

    if ($stmt->execute()) { //si esta variable se ejecuta se envía un mensaje de éxito
        $message = 'Usuario creado';
    } else { //si no se envía un mensaje de error
        $message = 'Lo sentimos, no se ha podido crear su usuario';
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

        /*.message {*/
        /*    position: absolute;*/
        /*    bottom: 0;*/
        /*    width: 100%;*/
        /*    height: 50px;*/
        /*    background-color: #ffb703;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    justify-content: center;*/

        /*}*/

        /*.close-icon {*/
        /*    position: absolute;*/
        /*    right: 0;*/
        /*    font-weight: bold;*/
        /*    margin-right: 30px;*/
        /*    font-size: 1.2rem;*/
        /*    cursor: pointer;*/
        /*}*/

        /*.info-icon {*/
        /*    margin-right: 30px;*/
        /*}*/
    </style>
</head>

<body>
<?php require 'partials/header.php' ?>

<form action="registrarse.php" method="post">
    <h1>Registrarse</h1>
    <input type="text" name="apodo" placeholder="Escribe un apodo">
    <input type="text" name="email" placeholder="Ingresa el email">
    <input type="password" name="password" placeholder="Escribe una contraseña">

    <input type="submit" class="boton_style" value="Confirmar">
    <?php if (isset($message)): ?>
        <p style="color: white;"><?php echo $message; ?></p>
    <?php endif; ?>
</form>
<br><br>
<div class="form-opt">
    <a href="iniciar_sesion.php">Iniciar sesión</a>
    <a href="index.php">Volver</a>
</div>
<!---->
<?php //if (!empty($message)) : ?>
<!--    <div class="message">-->
<!--        <img src="https://cdn-icons-png.flaticon.com/512/157/157933.png" width="30px" alt="" class="info-icon">-->
<!--        <span>--><?php //= $message ?><!--</span>-->
<!--        <span class="close-icon" onclick="closeMessage()">X</span>-->
<!--    </div>-->
<?php //endif; ?>
<!---->
<!---->
<!--</body>-->
<!---->
<!--<script>-->
<!--    const closeMessage = () => {-->
<!--        const messageContainer = document.querySelector('.message')-->
<!--        const closeIcon = document.querySelector('.close-icon');-->
<!--        messageContainer.style.display = 'none';-->
<!--    }-->
<!--</script>-->


</html>