<?php
require '../Conexion/conex.php';

$id_usuario = intval($_POST['usuario']);
$permisos = json_decode($_POST['permisos'], true);

// BORRAR PERMISOS EXISTENTES DEL USUARIO
$conn->query("DELETE FROM permisos_usuario WHERE id_usuario = $id_usuario");

// INSERTAR NUEVOS
foreach ($permisos as $p) {
    if ($p['checked']) {
        $id_permiso = intval($p['id_permiso']);
        $conn->query("INSERT INTO permisos_usuario (id_usuario, id_permiso) VALUES ($id_usuario, $id_permiso)");
    }
}

echo "OK";