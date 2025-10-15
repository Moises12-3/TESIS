<?php
require '../Conexion/conex.php'; // Asegúrate de que la ruta es correcta

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir a número para seguridad

    // Cambiar estado a inactivo en vez de eliminar
    $sql = "UPDATE productos SET estado = 'inactivo' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=deshabilitado"); // Redireccionar con mensaje
        exit();
    } else {
        echo "Error al cambiar el estado del producto.";
    }
    $stmt->close();
}
$conn->close();
?>
