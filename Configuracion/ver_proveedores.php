<?php
include '../Conexion/conex.php';

// Consulta proveedores
$sql = "SELECT * FROM proveedores ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Buttons JS -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

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
        lengthMenu: [ [5, 10, 25, 50, 100], [5, 10, 25, 50, 100] ], // opciones para mostrar
        order: [[0, "desc"]],
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
        dom: 'Bflrtip', // Botones + lengthMenu + tabla
        buttons: [
            {
                extend: 'excelHtml5',
                text: '📥 Exportar a Excel',
                className: 'btn btn-success mb-2',
                title: 'Proveedores',
                exportOptions: {
                    columns: ':visible' // solo columnas visibles
                }
            }
        ]
    });
});
</script>

<?php $conn->close(); ?>
