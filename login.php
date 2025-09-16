<?php
session_start();
header('Content-Type: application/json');
require_once "Conexion/conex.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare("SELECT id, email, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_email, $db_password);
        $stmt->fetch();

        if (password_verify($password, $db_password)) {
            $_SESSION["usuario"] = $db_email;
            $_SESSION["id"] = $id;
            echo json_encode(["status"=>"success"]);
        } else {
            echo json_encode([
                "status"=>"error",
                "type"=>"danger",
                "message"=>"❌ Usuario o contraseña incorrectos."
            ]);
        }
    } else {
        echo json_encode([
            "status"=>"error",
            "type"=>"warning",
            "message"=>"⚠️ Usuario no encontrado."
        ]);
    }
    $stmt->close();
    exit;
}

echo json_encode(["status"=>"error","type"=>"danger","message"=>"Solicitud no válida."]);
