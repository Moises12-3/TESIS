<?php
require '../Conexion/conex.php';

if (!isset($_GET['id_usuario'])) {
    echo json_encode([]);
    exit;
}

$id_usuario = intval($_GET['id_usuario']);

// OBTENER PÁGINAS
$paginas = $conn->query("SELECT * FROM paginas_projectos ORDER BY modulo, pagina");

// OBTENER permisos del usuario
$permisos_usuario = $conn->query("
    SELECT id_permiso 
    FROM permisos_usuario 
    WHERE id_usuario = $id_usuario
");

$permisos = [];
while ($p = $permisos_usuario->fetch_assoc()) {
    $permisos[] = $p['id_permiso'];
}

$resultado = [];

while ($row = $paginas->fetch_assoc()) {
    $resultado[] = [
        "id" => $row["id"],
        "modulo" => $row["modulo"],
        "pagina" => $row["pagina"],
        "checked" => in_array($row["id"], $permisos) ? true : false
    ];
}

echo json_encode($resultado);
