<?php
require '../Conexion/conex.php'; // Asegúrate de que la ruta es correcta

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir a número para evitar inyección SQL

    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=eliminado"); // Redireccionar con mensaje
        exit();
    } else {
        echo "Error al eliminar el producto.";
    }
    $stmt->close();
}
$conn->close();
?>
