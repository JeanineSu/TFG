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
    </style>
</head>

<body>
<img src="./images/logo.png" alt="Quien quiere ser millonario" width="300px">
<h1>¡ENHORABUENA!</h1>

<h2>Has ganado 1.000.000 €</h2>
<a href="partida.php">Volver a jugar</a>
<a href="encuesta.php">Encuesta de satisfacción</a>
<a href="logout.php">Salir</a>

</body>
</html>
