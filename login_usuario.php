<?php
session_start(); // Iniciar sesión
include("conexion.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Verificar si el usuario existe en la tabla `pacientes`
    $sql = "SELECT * FROM pacientes WHERE Usuario = ? AND Contrasena = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario válido
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $row['Usuario'];
        $_SESSION['nombre'] = $row['Nombre'];
        $_SESSION['id_paciente'] = $row['Id_paciente'];

        // Redirigir al área de usuario
        header("Location: area_usuario.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos. Inténtalo de nuevo.";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
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

        /* Estilos del contenedor de login */
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container h2 {
            font-size: 2rem;
            color: #003366; /* Azul marino */
            margin-bottom: 20px;
        }

        .login-container input {
            width: 100%;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .login-container button {
            width: 100%;
            padding: 15px;
            background-color: #003366; /* Azul marino */
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-container button:hover {
            background-color: #00aaff; /* Azul cielo */
        }

        .login-container p {
            margin-top: 20px;
        }

        .login-container a {
            color: #003366; /* Azul marino */
            text-decoration: underline;
        }

        .error {
            color: #ff0000;
            font-size: 1rem;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <form action="login_usuario.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Iniciar Sesión</button>
        </form>

        <p><a href="registro_usuario.php">¿No tienes una cuenta? Regístrate aquí</a></p>
    </div>
</body>
</html>



