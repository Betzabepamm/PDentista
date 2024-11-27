<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a la Clínica Dental</title>
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

        ul {
            list-style: none;
        }

        /* Header */
        header {
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            padding: 20px 0;
            text-align: center;
        }

        header .logo h1 {
            font-size: 2.5rem;
        }

        nav ul {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        nav ul li {
            margin: 0 20px;
        }

        nav ul li a {
            font-size: 1.2rem;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        nav ul li a:hover {
            color: #00aaff; /* Azul cielo */
        }

        /* Hero Section */
        .hero {
            background-color: #00aaff; /* Azul cielo */
            color: #ffffff;
            padding: 60px 20px;
            text-align: center;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .hero .btn {
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            padding: 10px 30px;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .hero .btn:hover {
            background-color: #00aaff; /* Azul cielo */
        }

        /* Tratamientos Section */
        #tratamientos {
            padding: 40px 20px;
            background-color: #f4f4f4;
            text-align: center;
        }

        #tratamientos h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #003366; /* Azul marino */
        }

        .tratamientos-container {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .tratamiento {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 30%;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .tratamiento:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .tratamiento h3 {
            font-size: 1.5rem;
            color: #003366; /* Azul marino */
        }

        .tratamiento p {
            font-size: 1rem;
            color: #666;
        }

        /* Footer */
        footer {
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }

        footer p {
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <h1>Clínica Dental</h1>
        </div>
        <nav>
            <ul>
                <li><a href="#tratamientos">Tratamientos</a></li>
                <li><a href="registro_usuario2.php">Agendar Cita</a></li>
                <li><a href="portal.php">Iniciar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="hero">
        <h2>Bienvenido a tu Salud Bucal</h2>
        <p>¡Tu sonrisa es lo más importante! Agenda tu cita con nosotros.</p>
        <a href="registro_usuario2.php" class="btn">Agendar Cita</a>
    </section>

    <section id="tratamientos">
        <h2>Nuestros Tratamientos</h2>
        <div class="tratamientos-container">
            <div class="tratamiento">
                <h3>Ortodoncia</h3>
                <p>Mejora la alineación de tus dientes y mejora tu sonrisa.</p>
            </div>
            <div class="tratamiento">
                <h3>Endodoncia</h3>
                <p>Tratamiento de conductos para eliminar infecciones dentales.</p>
            </div>
            <div class="tratamiento">
                <h3>Prótesis</h3>
                <p>Recupera la funcionalidad y estética con prótesis dentales.</p>
            </div>
        </div>
    </section>

    <section id="citas">
        <h2>Agenda tu cita</h2>
        <p>Completa el formulario para agendar tu cita con uno de nuestros dentistas.</p>
        <!-- Formulario de cita aquí -->
    </section>

    <footer>
        <p>&copy; 2024 Clínica Dental. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

