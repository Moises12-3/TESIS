<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>


<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>





<body>

<?php
include '../Conexion/conex.php';

$sql = "SELECT * FROM proveedores ORDER BY id DESC";
$result = $conn->query($sql);
?>

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

<?php $conn->close(); ?>


</body>
</html>