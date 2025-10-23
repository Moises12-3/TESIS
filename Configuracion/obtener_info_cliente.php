<?php
header('Content-Type: application/json; charset=utf-8');
include("../Conexion/conex.php");

if (!isset($_GET['id'])) {
    echo json_encode(null);
    exit;
}

$id = intval($_GET['id']);

// Asegurarse que el nombre de la tabla sea el correcto (clientes)
$sql = "SELECT id, descuento FROM clientes WHERE id = ? LIMIT 1";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(null);
    exit;
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
    // Forzar retorno numérico en descuento
    $cliente['descuento'] = isset($cliente['descuento']) ? (float)$cliente['descuento'] : 0;
    echo json_encode($cliente);
} else {
    echo json_encode(null);
}
$stmt->close();
$conn->close();
?>
