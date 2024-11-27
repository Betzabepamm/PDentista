<?php 
include("conexion.php");
session_start();

// Verificamos si el usuario está logueado y es recepcionista
if (!isset($_SESSION['id_personal']) || $_SESSION['tipo'] != 'recepcionista') {
    header("Location: login.php");
    exit();
}

// Obtener la lista de pacientes y dentistas activos
$pacientes_query = "SELECT Id_paciente, Nombre, Apellido_paterno, Apellido_materno FROM pacientes";
$pacientes_result = $conn->query($pacientes_query);

$dentistas_query = "SELECT Id_personal, Nombre, Apellido_paterno, Apellido_materno, Tipo, Especialidad FROM personal WHERE Estatus = 'activo' AND Tipo = 'Dentista'";
$dentistas_result = $conn->query($dentistas_query);

// Verificamos si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id_paciente = $_POST['id_paciente'];
    $id_personal = $_POST['id_personal'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $id_pago = !empty($_POST['id_pago']) ? $_POST['id_pago'] : null; // Opcional

    // Verificar si la cita ya existe con el mismo dentista, fecha y hora
    $check_query = "SELECT * FROM citas WHERE Id_personal = ? AND Fecha = ? AND Hora = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("iss", $id_personal, $fecha, $hora);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $error = "Ya existe una cita programada con este dentista en la misma fecha y hora.";
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
        //$stmt->close();
    }

    //$check_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Recepcionista</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            background-color: #e0f7ff; /* Azul cielo claro */
            color: #ffffff; /* Texto en blanco */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Contenedor de la cita */
        .cita-container {
            background-color: #003366; /* Azul marino */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 500px;
        }

        /* Título */
        .cita-container h2 {
            color: #00bfff; /* Azul cielo brillante */
            text-align: center;
            margin-bottom: 20px;
        }

        /* Formularios */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #00bfff; /* Azul cielo brillante */
        }

        select, input[type="date"], button {
            padding: 10px;
            border-radius: 5px;
            border: none;
            width: 100%;
            font-size: 16px;
        }

        select, input[type="date"] {
            background-color: #ffffff; /* Blanco */
            color: #003366; /* Azul marino */
        }

        select:focus, input[type="date"]:focus {
            outline: 2px solid #00bfff; /* Azul cielo brillante */
        }

        button {
            background-color: #00bfff; /* Azul cielo brillante */
            color: #ffffff; /* Blanco */
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0059b3; /* Azul marino intermedio */
        }

        /* Mensajes de error y éxito */
        .error {
            color: #ff4d4d; /* Rojo brillante */
            background-color: #ffe6e6; /* Rojo claro */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }

        .success {
            color: #4caf50; /* Verde brillante */
            background-color: #e8f5e9; /* Verde claro */
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 10px;
        }

        /* Enlace de regresar */
        .cita-container a {
            color: #00bfff; /* Azul cielo brillante */
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            display: block;
            margin-top: 20px;
            transition: color 0.3s ease;
        }

        .cita-container a:hover {
            color: #0059b3; /* Azul marino intermedio */
        }
    </style>
</head>
<body>
    <div class="cita-container">
        <h2>Agendar Cita - Recepcionista</h2>

        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <form action="agendar_citar.php" method="POST">
            <!-- Selección de paciente -->
            <label for="id_paciente">Seleccionar Paciente:</label>
            <select name="id_paciente" id="id_paciente" required>
                <option value="">Selecciona un paciente</option>
                <?php while ($paciente = $pacientes_result->fetch_assoc()): ?>
                    <option value="<?= $paciente['Id_paciente'] ?>">
                        <?= $paciente['Nombre'] . " " . $paciente['Apellido_paterno'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Selección de dentista -->
            <label for="id_personal">Seleccionar Dentista:</label>
            <select name="id_personal" id="id_personal" required>
                <option value="">Selecciona un dentista</option>
                <?php while ($dentista = $dentistas_result->fetch_assoc()): ?>
                    <option value="<?= $dentista['Id_personal'] ?>">
                        <?= $dentista['Nombre'] . " " . $dentista['Apellido_paterno'] . " - " . $dentista['Especialidad'] ?>
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
            </select><br><br>

            <!-- Selector de pago (opcional) -->
            <label for="id_pago">Seleccionar Pago (opcional):</label>
            <select name="id_pago" id="id_pago">
                <option value="">Sin pago asociado</option>
                <?php
                $pago_query = "SELECT Id_pago, Monto FROM registro_de_pago";
                $pago_result = $conn->query($pago_query);
                while ($pago = $pago_result->fetch_assoc()): ?>
                    <option value="<?= $pago['Id_pago'] ?>">
                        <?= "Pago ID: " . $pago['Id_pago'] . " - Monto: $" . $pago['Monto'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Botón para agendar -->
            <button type="submit">Agendar Cita</button>
        </form>

        <p><a href="recepcionista.php">Regresar al inicio</a></p>
    </div>

    <script>
            function validarFecha() {
            const fechaInput = document.getElementById('fecha');
            const fechaSeleccionada = new Date(fechaInput.value);
            const dia = fechaSeleccionada.getDay();  // 0 es domingo, 1 es lunes, etc.
            const fechaHoy = new Date();
            
            // Verificar si el día seleccionado es domingo
            if (dia === 0) {
                alert("Los domingos no se puede agendar citas.");
                fechaInput.setCustomValidity("Los domingos no están disponibles.");
            } else {
                fechaInput.setCustomValidity("");
            }
        }

        // Prevenir la selección de domingos
        document.getElementById('fecha').addEventListener('input', function() {
            const inputDate = this;
            const inputValue = inputDate.value;
            const date = new Date(inputValue);
            const dayOfWeek = date.getUTCDay(); // 0 es domingo

            if (dayOfWeek === 0) { // Si es domingo, reseteamos el valor
                alert("Los domingos no se puede agendar citas.");
                inputDate.value = "";
            }
        });
    </script>
</body>
</html> 






