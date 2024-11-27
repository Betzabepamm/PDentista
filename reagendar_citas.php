<?php
include("conexion.php");
session_start();

// Obtener todas las citas para ser mostradas en el selector
$citas_query = "SELECT c.Id_cita, c.Fecha, c.Hora, p.Nombre AS Dentista, p.Apellido_paterno, p.Apellido_materno 
                FROM citas c 
                JOIN personal p ON c.Id_personal = p.Id_personal";
$citas_result = $conn->query($citas_query);

// Obtener solo dentistas activos
$personal_query = "SELECT Id_personal, Nombre, Apellido_paterno, Apellido_materno, Especialidad 
                   FROM personal WHERE Estatus = 'activo' AND Tipo = 'Dentista'";
$personal_result = $conn->query($personal_query);

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id_cita = $_POST['id_cita'];
    $id_personal = $_POST['id_personal'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Verificar si ya existe una cita para el mismo dentista, fecha y hora
    $check_query = "SELECT COUNT(*) AS total FROM citas WHERE Id_personal = ? AND Fecha = ? AND Hora = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("iss", $id_personal, $fecha, $hora);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        $error = "La cita ya está ocupada para este dentista en la fecha y hora seleccionadas.";
    } else {
        // Actualizar la cita con los nuevos datos
        $update_query = "UPDATE citas SET Id_personal = ?, Fecha = ?, Hora = ? WHERE Id_cita = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("issi", $id_personal, $fecha, $hora, $id_cita);

        if ($stmt->execute()) {
            $success = "Cita reagendada exitosamente.";
        } else {
            $error = "Error al reagendar la cita: " . $conn->error;
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
    <title>Reagendar Cita</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Azul claro */
            color: #1a1a1a; /* Texto oscuro */
            margin: 0;
            padding: 0;
        }

        .cita-container {
            background-color: #ffffff; /* Blanco */
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e3e3;
        }

        h2 {
            color: #00264d; /* Azul marino */
            text-align: center;
        }

        label {
            display: block;
            font-weight: bold;
            color: #00264d; /* Azul marino */
            margin: 10px 0 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #b3cde0; /* Azul claro */
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        input:focus, select:focus, button:focus {
            outline: none;
            border-color: #003366; /* Azul marino */
        }

        button {
            background-color: #00264d; /* Azul marino */
            color: #ffffff; /* Blanco */
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background-color: #003366; /* Más oscuro */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #b3cde0; /* Azul claro */
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #00264d; /* Azul marino */
            color: #ffffff; /* Blanco */
        }

        table tr:nth-child(even) {
            background-color: #e6f2ff; /* Azul muy claro */
        }

        .error {
            color: #cc0000; /* Rojo */
            background-color: #ffe6e6; /* Rosa claro */
            border: 1px solid #cc0000;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .success {
            color: #006600; /* Verde oscuro */
            background-color: #e6ffe6; /* Verde claro */
            border: 1px solid #006600;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        a {
            color: #00264d; /* Azul marino */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="cita-container">
        <h2>Reagendar Cita</h2>

        <!-- Mensajes de éxito o error -->
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <!-- Formulario para reagendar citas -->
        <form action="reagendar_citas.php" method="POST">
            <!-- Selector de cita a reagendar -->
            <label for="id_cita">Seleccionar Cita:</label>
            <select name="id_cita" id="id_cita" required>
                <option value="">Selecciona una cita</option>
                <?php while ($cita = $citas_result->fetch_assoc()): ?>
                    <option value="<?= $cita['Id_cita'] ?>">
                        <?= "Cita: " . $cita['Fecha'] . " - " . $cita['Hora'] . " con " . $cita['Dentista'] . " " . $cita['Apellido_paterno'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Selector de dentista -->
            <label for="id_personal">Seleccionar Nuevo Dentista:</label>
            <select name="id_personal" id="id_personal" required>
                <option value="">Selecciona un dentista</option>
                <?php while ($personal = $personal_result->fetch_assoc()): ?>
                    <option value="<?= $personal['Id_personal'] ?>">
                        <?= $personal['Nombre'] . " " . $personal['Apellido_paterno'] . " (" . $personal['Especialidad'] . ")" ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <!-- Fecha y hora -->
            <label for="fecha">Nueva Fecha:</label>
            <input type="date" name="fecha" id="fecha" min="<?php echo date('Y-m-d'); ?>" required />

            <label for="hora">Nueva Hora:</label>
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
            </select>

            <!-- Botón para reagendar -->
            <button type="submit">Reagendar Cita</button>
        </form>

        <p><a href="recepcionista.php">Regresar al inicio</a></p>
    </div>

    <script>
        // Prevenir selección de domingos
        document.getElementById('fecha').addEventListener('input', function() {
            const inputDate = this;
            const date = new Date(inputDate.value);
            const dayOfWeek = date.getUTCDay();

            if (dayOfWeek === 0) {
                alert("Los domingos no se puede reagendar citas.");
                inputDate.value = "";
            }
        });
    </script>
</body>
</html>


