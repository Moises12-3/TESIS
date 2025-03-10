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
