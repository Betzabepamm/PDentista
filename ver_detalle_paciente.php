
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

    // Consulta para obtener los detalles del paciente
    $query = "SELECT * FROM pacientes WHERE Id_paciente = $id_paciente";
    $result = mysqli_query($conn, $query);

    // Verificar si la consulta fue exitosa y si el paciente existe
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Error: No se encontró el paciente o la consulta falló.");
    }

    // Obtener los datos del paciente
    $paciente = mysqli_fetch_assoc($result);

    // Consulta para obtener el diagnóstico del tratamiento
    $query_tratamiento = "SELECT Descripcion FROM tratamiento WHERE Id_cita IN (SELECT Id_cita FROM citas WHERE Id_paciente = $id_paciente)";
    $result_tratamiento = mysqli_query($conn, $query_tratamiento);

    // Verificar si existe un diagnóstico asociado
    $diagnostico_tratamiento = '';
    if ($result_tratamiento && mysqli_num_rows($result_tratamiento) > 0) {
        $tratamiento = mysqli_fetch_assoc($result_tratamiento);
        $diagnostico_tratamiento = $tratamiento['Descripcion'];
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
    <title>Detalle del Paciente</title>
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

        .no-data {
            text-align: center;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .btn-historial {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .btn-historial:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalle del Paciente</h1>

        <table>
            <tr>
                <th>Nombre</th>
                <td><?php echo htmlspecialchars($paciente['Nombre']); ?></td>
            </tr>
            <tr>
                <th>Apellido Paterno</th>
                <td><?php echo htmlspecialchars($paciente['Apellido_Paterno']); ?></td>
            </tr>
            <tr>
                <th>Apellido Materno</th>
                <td><?php echo htmlspecialchars($paciente['Apellido_Materno']); ?></td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td><?php echo htmlspecialchars($paciente['Telefono']); ?></td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td><?php echo htmlspecialchars($paciente['Direccion']); ?></td>
            </tr>
            <tr>
                <th>Diagnóstico</th>
                <td>
                    <?php 
                    // Mostrar el diagnóstico del tratamiento si existe
                    echo !empty($diagnostico_tratamiento) ? htmlspecialchars($diagnostico_tratamiento) : 'No disponible'; 
                    ?>
                    <!-- Botón para el historial de tratamiento -->
                    <br>
                    <a href="historial_tratamiento.php?id=<?php echo $id_paciente; ?>" class="btn-historial">Historial de Tratamiento</a>
                </td>
            </tr>
            <tr>
                <th>Fecha de Nacimiento</th>
                <td>
                    <?php 
                    // Verificar si la fecha de nacimiento está disponible
                    echo !empty($paciente['Fecha_nacimiento']) ? date('d-m-Y', strtotime($paciente['Fecha_nacimiento'])) : 'No disponible'; 
                    ?>
                </td>
            </tr>
            <tr>
                <th>Usuario</th>
                <td><?php echo htmlspecialchars($paciente['Usuario']); ?></td>
            </tr>
            <tr>
                <th>Contraseña</th>
                <td><?php echo htmlspecialchars($paciente['Contrasena']); ?></td>
            </tr>
        </table>

        <div style="text-align: center;">
            <a href="ver_pacientes.php" class="btn-volver">Volver a la lista de pacientes</a>
        </div>
    </div>
</body>
</html>

