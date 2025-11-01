<?php
include '../Conexion/conex.php'; // Ajusta ruta si es necesario

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $empresa = $_POST['empresa'] ?? '';

    $sql = "INSERT INTO proveedores (nombre, cedula, telefono, correo, direccion, empresa)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nombre, $cedula, $telefono, $correo, $direccion, $empresa);

    if ($stmt->execute()) {
        echo "Proveedor agregado correctamente 🎉";
    } else {
        echo "Error al guardar: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "⚠️ Método no permitido.";
}
?>
