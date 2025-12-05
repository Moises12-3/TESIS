<?php
session_start();
header('Content-Type: application/json');

// DEBUG: Mostrar error para ver qué pasa
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    // DEBUG: Ver qué usuario encontramos
    error_log("Usuario encontrado en JSON: " . $jsonUserData['email']);
    
    // DEBUG: Verificar si password_verify funciona
    $password_correct = password_verify($password, $jsonUserData['password']);
    error_log("Password correcto: " . ($password_correct ? "SÍ" : "NO"));
    
    if (password_verify($password, $jsonUserData['password'])) {
        $_SESSION["usuario"] = $jsonUserData['email'];
        $_SESSION["id"] = $jsonUserData['id'];
        
        // DEBUG: Ver qué email estamos comparando
        error_log("Email del usuario: " . $jsonUserData['email']);
        error_log("Comparando con admin@ventasphp.com: " . (strtolower($jsonUserData['email']) === "admin@ventasphp.com" ? "IGUAL" : "DIFERENTE"));
        
        // Redirección especial solo para admin (para backup.php)
        if (strtolower($jsonUserData['email']) === "admin@ventasphp.com") {
            error_log("Redirigiendo a backup.php");
            echo json_encode(["status"=>"success", "redirect"=>"./backup.php"]);
        } else {
            // Para todos los demás usuarios, redirigir al index principal
            error_log("Redirigiendo a backup.php");
            echo json_encode(["status"=>"success", "redirect"=>"./backup.php"]);
        }
        exit;
    } else {
        error_log("Password incorrecto para: " . $jsonUserData['email']);
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
                // Usuarios de BD van al index principal
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