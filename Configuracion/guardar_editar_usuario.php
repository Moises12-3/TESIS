<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $password = $_POST['password'];

    // Si no se cambia la contraseña, no la actualizamos
    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_DEFAULT); // Encriptar la nueva contraseña
        $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, direccion = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssi', $usuario, $nombre, $telefono, $direccion, $password, $id);
    } else {
        $sql = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssssi', $usuario, $nombre, $telefono, $direccion, $id);
    }

    if ($stmt->execute()) {
        echo "Usuario actualizado con éxito.";
        header("Location: ../VerUsuario.php"); // Redirigir a la página de listado de usuarios
        exit;
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>
