<?php
include("../Conexion/conex.php");

$nombre = trim($_POST["nombre"]);
$simbolo = trim($_POST["simbolo"]);
$estado = $_POST["estado"];

$sql = "INSERT INTO UnidadPeso (nombre, simbolo, estado) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

$respuesta = [];

if ($stmt) {
    $stmt->bind_param("sss", $nombre, $simbolo, $estado);
    if ($stmt->execute()) {
        $id = $stmt->insert_id;
        $fila = "<tr>
                    <td>{$id}</td>
                    <td>{$nombre}</td>
                    <td>{$simbolo}</td>
                    <td>" . ($estado == "activo" ? "🟢 Activo" : "🔴 Inactivo") . "</td>
                 </tr>";

        $respuesta = [
            "estado" => "ok",
            "mensaje" => '<div class="alert alert-success mt-3">✅ Unidad guardada exitosamente.</div>',
            "fila" => $fila
        ];
    } else {
        $respuesta = [
            "estado" => "error",
            "mensaje" => '<div class="alert alert-danger mt-3">❌ Error al guardar.</div>'
        ];
    }
    $stmt->close();
} else {
    $respuesta = [
        "estado" => "error",
        "mensaje" => '<div class="alert alert-warning mt-3">⚠️ Error en la consulta.</div>'
    ];
}

$conn->close();
echo json_encode($respuesta);
