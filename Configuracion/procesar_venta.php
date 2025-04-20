<?php
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $fecha = date("Y-m-d H:i:s");
    $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $data['productos']));
    $idUsuario = 1; // ID del usuario que realiza la venta
    $idCliente = $data['clienteId'];

    // PRIMERO: Validar que todas las cantidades sean válidas
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];

        // Obtener existencia actual y nombre del producto
        $stmt = $conn->prepare("SELECT existencia, nombre FROM productos WHERE id = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $result = $stmt->get_result();
        $productoData = $result->fetch_assoc();

        if ($productoData) {
            $existenciaActual = $productoData['existencia'];
            $nombreProducto = $productoData['nombre'];

            if ($cantidadVendida > $existenciaActual) {
                echo json_encode([
                    "status" => "error",
                    "message" => "⚠️ La cantidad solicitada para el producto <strong>$nombreProducto</strong> excede la existencia disponible. Solo hay <strong>$existenciaActual</strong> unidades en stock."
                ]);
                exit;
            }            
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Producto no encontrado con ID: $idProducto"
            ]);
            exit;
        }
    }

    // SI TODO ESTÁ BIEN: Insertar la venta
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, idUsuario, idCliente) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdii", $fecha, $total, $idUsuario, $idCliente);
    $stmt->execute();
    $idVenta = $stmt->insert_id;

    // Insertar detalle de productos y actualizar existencias
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];
        $precio = $producto['precio'];

        // Insertar en productos_ventas
        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idii", $cantidadVendida, $precio, $idProducto, $idVenta);
        $stmt->execute();

        // Actualizar la existencia
        $stmt = $conn->prepare("UPDATE productos SET existencia = existencia - ? WHERE id = ?");
        $stmt->bind_param("ii", $cantidadVendida, $idProducto);
        $stmt->execute();
    }

    echo json_encode(["status" => "success", "message" => "Venta realizada con éxito"]);
} else {
    echo json_encode(["status" => "error", "message" => "No se enviaron productos"]);
}

$conn->close();
?>
