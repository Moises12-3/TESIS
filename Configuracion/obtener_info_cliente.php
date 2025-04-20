<?php
// Conexión a la base de datos
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT id, descuento FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($cliente = $resultado->fetch_assoc()) {
        echo json_encode($cliente);
    } else {
        echo json_encode(null); // Cliente no encontrado
    }
} else {
    echo json_encode(null); // ID no recibido
}
?>
