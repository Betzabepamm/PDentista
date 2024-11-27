<?php
session_start(); // Iniciar sesión
include("conexion.php");

// Verificar si el usuario está logueado como paciente
if (!isset($_SESSION['usuario'])) {
    header("Location: login_usuario.php"); // Redirigir al login si no está logueado
    exit();
}

// Obtener los datos del usuario (paciente) de la sesión
$usuario = $_SESSION['usuario'];
$nombre = $_SESSION['nombre'];
$id_paciente = $_SESSION['id_paciente'];

// Consultar las citas agendadas para el paciente
$sql = "SELECT c.Id_cita, c.Fecha, c.Hora, p.Nombre AS personal_nombre, p.Apellido_paterno AS personal_apellido 
        FROM citas c
        JOIN personal p ON c.Id_personal = p.Id_personal
        WHERE c.Id_paciente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área de Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaf2f8; /* Azul claro muy suave */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #002855; /* Azul marino */
        }

        .area-usuario-container {
            background-color: #ffffff; /* Blanco */
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .area-usuario-container h2 {
            color: #002855; /* Azul marino */
            text-align: center;
            margin-bottom: 20px;
        }

        .area-usuario-container p {
            text-align: center;
            margin: 10px 0;
        }

        .area-usuario-container a {
            color: #0056b3; /* Azul claro */
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .area-usuario-container a:hover {
            text-decoration: underline;
            color: #002855; /* Azul marino */
        }

        .area-usuario-container h3 {
            margin-top: 20px;
            text-align: center;
            color: #002855; /* Azul marino */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #dcdde1; /* Gris claro */
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
    <div class="area-usuario-container">
        <h2>Bienvenido, <?= htmlspecialchars($nombre) ?> (Paciente)</h2>

        <p><a href="logout.php">Cerrar sesión</a></p>

        <h3>Citas Agendadas</h3>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Personal</th>
                </tr>
                <?php while ($cita = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($cita['Fecha']) ?></td>
                        <td><?= htmlspecialchars($cita['Hora']) ?></td>
                        <td><?= htmlspecialchars($cita['personal_nombre']) . " " . htmlspecialchars($cita['personal_apellido']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No tienes citas agendadas.</p>
        <?php endif; ?>

        <h3><a href="agendar_cita.php">Agendar una nueva cita</a></h3>
        <h3><a href="reagendar_citau.php">Reagendar cita</a></h3>
        <h3><a href="eliminar_citau.php">Eliminar mi cita</a></h3>
    </div>
</body>
</html>

