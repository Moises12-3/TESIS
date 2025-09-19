<?php
include("../Conexion/conex.php");

header('Content-Type: application/json');

$response = ["success" => false, "message" => ""];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $estado = $_POST["estado"] ?? "activo";

    if ($nombre !== "") {
        $sql = "INSERT INTO TipoPago (nombre, descripcion, estado) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $nombre, $descripcion, $estado);
            if ($stmt->execute()) {
                $response["success"] = true;
                $response["message"] = "✅ Tipo de pago guardado exitosamente.";
            } else {
                $response["message"] = "❌ Error al guardar el tipo de pago.";
            }
            $stmt->close();
        } else {
            $response["message"] = "⚠️ Error en la preparación de la consulta.";
        }
    } else {
        $response["message"] = "El campo nombre es obligatorio.";
    }
}

echo json_encode($response);
