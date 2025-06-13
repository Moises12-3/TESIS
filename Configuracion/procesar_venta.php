<?php
date_default_timezone_set('America/Managua'); // Ajusta a tu zona horaria local
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    $fecha = date("Y-m-d H:i:s");
    $idUsuario = 1;
    $idCliente = $data['clienteId'];
    $descuento = isset($data['descuento']) ? floatval($data['descuento']) : 0.0;
    $montoPagadoCliente = isset($data['monto_pagado_cliente']) ? floatval($data['monto_pagado_cliente']) : 0.0;
    $montoDevuelto = isset($data['monto_devuelto']) ? floatval($data['monto_devuelto']) : 0.0;
    error_log("Descuento recibido: $descuento");


    // Calcular el total sin descuento
    $subtotal = array_sum(array_map(fn($p) => $p['precio'] * $p['cantidad'], $data['productos']));

    // Aplicar descuento al total
    $totalConDescuento = $subtotal - ($subtotal * $descuento / 100);

    // Validar existencias
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

    // Generar número de factura
    $prefijo = "FAC";
    $fechaHoy = date("Ymd");
    $likePattern = "$prefijo-$fechaHoy-%";
    $stmt = $conn->prepare("SELECT numeroFactura FROM ventas WHERE numeroFactura LIKE ? ORDER BY numeroFactura DESC LIMIT 1");
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

    // Insertar la venta
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, descuento, monto_devuelto, monto_pagado_cliente, idUsuario, idCliente, numeroFactura) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sddddiis", $fecha, $totalConDescuento, $descuento, $montoDevuelto, $montoPagadoCliente, $idUsuario, $idCliente, $numeroFactura);
    $stmt->execute();
    $idVenta = $stmt->insert_id;

    // Insertar productos y actualizar existencias
    foreach ($data['productos'] as $producto) {
        $idProducto = $producto['id'];
        $cantidadVendida = $producto['cantidad'];
        $precio = $producto['precio'];

        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta, numeroFactura) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("idiss", $cantidadVendida, $precio, $idProducto, $idVenta, $numeroFactura);
        $stmt->execute();

        $stmt = $conn->prepare("UPDATE productos SET existencia = existencia - ? WHERE id = ?");
        $stmt->bind_param("ii", $cantidadVendida, $idProducto);
        $stmt->execute();
    }

    echo json_encode([
        "status" => "success",
        "message" => "Venta realizada con éxito. N° Factura: <strong>$numeroFactura</strong>. <a href='ver_detalle_factura.php?id=$numeroFactura' target='_blank'>Ver detalles</a>",
        "numeroFactura" => $numeroFactura
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No se enviaron productos"
    ]);
}

$conn->close();
?>
