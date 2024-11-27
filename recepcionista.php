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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Recepcionista</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        background-color: #f4f4f9;
        color: #333;
    }

    header {
        background-color: #002147; /* Azul marino */
        color: white;
        padding: 20px;
        text-align: center;
    }

    header h1 {
        margin: 0;
        font-size: 2rem;
    }

    nav ul {
        list-style: none;
        padding: 0;
        margin: 20px 0 0;
        display: flex;
        justify-content: center;
        background-color: #00509e; /* Azul intermedio */
    }

    nav ul li {
        margin: 0 10px;
    }

    nav ul li a {
        text-decoration: none;
        color: white;
        padding: 10px 20px;
        font-weight: bold;
        transition: background-color 0.3s;
    }

    nav ul li a:hover {
        background-color: #0074d9; /* Azul claro */
        border-radius: 5px;
    }

    section {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    h2, h3 {
        color: #002147; /* Azul marino */
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th, table td {
        text-align: left;
        padding: 12px;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #002147; /* Azul marino */
        color: white;
    }

    table tr:nth-child(even) {
        background-color: #f0f8ff; /* Azul claro */
    }

    table tr:hover {
        background-color: #e0f7ff; /* Azul más claro */
    }

    p {
        font-size: 1rem;
        line-height: 1.5;
    }

    @media (max-width: 768px) {
        nav ul {
            flex-direction: column;
            align-items: center;
        }

        nav ul li {
            margin-bottom: 10px;
        }

        table {
            font-size: 0.9rem;
        }
    }
</style>

    
</head>
<body>
    <header>
        <h1>Panel Recepcionista</h1>
        <nav>
            <ul>
                <li><a href="recepcionista.php">Inicio</a></li>
                <li><a href="agendar_citar.php">Agendar Cita</a></li>
                <li><a href="reagendar_citas.php">Reagendar citas</a></li>
                <li><a href="registrar_paciente.php">Registrar Paciente</a></li>
                <li><a href="eliminar_pacienter.php">Eliminar paciente</a></li>
                <li><a href="generarpagor.php.">Pago</a></li>
                <li><a href="eliminar_citar.php">Eliminar Cita</a></li>
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
                        echo "<td>" . $row['Apellido_Paterno'] . "</td>";
                        echo "<td>" . $row['Apellido_Materno'] . "</td>";
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

