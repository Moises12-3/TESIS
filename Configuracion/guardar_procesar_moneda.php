<?php
include("Conexion/conex.php");

$mensaje = ""; // Variable para mostrar mensajes

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"]);
    $simbolo = trim($_POST["simbolo"]);
    $tipo = $_POST["tipo"];
    $pais = trim($_POST["pais"]);
    $estado = $_POST["estado"];

    // Preparar la consulta
    $sql = "INSERT INTO Moneda (nombre, simbolo, pais, estado) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $nombre, $simbolo, $pais, $estado);
        if ($stmt->execute()) {
            $mensaje = '<div class="alert alert-success">Moneda guardada exitosamente.</div>';
        } else {
            $mensaje = '<div class="alert alert-danger">Error al guardar la moneda.</div>';
        }
        $stmt->close();
    } else {
        $mensaje = '<div class="alert alert-danger">Error en la preparación de la consulta.</div>';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agregar Moneda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Agregar Moneda</h2>

        <!-- Muestra el mensaje de éxito o error -->
        <?php echo $mensaje; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <
