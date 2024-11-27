<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Consulta para obtener todos los pacientes
$query = "SELECT Id_paciente, Nombre, Apellido_paterno, Apellido_materno, Fecha_nacimiento, Genero, Telefono, Diagnostico 
          FROM pacientes";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error al obtener pacientes: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Pacientes</title>
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
            margin: 20px 0;
            background-color: #ffffff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #003366;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
            color: #003366;
        }

        .actions a {
            margin-right: 10px;
            color: #007bff;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #00aaff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Pacientes</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Género</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['Id_paciente']; ?></td>
                    <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['Apellido_paterno']); ?></td>
                    <td><?php echo htmlspecialchars($row['Apellido_materno']); ?></td>
                    <td><?php echo $row['Fecha_nacimiento']; ?></td>
                    <td><?php echo $row['Genero']; ?></td>
                    <td><?php echo $row['Telefono']; ?></td>
                    <td class="actions">
                        <a href="ver_detalle_paciente.php?id=<?php echo $row['Id_paciente']; ?>">Ver</a>
                        <a href="modificar_paciente.php?id=<?php echo $row['Id_paciente']; ?>">Modificar</a>
                        
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="agregar_paciente.php" class="button">Agregar Paciente</a>
        <a href="eliminar_paciente.php" class="button">Eliminar Paciente</a>
        <a href="administrador.php" class="button">Volver</a>
        
    </div>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
