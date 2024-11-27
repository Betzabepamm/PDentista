<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy(); // Destruir la sesión
header("Location: login_usuario.php"); // Redirigir al login
exit();
?>
