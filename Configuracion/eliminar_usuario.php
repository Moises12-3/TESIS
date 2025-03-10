<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

// Verificar si el parámetro 'id' está presente en la URL
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Consulta para eliminar el usuario de la base de datos
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página VerUsuarios.php después de la eliminación
        header("Location: ../VerUsuario.php?mensaje=eliminado");
        exit();
    } else {
        echo "Error al eliminar el usuario.";
    }
} else {
    echo "ID de usuario no proporcionado.";
}
?>
