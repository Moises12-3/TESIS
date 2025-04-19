<?php
require '../Conexion/conex.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];

    // Actualizar el producto en la base de datos
    $sql = "UPDATE productos SET codigo = ?, nombre = ?, compra = ?, venta = ?, existencia = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdidi", $codigo, $nombre, $compra, $venta, $existencia, $id);


    // Calcular el Precio Unitario con IVA
    $precioUnitario = $compra / $existencia;
    $ivaCalculado = $precioUnitario * ($iva / 100);
    $precioConIVA = $precioUnitario + $ivaCalculado;

    // Validar que el precio de venta no sea menor que el precio unitario con IVA
    if ($venta < $precioConIVA) {
        echo "El precio de venta no puede ser menor que el precio unitario con IVA (" . number_format($precioConIVA, 2) . ").";
        exit();
    }
    
    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=actualizado");
        exit;
    } else {
        echo "Error al actualizar el producto.";
    }

    $stmt->close();
    $conn->close();
}
?>
