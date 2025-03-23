<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $usuario = trim($_POST["usuario"]);
    $nombre = trim($_POST["nombre"]);
    $cedula = trim($_POST["cedula"]);
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);
    $descuento = isset($_POST["descuento"]) ? floatval($_POST["descuento"]) : 0;
    $rol = trim($_POST["rol"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT); // Encriptar la contraseña

    // Validar que los campos no estén vacíos
    if (empty($usuario) || empty($nombre) || empty($cedula) || empty($telefono) || empty($direccion) || empty($password) || empty($rol)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Validar que el descuento sea un número válido entre 0 y 100
    if ($descuento < 0 || $descuento > 100) {
        die("Error: El descuento debe estar entre 0 y 100.");
    }

    // Validar que el rol sea válido
    $roles_validos = ['admin', 'editor', 'usuario'];
    if (!in_array($rol, $roles_validos)) {
        die("Error: Rol no válido.");
    }

    // Preparar la consulta SQL para evitar inyección SQL
    $sql = "INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, descuento, rol, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssdss", $usuario, $nombre, $cedula, $telefono, $direccion, $descuento, $rol, $password);
        
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
