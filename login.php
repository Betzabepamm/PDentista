<?php
include("conexion.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = trim($_POST['usuario']);
    $contrasena = trim($_POST['contrasena']);

    // Preparar la consulta con sentencias preparadas
    $sql = "SELECT * FROM personal WHERE Usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña (md5) con la contraseña de la base de datos
        if (($contrasena) === $row['Contrasena']) {
            // Iniciar sesión y guardar datos en la sesión
            $_SESSION['id_personal'] = $row['Id_personal'];
            $_SESSION['usuario'] = $row['Usuario'];
            $_SESSION['tipo'] = $row['Tipo'];

            // Redirigir según el tipo de usuario
            switch ($row['Tipo']) {
                case 'administrador':
                    header("Location: administrador.php");
                    exit();
                case 'dentista':
                    header("Location: dentista.php");
                    exit();
                case 'recepcionista':
                    header("Location: recepcionista.php");
                    exit();
                default:
                    echo "Tipo de usuario no válido.";
            }
        } else {
            // Contraseña incorrecta
            $error = "Contraseña incorrecta.";
        }
    } else {
        // Usuario no encontrado
        $error = "Usuario no encontrado.";
    }

    // Cerrar la consulta
    //$stmt->close();
}

// Cerrar la conexión
//$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .login-container {
            width: 400px;
            margin: 100px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            text-align: center;
        }

        h2 {
            color: #003366;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 80%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #003366;
            border-radius: 5px;
            font-size: 16px;
            color: #003366;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #3399ff;
            outline: none;
        }

        button {
            width: 80%;
            padding: 12px;
            background-color: #003366;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #3399ff;
        }

        .error {
            color: #ff0000;
            margin-bottom: 20px;
        }

        p {
            color: #003366;
            font-size: 14px;
        }

        a {
            color: #3399ff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
        
        <form action="login.php" method="POST">
            <input type="text" name="usuario" placeholder="Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            <button type="submit">Entrar</button>
        </form>

        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>
</html>






