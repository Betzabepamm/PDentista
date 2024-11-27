<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal de Acceso</title>
    <style>
        /* Estilos generales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Fondo blanco */
            color: #333;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Estilos del contenedor principal */
        .container {
            width: 100%;
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .container h1 {
            font-size: 2.5rem;
            color: #003366; /* Azul marino */
            margin-bottom: 20px;
        }

        .container p {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 30px;
        }

        /* Estilos del contenedor de botones */
        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .role-btn {
            padding: 15px;
            font-size: 1.2rem;
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .role-btn:hover {
            background-color: #00aaff; /* Azul cielo */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido al Sistema</h1>
        <p>Por favor selecciona tu rol para continuar:</p>

        <div class="btn-container">
            <!-- Botón para redirigir al login del paciente -->
            <button class="role-btn" id="btnPaciente">Paciente</button>

            <!-- Botón para redirigir al login del administrador/dentista/recepcionista -->
            <button class="role-btn" id="btnAdmin">Administrador / Dentista / Recepcionista</button>


            <li><a href="index.php">Regresar</a></li>
        </div>
    </div>

    <script>
        // Redirigir a la página de login del paciente
        document.getElementById('btnPaciente').addEventListener('click', function() {
            window.location.href = 'login_usuario.php'; // Redirige a la página de login del paciente
        });

        // Redirigir a la página de login del administrador, dentista o recepcionista
        document.getElementById('btnAdmin').addEventListener('click', function() {
            window.location.href = 'login.php'; // Redirige a la página de login de administrador, dentista o recepcionista
        });
    </script>
</body>
</html>


