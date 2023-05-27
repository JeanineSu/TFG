<!--Cabecera para poder volver al inicio de la app siempre que se quiera-->
<style>
    a {
        color: white !important;
    }

    header {
        max-height: 20px;
    }

    header,
    nav>ul {
        display: flex;
        flex-direction: row;
        justify-content: right;
        align-items: center;

    }

    li {
        list-style: none;
    }

    .logo {
        margin-left: 50px;
    }

    nav {
        margin-right: 50px;
    }

    nav>ul {
        gap: 50px;
        font-size: 1.2em;
    }
</style>

<header>
    <nav>
        <ul>
            <li>
                <a href="index.php">Inicio</a>

            </li>
            <li>
                <a href="partida.php">Partida rápida</a>

            </li>
            <li>
                <a href="iniciar_sesion.php">Iniciar sesión</a>

            </li>
            <li>
                <a href="registrarse.php">Registrarse</a>

            </li>
            <li>
                <a href="encuesta.php">Encuesta de satisfacción</a>

            </li>
        </ul>
    </nav>
</header>