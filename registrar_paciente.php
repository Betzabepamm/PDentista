<?php
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $diagnostico = "Pendiente"; // Valor por defecto para diagnóstico

    // Verificar si el usuario ya existe en la tabla personal o pacientes
    $sql_check = "SELECT * FROM personal WHERE Usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $usuario);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $error = "El usuario ya existe. Elige otro nombre.";
    } else {
        // Iniciar una transacción para insertar datos en ambas tablas
        $conn->begin_transaction();
        try {
            // Insertar en la tabla personal
            $sql_personal = "INSERT INTO personal (Nombre, Apellido_paterno, Apellido_materno, Usuario, Contrasena, Tipo, Estatus) 
                             VALUES (?, ?, ?, ?, ?, 'paciente', 'activo')";
            $stmt_personal = $conn->prepare($sql_personal);
            $stmt_personal->bind_param("sssss", $nombre, $apellido_paterno, $apellido_materno, $usuario, $contrasena);
            $stmt_personal->execute();

            // Insertar en la tabla pacientes
            $sql_pacientes = "INSERT INTO pacientes (Nombre, Apellido_paterno, Apellido_materno, Fecha_nacimiento, Genero, Direccion, Telefono, Diagnostico, Usuario, Contrasena) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_pacientes = $conn->prepare($sql_pacientes);
            $stmt_pacientes->bind_param(
                "ssssssisss",
                $nombre,
                $apellido_paterno,
                $apellido_materno,
                $fecha_nacimiento,
                $genero,
                $direccion,
                $telefono,
                $diagnostico,
                $usuario,
                $contrasena
            );
            $stmt_pacientes->execute();

            // Confirmar la transacción
            $conn->commit();
            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        } catch (Exception $e) {
            // Si algo falla, deshacer la transacción
            $conn->rollback();
            $error = "Error al registrar usuario: " . $e->getMessage();
        }
    }

    //$stmt_check->close();
}
//$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f8ff; /* Azul cielo claro */
            margin: 0;
            padding: 0;
            color: #333;
        }
        .registro-container {
            width: 100%;
            max-width: 450px;
            margin: 60px auto;
            padding: 30px;
            background-color: #ffffff; /* Blanco */
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            text-align: center;
        }
        h2 {
            font-size: 2rem;
            color: #002855; /* Azul marino */
            margin-bottom: 20px;
        }
        #mensaje {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            display: none; /* Oculto por defecto */
        }
        #mensaje.success {
            background-color: #cce7ff; /* Azul cielo claro */
            color: #002855; /* Azul marino */
            border: 1px solid #80bfff;
        }
        #mensaje.error {
            background-color: #ffc9c9; /* Fondo rojo claro */
            color: #850000; /* Rojo oscuro */
            border: 1px solid #ff8080;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            padding: 12px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        input:focus, select:focus {
            border-color: #80bfff; /* Azul cielo */
            box-shadow: 0 0 8px rgba(0, 128, 255, 0.5);
        }
        button {
            background-color: #002855; /* Azul marino */
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #00509e; /* Azul intermedio */
        }
        a {
            font-size: 1rem;
            color: #002855; /* Azul marino */
            text-decoration: none;
            transition: color 0.3s ease;
        }
        a:hover {
            color: #00509e; /* Azul intermedio */
        }
        @media (max-width: 600px) {
            .registro-container {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="registro-container">
        <h2>Registro de Usuario</h2>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <form action="registrar_paciente.php" method="POST">
            <!-- Datos del usuario -->
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido_paterno" placeholder="Apellido Paterno" required>
            <input type="text" name="apellido_materno" placeholder="Apellido Materno" required>
            <input type="date" name="fecha_nacimiento" placeholder="Fecha de Nacimiento" required>
            <select name="genero" required>
                <option value="">Selecciona el género</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
            <input type="text" name="direccion" placeholder="Dirección" required>
            <input type="text" name="telefono" placeholder="Teléfono (10 dígitos)" required>
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <!-- Botón para registrarse -->
            <button type="submit">Registrar</button>
        </form>

        <p><a href="login_usuario.php">¿Ya tienes cuenta? Inicia sesión aquí</a></p>
    </div>
          <form action="recepcionista.php" method="get">
            <button type="submit">Regresar</button>
    </div>
</body>
</html> 