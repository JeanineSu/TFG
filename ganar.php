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
            padding-top: 4em;
            max-height: 100vh;
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
        /* Estilo para la ventana emergente */
        .popup-container {
            display: none;
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .popup-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-container {
            display: flex;
            justify-content: flex-end;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
    </style>
</head>

<body>
<img src="./images/logo.png" alt="Quien quiere ser millonario" width="300px">
<h1>¡ENHORABUENA!</h1>

<h2>Has ganado 1.000.000 €</h2>

<a href="encuesta.php">Encuesta de satisfacción</a>
<a href="logout.php">Salir</a>


<!-- Botón Datos -->
<a href="#" id="datos-btn">Datos</a>

<!-- Ventana emergente -->
<div class="overlay" id="overlay"></div>
<div class="popup-container" id="popup">
    <h3 class="popup-title">Ingrese los datos de la tarjeta</h3>
    <div class="form-group">
        <label for="numero-tarjeta">Número de tarjeta:</label>
        <input type="text" id="numero-tarjeta">
    </div>
    <div class="form-group">
        <label for="fecha-expiracion">Fecha de expiración (MM/YY):</label>
        <input type="month" value=" - " id="fecha-expiracion">
    </div>
    <div class="form-group">
        <label for="cvc">CVC:</label>
        <input type="text" id="cvc" pattern="[0-9]{3}" maxlength="3" inputmode="numeric" required>
    </div>
    <div class="btn-container">
        <button class="btn" id="submit-btn">Enviar</button>
        <button class="btn" id="cancel-btn">Cancelar</button>
    </div>
</div>

<script>
    // Obtener elementos de la ventana emergente y el botón Datos
    var overlay = document.getElementById("overlay");
    var popup = document.getElementById("popup");
    var datosBtn = document.getElementById("datos-btn");

    // Mostrar la ventana emergente al hacer clic en el botón Datos
    datosBtn.addEventListener("click", function() {
        overlay.style.display = "block";
        popup.style.display = "block";
    });

    // Cerrar la ventana emergente al hacer clic en el botón Cancelar o fuera de la ventana
    overlay.addEventListener("click", function() {
        overlay.style.display = "none";
        popup.style.display = "none";
    });
    document.getElementById("cancel-btn").addEventListener("click", function() {
        overlay.style.display = "none";
        popup.style.display = "none";
    });
</script>
</body>
</html>
