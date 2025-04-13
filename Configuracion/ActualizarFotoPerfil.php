<?php
require_once "../Conexion/conex.php";  // Ajusta la ruta según sea necesario

// Verificar que se haya enviado un archivo
if ($_FILES['fotoPerfil']['error'] === UPLOAD_ERR_OK) {
    // Ruta donde se guardarán las imágenes de perfil
    $rutaGuardar = 'images/perfiles/';

    // Nombre del archivo y ruta completa
    $nombreArchivo = $_FILES['fotoPerfil']['name'];
    $rutaCompleta = $rutaGuardar . $nombreArchivo;

    // Mover archivo temporal a la ubicación deseada
    if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaCompleta)) {
        // Conexión a la base de datos (debes tener tu propia configuración de conexión)
        $conexion = new mysqli('localhost', 'usuario', 'contraseña', 'basedatos');

        // Verificar la conexión
        if ($conexion->connect_error) {
            die("Conexión fallida: " . $conexion->connect_error);
        }

        // Obtener el ID del usuario actual (debes manejar la sesión o el contexto adecuado)
        $idUsuario = 1; // Ejemplo, deberías obtenerlo según tu lógica de aplicación

        // Actualizar la ruta de la foto de perfil en la base de datos
        $query = "UPDATE usuarios SET foto_perfil = '$rutaCompleta' WHERE id = $idUsuario";

        if ($conexion->query($query) === TRUE) {
            echo "Foto de perfil actualizada correctamente.";
        } else {
            echo "Error al actualizar la foto de perfil: " . $conexion->error;
        }

        // Cerrar conexión
        $conexion->close();
    } else {
        echo "Error al mover el archivo.";
    }
} else {
    echo "Error al subir archivo: " . $_FILES['fotoPerfil']['error'];
}
?>
