<?php
// Datos de conexión a la base de datos
$host = 'localhost'; // o la IP de tu servidor
$usuario = 'root'; // tu usuario de MySQL
$contrasena = ''; // tu contraseña de MySQL
$base_de_datos = 'consultorio'; // nombre de la base de datos

// Crear conexión
$conn = new mysqli($host, $usuario, $contrasena, $base_de_datos);

// Comprobar si hay error en la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener los registros de pago junto con la información del paciente
$sql = "SELECT rp.Id_pago, rp.Fecha_pago, rp.Monto, rp.Id_cita, p.Nombre, p.Apellido_paterno, p.Apellido_materno 
        FROM registro_de_pago rp
        JOIN citas c ON rp.Id_cita = c.Id_cita
        JOIN pacientes p ON c.Id_paciente = p.Id_paciente";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Registro de Pagos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Blanco */
            color: #003366; /* Azul marino */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #003366; /* Azul marino */
            color: #ffffff; /* Blanco */
            padding: 20px;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #f0f8ff; /* Azul claro */
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #003366; /* Azul marino */
        }

        th {
            background-color: #003366; /* Azul marino */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #e6f7ff; /* Azul muy claro */
        }

        tr:hover {
            background-color: #cceeff; /* Azul claro al pasar el ratón */
        }

        .container {
            padding: 20px;
        }

        .no-records {
            text-align: center;
            font-size: 18px;
            color: #003366; /* Azul marino */
        }

        .generate-button {
            background-color: #003366; /* Azul marino */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: 200px;
        }

        .generate-button:hover {
            background-color: #00509e; /* Azul marino más claro */
        }
    </style>
</head>
<body>

<header>
    <h1>Generar Registro de Pagos</h1>
</header>

<div class="container">
    <?php
    // Verificar si hay resultados
    if ($resultado->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID Pago</th>
                    <th>Fecha de Pago</th>
                    <th>Monto</th>
                    <th>ID Cita</th>
                    <th>Paciente</th>
                </tr>";

        // Mostrar los registros de pago junto con la información del paciente
        while($fila = $resultado->fetch_assoc()) {
            $paciente = $fila["Nombre"] . " " . $fila["Apellido_paterno"] . " " . $fila["Apellido_materno"];
            echo "<tr>
                    <td>" . $fila["Id_pago"] . "</td>
                    <td>" . $fila["Fecha_pago"] . "</td>
                    <td>" . $fila["Monto"] . "</td>
                    <td>" . $fila["Id_cita"] . "</td>
                    <td>" . $paciente . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-records'>No hay registros de pago.</p>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>

    <!-- Botón para generar un nuevo registro de pago -->
    <button class="generate-button" onclick="window.location.href='pagor.php'">Generar Pago</button>
    <button class="recepcionista.php">Regresar al inicio</button>
</div>

</body>
</html>

