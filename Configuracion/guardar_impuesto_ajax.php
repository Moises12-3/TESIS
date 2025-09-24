<?php
include("../Conexion/conex.php"); // Ajusta la ruta según tu estructura

header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : '';
    $porcentaje = isset($_POST["porcentaje"]) ? trim($_POST["porcentaje"]) : '';
    $descripcion = isset($_POST["descripcion"]) ? trim($_POST["descripcion"]) : '';
    $tipo_impuesto = isset($_POST["tipo_impuesto"]) ? trim($_POST["tipo_impuesto"]) : '';
    $estado = isset($_POST["estado"]) ? trim($_POST["estado"]) : '';
    $id = isset($_POST["id"]) ? trim($_POST["id"]) : null;

    if ($nombre === "" || $porcentaje === "" || $tipo_impuesto === "" || $estado === "") {
        echo json_encode(["status" => "error", "message" => "❌ Por favor complete todos los campos obligatorios."]);
        exit;
    }

    if ($id) {
        // Actualizar
        $sql = "UPDATE Impuesto SET nombre=?, porcentaje=?, descripcion=?, tipo_impuesto=?, estado=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssi", $nombre, $porcentaje, $descripcion, $tipo_impuesto, $estado, $id);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "✅ Impuesto actualizado correctamente."];
            } else {
                $response = ["status" => "error", "message" => "❌ Error al actualizar el impuesto."];
            }
            $stmt->close();
        }
    } else {
        // Insertar
        $sql = "INSERT INTO Impuesto (nombre, porcentaje, descripcion, tipo_impuesto, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nombre, $porcentaje, $descripcion, $tipo_impuesto, $estado);
            if ($stmt->execute()) {
                $response = ["status" => "success", "message" => "✅ Impuesto guardado correctamente."];
            } else {
                $response = ["status" => "error", "message" => "❌ Error al guardar el impuesto."];
            }
            $stmt->close();
        }
    }
}

$conn->close();
echo json_encode($response);
