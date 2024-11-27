<?php
session_start();

// Verificamos si la sesión está iniciada y si el usuario es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    // Si no es administrador o no hay sesión, redirigimos al login
    header("Location: login.php");
    exit;
}

include('conexion.php');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Estilos de la cabecera */
        header {
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2.5rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin-top: 10px;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #ffffff;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #00aaff; /* Azul cielo */
        }

        /* Estilos de la sección principal */
        section {
            padding: 40px 20px;
            text-align: center;
        }

        section h2 {
            font-size: 2rem;
            color: #003366; /* Azul marino */
            margin-bottom: 20px;
        }

        section p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        /* Estilos de los botones */
        button {
            padding: 15px 25px;
            margin: 10px;
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            font-size: 1.1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00aaff; /* Azul cielo */
        }

        /* Estilos del pie de página */
        footer {
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel Administrador</h1>
        <nav>
            <ul>
                <li><a href="administrador.php">Inicio</a></li>
                <li><a href="index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Bienvenido, Administrador</h2>
        <p>Desde este panel, puedes gestionar la información de los pacientes y del personal.</p>

        <!-- Botones para acceder a la información de recepcionistas y dentistas -->
        <div>
            <h3>Gestión de Personal</h3>
            <p>Accede y administra la información de los empleados de la clínica.</p>
            <button onclick="window.location.href='ver_recepcionista.php'">Ver Recepcionistas</button>
            <button onclick="window.location.href='ver_dentista.php'">Ver Dentistas</button>
        </div>

        <!-- Botones para gestionar los pacientes -->
        <div>
            <h3>Gestión de Pacientes</h3>
            <p>Visualiza y administra los pacientes que visitan la clínica.</p>
            <button onclick="window.location.href='ver_pacientes.php'">Ver Pacientes</button>
        </div>
        <div>
            <h3>Gestión de citas</h3>
            <p>Visualiza y administra las citas .</p>
            <button onclick="window.location.href='ver_citas.php'">Ver Citas</button>
        </div>
       




    </section>

    <footer>
        <p>&copy; 2024 Clínica Dental. Todos los derechos reservados.</p>
    </footer>
</body>
</html>


