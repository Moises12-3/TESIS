<?php
// Ruta del archivo JSON
$configPath = __DIR__ . '/conexion.json';

// Verificar que el archivo existe
if (!file_exists($configPath)) {
    die("Error: No se encontró el archivo de configuración JSON.");
}

// Leer y decodificar el archivo JSON
$configData = json_decode(file_get_contents($configPath), true);

// Validar que se pudo decodificar correctamente
if ($configData === null) {
    die("Error: No se pudo leer el archivo JSON. Revisa la sintaxis.");
}

// Extraer los valores
$servername = $configData['servername'];
$username   = $configData['username'];
$password   = $configData['password'];
$database   = $configData['database'];
$port       = $configData['port'];

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database, $port);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configurar codificación UTF-8
$conn->set_charset("utf8mb4");

// echo "Conexión exitosa"; // <-- Descomenta para probar
?>
