<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Verificar si el id está en la URL
if (isset($_GET['id'])) {
    $id_personal = $_GET['id'];

    // Obtener los datos del recepcionista
    $query = "SELECT * FROM personal WHERE Id_personal = $id_personal AND Tipo = 'Recepcionista'";
    $result = mysqli_query($conn, $query);
    $recepcionista = mysqli_fetch_assoc($result);

    if (!$recepcionista) {
        echo "Recepcionista no encontrado.";
        exit;
    }

    // Si el formulario es enviado, actualizamos los datos
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $apellido_paterno = mysqli_real_escape_string($conn, $_POST['apellido_paterno']);
        $apellido_materno = mysqli_real_escape_string($conn, $_POST['apellido_materno']);
        $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
        $correo = mysqli_real_escape_string($conn, $_POST['correo']);
        $estatus = mysqli_real_escape_string($conn, $_POST['estatus']);
        $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
        $contrasena = mysqli_real_escape_string($conn, $_POST['contrasena']);

        // Actualizar los datos en la base de datos
        $update_query = "UPDATE personal SET 
                            Nombre = '$nombre', 
                            Apellido_paterno = '$apellido_paterno', 
                            Apellido_materno = '$apellido_materno', 
                            Telefono = '$telefono', 
                            Correo = '$correo', 
                            Estatus = '$estatus', 
                            Usuario = '$usuario', 
                            Contrasena = '$contrasena' 
                        WHERE Id_personal = $id_personal";

        if (mysqli_query($conn, $update_query)) {
            header("Location: ver_recepcionista.php"); // Redirige después de la actualización
            exit;
        } else {
            echo "Error al actualizar los datos: " . mysqli_error($conn);
        }
    }
} else {
    echo "No se ha especificado un ID de recepcionista.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Recepcionista</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #003366;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 1rem;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"], input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
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
        }

        input[type="submit"]:hover {
            background-color: #005499;
        }

        .back-link {
            text-align: center;
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #003366;
        }

        .back-link:hover {
            color: #005499;
        }
    </style>
</head>
<body>

    <h1>Modificar Recepcionista</h1>

    <form action="modificar_recepcionista.php?id=<?php echo $recepcionista['Id_personal']; ?>" method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($recepcionista['Nombre']); ?>" placeholder="Nombre" required>

    <label for="apellido_paterno">Apellido Paterno</label>
    <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?php echo htmlspecialchars($recepcionista['Apellido_Paterno']); ?>" placeholder="Apellido paterno" required>



    <label for="apellido_materno">Apellido Materno:</label>
    <input type="text" id="apellido_materno" name="apellido_materno" value="<?php echo htmlspecialchars($recepcionista['Apellido_Materno']); ?>"  placeholder="Apellido materno" required>


    <label for="telefono">Teléfono:</label>
    <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($recepcionista['Telefono']); ?>" placeholder="Teléfono" required>

    <label for="correo">Correo Electrónico:</label>
    <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($recepcionista['Correo']); ?>" placeholder="Correo Electrónico" required>

    <label for="usuario">Nombre de Usuario:</label>
    <input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($recepcionista['Usuario']); ?>" placeholder="Nombre de Usuario" required>

    <label for="contrasena">Contraseña:</label>
    <input type="password" id="contrasena" name="contrasena" value="<?php echo htmlspecialchars($recepcionista['Contrasena']); ?>" placeholder="Contraseña" required>

    <label for="estatus">Estatus:</label>
    <input type="text" id="estatus" name="estatus" value="<?php echo htmlspecialchars($recepcionista['Estatus']); ?>" placeholder="Estatus" required>

    <input type="submit" value="Actualizar Información">
</form>


    <a href="ver_recepcionista.php" class="back-link">Volver a la lista de recepcionistas</a>

</body>
</html>

