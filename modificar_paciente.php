<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Verificar si se recibió el ID del paciente
if (!isset($_GET['id'])) {
    die("ID de paciente no especificado.");
}

$id_paciente = intval($_GET['id']);

// Consultar los datos actuales del paciente
$query = "SELECT * FROM pacientes WHERE Id_paciente = $id_paciente";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Paciente no encontrado.");
}

$paciente = mysqli_fetch_assoc($result);

$success_message = ""; // Variable para manejar el mensaje de éxito

// Procesar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido_paterno = mysqli_real_escape_string($conn, $_POST['apellido_paterno']);
    $apellido_materno = mysqli_real_escape_string($conn, $_POST['apellido_materno']);
    $fecha_nacimiento = mysqli_real_escape_string($conn, $_POST['fecha_nacimiento']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);

    $query_update = "UPDATE pacientes SET 
                        Nombre = '$nombre',
                        Apellido_paterno = '$apellido_paterno',
                        Apellido_materno = '$apellido_materno',
                        Fecha_nacimiento = '$fecha_nacimiento',
                        Genero = '$genero',
                        Telefono = '$telefono'
                     WHERE Id_paciente = $id_paciente";

    if (mysqli_query($conn, $query_update)) {
        $success_message = "¡Los datos se han modificado correctamente!";
    } else {
        $error_message = "Error al actualizar el paciente: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Paciente</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #003366;
        }

        form, .success {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #003366;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #00aaff;
        }

        .error {
            color: red;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .success a {
            margin-top: 20px;
            display: inline-block;
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .success a:hover {
            background-color: #00aaff;
        }
    </style>
</head>
<body>
    <h1>Modificar Paciente</h1>

    <?php if (!empty($success_message)): ?>
        <div class="success">
            <p><?php echo $success_message; ?></p>
            <a href="ver_pacientes.php">Volver</a>
        </div>
    <?php else: ?>
        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($paciente['Nombre']); ?>" required>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?php echo htmlspecialchars($paciente['Apellido_Paterno']); ?>" required>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="<?php echo htmlspecialchars($paciente['Apellido_Materno']); ?>" required>

            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo $paciente['Fecha_nacimiento']; ?>" required>

            <label for="genero">Género:</label>
            <select name="genero" id="genero" required>
                <option value="Masculino" <?php echo $paciente['Genero'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo $paciente['Genero'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                <option value="Otro" <?php echo $paciente['Genero'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
            </select>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" value="<?php echo $paciente['Telefono']; ?>" required>

            <button type="submit" class="button">Guardar Cambios</button>
            <a href="ver_pacientes.php" class="button">Cancelar</a>
        </form>
    <?php endif; ?>
</body>
</html>
