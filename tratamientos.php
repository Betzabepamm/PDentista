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

// Registrar tratamiento si se recibe información
if (isset($_POST['registrar_tratamiento'])) {
    $id_cita = $_POST['id_cita'];
    $descripcion = $_POST['descripcion'];
    $observaciones = $_POST['observaciones'];

    // Comprobar si existe un tratamiento anterior y agregarlo al historial
    $sql_historial = "SELECT Historial_de_cambios FROM tratamiento WHERE Id_cita = ?";
    $stmt = $conn->prepare($sql_historial);
    $stmt->bind_param("i", $id_cita);
    $stmt->execute();
    $result_historial = $stmt->get_result();
    $historial = "";

    if ($result_historial->num_rows > 0) {
        $historial = $result_historial->fetch_assoc()['Historial_de_cambios'];
    }

    // Agregar el nuevo tratamiento al historial
    $nuevo_historial = $historial ? $historial . "\n" : "";
    $nuevo_historial .= "Tratamiento registrado: " . $descripcion . " - " . $observaciones . " (Fecha: " . date('Y-m-d H:i:s') . ")";

    // Insertar el nuevo tratamiento
    $sql_tratamiento = "INSERT INTO tratamiento (Id_cita, Descripcion, Observaciones, Historial_de_cambios) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_tratamiento);
    $stmt->bind_param("isss", $id_cita, $descripcion, $observaciones, $nuevo_historial);

    if ($stmt->execute()) {
        echo "<script>alert('Tratamiento registrado exitosamente');</script>";
        echo "<script>window.location.href = 'tratamientos.php';</script>";
    } else {
        echo "<script>alert('Error al registrar el tratamiento');</script>";
    }
}

