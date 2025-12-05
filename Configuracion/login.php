<?php
session_start();
header('Content-Type: application/json');

// Primero intentamos la conexión pero manejamos el error
try {
    require_once "../Conexion/conex.php";
    $conn_available = ($conn !== null && $conn->ping());
} catch (Exception $e) {
    $conn_available = false;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status"=>"error","type"=>"danger","message"=>"Solicitud no válida."]);
    exit;
}

$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($email === '') {
    echo json_encode(["status"=>"error","type"=>"warning","message"=>"⚠️ Ingrese un correo."]);
    exit;
}

// --- Comprobar credenciales desde JSON primero ---
$credFile = __DIR__ . "/../json/credencial.json";
$userFoundInJson = false;
$jsonUserData = null;

if (file_exists($credFile)) {
    $json = file_get_contents($credFile);
    $data = json_decode($json, true);
    
    if (isset($data['usuarios']) && is_array($data['usuarios'])) {
        foreach ($data['usuarios'] as $u) {
            if (isset($u['email']) && strtolower($u['email']) === strtolower($email)) {
                $userFoundInJson = true;
                $jsonUserData = $u;
                break;
            }
        }
    }
}

// Si el usuario está en el JSON, validar contraseña
if ($userFoundInJson) {
    if (password_verify($password, $jsonUserData['password'])) {
        $_SESSION["usuario"] = $jsonUserData['email'];
        $_SESSION["id"] = $jsonUserData['id'];
        
        // Redirección especial para admin
        if (strtolower($jsonUserData['email']) === "admin@ventasphp.com") {
            echo json_encode(["status"=>"success", "redirect"=>"./backup.php"]);
        } else {
            echo json_encode(["status"=>"success", "redirect"=>"./index.php"]);
        }
        exit;
    } else {
        echo json_encode([
            "status"=>"error",
            "type"=>"danger",
            "message"=>"❌ Usuario o contraseña incorrectos."
        ]);
        exit;
    }
}

// Si no está en JSON y hay conexión a la BD, buscar en la base de datos
if ($conn_available) {
    $stmt = $conn->prepare("SELECT id, email, password FROM usuarios WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $db_email, $db_password);
            $stmt->fetch();
            
            if (password_verify($password, $db_password)) {
                $_SESSION["usuario"] = $db_email;
                $_SESSION["id"] = $id;
                echo json_encode(["status"=>"success", "redirect"=>"./index.php"]);
                $stmt->close();
                exit;
            } else {
                $stmt->close();
                echo json_encode([
                    "status"=>"error",
                    "type"=>"danger",
                    "message"=>"❌ Usuario o contraseña incorrectos."
                ]);
                exit;
            }
        }
        $stmt->close();
    }
    
    // Si llegamos aquí, el usuario no está en la BD
    echo json_encode([
        "status"=>"error",
        "type"=>"warning",
        "message"=>"⚠️ Usuario no encontrado."
    ]);
    exit;
} else {
    // Si no hay conexión a la BD y no está en JSON
    echo json_encode([
        "status"=>"error",
        "type"=>"danger",
        "message"=>"⚠️ No se puede conectar a la base de datos. Usuario no encontrado en el sistema."
    ]);
    exit;
}
?>