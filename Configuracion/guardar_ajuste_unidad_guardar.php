<?php
include("../Conexion/conex.php");
header('Content-Type: application/json');

$response = ["status" => "error", "message" => "Error desconocido"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $simbolo = trim($_POST["simbolo"]);
    $estado = $_POST["estado"];

    $sql = "INSERT INTO UnidadPeso (nombre, simbolo, estado) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $nombre, $simbolo, $estado);

        if ($stmt->execute()) {
            $response = ["status" => "success", "message" => "✅ Unidad guardada correctamente."];
        } else {
            $response = ["status" => "error", "message" => "❌ Error al guardar en la BD."];
        }
        $stmt->close();
    } else {
        $response = ["status" => "error", "message" => "⚠️ Error en la preparación de la consulta."];
    }
}

$conn->close();
echo json_encode($response);
