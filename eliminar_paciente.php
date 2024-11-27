<?php
session_start();

// Verificar si el usuario es administrador (o quien tenga permiso para eliminar pacientes)
if ($_SESSION['tipo'] != 'administrador') {
    header("Location: login.php");
    exit;
}

include('conexion.php');

// Obtener el ID del administrador logueado
$id_administrador = $_SESSION['id_personal'];  // Asegúrate de que 'id_personal' esté en la sesión

// Obtener los pacientes registrados
$sql_pacientes = "SELECT Id_paciente, Nombre, Apellido_paterno, Apellido_materno, Telefono FROM pacientes";
$stmt = $conn->prepare($sql_pacientes);
$stmt->execute();
$result_pacientes = $stmt->get_result();

// Eliminar paciente si se solicita
if (isset($_GET['eliminar']) && isset($_GET['id_paciente'])) {
    $id_paciente = $_GET['id_paciente'];

    // Eliminar primero los tratamientos relacionados con el paciente
    $sql_eliminar_tratamientos = "DELETE FROM tratamiento WHERE Id_cita IN (SELECT Id_cita FROM citas WHERE Id_paciente = ?)";
    $stmt = $conn->prepare($sql_eliminar_tratamientos);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();

    // Eliminar luego las citas del paciente
    $sql_eliminar_citas = "DELETE FROM citas WHERE Id_paciente = ?";
    $stmt = $conn->prepare($sql_eliminar_citas);
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();

    // Finalmente eliminar al paciente
    $sql_eliminar_paciente = "DELETE FROM pacientes WHERE Id_paciente = ?";
    $stmt = $conn->prepare($sql_eliminar_paciente);
    $stmt->bind_param("i", $id_paciente);
    
    if ($stmt->execute()) {
        echo "<script>alert('Paciente eliminado exitosamente');</script>";
        echo "<script>window.location.href = 'eliminar_paciente.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el paciente');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Pacientes</title>
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

        /* Eliminar botón */
        .btn-eliminar {
            background-color: #ff4d4d; /* Rojo */
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
        }

        .btn-eliminar:hover {
            background-color: #ff1a1a; /* Rojo más oscuro */
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
        <h1>Panel Administrador</h1>
        <nav>
            <ul>
            <li><a href="administrador.php">Inicio</a></li>
            <li><a href="index.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h2>Eliminar Pacientes Registrados</h2>
        <p>Aquí podrás eliminar a los pacientes registrados en el sistema.</p>

        <!-- Mostrar Pacientes -->
        <table>
            <thead>
                <tr>
                    <th>ID Paciente</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pacientes->num_rows > 0) {
                    while($row = $result_pacientes->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Id_paciente'] . "</td>";
                        echo "<td>" . $row['Nombre'] . " " . $row['Apellido_paterno'] . " " . $row['Apellido_materno'] . "</td>";
                        echo "<td>" . $row['Telefono'] . "</td>";
                        echo "<td><a href='?eliminar=true&id_paciente=" . $row['Id_paciente'] . "' class='btn-eliminar' onclick='return confirm(\"¿Estás seguro de eliminar a este paciente?\")'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay pacientes registrados para eliminar.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <p><a href="administrador.php">Regresar al inicio</a></p>
    </section>
</body>
</html>

<?php
//$conn->close();
?>



