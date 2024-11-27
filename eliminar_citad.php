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

// Eliminar cita si se solicita
if (isset($_GET['eliminar']) && isset($_GET['id_cita'])) {
    $id_cita = $_GET['id_cita'];

    // Eliminar primero el tratamiento relacionado con la cita
    $sql_eliminar_tratamiento = "DELETE FROM tratamiento WHERE Id_cita = ?";
    $stmt = $conn->prepare($sql_eliminar_tratamiento);
    $stmt->bind_param("i", $id_cita);
    $stmt->execute();

    // Luego eliminar la cita
    $sql_eliminar_cita = "DELETE FROM citas WHERE Id_cita = ? AND Id_personal = ?";
    $stmt = $conn->prepare($sql_eliminar_cita);
    $stmt->bind_param("ii", $id_cita, $id_dentista);
    
    if ($stmt->execute()) {
        echo "<script>alert('Cita eliminada exitosamente');</script>";
        echo "<script>window.location.href = 'eliminar_citad.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar la cita');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Citas</title>
    <style>
        /* General */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Azul claro */
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #003366; /* Azul marino */
            color: #ffffff; /* Blanco */
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2em;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 10px 0 0;
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
            font-weight: bold;
            padding: 10px 15px;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: background-color 0.3s, border 0.3s;
        }

        nav ul li a:hover {
            background-color: #ffffff; /* Blanco */
            color: #003366; /* Azul marino */
            border: 1px solid #003366; /* Azul marino */
        }

        /* Section */
        section {
            padding: 20px;
            margin: 20px auto;
            max-width: 800px;
            background-color: #ffffff; /* Blanco */
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        section h2 {
            color: #003366; /* Azul marino */
            text-align: center;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table thead {
            background-color: #003366; /* Azul marino */
            color: #ffffff; /* Blanco */
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table tr:nth-child(even) {
            background-color: #f0f8ff; /* Azul claro */
        }

        table tr:hover {
            background-color: #e6f2ff; /* Azul más claro */
        }

        /* Links */
        a {
            color: #003366; /* Azul marino */
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
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
        <h2>Eliminar Citas Agendadas</h2>
        <p>Aquí podrás eliminar las citas que tienes agendadas según tu especialidad.</p>

        <!-- Mostrar Citas -->
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Eliminar</th>
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
                        echo "<td><a href='?eliminar=true&id_cita=" . $row['Id_cita'] . "' onclick='return confirm(\"¿Estás seguro de eliminar esta cita?\")'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No tienes citas agendadas para eliminar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
            <p><a href="dentista.php">Regresar al inicio</a></p>
    </section>
</body>
</html>

<?php
//$conn->close();
?>


