<?php
include("conexion.php");

// Obtener las citas de la base de datos, incluyendo la especialidad del dentista
$sql = "SELECT citas.Id_cita, citas.Fecha, citas.Hora, pacientes.Nombre AS paciente_nombre, personal.Nombre AS dentista_nombre, personal.Especialidad 
        FROM citas
        JOIN pacientes ON citas.Id_paciente = pacientes.Id_paciente
        JOIN personal ON citas.Id_personal = personal.Id_personal";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Citas</title>
    <style>
        /* Estilos CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .citas-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #002147; /* Azul marino */
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #f0f8ff; /* Azul claro */
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #002147; /* Azul marino */
            color: #ffffff; /* Blanco */
            text-transform: uppercase;
        }

        table tr:nth-child(even) {
            background-color: #e6f2ff; /* Azul muy claro */
        }

        table tr:hover {
            background-color: #cce7ff; /* Azul claro más intenso */
        }

        button {
            background-color: #002147; /* Azul marino */
            color: #ffffff; /* Blanco */
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #004085; /* Azul intermedio */
        }

    </style>
</head>
<body>
    <div class="citas-container">
        <h2>Ver Citas</h2>

        <!-- Tabla para mostrar las citas -->
        <table>
            <thead>
                <tr>
                    <th>ID Cita</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Paciente</th>
                    <th>Dentista</th>
                    <th>Especialidad</th> <!-- Columna para especialidad -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Id_cita'] . "</td>";
                        echo "<td>" . $row['Fecha'] . "</td>";
                        echo "<td>" . $row['Hora'] . "</td>";
                        echo "<td>" . $row['paciente_nombre'] . "</td>";
                        echo "<td>" . $row['dentista_nombre'] . "</td>";
                        echo "<td>" . $row['Especialidad'] . "</td>"; // Mostrar especialidad
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay citas registradas.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Botón para regresar a la página anterior -->
        <form action="administrador.php" method="get">
            <button type="submit">Regresar</button>
        </form>
    </div>
</body>
</html>

<?php
//$conn->close();
?>

