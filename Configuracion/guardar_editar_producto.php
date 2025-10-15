<?php
require '../Conexion/conex.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];
    $iva = $_POST['iva'];
    $idMoneda = $_POST['moneda'];
    $idUnidadPeso = $_POST['unidad'];
    $fecha_vencimiento = !empty($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : null;

    // Calcular el Precio Unitario con IVA
    $precioUnitario = $existencia > 0 ? $compra / $existencia : 0;
    $ivaCalculado = $precioUnitario * ($iva / 100);
    $precioConIVA = $precioUnitario + $ivaCalculado;

    // Validar que el precio de venta no sea menor que el precio unitario con IVA
    if ($venta < $precioConIVA) {
        echo "⚠️ El precio de venta no puede ser menor que el precio unitario con IVA (" . number_format($precioConIVA, 2) . ").";
        exit();
    }

    // Actualizar el producto en la base de datos
    $sql = "UPDATE productos 
            SET codigo = ?, nombre = ?, compra = ?, venta = ?, existencia = ?, iva = ?, idMoneda = ?, id_UnidadPeso = ?, fecha_vencimiento = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Vincular parámetros (la fecha se pasa como tipo 's')
    $stmt->bind_param("ssddidissi", 
        $codigo, 
        $nombre, 
        $compra, 
        $venta, 
        $existencia, 
        $iva, 
        $idMoneda, 
        $idUnidadPeso, 
        $fecha_vencimiento, 
        $id
    );

    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=actualizado");
        exit;
    } else {
        echo "❌ Error al actualizar el producto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
