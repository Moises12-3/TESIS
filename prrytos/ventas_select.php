<?php
include("../Conexion/conex.php");

// Consultas para cada tabla
$sqlMoneda = "SELECT id, nombre, simbolo FROM Moneda WHERE estado = 'activo'";
$sqlUnidadPeso = "SELECT id, nombre, simbolo FROM UnidadPeso WHERE estado = 'activo'";
$sqlTipoPago = "SELECT id, nombre FROM TipoPago";
$sqlImpuesto = "SELECT id, nombre, porcentaje FROM Impuesto WHERE estado = 'Activo'";

$monedas = $conn->query($sqlMoneda);
$unidades = $conn->query($sqlUnidadPeso);
$tiposPago = $conn->query($sqlTipoPago);
//$impuestos = $conn->query($sqlImpuesto);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Select dinámicos con buscador</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-lg rounded-4">
        <div class="card-body">
            <h3 class="mb-4 text-center">Formulario con Selects Dinámicos + Buscador</h3>
            
            <form method="POST" action="procesar.php">
                <!-- Moneda -->
                <div class="mb-3">
                    <label for="moneda" class="form-label">Moneda</label>
                    <select class="form-select select2" id="moneda" name="moneda" required>
                        <option value="">-- Selecciona Moneda --</option>
                        <?php while($row = $monedas->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['nombre'] ?> (<?= $row['simbolo'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Unidad de Peso -->
                <div class="mb-3">
                    <label for="unidad" class="form-label">Unidad de Peso</label>
                    <select class="form-select select2" id="unidad" name="unidad" required>
                        <option value="">-- Selecciona Unidad --</option>
                        <?php while($row = $unidades->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['nombre'] ?> (<?= $row['simbolo'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Tipo de Pago -->
                <div class="mb-3">
                    <label for="tipoPago" class="form-label">Tipo de Pago</label>
                    <select class="form-select select2" id="tipoPago" name="tipoPago" required>
                        <option value="">-- Selecciona Tipo de Pago --</option>
                        <?php while($row = $tiposPago->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Impuesto -->
                <div class="mb-3">
                    <label for="impuesto" class="form-label">Impuesto</label>
                    <select class="form-select select2" id="impuesto" name="impuesto" required>
                        <option value="">-- Selecciona Impuesto --</option>
                        <?php while($row = $impuestos->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= $row['nombre'] ?> (<?= $row['porcentaje'] ?>%)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Botón -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar Select2 en todos los selects con class="select2"
        $('.select2').select2({
            placeholder: "Selecciona una opción",
            allowClear: true,
            width: '100%'
        });
    });
</script>
</body>
</html>
