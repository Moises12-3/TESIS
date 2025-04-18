<?php
session_start(); // Iniciar sesión si no está iniciada
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

// Verificamos si el archivo fue enviado
if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] == 0) {
    $directorioDestino = '../images/perfiles/';

    // Asegurarse de que el directorio exista
    if (!file_exists($directorioDestino)) {
        mkdir($directorioDestino, 0777, true); // Crear la carpeta si no existe
    }

    // Nombre único para evitar sobreescritura
    $nombreArchivo = uniqid('perfil_') . '_' . basename($_FILES['fotoPerfil']['name']);
    $rutaRelativa = 'images/perfiles/' . $nombreArchivo; // Esta es la ruta que se guardará en la BD
    $rutaCompleta = $directorioDestino . $nombreArchivo;  // Ruta para guardar en el servidor

    // Mover el archivo
    if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $rutaCompleta)) {
        // Conexión a la base de datos
        include '../Conexion/conex.php';

        // ID del usuario (por sesión, puedes ajustarlo si lo necesitas)
        $idUsuario = $_SESSION['id_usuario'];

        // Consulta para actualizar la ruta de la imagen en la BD
        $stmt = $conn->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->bind_param("si", $rutaRelativa, $idUsuario);

        if ($stmt->execute()) {
            echo "Imagen subida y ruta guardada correctamente.";
        } else {
            echo "Error al guardar la ruta en la base de datos.";
        }

        $stmt->close();
        $conn->close();

    } else {
        echo "Error al mover la imagen al servidor.";
    }
} else {
    echo "No se envió ninguna imagen o hubo un error en la subida.";
}
?>
