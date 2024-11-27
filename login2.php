<?php
session_start();
include 'conexion.php'; // Archivo para conectar con la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $rol = $_POST['rol']; // Rol seleccionado

    // Consultar la tabla correspondiente según el rol seleccionado
    switch ($rol) {
        case 'paciente':
            $tabla = 'pacientes';
            $query = "SELECT * FROM $tabla WHERE Usuario = ? AND Contraseña = ?";
            break;
        case 'dentista':
        case 'recepcionista':
            $tabla = 'personal';
            $query = "SELECT * FROM $tabla WHERE Usuario = ? AND Contraseña = ? AND Tipo = ?";
            break;
        case 'administrador':
            $tabla = 'personal';
            $query = "SELECT * FROM $tabla WHERE Usuario = ? AND Contraseña = ? AND Tipo = 'administrador'";
            break;
        default:
            $error = "Rol no válido.";
            break;
    }

    if (!isset($error)) {
        $stmt = $conn->prepare($query);
        if ($rol === 'dentista' || $rol === 'recepcionista') {
            $stmt->bind_param('sss', $usuario, $contrasena, $rol);
        } else {
            $stmt->bind_param('ss', $usuario, $contrasena);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $datos = $resultado->fetch_assoc();
            $_SESSION['usuario'] = $datos['Usuario'];
            $_SESSION['nombre'] = $datos['Nombre'];
            $_SESSION['tipo_usuario'] = $rol;

            // Redirigir según el rol
            if ($rol === 'paciente') {
                header("Location: index.php");
            } elseif ($rol === 'dentista' || $rol === 'recepcionista') {
                header("Location: index2.php");
            } elseif ($rol === 'administrador') {
                header("Location: index3.php");
            }
            exit();
        } else {
            $error = "Credenciales incorrectas.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="styles.css"> <!-- Opcional -->
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <label for="rol">Selecciona tu rol:</label>
            <select name="rol" id="rol" required>
                <option value="paciente">Paciente</option>
                <option value="dentista">Dentista</option>
                <option value="recepcionista">Recepcionista</option>
                <option value="administrador">Administrador</option>
            </select>
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" id="usuario" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" id="contrasena" required>
            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
