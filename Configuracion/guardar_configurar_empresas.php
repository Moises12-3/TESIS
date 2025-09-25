<?php
include("../Conexion/conex.php"); // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST['id'] ?? null;
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $direccion = $conn->real_escape_string($_POST['direccion']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $fax = $conn->real_escape_string($_POST['fax']);
    $identidad_juridica = $conn->real_escape_string($_POST['identidad_juridica']);

    if($id) {
        // Actualizar empresa existente
        $stmt = $conn->prepare("UPDATE empresa SET nombre=?, direccion=?, correo=?, telefono=?, fax=?, identidad_juridica=? WHERE id=?");
        $stmt->bind_param("ssssssi", $nombre, $direccion, $correo, $telefono, $fax, $identidad_juridica, $id);
        if ($stmt->execute()) {
            echo "✏️ Empresa actualizada correctamente.";
        } else {
            http_response_code(500);
            echo "❌ Error al actualizar la empresa: " . $stmt->error;
        }
    } else {
        // Insertar nueva empresa
        $codigo_interno = uniqid('EMP_');
        $stmt = $conn->prepare("INSERT INTO empresa (nombre, direccion, correo, telefono, fax, codigo_interno, identidad_juridica) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nombre, $direccion, $correo, $telefono, $fax, $codigo_interno, $identidad_juridica);
        if ($stmt->execute()) {
            echo "✅ Empresa guardada correctamente.";
        } else {
            http_response_code(500);
            echo "❌ Error al guardar la empresa: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>
