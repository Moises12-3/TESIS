<?php
session_start(); // Inicia la sesión

// Incluir la conexión
require_once "Conexion/conex.php";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["email"];
    $password = $_POST["password"];

    // Preparar consulta segura
    $stmt = $conn->prepare("SELECT id, usuario, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_usuario, $db_password);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $db_password)) {
            $_SESSION["usuario"] = $db_usuario;
            $_SESSION["id"] = $id;
            header("Location: index.php"); // Redirigir si es correcto
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
        }
    } else {
        $error = "Usuario no encontrado";
    }

    $stmt->close();
}
?>
