<?php
include '../Conexion/conex.php';

$sql = "SELECT * FROM proveedores ORDER BY id DESC";
$result = $conn->query($sql);
?>

<table id="tablaProveedoresData" class="table table-bordered table-hover align-middle text-center">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Teléfono</th>
            <th>Correo</th>
            <th>Dirección</th>
            <th>Empresa</th>
            <th>Fecha Registro</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td></td>
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
            <tr><td colspan="9">⚠️ No hay proveedores registrados.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $conn->close(); ?>
