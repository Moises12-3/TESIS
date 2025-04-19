<?php
// Conexión a la base de datos
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

$sql = "SELECT id, nombre FROM clientes";
$resultado = $conn->query($sql);

$clientes = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $clientes[] = $fila;
    }
}

echo json_encode($clientes);
?>
