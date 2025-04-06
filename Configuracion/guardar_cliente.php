<?php
include '../Conexion/conex.php';

$nombre = trim($_POST['nombre_cliente']);
$cedula = trim($_POST['cedula_cliente']);
$telefono = trim($_POST['telefono_cliente']);
$direccion = trim($_POST['direccion_cliente']);
$descuento = isset($_POST['descuento_cliente']) ? floatval($_POST['descuento_cliente']) : 0.00;

if ($nombre && $cedula && $telefono && $direccion) {
    // Verificar si la cédula o el teléfono ya existen
    $stmt_check = $conn->prepare("SELECT id FROM clientes WHERE cedula = ? OR telefono = ?");
    $stmt_check->bind_param("ss", $cedula, $telefono);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Ya existe un cliente con la misma cédula o teléfono
        $stmt_check->close();
        header("Location: ../AgregarClientes.php?mensaje=duplicado");
        exit();
    }

    $stmt_check->close();

    // Insertar nuevo cliente si no hay duplicados
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, cedula, telefono, direccion, descuento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssd", $nombre, $cedula, $telefono, $direccion, $descuento);

    if ($stmt->execute()) {
        header("Location: ../AgregarClientes.php?mensaje=guardado");
        exit();
    } else {
        header("Location: ../AgregarClientes.php?mensaje=error");
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../AgregarClientes.php?mensaje=incompleto");
    exit();
}

$conn->close();
?>
