<?php
include("../Conexion/conex.php"); // Ajusta la ruta según tu estructura

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Obtener la última empresa registrada
    $resultado = $conn->query("SELECT id, nombre FROM empresa ORDER BY id DESC LIMIT 1");
    if ($resultado && $resultado->num_rows > 0) {
        $empresa = $resultado->fetch_assoc();
        $id_empresa = $empresa['id'];
        $nombre_empresa = preg_replace('/[^a-zA-Z0-9]/', '_', $empresa['nombre']);

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $archivo = $_FILES['logo'];
            $tiposPermitidos = ['image/jpeg','image/png','image/gif','image/webp'];

            if (in_array($archivo['type'], $tiposPermitidos)) {
                $carpetaDestino = __DIR__ . '/images/logo_empresa/';
                if (!is_dir($carpetaDestino)) mkdir($carpetaDestino, 0755, true);

                $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
                $nombreArchivo = $nombre_empresa . '_' . uniqid() . '.' . $extension;
                $rutaDestino = $carpetaDestino . $nombreArchivo;
                $rutaRelativa = 'Configuracion/images/logo_empresa/' . $nombreArchivo;

                if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                    $stmt = $conn->prepare("UPDATE empresa SET foto_perfil = ? WHERE id = ?");
                    $stmt->bind_param("si", $rutaRelativa, $id_empresa);
                    if ($stmt->execute()) {
                        echo "✅ Logo subido correctamente!";
                    } else {
                        http_response_code(500);
                        echo "❌ Error al actualizar la ruta: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    http_response_code(500);
                    echo "❌ Error al mover el archivo.";
                }
            } else {
                http_response_code(400);
                echo "⚠️ Tipo de archivo no permitido. Solo JPG, PNG, GIF o WEBP.";
            }
        } else {
            http_response_code(400);
            echo "⚠️ No se seleccionó ningún archivo o hubo un error en la subida.";
        }
    } else {
        http_response_code(400);
        echo "⚠️ Debe registrar la empresa antes de subir el logo.";
    }

} else {
    http_response_code(405);
    echo "Método no permitido";
}
?>
