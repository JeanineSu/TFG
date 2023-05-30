<?php
session_start();
require 'database.php';
if (isset($_SESSION['user_id'])){
    $records=$conn->prepare('SELECT id, apodo, email, password FROM users WHERE id =:id');
    $records->bindParam(':id', $_SESSION['user_id']);
    $records->execute();
    $results=$records->fetch(PDO::FETCH_ASSOC);

    $user=null;

    if (count($results) > 0) {
        $user = $results;
    }
}

$server = 'localhost';
$username = 'root';
$password = '';
$database = 'usuarios_database';

try {
    $conn = new PDO("mysql:host=$server; dbname=$database;", $username, $password);
} catch (PDOException $e) {
    die('Connection Failed: ' . $e->getMessage());
}


$message = ''; //variable global para los mensajes

//si los campos que recibo a traves del metodo POST no estan vacios se pueden agregar a la bbdd
if (!empty($_POST['provincia']) && !empty($_POST['codigopostal']) && !empty($_POST['calle'])) {
    $sql = "INSERT INTO direccion (provincia, codigopostal, calle) VALUES (:provincia, :codigopostal, :calle)"; //variable de sql

    $stmt = $conn->prepare($sql); //crear variable para ejecutar el metodo prepare (que ejecuta la consulta sql)
    $stmt->bindParam(':provincia', $_POST['provincia']);
    $stmt->bindParam(':codigopostal', $_POST['codigopostal']);
    $stmt->bindParam(':calle', $_POST['calle']);
    $stmt->execute();

}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Victoria</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="/images/logo.png">
    <style>
        body {
            background: url('./images/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            padding-top: 0em;
            max-height: 100vh;
            background-attachment: fixed;
        }
        h1 {
            font-family: Arial, sans-serif;
            font-size: 4rem;
            font-weight: bold;
            color: white;
        }

        a {
            text-decoration: none;
            color: white;
            cursor: pointer;
            display: inline-block;
            margin: 20px;
            padding: 15px;
            border: 2px solid white;
            border-radius: 50px;
            font-size: 20px;
            background: #2d0e53;
            box-shadow: -1px -1px 5px #fff, 2px 2px 20px #1f1e1e, inset 0px 0px 0px #fff, inset 0px 0px 0px #1f1e1e;
        }

        a:hover {
            box-shadow: -1px -1px 25px #fff, 2px 2px 20px #1f1e1e, inset 0px 0px 0px #fff, inset 0px 0px 0px #1f1e1e;

        }

        #formulario {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }

        .form-container {
            margin-bottom: 50px;
            max-width: 400px;
            padding: 20px;
            background-color: #6a4c93;
            border-radius: 31px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center; /* Agregamos esta línea para centrar el contenido */
            border: 2px solid black;
        }

        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-container p {
            color: white;
            font-weight: bold;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: black;
        }

        .form-container select,
        .form-container input[type="text"],
        .form-container input[type="number"],
        .form-container button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 50px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 10px;
        }

        .form-container button {
            background-color: #ffbd00;
            color: #fff;
            cursor: pointer;
            margin-left: auto;
            display: block;
            font-weight: bold;
        }

        .form-container button:hover {
            background-color: #ffca28;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .hidden {
            display: none;
        }
        .close-cross {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .close-cross:before,
        .close-cross:after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 16px;
            height: 2px;
            background-color: #333;
            transition: background-color 0.3s ease;
        }

        .close-cross:before {
            transform: translate(-50%, -50%) rotate(45deg);
        }

        .close-cross:after {
            transform: translate(-50%, -50%) rotate(-45deg);
        }

        .close-cross:hover:before,
        .close-cross:hover:after {
            background-color: #999;
        }

    </style>
</head>

<body>
<img src="./images/trofeo.png" alt="Quien quiere ser millonario" width="250px">

<?php if(!empty($user)): ?>
    <h1>¡ENHORABUENA!</h1> <span id="usuario" style="color: white; font-family: Arial, sans-serif; font-size: 3rem; font-weight: bold"> <?= $user['apodo'];  ?></span>
    <br><br>
<?php endif; ?>

<h2>Has ganado 1.000.000 €</h2>

<a href="encuesta.php">Encuesta de satisfacción</a>
<a href="logout.php">Salir</a>

<button onclick="mostrarFormulario()">Obtén tu premio</button>

<form action="ganar.php" method="post" id="formulario">

    <div class="form-container">
        <p>Si deseas recibir un pequeño obsequio rellena los siguientes campos</p>
        <div class="close-cross" onclick="cerrarFormulario()"></div>
        <button type="button" onclick="verTrofeo()">Ver trofeo</button>
        <button type="button" onclick="habilitarCampos()">Enviar a casa</button>
        <?php if(!empty($user)): ?>
            <input type="text" id="email" name="email" required value="<?php echo $user['email']; ?>" readonly>

        <?php endif; ?>
        <label for="provincia">Provincia:</label>
        <select id="provincia" name="provincia" disabled>
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
        <label for="codigopostal">Codigo Postal:</label>
        <input type="number" id="codigopostal" name="codigopostal" placeholder="Codigo Postal" disabled>
        <label for="calle">Calle:</label>
        <input type="text" id="calle" name="calle" placeholder="Calle" disabled>

        <button type="submit" id="envio" name="envio" disabled>Confirmar envío a casa</button>

    </div>
</form>
<?php if (isset($message)): ?>
    <p style="color: white;"><?php echo $message; ?></p>
<?php endif; ?>
<script>
    function mostrarFormulario() {
        var formulario = document.getElementById("formulario");
        formulario.style.display = "block";
    }

    function verTrofeo() {
        // Aquí puedes añadir la lógica para descargar la imagen
        // Por ejemplo:
        window.location.href = "images/logo.png";
    }

    function habilitarCampos() {
        document.getElementById("provincia").disabled = false;
        document.getElementById("codigopostal").disabled = false;
        document.getElementById("calle").disabled = false;
        document.getElementById("envio").disabled = false;
    }
    function cerrarFormulario() {
        var formContainer = document.querySelector(".form-container");
        formContainer.classList.add("hidden");
    }

</script>


</body>
</html>
