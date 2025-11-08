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
    $idProveedor = $_POST['proveedor']; // ✅ Nuevo campo
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

    // ✅ Actualizar el producto incluyendo el proveedor
    $sql = "UPDATE productos 
            SET codigo = ?, 
                nombre = ?, 
                compra = ?, 
                venta = ?, 
                existencia = ?, 
                iva = ?, 
                idMoneda = ?, 
                id_UnidadPeso = ?, 
                idProveedor = ?, 
                fecha_vencimiento = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // ✅ Vincular parámetros (se agregó idProveedor antes de la fecha)
    $stmt->bind_param("ssddidiiisi", 
        $codigo, 
        $nombre, 
        $compra, 
        $venta, 
        $existencia, 
        $iva, 
        $idMoneda, 
        $idUnidadPeso, 
        $idProveedor, 
        $fecha_vencimiento, 
        $id
    );

    if ($stmt->execute()) {
        //header("Location: ../EditarProducto.php?id=$id&mensaje=actualizado");
        header("Location: ../VerProductos.php");
        exit;
    } else {
        echo "❌ Error al actualizar el producto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
