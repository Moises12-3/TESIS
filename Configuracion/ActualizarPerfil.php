<?php
session_start();
require_once "../Conexion/conex.php";  // Ajusta la ruta según sea necesario

// Verificar si la sesión está activa
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['id'];

// Recibir los datos del formulario y sanitizarlos
$usuario = isset($_POST['usuario']) ? trim($_POST['usuario']) : '';
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$cedula = isset($_POST['cedula']) ? trim($_POST['cedula']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
$direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
$descuento = isset($_POST['descuento']) ? trim($_POST['descuento']) : '';
$rol = isset($_POST['rol']) ? trim($_POST['rol']) : '';

// Validar que no haya campos vacíos
if (empty($usuario) || empty($nombre) || empty($cedula) || empty($telefono) || empty($direccion) || empty($descuento) || empty($rol)) {
    header("Location: ../MyProfile.php?mensaje=incompleto");
    exit();
}

// Validaciones específicas de tipo de datos
if (!preg_match("/^[a-zA-Z0-9]+$/", $usuario)) {
    header("Location: ../MyProfile.php?mensaje=usuario_invalido");
    exit();
}

if (!preg_match("/^[a-zA-Z]+$/", $nombre)) {
    header("Location: ../MyProfile.php?mensaje=nombre_invalido");
    exit();
}

if (!preg_match("/^[a-zA-Z0-9]+$/", $cedula)) {
    header("Location: ../MyProfile.php?mensaje=cedula_invalida");
    exit();
}

if (!preg_match("/^[0-9]{8,15}$/", $telefono)) {
    // Validar que el teléfono sea numérico y tenga entre 8 y 15 dígitos
    header("Location: ../MyProfile.php?mensaje=telefono_invalido");
    exit();
}

if (!is_numeric($descuento)) {
    // Validar que el descuento sea un número decimal
    header("Location: ../MyProfile.php?mensaje=descuento_invalido");
    exit();
}

if (!preg_match("/^[a-zA-Z]+$/", $rol)) {
    // Validar que el rol sea solo letras
    header("Location: ../MyProfile.php?mensaje=rol_invalido");
    exit();
}

// Sanitización para evitar inyección SQL (aunque ya usas `prepare` y `bind_param`)
$usuario = mysqli_real_escape_string($conn, $usuario);
$nombre = mysqli_real_escape_string($conn, $nombre);
$cedula = mysqli_real_escape_string($conn, $cedula);
$telefono = mysqli_real_escape_string($conn, $telefono);
$direccion = mysqli_real_escape_string($conn, $direccion);
$descuento = mysqli_real_escape_string($conn, $descuento);
$rol = mysqli_real_escape_string($conn, $rol);

// Actualizar los datos en la base de datos
$sql = "UPDATE usuarios SET 
    usuario = ?, nombre = ?, cedula = ?, telefono = ?, direccion = ?, descuento = ?, rol = ?
    WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssdsi", $usuario, $nombre, $cedula, $telefono, $direccion, $descuento, $rol, $id);

// Ejecutar la consulta
if ($stmt->execute()) {
    header("Location: ../MyProfile.php?mensaje=guardado");
} else {
    header("Location: ../MyProfile.php?mensaje=error");
}
?>
