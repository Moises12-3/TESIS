<?php
include("../Conexion/conex.php"); // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $fax = $conn->real_escape_string($_POST['fax']);
    $identidad_juridica = $conn->real_escape_string($_POST['identidad_juridica']);
    $codigo_interno = uniqid('EMP_');

    $stmt = $conn->prepare("INSERT INTO empresa (nombre, direccion, correo, telefono, fax, codigo_interno, identidad_juridica) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nombre, $direccion, $correo, $telefono, $fax, $codigo_interno, $identidad_juridica);

    if ($stmt->execute()) {
        echo "✅ Empresa guardada correctamente.";
    } else {
        http_response_code(500);
        echo "❌ Error al guardar la empresa: " . $stmt->error;
    }

    $stmt->close();
}
?>
