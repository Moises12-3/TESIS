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
        $sqlProductos = "SELECT pv.idProducto, pv.cantidad, pv.precio, p.nombre, p.codigo
                         FROM productos_ventas pv
                         INNER JOIN productos p ON pv.idProducto = p.id
                         WHERE pv.numeroFactura = ?";  

        $stmtProductos = $conn->prepare($sqlProductos);
        $stmtProductos->bind_param("s", $numeroFactura);
        $stmtProductos->execute();
        $productos = $stmtProductos->get_result();

        // Mostrar info de la venta
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

        // Formulario de devolución con validación
        echo '<h4>📦 Detalle de Productos</h4>
              <form id="formDevolucion" method="post" action="procesar_devolucion.php">
              <input type="hidden" name="numeroFactura" value="'.htmlspecialchars($numeroFactura).'">
              <input type="hidden" name="idVenta" value="'.$venta['idVenta'].'">
              <div class="mb-3">
                  <label for="motivo" class="form-label">Motivo de la devolución</label>
                  <input type="text" class="form-control" name="motivo" placeholder="Ejemplo: producto dañado" required>
              </div>

              <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Devolver</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Cantidad vendida</th>
                        <th>Precio Unitario (C$)</th>
                        <th>Subtotal (C$)</th>
                        <th>Cantidad a devolver</th>
                    </tr>
                </thead>
                <tbody>';

        while ($row = $productos->fetch_assoc()) {
            $subtotal = $row['cantidad'] * $row['precio'];
            echo '<tr>
                    <td><input type="checkbox" name="productos['.$row['idProducto'].'][check]" value="1"></td>
                    <td>'.htmlspecialchars($row['codigo']).'</td>
                    <td>'.htmlspecialchars($row['nombre']).'</td>
                    <td>'.$row['cantidad'].'</td>
                    <td>'.number_format($row['precio'],2).'</td>
                    <td>'.number_format($subtotal,2).'</td>
                    <td><input type="number" class="form-control" 
                               name="productos['.$row['idProducto'].'][cantidad]" 
                               min="1" max="'.$row['cantidad'].'" disabled></td>
                  </tr>';
        }

        echo '</tbody></table>
              <button type="submit" class="btn btn-success">Registrar devolución</button>
              </form>

              <script>
              // Habilitar input cantidad solo si está marcado el checkbox
              $("#formDevolucion input[type=checkbox]").change(function(){
                  $(this).closest("tr").find("input[type=number]").prop("disabled", !this.checked);
              });
              </script>';

    } else {
        echo '<div class="alert alert-danger text-center">❌ No se encontró la factura con el número ingresado.</div>';
    }
} else {
    echo '<div class="alert alert-warning text-center">Ingrese un número de factura válido.</div>';
}
?>
