<?php 
include '../Conexion/conex.php';

// Recibir parámetros
$fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d', strtotime('-7 days'));
$fecha_fin = $_POST['fecha_fin'] ?? date('Y-m-d');
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 10;
$pagina = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;
$inicio = ($pagina - 1) * $cantidad;

// Contar total de registros
$count_sql = "SELECT COUNT(*) AS total FROM productos_ventas pv
    INNER JOIN ventas v ON pv.idVenta = v.id
    WHERE DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
$count_result = $conn->query($count_sql);
$total_registros = $count_result->fetch_assoc()['total'];
$total_paginas = ceil($total_registros / $cantidad);

// Consulta con LIMIT para paginación
$sql = "SELECT 
            pv.id,
            p.codigo,
            p.nombre AS nombre_producto,
            pv.cantidad,
            pv.precio,
            (pv.cantidad * pv.precio) AS total_linea,
            v.id AS idVenta,
            v.numeroFactura,
            v.fecha,
            v.total AS total_venta,
            v.descuento AS descuento_venta,
            v.monto_pagado_cliente,
            v.monto_devuelto,
            c.nombre AS cliente_nombre,
            (SELECT COUNT(*) FROM devoluciones d WHERE d.idVenta = v.id) AS cantidad_devoluciones
        FROM productos_ventas pv
        INNER JOIN productos p ON pv.idProducto = p.id
        INNER JOIN ventas v ON pv.idVenta = v.id
        LEFT JOIN clientes c ON v.idCliente = c.id
        WHERE DATE(v.fecha) BETWEEN '$fecha_inicio' AND '$fecha_fin'
        ORDER BY v.fecha DESC
        LIMIT $inicio, $cantidad";

$result = $conn->query($sql);

// Mostrar tabla
?>
<table class="table table-hover table-bordered">
    <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Código</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total Línea</th>
            <th>Factura</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Total Venta</th>
            <th>Descuento</th>
            <th>Pagó Cliente</th>
            <th>Cambio</th>
            <th>Devoluciones</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $contador = $inicio + 1;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $tuvoDev = ($row['cantidad_devoluciones'] > 0)
                        ? "<span class='badge bg-danger'>Sí ({$row['cantidad_devoluciones']})</span>"
                        : "<span class='badge bg-success'>No</span>";
    ?>

        <tr>
            <td><?= $contador++; ?></td>
            <td><?= $row['codigo']; ?></td>
            <td><?= $row['nombre_producto']; ?></td>
            <td><?= $row['cantidad']; ?></td>
            <td>$<?= number_format($row['precio'], 2); ?></td>
            <td class="fw-bold text-success">$<?= number_format($row['total_linea'], 2); ?></td>
            <td><?= $row['numeroFactura']; ?></td>
            <td><?= $row['fecha']; ?></td>
            <td><?= $row['cliente_nombre'] ?? 'S/N'; ?></td>

            <td class="fw-bold">$<?= number_format($row['total_venta'], 2); ?></td>
            <td><?= number_format($row['descuento_venta'], 2); ?>%</td>
            <td>$<?= number_format($row['monto_pagado_cliente'], 2); ?></td>
            <td>$<?= number_format($row['monto_devuelto'], 2); ?></td>

            <td><?= $tuvoDev; ?></td>
        </tr>

    <?php 
        }
    } else { ?>
        <tr>
            <td colspan="15" class="text-center text-danger">No se encontraron productos vendidos.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<!-- Paginación -->
<?php if($total_paginas > 1): ?>
<nav>
    <ul class="pagination justify-content-center mt-3">
        <?php for($i=1; $i<=$total_paginas; $i++): ?>
            <li class="page-item <?= ($i==$pagina) ? 'active' : '' ?>">
                <a class="page-link pagina" href="javascript:void(0);" data-pagina="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>
