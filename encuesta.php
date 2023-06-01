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

    <style>
        body{
            background: url('./images/background.jpg');
        }
        select{
            outline: none;
            padding: 20px;
            display: block;
            width: 348px;
            border-radius: 50px;
            border: 1px solid #eee;
            margin: 20px auto;
            background-color: #8D8499;
            color: white;
        }
    </style>
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
    <label for="ciudad">Provincia:</label>
    <select id="ciudad" name="ciudad" required>
        <option value="">Seleccione una provincia</option>
        <option value="alava">Álava</option>
        <option value="albacete">Albacete</option>
        <option value="alicante">Alicante</option>
        <option value="almeria">Almería</option>
        <option value="asturias">Asturias</option>
        <option value="avila">Ávila</option>
        <option value="badajoz">Badajoz</option>
        <option value="barcelona">Barcelona</option>
        <option value="burgos">Burgos</option>
        <option value="caceres">Cáceres</option>
        <option value="cadiz">Cádiz</option>
        <option value="cantabria">Cantabria</option>
        <option value="castellon">Castellón</option>
        <option value="ciudadreal">Ciudad Real</option>
        <option value="cordoba">Córdoba</option>
        <option value="cuenca">Cuenca</option>
        <option value="girona">Girona</option>
        <option value="granada">Granada</option>
        <option value="guadalajara">Guadalajara</option>
        <option value="guipuzcoa">Guipúzcoa</option>
        <option value="huelva">Huelva</option>
        <option value="huesca">Huesca</option>
        <option value="jaen">Jaén</option>
        <option value="larioja">La Rioja</option>
        <option value="las_palmas">Las Palmas</option>
        <option value="leon">León</option>
        <option value="lleida">Lleida</option>
        <option value="lugo">Lugo</option>
        <option value="madrid">Madrid</option>
        <option value="malaga">Málaga</option>
        <option value="murcia">Murcia</option>
        <option value="navarra">Navarra</option>
        <option value="ourense">Ourense</option>
        <option value="palencia">Palencia</option>
        <option value="pontevedra">Pontevedra</option>
        <option value="salamanca">Salamanca</option>
        <option value="santacruzdetenerife">Santa Cruz de Tenerife</option>
        <option value="segovia">Segovia</option>
        <option value="sevilla">Sevilla</option>
        <option value="soria">Soria</option>
        <option value="tarragona">Tarragona</option>
        <option value="teruel">Teruel</option>
        <option value="toledo">Toledo</option>
        <option value="valencia">Valencia</option>
        <option value="valladolid">Valladolid</option>
        <option value="vizcaya">Vizcaya</option>
        <option value="zamora">Zamora</option>
        <option value="zaragoza">Zaragoza</option>
    </select>
    <label for="sexo">Sexo:</label>
    <select id="sexo" name="sexo" required>
        <option value=""></option>
        <option value="mujer">Mujer</option>
        <option value="hombre">Hombre</option>
        <option value="no_especifico">No específico</option>
    </select>
    <label for="nota">Valora del 1 al 10</label>
    <input type="number" id="nota" name="nota" inputmode="numeric" min="1" max="10" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Ingrese solo números" required>
    <input type="submit" value="Enviar">
</form>
</body>

</html>