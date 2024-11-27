<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // Dirección del servidor
$usuario = 'root';   // Usuario de la base de datos
$contrasena = '';    // Contraseña de la base de datos
$base_de_datos = 'consultorio'; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar variables para evitar errores
$mensaje = "Ha ocurrido un problema inesperado.";
$mensaje_clase = "error";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id_cita = $_POST['Id_cita'];
    $fecha_pago = $_POST['Fecha_pago'];
    $monto = $_POST['Monto'];

    // Validar que los campos no estén vacíos
    if (!empty($id_cita) && !empty($fecha_pago) && !empty($monto)) {
        // Insertar los datos en la tabla registro_de_pago
        $sql = "INSERT INTO registro_de_pago (Id_cita, Fecha_pago, Monto) 
                VALUES ('$id_cita', '$fecha_pago', '$monto')";

        if ($conn->query($sql) === TRUE) {
            $mensaje = "El pago se registró correctamente.";
            $mensaje_clase = "success";
        } else {
            $mensaje = "Error al registrar el pago: " . $conn->error;
            $mensaje_clase = "error";
        }
    } else {
        $mensaje = "Por favor, completa todos los campos.";
        $mensaje_clase = "error";
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guardar Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Blanco */
            color: #003366; /* Azul marino */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #003366; /* Azul marino */
            color: #ffffff; /* Blanco */
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #003366;
            background-color: #f0f8ff; /* Azul claro */
            border-radius: 8px;
            text-align: center;
        }

        .mensaje {
            font-size: 18px;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .success {
            color: #006400; /* Verde oscuro */
            background-color: #d4edda; /* Fondo verde claro */
            border: 1px solid #c3e6cb;
        }

        .error {
            color: #b22222; /* Rojo oscuro */
            background-color: #f8d7da; /* Fondo rojo claro */
            border: 1px solid #f5c6cb;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #003366;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        button {
            background-color: #003366; /* Azul marino */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #00509e; /* Azul más claro */
        }
    </style>
</head>
<body>

<header>
    <h1>Resultado del Registro</h1>
</header>

<div class="container">
    <div class="mensaje <?php echo htmlspecialchars($mensaje_clase); ?>">
        <?php echo htmlspecialchars($mensaje); ?>
    </div>

    <div class="back-link">
        <a href="generarpagor.php">Volver a la lista de pacientes</a>
    </div>
</div>

</body>
</html>

