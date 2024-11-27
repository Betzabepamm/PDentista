<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Verificar si el ID del paciente está presente en la URL
if (isset($_GET['id'])) {
    $id_paciente = $_GET['id'];

    // Consulta para obtener el historial de tratamientos del paciente
    $query_tratamiento = "
        SELECT t.Descripcion, t.Observaciones, t.Historial_de_cambios 
        FROM tratamiento t
        JOIN citas c ON c.Id_cita = t.Id_cita
        WHERE c.Id_paciente = $id_paciente
    ";
    $result_tratamiento = mysqli_query($conn, $query_tratamiento);

    // Verificar si la consulta fue exitosa y si existen tratamientos
    if (!$result_tratamiento || mysqli_num_rows($result_tratamiento) == 0) {
        die("Error: No se encontró historial de tratamientos o la consulta falló.");
    }
} else {
    die("Error: El ID del paciente no está presente.");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Tratamientos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        h1 {
            text-align: center;
            color: #003366;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
            background-color: #ffffff;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #003366;
            color: #ffffff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e0f7fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .btn-volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .btn-volver:hover {
            background-color: #00aaff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Historial de Tratamientos</h1>

        <table>
            <tr>
                <th>Descripción</th>
                <th>Observaciones</th>
                <th>Historial de Cambios</th>
            </tr>

            <?php
            // Mostrar los tratamientos del paciente
            while ($tratamiento = mysqli_fetch_assoc($result_tratamiento)) {
                echo "<tr>
                        <td>" . htmlspecialchars($tratamiento['Descripcion']) . "</td>
                        <td>" . htmlspecialchars($tratamiento['Observaciones']) . "</td>
                        <td>" . htmlspecialchars($tratamiento['Historial_de_cambios']) . "</td>
                    </tr>";
            }
            ?>

        </table>

        <div style="text-align: center;">
            <a href="ver_pacientes.php" class="btn-volver">Volver </a>
        </div>
    </div>
</body>
</html>