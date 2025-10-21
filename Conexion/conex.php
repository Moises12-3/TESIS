<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "ventas_php";
$port = "33067"; 

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Quita este `echo` en producción
// echo "Conexión exitosa";

// Esto es clave para que reconozca tildes y ñ
$conn->set_charset("utf8mb4");
?>
