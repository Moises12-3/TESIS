<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $usuario = trim($_POST["usuario"]);
    $nombre = trim($_POST["nombre"]);
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT); // Encriptar la contraseña

    // Validar que los campos no estén vacíos
    if (empty($usuario) || empty($nombre) || empty($telefono) || empty($direccion) || empty($password)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Preparar la consulta SQL para evitar inyección SQL
    $sql = "INSERT INTO usuarios (usuario, nombre, telefono, direccion, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssss", $usuario, $nombre, $telefono, $direccion, $password);
        
        if ($stmt->execute()) {
            echo "Usuario registrado exitosamente.";
            header("Location: ../VerUsuario.php"); // Redirigir a la página principal
            exit();
        } else {
            echo "Error al guardar el usuario: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta.";
    }

    $conn->close();
} else {
    echo "Método de solicitud inválido.";
}
?>
