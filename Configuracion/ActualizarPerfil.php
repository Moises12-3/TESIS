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
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validación mínima
if(empty($nombre)){
    echo "El nombre no puede estar vacío";
    exit;
}

// Validar contraseña si se ingresó
if(!empty($password)){
    if($password !== $confirm_password){
        echo "Las contraseñas no coinciden";
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nombre=?, cedula=?, telefono=?, direccion=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $cedula, $telefono, $direccion, $passwordHash, $idUsuario);
} else {
    // Si no cambia contraseña
    $sql = "UPDATE usuarios SET nombre=?, cedula=?, telefono=?, direccion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $cedula, $telefono, $direccion, $idUsuario);
}

if($stmt->execute()){
    echo "Perfil actualizado correctamente";
} else {
    echo "Error al actualizar perfil";
}
?>
