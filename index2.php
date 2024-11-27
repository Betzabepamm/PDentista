<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit();
}

// Verificar el tipo de usuario
if ($_SESSION['tipo_usuario'] !== 'dentista' && $_SESSION['tipo_usuario'] !== 'recepcionista') {
    echo "No tienes permiso para acceder a esta página.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Dentistas y Recepcionistas</title>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo de estilos opcional -->
</head>
<body>
    <header>
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?> (<?php echo ucfirst($_SESSION['tipo_usuario']); ?>)</h1>
    </header>
    <nav>
        <ul>
            <li><a href="ver_pacientes.php">Ver Pacientes</a></li>
            <li><a href="agendar_citasr.php">Agendar Citas</a></li>
            <li><a href="ver_dentista.php">Ver Dentistas</a></li>
            <li><a href="ver_recepcionista.php">Ver Recepcionistas</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
    <main>
        <p>Esta página solo puede ser vista por dentistas y recepcionistas.</p>
    </main>
    <footer>
        <p>© 2024 Clínica Dental</p>
    </footer>
</body>
</html>
