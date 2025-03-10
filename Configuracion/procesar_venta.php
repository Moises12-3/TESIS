<?php
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $fecha = date("Y-m-d H:i:s");
    $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $data));
    $idUsuario = 1; // ID del usuario que realiza la venta (puedes cambiarlo dinámicamente)

    // Insertar venta
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, idUsuario) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $fecha, $total, $idUsuario);
    $stmt->execute();
    $idVenta = $stmt->insert_id;

    // Insertar productos en `productos_ventas`
    foreach ($data as $producto) {
        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idii", $producto['cantidad'], $producto['precio'], $producto['id'], $idVenta);
        $stmt->execute();
    }

    echo json_encode(["status" => "success", "message" => "Venta realizada con éxito"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se enviaron productos"]);
}

$conn->close();
?>
