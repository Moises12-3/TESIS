<?php
require_once "../Conexion/conex.php";
session_start();

// Validar sesión
if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no válida']);
    exit;
}

$idUsuario = $_SESSION['id'];

// Obtener los datos enviados y sanear
$nombre   = trim($_POST['nombre'] ?? '');
$cedula   = trim($_POST['cedula'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validación mínima
if(empty($nombre)){
    echo json_encode(['status' => 'error', 'message' => 'El nombre no puede estar vacío']);
    exit;
}

// Preparar SQL
if(!empty($password)){
    if($password !== $confirm_password){
        echo json_encode(['status' => 'error', 'message' => 'Las contraseñas no coinciden']);
        exit;
    }
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE usuarios SET nombre=?, cedula=?, telefono=?, direccion=?, password=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $cedula, $telefono, $direccion, $passwordHash, $idUsuario);
} else {
    // No se actualiza contraseña
    $sql = "UPDATE usuarios SET nombre=?, cedula=?, telefono=?, direccion=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nombre, $cedula, $telefono, $direccion, $idUsuario);
}

// Ejecutar y retornar respuesta
if($stmt->execute()){
    echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado correctamente']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar perfil']);
}

$stmt->close();
$conn->close();
?>
