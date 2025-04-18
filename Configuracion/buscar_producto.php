<?php
require '../Conexion/conex.php';

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Consulta para obtener los datos del producto
    $sql = "SELECT id, codigo, nombre, venta FROM productos WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode($fila);
    } else {
        echo json_encode(null); // Si no se encuentra el producto
    }

    $conn->close();
}
?>