// Eliminar tratamiento si se recibe solicitud
if (isset($_POST['eliminar_tratamiento'])) {
    $id_tratamiento = $_POST['id_tratamiento'];

    $sql_eliminar_tratamiento = "DELETE FROM tratamiento WHERE Id_tratamiento = ?";
    $stmt = $conn->prepare($sql_eliminar_tratamiento);
    $stmt->bind_param("i", $id_tratamiento);

    if ($stmt->execute()) {
        echo "<script>alert('Tratamiento eliminado exitosamente');</script>";
        echo "<script>window.location.href = 'tratamientos.php';</script>";
    } else {
        echo "<script>alert('Error al eliminar el tratamiento');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tratamientos</title>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Estilos para el Body */
body {
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;
    font-size: 16px;
    padding: 20px;
}

/* Header */
header {
    background-color: #003366;
    color: #fff;
    padding: 20px 10px;
    text-align: center;
    border-radius: 8px;
}

header h1 {
    font-size: 2em;
    margin-bottom: 10px;
}

header nav ul {
    list-style: none;
    padding: 0;
    display: flex;
    justify-content: center;
    gap: 15px;
}

header nav ul li a {
    text-decoration: none;
    color: #fff;
    font-weight: bold;
    transition: color 0.3s ease;
}

header nav ul li a:hover {
    color: #ffcc00;
}

/* Section */
section {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

section h2 {
    color: #003366;
    margin-bottom: 10px;
}

section p {
    margin-bottom: 15px;
}

/* Tabla */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

table th {
    background-color: #003366;
    color: white;
    font-weight: bold;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

/* Botones */
button, a {
    display: inline-block;
    background-color: #003366;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

button:hover, a:hover {
    background-color: #ffcc00;
    color: #003366;
}

/* Hora en tiempo real */
#hora {
    font-weight: bold;
    color: #003366;
}

/* Formulario */
form {
    margin-top: 15px;
}

form button {
    background-color: #d9534f;
}

form button:hover {
    background-color: #c9302c;
}

    </style>
    
    <script>
        // Función para mostrar la hora en tiempo real
        function mostrarHora() {
            var fecha = new Date();
            var horas = fecha.getHours();
            var minutos = fecha.getMinutes();
            var segundos = fecha.getSeconds();
            
            // Agregar un cero delante si los minutos o segundos son menores de 10
            minutos = minutos < 10 ? "0" + minutos : minutos;
            segundos = segundos < 10 ? "0" + segundos : segundos;

            // Mostrar la hora en formato HH:mm:ss
            var horaActual = horas + ":" + minutos + ":" + segundos;

            // Actualizar el elemento con el id "hora" con la hora actual
            document.getElementById("hora").innerText = horaActual;
        }

        // Ejecutar la función cada segundo para actualizar la hora en tiempo real
        setInterval(mostrarHora, 1000);
    </script>
</head>
<body onload="mostrarHora()">
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
    <h2>Tratamientos Agendados</h2>
    <p>Aquí podrás ver y registrar tratamientos para las citas agendadas.</p>

    <!-- Mostrar Hora Actual -->
    <p><strong>Hora Actual:</strong> <span id="hora"></span></p>

    <!-- Mostrar Citas -->
    <h3>Citas con Tratamientos Pendientes</h3>
    <table>
        <thead>
            <tr>
                <th>ID Cita</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paciente</th>
                <th>Acciones</th>
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
                    echo "<td>
                            <a href='tratamientos.php?id_cita=" . $row['Id_cita'] . "&action=ver'>Ver Tratamiento</a> | 
                            <a href='tratamientos.php?id_cita=" . $row['Id_cita'] . "&action=registrar'>Registrar Tratamiento</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No tienes citas con tratamientos pendientes.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Verificar si se ha pasado una cita específica para ver o registrar un tratamiento
    if (isset($_GET['id_cita'])) {
        $id_cita = $_GET['id_cita'];
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        // Obtener detalles de la cita seleccionada
        $sql_cita_detalle = "SELECT citas.Id_cita, citas.Fecha, citas.Hora, pacientes.Nombre AS paciente_nombre, 
                                     pacientes.Apellido_paterno AS paciente_apellido
                              FROM citas
                              JOIN pacientes ON citas.Id_paciente = pacientes.Id_paciente
                              WHERE citas.Id_cita = ?";
        $stmt = $conn->prepare($sql_cita_detalle);
        $stmt->bind_param("i", $id_cita);
        $stmt->execute();
        $result_cita_detalle = $stmt->get_result();
        $cita = $result_cita_detalle->fetch_assoc();

        if ($action == 'ver') {
            // Ver tratamiento si ya existe
            $sql_tratamiento = "SELECT * FROM tratamiento WHERE Id_cita = ?";
            $stmt = $conn->prepare($sql_tratamiento);
            $stmt->bind_param("i", $id_cita);
            $stmt->execute();
            $result_tratamiento = $stmt->get_result();
            
            if ($result_tratamiento->num_rows > 0) {
                echo "<h3>Tratamientos Registrados para la Cita {$cita['Id_cita']}</h3>";
                while ($tratamiento = $result_tratamiento->fetch_assoc()) {
                    echo "<p><strong>Descripción:</strong> {$tratamiento['descripcion']}</p>";
                    echo "<p><strong>Observaciones:</strong> {$tratamiento['observaciones']}</p>";
                    echo "<p><strong>Historial de Cambios:</strong><br>{$tratamiento['historial_de_cambios']}</p>";
                    
                    // Formulario para eliminar el tratamiento
                    echo "<form action='tratamientos.php' method='POST'>
                            <input type='hidden' name='id_tratamiento' value='" . $tratamiento['Id_tratamiento'] . "'>
                            <button type='submit' name='eliminar_tratamiento'>Eliminar Tratamiento</button>
                          </form>";
                }
            } else {
                echo "<p>No se ha registrado un tratamiento para esta cita.</p>";
            }
        }
    }
    ?>

    <!-- Botón para regresar a dentista.php -->
    <form action="dentista.php" method="get">
        <button type="submit">Regresar 

        </button>
    </form>

</section>
</body>
</html>





