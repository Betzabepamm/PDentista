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

// Consulta para obtener todos los pacientes
$sql = "SELECT Id_paciente, Nombre, Apellido_paterno, Apellido_materno, Fecha_nacimiento, Genero, Direccion, Telefono FROM pacientes";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Pagos</title>
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
            width: 90%;
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

        .generate-button {
            background-color: #003366; /* Azul marino */
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
        }

        .generate-button:hover {
            background-color: #00509e; /* Azul marino más claro */
        }

        .container {
            padding: 20px;
        }

        .no-records {
            text-align: center;
            font-size: 18px;
            color: #003366; /* Azul marino */
        }
    </style>
</head>
<body>

<header>
    <h1>Generar Pagos por Paciente</h1>
</header>

<div class="container">
    <?php
    // Verificar si hay resultados
    if ($resultado->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>ID Paciente</th>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Género</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Acción</th>
                </tr>";

        // Mostrar la información de cada paciente
        while($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>" . $fila["Id_paciente"] . "</td>
                    <td>" . $fila["Nombre"] . "</td>
                    <td>" . $fila["Apellido_paterno"] . "</td>
                    <td>" . $fila["Apellido_materno"] . "</td>
                    <td>" . $fila["Fecha_nacimiento"] . "</td>
                    <td>" . $fila["Genero"] . "</td>
                    <td>" . $fila["Direccion"] . "</td>
                    <td>" . $fila["Telefono"] . "</td>
                    <td>
                        <form action='procesar_pago.php' method='POST'>
                            <input type='hidden' name='Id_paciente' value='" . $fila["Id_paciente"] . "'>
                            <button type='submit' class='generate-button'>Generar Pago</button>
                        </form>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='no-records'>No hay pacientes registrados.</p>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</div>

</body>
</html>
