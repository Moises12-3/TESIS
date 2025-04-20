<?php
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $fecha = date("Y-m-d H:i:s");
    $total = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $data['productos']));
    $idUsuario = 1;
    $idCliente = $data['clienteId'];

    // Validar que todas las cantidades sean válidas
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];

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

    // Generar número de factura único
    $prefijo = "FAC";
    $fechaHoy = date("Ymd");

    $stmt = $conn->prepare("SELECT numeroFactura FROM ventas WHERE numeroFactura LIKE ? ORDER BY numeroFactura DESC LIMIT 1");
    $likePattern = "$prefijo-$fechaHoy-%";
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();

    $ultimoNumero = 0;
    if ($fila = $result->fetch_assoc()) {
        $partes = explode("-", $fila['numeroFactura']);
        $ultimoNumero = (int)$partes[2];
    }

    $nuevoNumero = str_pad($ultimoNumero + 1, 4, "0", STR_PAD_LEFT);
    $numeroFactura = "$prefijo-$fechaHoy-$nuevoNumero";

    // Insertar la venta con número de factura
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, idUsuario, idCliente, numeroFactura) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdiis", $fecha, $total, $idUsuario, $idCliente, $numeroFactura);
    $stmt->execute();
    $idVenta = $stmt->insert_id;

    // Insertar productos vendidos y actualizar existencias
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];
        $precio = $producto['precio'];

        // Insertar en productos_ventas con el mismo número de factura
        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta, numeroFactura) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idiss", $cantidadVendida, $precio, $idProducto, $idVenta, $numeroFactura);
        $stmt->execute();

        // Actualizar existencia
        $stmt = $conn->prepare("UPDATE productos SET existencia = existencia - ? WHERE id = ?");
        $stmt->bind_param("ii", $cantidadVendida, $idProducto);
        $stmt->execute();
    }

    echo json_encode([
        "status" => "success",
        "message" => "Venta realizada con éxito. N° Factura: <strong>$numeroFactura</strong>"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No se enviaron productos"
    ]);
}

$conn->close();
?>
