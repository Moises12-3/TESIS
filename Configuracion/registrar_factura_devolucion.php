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

    $errores = [];
    $devoluciones_insertadas = 0;

    foreach ($productos as $idProducto => $detalle) {

        if (!isset($detalle['check'])) continue; // Solo si está marcado

        $cantidad_devuelta_actual = (int)$detalle['cantidad'];

        // Obtener cantidad vendida
        $stmt = $conn->prepare("SELECT cantidad FROM productos_ventas WHERE idVenta = ? AND idProducto = ?");
        $stmt->bind_param("ii", $idVenta, $idProducto);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $cantidad_vendida = $row['cantidad'] ?? 0;

        // Obtener cantidad devuelta previamente
        $stmtPrev = $conn->prepare("
            SELECT SUM(cantidad_devuelta) AS devuelto_previamente 
            FROM devoluciones 
            WHERE idVenta = ? AND idProducto = ?
        ");
        $stmtPrev->bind_param("ii", $idVenta, $idProducto);
        $stmtPrev->execute();
        $rowPrev = $stmtPrev->get_result()->fetch_assoc();
        $cantidad_devuelta_previa = $rowPrev['devuelto_previamente'] ?? 0;

        // Validaciones
        if ($cantidad_devuelta_actual < 1) {
            $errores[] = "Debe indicar al menos 1 unidad para devolver del producto ID $idProducto.";
            continue;
        }

        if (($cantidad_devuelta_previa + $cantidad_devuelta_actual) > $cantidad_vendida) {
            $errores[] = "Devolución excede lo vendido (vendidas: $cantidad_vendida, ya devueltas: $cantidad_devuelta_previa, intentando devolver: $cantidad_devuelta_actual).";
            continue;
        }

        // Insertar en la tabla devoluciones
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

        if ($stmtInsert->execute()) {

            $devoluciones_insertadas++;

            // Devolver al inventario
            $stmtUpdate = $conn->prepare("UPDATE productos SET existencia = existencia + ? WHERE id = ?");
            $stmtUpdate->bind_param("ii", $cantidad_devuelta_actual, $idProducto);
            $stmtUpdate->execute();

        } else {
            $errores[] = "Error al registrar la devolución del producto ID $idProducto.";
        }
    }

    // Si hubo devoluciones, actualizar el monto devuelto de la venta
    if ($devoluciones_insertadas > 0) {

        $stmtMonto = $conn->prepare("
            SELECT SUM(d.cantidad_devuelta * pv.precio) AS total_devuelto 
            FROM devoluciones d
            INNER JOIN productos_ventas pv ON d.idProducto = pv.idProducto AND d.idVenta = pv.idVenta
            WHERE d.idVenta = ?
        ");
        $stmtMonto->bind_param("i", $idVenta);
        $stmtMonto->execute();
        $res = $stmtMonto->get_result()->fetch_assoc();
        $total_devuelto = $res['total_devuelto'] ?? 0;

        $stmtUpdVenta = $conn->prepare("UPDATE ventas SET monto_devuelto = ? WHERE id = ?");
        $stmtUpdVenta->bind_param("di", $total_devuelto, $idVenta);
        $stmtUpdVenta->execute();

        echo '<div class="alert alert-success">✅ Se registraron '.$devoluciones_insertadas.' devoluciones correctamente.</div>';
    }

    // Mostrar errores
    if (!empty($errores)) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errores as $e) {
            echo "<li>$e</li>";
        }
        echo '</ul></div>';
    }

} else {
    echo '<div class="alert alert-warning">Método no permitido.</div>';
}
?>
