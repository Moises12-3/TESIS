<?php
require '../Conexion/conex.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];

    $sql = "INSERT INTO productos (codigo, nombre, compra, venta, existencia) 
            VALUES ('$codigo', '$nombre', '$compra', '$venta', '$existencia')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../VerProductos.php?mensaje=producto_agregado");
        exit();
    } else {
        echo "Error al guardar el producto: " . $conn->error;
    }

    $conn->close();
}
?>
