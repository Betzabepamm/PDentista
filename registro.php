<?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Verificamos si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario y sanearlos para evitar inyecciones SQL
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido_paterno = mysqli_real_escape_string($conn, $_POST['apellido_paterno']);
    $apellido_materno = mysqli_real_escape_string($conn, $_POST['apellido_materno']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $correo = mysqli_real_escape_string($conn, $_POST['correo']);
    $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
    $contrasena = ($_POST['contrasena']); // Encriptamos la contraseña
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']); // Administrador, dentista, recepcionista
    $especialidad = isset($_POST['especialidad']) ? mysqli_real_escape_string($conn, $_POST['especialidad']) : ''; // Solo para dentista

    // Verificamos si ya existe un usuario con el mismo nombre de usuario
    $check_usuario = "SELECT * FROM personal WHERE Usuario = '$usuario'";
    $result = mysqli_query($conn, $check_usuario);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "El nombre de usuario ya está registrado.";
    } else {
        // Insertamos los datos en la tabla de personal sin incluir Id_personal (auto increment)
        if ($tipo == 'dentista') {
            // Si es dentista, también guardamos la especialidad
            $query = "INSERT INTO personal (Nombre, Apellido_paterno, Apellido_materno, Telefono, Correo, Usuario, Contrasena, Tipo, Estatus, Especialidad) 
                      VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$telefono', '$correo', '$usuario', '$contrasena', '$tipo', 'activo', '$especialidad')";
        } else {
            // Si no es dentista, no se incluye la especialidad
            $query = "INSERT INTO personal (Nombre, Apellido_paterno, Apellido_materno, Telefono, Correo, Usuario, Contrasena, Tipo, Estatus) 
                      VALUES ('$nombre', '$apellido_paterno', '$apellido_materno', '$telefono', '$correo', '$usuario', '$contrasena', '$tipo', 'activo')";
        }
        
        // Ejecutar la consulta y verificar si la inserción fue exitosa
        if (mysqli_query($conn, $query)) {
            $success = "Registro exitoso. Ahora puedes iniciar sesión.";
        } else {
            $error = "Error al registrar: " . mysqli_error($conn);
        }
    }
}

// Cerrar la conexión (opcional si se quiere al final del script)
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f6fa; /* Azul claro muy suave */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container styles */
        .registro-container {
            background-color: #1a2b4f; /* Azul marino */
            padding: 75px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            color: #fff; /* Blanco */
            text-align: center;
        }

        /* Title styles */
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #ffffff; /* Blanco */
        }

        /* Input styles */
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }

        /* Input fields */
        input {
            background-color: #e8eff9; /* Azul claro */
            border: 1px solid #ccc;
        }

        input:focus {
            outline: none;
            border: 1px solid #4285f4; /* Azul más vivo */
        }

        /* Select dropdown */
        select {
            background-color: #e8eff9;
            border: 1px solid #ccc;
        }

        /* Button styles */
        button {
            background-color: #4a90e2; /* Azul claro medio */
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #357abd; /* Azul más oscuro */
        }

        /* Special fields */
        #especialidad_field {
            margin-top: 15px;
            text-align: left;
        }

        /* Messages */
        .error {
            color: #ff6b6b; /* Rojo claro */
            background-color: #ffe8e8;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .success {
            color: #4caf50; /* Verde */
            background-color: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        /* Link styles */
        a {
            color: #4a90e2; /* Azul claro */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Función para mostrar el campo de especialidad solo si el tipo de usuario es dentista
        function mostrarEspecialidad() {
            var tipoUsuario = document.getElementById('tipo_usuario').value;
            var especialidadField = document.getElementById('especialidad_field');
            if (tipoUsuario === 'dentista') {
                especialidadField.style.display = 'block';
            } else {
                especialidadField.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="registro-container">
        <h2>Crear Cuenta</h2>
        
        <?php
        if (isset($error)) {
            echo "<p class='error'>$error</p>";
        }
        if (isset($success)) {
            echo "<p class='success'>$success</p>";
        }
        ?>
        
        <form action="registro.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido_paterno" placeholder="Apellido Paterno" required>
            <input type="text" name="apellido_materno" placeholder="Apellido Materno" required>
            <input type="tel" name="telefono" placeholder="Teléfono" required>
            <input type="email" name="correo" placeholder="Correo Electrónico" required>
            <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="contrasena" placeholder="Contraseña" required>
            
            <label for="tipo">Tipo de Usuario</label>
            <select name="tipo" id="tipo_usuario" required onchange="mostrarEspecialidad()">
                <option value="administrador">Administrador</option>
                <option value="dentista">Dentista</option>
                <option value="recepcionista">Recepcionista</option>
            </select>
            
            <!-- Campo de especialidad (solo visible si se selecciona "dentista") -->
            <div id="especialidad_field" style="display: none;">
                <label for="especialidad">Especialidad del Dentista:</label>
                <input type="text" name="especialidad" placeholder="Especialidad">
            </div>
            
            <button type="submit">Registrar</button>
        </form>
        
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            <form action="index.php" method="get">
            <button type="submit">Regresar</button>
    </div>
</body>
</html>







