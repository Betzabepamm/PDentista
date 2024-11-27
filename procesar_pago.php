<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // Dirección del servidor
$usuario = 'root';   // Usuario de la base de datos
$contrasena = '';    // Contraseña de la base de datos
$base_de_datos = 'consultorio'; // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Comprobar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Inicializar ID del paciente
$id_paciente = "";

// Verificar si se recibe el ID del paciente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['Id_paciente']) && !empty($_POST['Id_paciente'])) {
        $id_paciente = $_POST['Id_paciente'];
    } else {
        die("Error: No se recibió el ID del paciente.");
    }
} elseif (isset($_GET['Id_paciente'])) {
    $id_paciente = $_GET['Id_paciente'];
} else {
    die("Error: No se proporcionó el ID del paciente.");
}

// Obtener información del paciente
$sql_paciente = "SELECT * FROM pacientes WHERE Id_paciente = $id_paciente";
$resultado_paciente = $conn->query($sql_paciente);
$paciente = $resultado_paciente->fetch_assoc();

if (!$paciente) {
    die("Paciente no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pago</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #003366;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #003366;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #003366;
            background-color: #f0f8ff;
            border-radius: 8px;
        }

        label {
            font-size: 16px;
            display: block;
            margin-bottom: 8px;
            color: #003366;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #003366;
            border-radius: 5px;
        }

        button {
            background-color: #003366;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #00509e;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #003366;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <h1>Registrar Pago</h1>
</header>

<div class="container">
    <h3>Información del Paciente</h3>
    <p><strong>Nombre:</strong> <?php echo $paciente['Nombre'] . ' ' . $paciente['Apellido_Paterno'] . ' ' . $paciente['Apellido_Materno']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $paciente['Telefono']; ?></p>

    <form action="guardar_pago.php" method="POST">
        <!-- El ID del paciente se pasa como un campo oculto -->
        <input type="hidden" name="Id_paciente" value="<?php echo htmlspecialchars($id_paciente); ?>">

        <label for="Id_cita">ID de la Cita:</label>
        <input type="number" name="Id_cita" id="Id_cita" required>

        <label for="Fecha_pago">Fecha de Pago:</label>
        <input type="date" name="Fecha_pago" id="Fecha_pago" required>

        <label for="Monto">Monto:</label>
        <input type="number" name="Monto" id="Monto" step="0.01" required>

        <button type="submit">Guardar Pago</button>
    </form>

    <div class="back-link">
        <a href="generarpagor.php?Id_paciente=<?php echo urlencode($id_paciente); ?>">Volver a la Lista de Pacientes</a>
    </div>
</div>

<?php
// Cerrar la conexión
//$conn->close();
?>

</body>
</html>


