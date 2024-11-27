

<?php
session_start();

// Verificamos si el usuario está autenticado y es administrador
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php'); // Incluye tu archivo de conexión a la base de datos

// Verificar si la conexión fue exitosa
if (!$conn) {
    die("ERROR: No se pudo conectar a la base de datos. " . mysqli_connect_error());
}

// Consulta para obtener las recepcionistas registradas de la tabla 'personal'
$query = "SELECT Id_personal, Nombre, Apellido_paterno, Apellido_materno, Telefono, Correo, Estatus, Usuario, Contrasena
          FROM personal 
          WHERE Tipo = 'Recepcionista'"; // Asegúrate de que el campo Tipo sea 'Recepcionista'

$result = mysqli_query($conn, $query); // Ejecuta la consulta

if (!$result) {
    die("Error en la ejecución de la consulta: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Recepcionistas</title>
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

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #003366;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        a:hover {
            background-color: #00aaff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .no-data {
            text-align: center;
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .btn-modificar {
            padding: 5px 15px;
            background-color: lightblue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-modificar:hover {
            background-color: #00aaff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recepcionistas Registradas</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estatus</th>
                        <th>Usuario</th>
                        <th>Contraseña</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['Apellido_paterno']); ?></td>
                            <td><?php echo htmlspecialchars($row['Apellido_materno']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telefono']); ?></td>
                            <td><?php echo htmlspecialchars($row['Correo']); ?></td>
                            <td><?php echo htmlspecialchars($row['Estatus']); ?></td>
                            <td><?php echo htmlspecialchars($row['Usuario']); ?></td>
                            <td><?php echo htmlspecialchars($row['Contrasena']); ?></td>
                            <td><a href="modificar_recepcionista.php?id=<?php echo $row['Id_personal']; ?>" class="btn-modificar">Modificar</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">No hay recepcionistas registradas en el sistema.</p>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="administrador.php">Volver al Panel</a>
        </div>
    </div>
</body>
</html>



