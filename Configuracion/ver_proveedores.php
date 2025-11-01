<?php
include '../Conexion/conex.php';

// Consulta proveedores
$sql = "SELECT * FROM proveedores ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<!-- jQuery (ya cargado en proveedor.php, si no, incluye) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>


<table id="tablaProveedoresData" class="table table-bordered table-hover align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th>🆔 ID</th>
            <th>👤 Nombre</th>
            <th>🪪 Cédula</th>
            <th>📞 Teléfono</th>
            <th>📧 Correo</th>
            <th>🏠 Dirección</th>
            <th>🏢 Empresa</th>
            <th>🕒 Fecha Registro</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['cedula']) ?></td>
                    <td><?= htmlspecialchars($row['telefono']) ?></td>
                    <td><?= htmlspecialchars($row['correo']) ?></td>
                    <td><?= htmlspecialchars($row['direccion']) ?></td>
                    <td><?= htmlspecialchars($row['empresa']) ?></td>
                    <td><?= $row['fecha_registro'] ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">⚠️ No hay proveedores registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>


<script>
$(document).ready(function() {
    $('#tablaProveedoresData').DataTable({
        responsive: true,
        pageLength: 5, // filas por página por defecto
        lengthMenu: [5, 10, 25, 50, 100],
        language: {
            lengthMenu: "Mostrar _MENU_ registros por página",
            zeroRecords: "No se encontraron resultados 😢",
            info: "Mostrando página _PAGE_ de _PAGES_",
            infoEmpty: "No hay registros disponibles",
            infoFiltered: "(filtrado de _MAX_ registros totales)",
            search: "🔍 Buscar:",
            paginate: {
                first: "Primero",
                last: "Último",
                next: "Siguiente ➡️",
                previous: "⬅️ Anterior"
            }
        },
        order: [[0, "desc"]] // Ordenar por ID descendente
    });
});
</script>

<?php $conn->close(); ?>  

