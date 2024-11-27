<?php
session_start();
if ($_SESSION['tipo'] != 'recepcionista') {
    header("Location: login.php");
    exit;
}

include('conexion.php');

// Obtener las citas registradas
$sql_citas = "SELECT citas.Id_cita, citas.Fecha, citas.Hora, pacientes.Nombre AS paciente_nombre, personal.Nombre AS dentista_nombre 
              FROM citas
              JOIN pacientes ON citas.Id_paciente = pacientes.Id_paciente
              JOIN personal ON citas.Id_personal = personal.Id_personal";
$result_citas = $conn->query($sql_citas);

// Obtener los pacientes registrados
$sql_pacientes = "SELECT * FROM pacientes";
$result_pacientes = $conn->query($sql_pacientes);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Recepcionista</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Panel Recepcionista</h1>
        <nav>
            <ul>
                <li><a href="agendar_citar.php">Agendar Cita</a></li>
                <li><a href="registrar_paciente.php">Registrar Paciente</a></li>
                <li><a href="index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Gestiona Citas y Pacientes</h2>
        <p>Aquí puedes registrar pacientes y agendar citas.</p>

        <!-- Mostrar Citas -->
        <h3>Citas Registradas</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
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
                        echo "<td>" . $row['paciente_nombre'] . "</td>";
                        echo "<td>" . $row['dentista_nombre'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay citas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Mostrar Pacientes -->
        <h3>Pacientes Registrados</h3>
        <table>
            <thead>
                <tr>
                    <th>ID Paciente</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Teléfono</th>
                    <th>Diagnóstico</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pacientes->num_rows > 0) {
                    while($row = $result_pacientes->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Id_paciente'] . "</td>";
                        echo "<td>" . $row['Nombre'] . "</td>";
                        echo "<td>" . $row['Apellido_paterno'] . "</td>";
                        echo "<td>" . $row['Apellido_materno'] . "</td>";
                        echo "<td>" . $row['Telefono'] . "</td>";
                        echo "<td>" . $row['Diagnostico'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay pacientes registrados.</td></tr>";
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

