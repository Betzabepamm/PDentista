<?php
include("conexion.php");
session_start();

// Verificamos si el paciente está logueado
if (!isset($_SESSION['id_paciente'])) {
    header("Location: login.php");
    exit();
}

$id_paciente = $_SESSION['id_paciente']; // ID del paciente logueado

// Obtener los datos del paciente logueado
$paciente_query = "SELECT Id_paciente, Nombre, Apellido_paterno, Apellido_materno FROM pacientes WHERE Id_paciente = ?";
$stmt = $conn->prepare($paciente_query);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$paciente_result = $stmt->get_result();
$paciente = $paciente_result->fetch_assoc();

// Obtener solo los dentistas activos
$personal_query = "SELECT Id_personal, Nombre, Apellido_paterno, Apellido_materno, Tipo, Especialidad FROM personal WHERE Estatus = 'activo' AND Tipo = 'Dentista'";
$personal_result = $conn->query($personal_query);

// Opcional: obtener los pagos disponibles
$pago_query = "SELECT Id_pago, Monto, Fecha_pago FROM registro_de_pago";
$pago_result = $conn->query($pago_query);

// Verificamos si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id_personal = $_POST['id_personal'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $id_pago = !empty($_POST['id_pago']) ? $_POST['id_pago'] : null; // Opcional

    // Verificar si ya existe una cita para el mismo dentista, fecha y hora
    $check_query = "SELECT COUNT(*) AS total FROM citas WHERE Id_personal = ? AND Fecha = ? AND Hora = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("iss", $id_personal, $fecha, $hora);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        // Cita duplicada
        $error = "La cita ya está ocupada para este dentista en la fecha y hora seleccionadas.";
    } else {
        // Preparar la consulta para insertar la cita
        $sql = "INSERT INTO citas (Id_paciente, Id_personal, Fecha, Hora, Id_pago) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissi", $id_paciente, $id_personal, $fecha, $hora, $id_pago);

        // Ejecutar y verificar si se guardó correctamente
        if ($stmt->execute()) {
            $success = "Cita agendada exitosamente.";
        } else {
            $error = "Error al agendar la cita: " . $conn->error;
        }
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita</title>
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

        /* Estilo del contenedor principal de la cita */
        .cita-container {
            width: 50%;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        /* Encabezado del contenedor */
        .cita-container h2 {
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
        input[type="text"], input[type="date"], select {
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

        /* Estilo del selector de dentistas */
        select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Estilo del contenedor de mensajes de error/success */
        .cita-container p {
            text-align: center;
        }

        /* Mejorar la visualización en dispositivos pequeños */
        @media (max-width: 768px) {
            .cita-container {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="cita-container">
        <h2>Agendar Cita</h2>

        <!-- Mensajes de éxito o error -->
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <!-- Formulario para agendar cita -->
        <form action="agendar_cita.php" method="POST">
            <!-- Información del paciente logueado -->
            <label for="id_paciente">Paciente:</label>
            <input type="text" value="<?= $paciente['Nombre'] . " " . $paciente['Apellido_paterno'] . " " . $paciente['Apellido_materno'] ?>" readonly>

            <!-- Selector de personal (solo dentistas) -->
            <label for="id_personal">Seleccionar Personal (Dentista):</label>
            <select name="id_personal" id="id_personal" required>
                <option value="">Selecciona un dentista</option>
                <?php while ($personal = $personal_result->fetch_assoc()): ?>
                    <option value="<?= $personal['Id_personal'] ?>">
                        <?= $personal['Tipo'] . ": " . $personal['Nombre'] . " " . $personal['Apellido_paterno'] . " " . $personal['Apellido_materno'] . " - Especialidad: " . $personal['Especialidad'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Fecha y Hora -->
            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" min="<?php echo date('Y-m-d'); ?>" required />

            <label for="hora">Hora:</label>
            <select name="hora" id="hora" required>
                <option value="09:00">09:00</option>
                <option value="09:30">09:30</option>
                <option value="10:00">10:00</option>
                <option value="10:30">10:30</option>
                <option value="11:00">11:00</option>
                <option value="11:30">11:30</option>
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
                <option value="15:30">15:30</option>
                <option value="16:00">16:00</option>
                <option value="16:30">16:30</option>
                <option value="17:00">17:00</option>
                <option value="17:30">17:30</option>
                <option value="18:00">18:00</option>
                <option value="18:30">18:30</option>
                <option value="19:00">19:00</option>
            </select>

            <!-- Pago opcional -->
            <label for="id_pago">Pago:</label>
            <select name="id_pago" id="id_pago">
                <option value="">Selecciona un pago (opcional)</option>
                <?php while ($pago = $pago_result->fetch_assoc()): ?>
                    <option value="<?= $pago['Id_pago'] ?>"><?= "$" . $pago['Monto'] . " - " . $pago['Fecha_pago'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Agendar Cita</button>
        </form>

        <!-- Enlace de regreso -->
        <a href="area_usuario.php">Regresar al inicio</a>
    </div>
</body>
</html>



















