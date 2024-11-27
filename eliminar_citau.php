<?php
include("conexion.php");
session_start();

// Verificamos si el paciente está logueado
if (!isset($_SESSION['id_paciente'])) {
    header("Location: login.php");
    exit();
}

$id_paciente = $_SESSION['id_paciente'];

// Obtener el ID del paciente logueado
$id_paciente = $_SESSION['id_paciente'];

// Obtener las citas del paciente logueado
$citas_query = "
    SELECT 
        c.Id_cita, 
        c.Fecha, 
        c.Hora, 
        d.Nombre AS Dentista_Nombre, 
        d.Apellido_paterno AS Dentista_Apellido
    FROM 
        citas c
    INNER JOIN 
        personal d ON c.Id_personal = d.Id_personal
    WHERE 
        c.Id_paciente = ?";
$stmt = $conn->prepare($citas_query);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$citas_result = $stmt->get_result();

// Verificamos si se envió el formulario para eliminar una cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cita = $_POST['id_cita'];

    // Verificar que la cita pertenece al paciente logueado antes de eliminarla
    $verify_query = "SELECT Id_cita FROM citas WHERE Id_cita = ? AND Id_paciente = ?";
    $stmt_verify = $conn->prepare($verify_query);
    $stmt_verify->bind_param("ii", $id_cita, $id_paciente);
    $stmt_verify->execute();
    $result_verify = $stmt_verify->get_result();

    if ($result_verify->num_rows > 0) {
        // Eliminar la cita seleccionada
        $delete_query = "DELETE FROM citas WHERE Id_cita = ?";
        $stmt_delete = $conn->prepare($delete_query);
        $stmt_delete->bind_param("i", $id_cita);

        if ($stmt_delete->execute()) {
            $success = "La cita ha sido eliminada exitosamente.";
        } else {
            $error = "Error al eliminar la cita: " . $conn->error;
        }
    } else {
        $error = "No se encontró la cita o no tienes permiso para eliminarla.";
    }

    // Recargar las citas después de la eliminación
    $stmt->execute();
    $citas_result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cita</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            color: #333;
        }
        a {
            color: #003366;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        /* Contenedor principal */
        .cita-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        /* Títulos */
        h2 {
            color: #003366;
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }
        /* Mensajes de éxito y error */
        .success {
            color: #4caf50;
            background-color: #d4edda;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .error {
            color: #e53935;
            background-color: #f8d7da;
            padding: 10px;
            border: 1px solid #e53935;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background-color: #003366;
            color: #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        /* Botones */
        button {
            background-color: #e53935;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #c62828;
        }
        /* Enlaces */
        p {
            text-align: center;
            margin-top: 20px;
        }
        p a {
            font-weight: bold;
            color: #003366;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="cita-container">
        <h2>Eliminar Cita</h2>

        <!-- Mensajes de éxito o error -->
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>

        <!-- Tabla para mostrar las citas del paciente -->
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Dentista</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($citas_result->num_rows > 0): ?>
                    <?php while ($cita = $citas_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($cita['Id_cita']) ?></td>
                            <td><?= htmlspecialchars($cita['Fecha']) ?></td>
                            <td><?= htmlspecialchars($cita['Hora']) ?></td>
                            <td><?= htmlspecialchars($cita['Dentista_Nombre'] . " " . $cita['Dentista_Apellido']) ?></td>
                            <td>
                                <!-- Formulario para eliminar cita -->
                                <form action="eliminar_citau.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                    <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['Id_cita']) ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No tienes citas programadas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p><a href="area_usuario.php">Regresar al inicio</a></p>
    </div>
</body>
</html>
