<?php
// Conexión a la base de datos
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos


$query = isset($_GET['query']) ? $_GET['query'] : ''; // Obtener el valor de la búsqueda

// Consulta para obtener clientes
$sql = "SELECT id, nombre FROM clientes WHERE nombre LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%"; // Agregar el comodín para la búsqueda
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$resultado = $stmt->get_result();

$clientes = [];
while ($cliente = $resultado->fetch_assoc()) {
    $clientes[] = $cliente; // Agregar cada cliente al array
}

echo json_encode($clientes); // Devolver los clientes como JSON
?>

