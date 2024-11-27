<?php
session_start();

// Verificar si el usuario es dentista
if ($_SESSION['tipo'] != 'dentista') {
    header("Location: login.php");
    exit;
}

include('conexion.php');

// Obtener el ID del dentista logueado
$id_dentista = $_SESSION['id_personal'];  // Asegúrate de que 'id_personal' esté en la sesión

// Obtener la especialidad del dentista logueado
$sql_especialidad = "SELECT Especialidad FROM personal WHERE Id_personal = ?";
$stmt = $conn->prepare($sql_especialidad);
$stmt->bind_param("i", $id_dentista);
$stmt->execute();
$result_especialidad = $stmt->get_result();

if ($result_especialidad->num_rows > 0) {
    $especialidad_dentista = $result_especialidad->fetch_assoc()['Especialidad'];
} else {
    die("No se pudo obtener la especialidad del dentista.");
}

// Obtener las citas asignadas al dentista logueado según su especialidad
$sql_citas = "SELECT citas.Id_cita, citas.Fecha, citas.Hora, pacientes.Nombre AS paciente_nombre, 
                    pacientes.Apellido_paterno AS paciente_apellido, personal.Nombre AS dentista_nombre
              FROM citas
              JOIN pacientes ON citas.Id_paciente = pacientes.Id_paciente
              JOIN personal ON citas.Id_personal = personal.Id_personal
              WHERE citas.Id_personal = ? AND personal.Especialidad = ?";
$stmt = $conn->prepare($sql_citas);
$stmt->bind_param("is", $id_dentista, $especialidad_dentista);
$stmt->execute();
$result_citas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Dentista</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f6fc; /* Azul claro muy suave */
            color: #002855; /* Azul marino */
        }

        header {
            background-color: #002855; /* Azul marino */
            color: #ffffff; /* Blanco */
            padding: 20px;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            color: #ffffff; /* Blanco */
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }

        /* Section */
        section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            background-color: #ffffff; /* Blanco */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }

        section h2 {
            color: #002855; /* Azul marino */
            text-align: center;
            margin-bottom: 20px;
        }

        section p {
            text-align: center;
            color: #002855; /* Azul marino */
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #d1d9e6; /* Azul grisáceo */
        }

        table th {
            background-color: #002855; /* Azul marino */
            color: #ffffff; /* Blanco */
            text-transform: uppercase;
            font-size: 14px;
        }

        table tr:nth-child(even) {
            background-color: #f0f4f8; /* Azul claro muy suave */
        }

        table tr:hover {
            background-color: #d9e6f2; /* Azul claro intermedio */
        }

        table td {
            font-size: 14px;
            color: #002855; /* Azul marino */
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel Dentista</h1>
        <nav>
            <ul>
                <li><a href="dentista.php">Inicio</a></li>
                <li><a href="eliminar_citad.php">Eliminar Citas</a></li>
                <li><a href="tratamientos.php">Tratamientos</a></li>
                <li><a href="index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Citas Agendadas</h2>
        <p>Aquí podrás ver las citas agendadas según tu especialidad: <?php echo htmlspecialchars($especialidad_dentista); ?></p>

        <!-- Mostrar Citas -->
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_citas->num_rows > 0) {
                    while($row = $result_citas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Id_cita'] . "</td>";
                        echo "<td>" . $row['Fecha'] . "</td>";
                        echo "<td>" . $row['Hora'] . "</td>";
                        echo "<td>" . $row['paciente_nombre'] . " " . $row['paciente_apellido'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No tienes citas agendadas en tu especialidad.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </section>
</body>
</html>

<?php
//$conn->close();
?>
