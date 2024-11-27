<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit();
}

// Verificar el tipo de usuario
if ($_SESSION['tipo_usuario'] !== 'administrador') {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Administrador</title>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo de estilos opcional -->
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?> (Administrador)</h1>
    </header>
    <nav>
        <ul>
            <li><a href="ver_pacientes.php">Ver Pacientes</a></li>
            <li><a href="agendar_citasr.php">Agendar Citas</a></li>
            <li><a href="ver_dentista.php">Ver Dentistas</a></li>
            <li><a href="ver_recepcionista.php">Ver Recepcionistas</a></li>
            <li><a href="gestionar_usuarios.php">Gestionar Usuarios</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <main>
        <p>Este panel está diseñado exclusivamente para administradores.</p>
    </main>
    <footer>
        <p>© 2024 Clínica Dental</p>
    </footer>
</body>
</html>
