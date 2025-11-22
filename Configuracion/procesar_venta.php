<?php
date_default_timezone_set('America/Managua'); 
require '../Conexion/conex.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data) || empty($data['productos'])) {
    echo json_encode(["status" => "error", "message" => "No se enviaron productos"]);
    exit;
}

$idCliente = isset($data['clienteId']) ? intval($data['clienteId']) : 0;
$descuento = isset($data['descuento']) ? floatval($data['descuento']) : 0.0;
$montoPagadoCliente = isset($data['monto_pagado_cliente']) ? floatval($data['monto_pagado_cliente']) : 0.0;
$montoDevuelto = isset($data['monto_devuelto']) ? floatval($data['monto_devuelto']) : 0.0;
$fecha = date("Y-m-d H:i:s");
$idUsuario = 1;

$idEmpresa = isset($data['id_empresa']) ? intval($data['id_empresa']) : 1; 
$idTipoPago = isset($data['id_tipoPago']) ? intval($data['id_tipoPago']) : 1; 

if ($idCliente <= 0) {
    echo json_encode(["status"=>"error","message"=>"Cliente no válido"]);
    exit;
}

$conn->begin_transaction();

try {
    $subtotal = 0;
    foreach($data['productos'] as $p) {
        $idProducto = intval($p['id']);
        $cantidadVendida = intval($p['cantidad']);
        $precio = floatval($p['precio']);

        $stmt = $conn->prepare("SELECT existencia, nombre FROM productos WHERE id = ?");
        if(!$stmt) throw new Exception("Error prepare select producto: ".$conn->error);
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $result = $stmt->get_result();
        $fila = $result->fetch_assoc();
        $stmt->close();

        if(!$fila) throw new Exception("Producto no encontrado con ID: $idProducto");
        if($cantidadVendida > $fila['existencia']) throw new Exception("⚠️ La cantidad solicitada para {$fila['nombre']} excede la existencia disponible ({$fila['existencia']})");

        $subtotal += $precio * $cantidadVendida;
    }

    $totalConDescuento = $subtotal - ($subtotal * $descuento / 100);

    $prefijo = "FAC";
    $fechaHoy = date("Ymd");
    $likePattern = "$prefijo-$fechaHoy-%";
    $stmt = $conn->prepare("SELECT numeroFactura FROM ventas WHERE numeroFactura LIKE ? ORDER BY numeroFactura DESC LIMIT 1");
    if(!$stmt) throw new Exception("Error prepare select factura: ".$conn->error);
    $stmt->bind_param("s", $likePattern);
    $stmt->execute();
    $result = $stmt->get_result();
    $ultimoNumero = 0;
    if($fila = $result->fetch_assoc()){
        $partes = explode("-",$fila['numeroFactura']);
        $ultimoNumero = intval($partes[2]);
    }
    $stmt->close();

    $nuevoNumero = str_pad($ultimoNumero+1,4,"0",STR_PAD_LEFT);
    $numeroFactura = "$prefijo-$fechaHoy-$nuevoNumero";

    // CORRECCIÓN: Cambié el tipo del bind_param de 'i' a 's' para $numeroFactura
    $stmt = $conn->prepare("INSERT INTO ventas (fecha, total, descuento, monto_devuelto, monto_pagado_cliente, idUsuario, idCliente, numeroFactura, id_empresa, id_tipoPago) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if(!$stmt) throw new Exception("Error prepare insert venta: ".$conn->error);
    $stmt->bind_param("sddddiissi", $fecha, $totalConDescuento, $descuento, $montoDevuelto, $montoPagadoCliente, $idUsuario, $idCliente, $numeroFactura, $idEmpresa, $idTipoPago);
    if(!$stmt->execute()) throw new Exception("Error execute insert venta: ".$stmt->error);
    $idVenta = $stmt->insert_id;
    $stmt->close();

    foreach($data['productos'] as $producto){
        $idProducto = intval($producto['id']);
        $cantidadVendida = intval($producto['cantidad']);
        $precio = floatval($producto['precio']);

        $stmt = $conn->prepare("INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta, numeroFactura) VALUES (?, ?, ?, ?, ?)");
        if(!$stmt) throw new Exception("Error prepare insert prod venta: ".$conn->error);
        $stmt->bind_param("idiss",$cantidadVendida, $precio, $idProducto, $idVenta, $numeroFactura);
        if(!$stmt->execute()) throw new Exception("Error execute insert prod venta: ".$stmt->error);
        $stmt->close();

        $stmt = $conn->prepare("UPDATE productos SET existencia = existencia - ? WHERE id = ?");
        if(!$stmt) throw new Exception("Error prepare update prod: ".$conn->error);
        $stmt->bind_param("ii", $cantidadVendida, $idProducto);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();

    echo json_encode([
        "status"=>"success",
        "message"=>"Venta realizada con éxito. N° Factura: <strong>$numeroFactura</strong>",
        "numeroFactura"=>$numeroFactura
    ]);

} catch(Exception $e) {
    $conn->rollback();
    echo json_encode(["status"=>"error","message"=>$e->getMessage()]);
}

$conn->close();
?>
