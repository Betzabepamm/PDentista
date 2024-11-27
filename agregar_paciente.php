
<?php
include("conexion.php");
session_start();

// Verificamos si el personal está logueado
if (!isset($_SESSION['id_personal'])) {
    header("Location: login.php");
    exit();
}

// Verificamos si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $diagnostico = $_POST['diagnostico'];
    $usuario = $_POST['usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Preparar la consulta para insertar el nuevo paciente
    $sql = "INSERT INTO pacientes (Nombre, Apellido_paterno, Apellido_materno, Fecha_nacimiento, Genero, Direccion, Telefono, Diagnostico, Usuario, Contrasena) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $nombre, $apellido_paterno, $apellido_materno, $fecha_nacimiento, $genero, $direccion, $telefono, $diagnostico, $usuario, $contrasena);

    if ($stmt->execute()) {
        $success = "Paciente agregado exitosamente.";
    } else {
        $error = "Error al agregar el paciente: " . $conn->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Paciente</title>
    <style>
        /* Reset de algunos márgenes y paddings predeterminados */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Establecer el color de fondo y los colores base */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Estilo del contenedor principal del formulario */
        .form-container {
            width: 50%;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado del contenedor */
        .form-container h2 {
            text-align: center;
            color: #003366; /* Azul marino */
            margin-bottom: 20px;
        }

        /* Estilo de los mensajes de éxito y error */
        .error {
            color: #ff0000;
            text-align: center;
            margin-top: 20px;
        }

        .success {
            color: #28a745;
            text-align: center;
            margin-top: 20px;
        }

        /* Estilo del formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #003366; /* Azul marino */
        }

        /* Campos de texto */
        input[type="text"], input[type="date"], input[type="tel"], input[type="password"] {
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilo de los botones */
        button {
            background-color: #0099ff; /* Azul cielo */
            color: white;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #007acc; /* Azul más oscuro */
        }

        /* Enlace de "Regresar al inicio" */
        a {
            text-decoration: none;
            color: #003366; /* Azul marino */
            font-size: 16px;
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        a:hover {
            color: #0099ff; /* Azul cielo */
        }

        /* Mejorar la visualización en dispositivos pequeños */
        @media (max-width: 768px) {
            .form-container {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Agregar Paciente</h2>

        <!-- Mensajes de éxito o error -->
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <!-- Formulario para agregar paciente -->
        <form action="agregar_paciente.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" required>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>

            <label for="genero">Género:</label>
            <select id="genero" name="genero" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" required>

            <label for="diagnostico">Diagnóstico:</label>
            <input type="text" id="diagnostico" name="diagnostico" required>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Agregar Paciente</button>
        </form>

        <a href="administrador.php">Regresar al inicio</a>
    </div>
</body>
</html>
