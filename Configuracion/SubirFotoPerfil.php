<?php
require_once "../Conexion/conex.php";
session_start();

if (!isset($_SESSION['id'])) {
    echo "Sesión no válida";
    exit;
}

$idUsuario = $_SESSION['id'];

// Validar archivo
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $ext = strtolower($ext);
    $permitidos = ['jpg','jpeg','png'];

    if(!in_array($ext, $permitidos)){
        echo "Formato no permitido";
        exit;
    }

    // Nombre único de archivo
    $nombreArchivo = "perfil_" . $idUsuario . "_" . time() . "." . $ext;
    $carpeta = "../images/photo_perfil/";
    $ruta = $carpeta . $nombreArchivo;

    // Mover archivo a la carpeta
    if(move_uploaded_file($_FILES['foto']['tmp_name'], $ruta)){
        // Guardar la ruta completa en BD
        $rutaBD = "images/photo_perfil/" . $nombreArchivo; // Ruta relativa para mostrar en la web
        $sql = "UPDATE usuarios SET foto_perfil=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $rutaBD, $idUsuario);

        if($stmt->execute()){
            echo $nombreArchivo; // Retornamos solo el nombre para actualizar el preview
        } else {
            echo "Error al guardar en BD";
        }
    } else {
        echo "Error al mover archivo";
    }
} else {
    echo "No se recibió archivo";
}
?>
