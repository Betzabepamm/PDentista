<?php
// Datos de conexión a la base de datos
$server = "localhost";
$database = "consultorio";
$username = "root";
$password = "";

// Crear conexión
$conn = mysqli_connect($server, $username, $password, $database);
  
// Revisar que la conexión fue exitosa
  if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}else {
    //echo "Conexión exitosa a la base de datos '$database'.";
}


// Cerrar la conexión
//mysqli_close($conn);
?>


