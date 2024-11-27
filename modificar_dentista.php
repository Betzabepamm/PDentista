<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Verificamos si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para obtener los datos del dentista por su ID
    $query = "SELECT Id_personal, Nombre, Apellido_paterno, Apellido_materno, Telefono, Correo, Estatus, Especialidad, Usuario, Contrasena 
              FROM personal 
              WHERE Id_personal = '$id' AND Tipo = 'Dentista'";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) == 0) {
        die("No se encontró el dentista.");
    }

    // Recuperamos los datos del dentista
    $row = mysqli_fetch_assoc($result);
} else {
    die("ID no proporcionado.");
}

// Si el formulario es enviado, actualizamos los datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido_paterno = mysqli_real_escape_string($conn, $_POST['apellido_paterno']);
    $apellido_materno = mysqli_real_escape_string($conn, $_POST['apellido_materno']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $estatus = mysqli_real_escape_string($conn, $_POST['estatus']);
    $especialidad = mysqli_real_escape_string($conn, $_POST['especialidad']);
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

    // Consulta para actualizar los datos del dentista
    $update_query = "UPDATE personal 
                     SET Nombre = '$nombre', Apellido_paterno = '$apellido_paterno', Apellido_materno = '$apellido_materno', 
                         Telefono = '$telefono', Correo = '$correo', Estatus = '$estatus', Especialidad = '$especialidad', 
                         Usuario = '$usuario', Contrasena = '$contrasena' 
                     WHERE Id_personal = '$id' AND Tipo = 'Dentista'";

    if (mysqli_query($conn, $update_query)) {
        echo "Dentista actualizado exitosamente.";
        header("Location: ver_dentista.php"); // Redirige a la lista de dentistas
        exit;
    } else {
        echo "Error al actualizar los datos: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Dentista</title>
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

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="tel"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 1rem;
        }

        input[type="submit"] {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #00aaff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a:hover {
            background-color: #00aaff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Modificar Dentista</h1>
        <form method="POST" action="modificar_dentista.php?id=<?php echo $row['Id_personal']; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['Nombre']); ?>" required>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($row['Apellido_paterno']); ?>" required>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($row['Apellido_materno']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($row['Telefono']); ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($row['Correo']); ?>" required>

            <label for="estatus">Estatus:</label>
            <select id="estatus" name="estatus" required>
                <option value="Activo" <?php if ($row['Estatus'] == 'Activo') echo 'selected'; ?>>Activo</option>
                <option value="Inactivo" <?php if ($row['Estatus'] == 'Inactivo') echo 'selected'; ?>>Inactivo</option>
            </select>

            <label for="especialidad">Especialidad:</label>
            <input type="text" id="especialidad" name="especialidad" value="<?php echo htmlspecialchars($row['Especialidad']); ?>" required>

            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($row['Usuario']); ?>" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($row['Contrasena']); ?>" required>

            <input type="submit" value="Guardar Cambios">
        </form>

        <div style="text-align: center;">
            <a href="ver_dentista.php">Volver a la lista de Dentistas</a>
        </div>
    </div>
</body>
</html>
