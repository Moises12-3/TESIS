<?php
require '../Conexion/conex.php';

if (isset($_POST['numeroFactura']) && !empty($_POST['numeroFactura'])) {
    $numeroFactura = $_POST['numeroFactura'];

    // Datos de la venta y cliente
    $sql = "SELECT v.id AS idVenta, v.fecha, v.total, v.descuento, v.monto_pagado_cliente, 
                   v.monto_devuelto, c.nombre AS nombre_cliente, c.cedula, c.telefono, c.direccion
            FROM ventas v
            LEFT JOIN clientes c ON v.idCliente = c.id
            WHERE v.numeroFactura = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $numeroFactura);
    $stmt->execute();
    $venta = $stmt->get_result()->fetch_assoc();

    if ($venta) {
        // Productos de la venta
        $sqlProductos = "SELECT pv.cantidad, pv.precio, p.nombre, p.codigo
                         FROM productos_ventas pv
                         INNER JOIN productos p ON pv.idProducto = p.id
                         WHERE pv.numeroFactura = ?";
        $stmt = $conn->prepare($sqlProductos);
        $stmt->bind_param("s", $numeroFactura);
        $stmt->execute();
        $productos = $stmt->get_result();

        // Construir HTML
        echo '<div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Factura: '.htmlspecialchars($numeroFactura).'</h5>
                    <p><strong>Fecha:</strong> '.$venta['fecha'].'</p>
                    <p><strong>Cliente:</strong> '.$venta['nombre_cliente'].' ('.$venta['cedula'].')</p>
                    <p><strong>Teléfono:</strong> '.$venta['telefono'].'</p>
                    <p><strong>Dirección:</strong> '.$venta['direccion'].'</p>
                    <p><strong>Total:</strong> C$ '.number_format($venta['total'], 2).'</p>
                    <p><strong>Descuento:</strong> '.$venta['descuento'].'%</p>
                    <p><strong>Monto Pagado:</strong> C$ '.number_format($venta['monto_pagado_cliente'], 2).'</p>
                    <p><strong>Vuelto:</strong> C$ '.number_format($venta['monto_devuelto'], 2).'</p>
                </div>
              </div>';

        echo '<h4>📦 Detalle de Productos</h4>
              <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario (C$)</th>
                        <th>Subtotal (C$)</th>
                    </tr>
                </thead>
                <tbody>';
        while ($row = $productos->fetch_assoc()) {
            echo '<tr>
                    <td>'.htmlspecialchars($row['codigo']).'</td>
                    <td>'.htmlspecialchars($row['nombre']).'</td>
                    <td>'.$row['cantidad'].'</td>
                    <td>'.number_format($row['precio'],2).'</td>
                    <td>'.number_format($row['precio'] * $row['cantidad'],2).'</td>
                  </tr>';
        }
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-danger text-center">❌ No se encontró la factura con el número ingresado.</div>';
    }
} else {
    echo '<div class="alert alert-warning text-center">Ingrese un número de factura válido.</div>';
}
?>
