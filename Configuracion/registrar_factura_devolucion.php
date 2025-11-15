<?php
require '../Conexion/conex.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $numeroFactura = $_POST['numeroFactura'] ?? '';
    $idVenta = $_POST['idVenta'] ?? 0;
    $motivo = trim($_POST['motivo'] ?? '');
    $productos = $_POST['productos'] ?? [];

    if (empty($numeroFactura) || empty($idVenta) || empty($productos)) {
        echo '<div class="alert alert-warning">Datos incompletos para procesar la devolución.</div>';
        exit;
    }

    // Obtener valores actuales de la venta
    $sqlVenta = $conn->prepare("SELECT total, descuento, monto_pagado_cliente, monto_devuelto FROM ventas WHERE id = ?");
    $sqlVenta->bind_param("i", $idVenta);
    $sqlVenta->execute();
    $venta = $sqlVenta->get_result()->fetch_assoc();

    $total_original      = $venta['total'];
    $descuento_original  = $venta['descuento'];
    $pagado_original     = $venta['monto_pagado_cliente'];
    $vuelto_original     = $venta['monto_devuelto'];

    $errores = [];
    $devoluciones_insertadas = 0;
    $monto_devuelto_operacion = 0;

    foreach ($productos as $idProducto => $detalle) {

        if (!isset($detalle['check'])) continue;

        $cantidad_devuelta_actual = (int)$detalle['cantidad'];

        // Obtener cantidad vendida
        $stmt = $conn->prepare("SELECT cantidad, precio FROM productos_ventas WHERE idVenta = ? AND idProducto = ?");
        $stmt->bind_param("ii", $idVenta, $idProducto);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $cantidad_vendida = $row['cantidad'] ?? 0;
        $precio_unitario = $row['precio'] ?? 0;

        // Obtener devoluciones previas
        $stmtPrev = $conn->prepare("
            SELECT SUM(cantidad_devuelta) AS devuelto_previamente 
            FROM devoluciones 
            WHERE idVenta = ? AND idProducto = ?
        ");
        $stmtPrev->bind_param("ii", $idVenta, $idProducto);
        $stmtPrev->execute();
        $rowPrev = $stmtPrev->get_result()->fetch_assoc();
        $cantidad_devuelta_previa = $rowPrev['devuelto_previamente'] ?? 0;

        // Validación
        if (($cantidad_devuelta_previa + $cantidad_devuelta_actual) > $cantidad_vendida) {
            $errores[] = "La devolución excede lo vendido del producto ID $idProducto.";
            continue;
        }

        // Calcular subtotal devuelto para este producto
        $subtotal_devuelto = $cantidad_devuelta_actual * $precio_unitario;
        $monto_devuelto_operacion += $subtotal_devuelto;

        // Insertar registro en tabla devoluciones
        $stmtInsert = $conn->prepare("
            INSERT INTO devoluciones (idVenta, numeroFactura, idProducto, cantidad_vendida, cantidad_devuelta_previa, cantidad_devuelta, motivo)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtInsert->bind_param(
            "isiiiis",
            $idVenta,
            $numeroFactura,
            $idProducto,
            $cantidad_vendida,
            $cantidad_devuelta_previa,
            $cantidad_devuelta_actual,
            $motivo
        );
        $stmtInsert->execute();

        $devoluciones_insertadas++;

        // Actualizar inventario
        $stmtUpdate = $conn->prepare("UPDATE productos SET existencia = existencia + ? WHERE id = ?");
        $stmtUpdate->bind_param("ii", $cantidad_devuelta_actual, $idProducto);
        $stmtUpdate->execute();
    }

    // Actualizar venta si hubo devoluciones
    if ($devoluciones_insertadas > 0) {

        // Calcular nuevo monto devuelto total
        $monto_devuelto_total = $vuelto_original + $monto_devuelto_operacion;

        // Nuevo total después de devoluciones
        $total_nuevo = $total_original - $monto_devuelto_operacion;

        // Calcular nuevo vuelto
        $nuevo_vuelto = $pagado_original - $total_nuevo;
        if ($nuevo_vuelto < 0) $nuevo_vuelto = 0;

        // Actualizar tabla ventas
        $stmtUpdVenta = $conn->prepare("
            UPDATE ventas 
            SET monto_devuelto = ?, total = ?
            WHERE id = ?
        ");

        $stmtUpdVenta->bind_param("dii", $monto_devuelto_total, $total_nuevo, $idVenta);
        $stmtUpdVenta->execute();

        // Mostrar resumen
        echo '
        <div class="card p-3 shadow">
            <h4 class="mb-3">✔ Devolución aplicada correctamente</h4>
            <p><strong>Monto devuelto en esta operación:</strong> C$ '.number_format($monto_devuelto_operacion,2).'</p>
            <p><strong>Monto devuelto acumulado:</strong> C$ '.number_format($monto_devuelto_total,2).'</p>

            <hr>

            <h5>Valores originales</h5>
            <p><strong>Total original:</strong> C$ '.number_format($total_original,2).'</p>
            <p><strong>Descuento:</strong> '.$descuento_original.'%</p>
            <p><strong>Monto pagado:</strong> C$ '.number_format($pagado_original,2).'</p>
            <p><strong>Vuelto original:</strong> C$ '.number_format($vuelto_original,2).'</p>

            <hr>

            <h5>Valores actualizados</h5>
            <p><strong>Nuevo total:</strong> C$ '.number_format($total_nuevo,2).'</p>
            <p><strong>Nuevo vuelto:</strong> C$ '.number_format($nuevo_vuelto,2).'</p>
        </div>';
    }

    if (!empty($errores)) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errores as $e) echo "<li>$e</li>";
        echo '</ul></div>';
    }

} else {
    echo '<div class="alert alert-warning">Método no permitido.</div>';
}
?>
