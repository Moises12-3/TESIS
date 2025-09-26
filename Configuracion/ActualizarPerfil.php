<?php
require_once "../Conexion/conex.php";
session_start();

if (!isset($_SESSION['id'])) {
    echo "Sesión no válida";
    exit;
}

$idUsuario = $_SESSION['id'];

// Obtener los datos enviados
$nombre = $_POST['nombre'] ?? '';
$cedula = $_POST['cedula'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$direccion = $_POST['direccion'] ?? '';

// Validación mínima
if(empty($nombre)){
    echo "El nombre no puede estar vacío";
    exit;
}

// Actualizar solo los campos permitidos
$sql = "UPDATE usuarios SET nombre=?, cedula=?, telefono=?, direccion=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $cedula, $telefono, $direccion, $idUsuario);

if($stmt->execute()){
    echo "Perfil actualizado correctamente";
} else {
    echo "Error al actualizar perfil";
}
?>
