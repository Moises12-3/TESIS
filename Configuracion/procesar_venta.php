<?php
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $fecha = date("Y-m-d H:i:s");
    $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $data['productos']));
    $idUsuario = 1; // ID del usuario que realiza la venta
    $idCliente = $data['clienteId'];

    // Insertar venta
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, idUsuario, idCliente) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdii", $fecha, $total, $idUsuario, $idCliente);
    $stmt->execute();
    $idVenta = $stmt->insert_id;

    // Procesar cada producto
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];
        $precio = $producto['precio'];

        // Guardar en productos_ventas
        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idii", $cantidadVendida, $precio, $idProducto, $idVenta);
        $stmt->execute();

        // Obtener la existencia actual del producto
        $stmt = $conn->prepare("SELECT existencia FROM productos WHERE id = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $result = $stmt->get_result();
        $productoData = $result->fetch_assoc();

        if ($productoData) {
            $nuevaExistencia = $productoData['existencia'] - $cantidadVendida;

            // Asegurar que no quede en negativo
            if ($nuevaExistencia < 0) {
                $nuevaExistencia = 0;
            }

            // Actualizar la existencia en la tabla productos
            $stmt = $conn->prepare("UPDATE productos SET existencia = ? WHERE id = ?");
            $stmt->bind_param("ii", $nuevaExistencia, $idProducto);
            $stmt->execute();
        }
    }

    echo json_encode(["status" => "success", "message" => "Venta realizada con éxito"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se enviaron productos"]);
}

$conn->close();
?>
