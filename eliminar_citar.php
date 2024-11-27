<?php
include("conexion.php");
session_start();

// Verificamos si la recepcionista está logueada
if (!isset($_SESSION['id_personal']) || strtolower($_SESSION['tipo']) !== 'recepcionista') {
    die("Acceso denegado. No tienes permiso para acceder a esta página.");
}

// Obtener todas las citas existentes
$citas_query = "
    SELECT 
        c.Id_cita, 
        c.Fecha, 
        c.Hora, 
        p.Nombre AS Paciente_Nombre, 
        p.Apellido_paterno AS Paciente_Apellido,
        d.Nombre AS Dentista_Nombre, 
        d.Apellido_paterno AS Dentista_Apellido
    FROM 
        citas c
    INNER JOIN 
        pacientes p ON c.Id_paciente = p.Id_paciente
    INNER JOIN 
        personal d ON c.Id_personal = d.Id_personal";
$citas_result = $conn->query($citas_query);

// Verificamos si se envió el formulario para eliminar una cita
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cita = $_POST['id_cita'];

    // Verificar si la cita tiene tratamientos asociados
    $check_query = "SELECT COUNT(*) as count FROM tratamiento WHERE Id_cita = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("i", $id_cita);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $row_check = $result_check->fetch_assoc();

    if ($row_check['count'] > 0) {
        // Mostrar mensaje si la cita tiene tratamientos asociados
        $error = "No se puede eliminar la cita porque tiene tratamientos asociados.";
    } else {
        // Eliminar la cita seleccionada
        $delete_query = "DELETE FROM citas WHERE Id_cita = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id_cita);

        if ($stmt->execute()) {
            $success = "La cita ha sido eliminada exitosamente.";
        } else {
            $error = "Error al eliminar la cita: " . $conn->error;
        }

        // Recargar las citas después de la eliminación
        $citas_result = $conn->query($citas_query);
    }
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
            background-color: #f7f7f7; /* Fondo gris claro */
            margin: 0;
            padding: 0;
            color: #333;
        }

        a {
            color: #003366; /* Azul marino */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Contenedor principal */
        .cita-container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff; /* Blanco */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Títulos */
        h2 {
            color: #003366; /* Azul marino */
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
        }

        /* Mensajes de éxito y error */
        .success {
            color: #4caf50; /* Verde */
            background-color: #d4edda;
            padding: 10px;
            border: 1px solid #4caf50;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .error {
            color: #e53935; /* Rojo */
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
            background-color: #003366; /* Azul marino */
            color: #fff; /* Blanco */
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; /* Gris claro */
        }

        tr:hover {
            background-color: #f1f1f1; /* Gris más claro */
        }

        /* Botones */
        button {
            background-color: #e53935; /* Rojo */
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c62828; /* Rojo oscuro */
        }

        /* Enlaces */
        p {
            text-align: center;
            margin-top: 20px;
        }

        p a {
            font-weight: bold;
            color: #003366; /* Azul marino */
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

        <!-- Tabla para mostrar las citas existentes -->
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
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
                            <td><?= htmlspecialchars($cita['Paciente_Nombre'] . " " . $cita['Paciente_Apellido']) ?></td>
                            <td><?= htmlspecialchars($cita['Dentista_Nombre'] . " " . $cita['Dentista_Apellido']) ?></td>
                            <td>
                                <!-- Botón para eliminar cita -->
                                <form action="eliminar_citar.php" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                    <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['Id_cita']) ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No hay citas programadas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <p><a href="recepcionista.php">Regresar al inicio</a></p>
    </div>
</body>
</html>

