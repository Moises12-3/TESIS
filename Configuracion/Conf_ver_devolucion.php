<?php
require '../Conexion/conex.php';

$registros_por_pagina = isset($_POST['limite']) ? (int)$_POST['limite'] : 10;
$pagina_actual = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$busqueda = isset($_POST['buscar']) ? $conn->real_escape_string($_POST['buscar']) : "";

$condicion_busqueda = "";
if ($busqueda !== "") {
    $condicion_busqueda = " AND (
        d.numeroFactura LIKE '%$busqueda%' OR
        c.nombre LIKE '%$busqueda%' OR
        c.cedula LIKE '%$busqueda%' OR
        p.nombre LIKE '%$busqueda%' OR
        p.codigo LIKE '%$busqueda%' OR
        d.motivo LIKE '%$busqueda%' OR
        u.nombre LIKE '%$busqueda%'
    )";
}

$sql_total = "SELECT COUNT(*) AS total
              FROM devoluciones d
              INNER JOIN ventas v ON d.idVenta = v.id
              INNER JOIN productos_ventas pv ON d.idProducto = pv.idProducto AND d.idVenta = pv.idVenta
              INNER JOIN productos p ON d.idProducto = p.id
              LEFT JOIN clientes c ON v.idCliente = c.id
              LEFT JOIN usuarios u ON v.idUsuario = u.id
              WHERE 1 $condicion_busqueda";

$total_resultado = $conn->query($sql_total)->fetch_assoc();
$total_registros = $total_resultado['total'];
$total_paginas = ceil($total_registros / $registros_por_pagina);

$sql = "SELECT 
            d.id,
            d.numeroFactura,
            d.cantidad_vendida,
            d.cantidad_devuelta,
            d.cantidad_devuelta_previa,
            d.motivo,
            d.fecha_devolucion,
            v.total AS total_venta,
            v.descuento AS descuento_venta,
            v.monto_pagado_cliente,
            v.monto_devuelto,
            c.nombre AS nombre_cliente,
            c.cedula AS cedula_cliente,
            c.telefono AS telefono_cliente,
            c.direccion AS direccion_cliente,
            u.nombre AS usuario_venta,
            p.nombre AS nombre_producto,
            p.codigo AS codigo_producto,
            pv.precio AS precio_unitario,
            (pv.cantidad * pv.precio) AS subtotal_producto,
            (d.cantidad_devuelta * pv.precio) AS monto_devuelto_producto
        FROM devoluciones d
        INNER JOIN ventas v ON d.idVenta = v.id
        INNER JOIN productos_ventas pv ON d.idProducto = pv.idProducto AND d.idVenta = pv.idVenta
        INNER JOIN productos p ON d.idProducto = p.id
        LEFT JOIN clientes c ON v.idCliente = c.id
        LEFT JOIN usuarios u ON v.idUsuario = u.id
        WHERE 1 $condicion_busqueda
        ORDER BY d.fecha_devolucion DESC
        LIMIT $registros_por_pagina OFFSET $offset";

$result = $conn->query($sql);

// ==========================
// IMPRIMIR TABLA
// ==========================
?>

<?php if ($result->num_rows > 0): ?>

<table class="table table-striped table-bordered table-hover">
<thead class="table-dark text-center">
    <tr>
        <th>Acción</th> 
        <th>ID</th>
        <th>Factura</th>
        <th>Cliente</th>
        <th>Cédula</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Producto</th>
        <th>Código</th>
        <th>Cant. Vendida</th>
        <th>Precio Unit.</th>
        <th>Subtotal</th>
        <th>Cant. Devuelta</th>
        <th>Devuelto Prev.</th>
        <th>Monto Devuelto</th>
        <th>Motivo</th>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Total Venta</th>
        <th>Descuento</th>
        <th>Pagado</th>
        <th>Devuelto</th>
    </tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td class="text-center">
        <button class="btn btn-sm btn-primary" onclick="imprimirFila(this)">🖨️</button>
    </td>
    <td><?= $row['id'] ?></td>
    <td><?= $row['numeroFactura'] ?></td>
    <td><?= $row['nombre_cliente'] ?></td>
    <td><?= $row['cedula_cliente'] ?></td>
    <td><?= $row['telefono_cliente'] ?></td>
    <td><?= $row['direccion_cliente'] ?></td>
    <td><?= $row['nombre_producto'] ?></td>
    <td><?= $row['codigo_producto'] ?></td>
    <td><?= $row['cantidad_vendida'] ?></td>
    <td><?= number_format($row['precio_unitario'],2) ?></td>
    <td><?= number_format($row['subtotal_producto'],2) ?></td>
    <td><?= $row['cantidad_devuelta'] ?></td>
    <td><?= $row['cantidad_devuelta_previa'] ?></td>
    <td><?= number_format($row['monto_devuelto_producto'],2) ?></td>
    <td><?= $row['motivo'] ?></td>
    <td><?= $row['usuario_venta'] ?></td>
    <td><?= $row['fecha_devolucion'] ?></td>
    <td><?= number_format($row['total_venta'],2) ?></td>
    <td><?= $row['descuento_venta'] ?></td>
    <td><?= number_format($row['monto_pagado_cliente'],2) ?></td>
    <td><?= number_format($row['monto_devuelto'],2) ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- PAGINACIÓN AJAX -->
<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i=1; $i <= $total_paginas; $i++): ?>
            <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                <a class="page-link" href="#" onclick="cargarTabla(<?= $i ?>)">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>

<?php else: ?>
<div class="alert alert-info text-center">No se encontraron devoluciones.</div>
<?php endif; ?>
