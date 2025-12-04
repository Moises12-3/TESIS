<?php
session_start();
header('Content-Type: application/json');
require_once "../Conexion/conex.php"; // $conn debe venir de aquí (mysqli)

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

// --- Comprobar credencial admin desde JSON ---
$credFile = __DIR__ . "../json/credencial.json"; // ajusta si la ruta es distinta
if (file_exists($credFile)) {
    $json = file_get_contents($credFile);
    $data = json_decode($json, true);
} else {
    $data = null;
}

$adminEmail = "admin@ventasphp.com";
$adminHash = null;
if (isset($data['usuarios']) && is_array($data['usuarios'])) {
    foreach ($data['usuarios'] as $u) {
        if (isset($u['email']) && $u['email'] === $adminEmail) {
            $adminHash = $u['password'];
            break;
        }
    }
}

// Si el usuario es el admin definido, validar contra el hash JSON
if (strtolower($email) === strtolower($adminEmail) && $adminHash !== null) {
    if (password_verify($password, $adminHash)) {
        // Sesión para admin y redirigir a backup.php
        $_SESSION["usuario"] = $adminEmail;
        // Si tienes un id en el JSON podrías setearlo, si no usaremos id = 0
        $_SESSION["id"] = 0;
        echo json_encode(["status"=>"success", "redirect"=>"backup.php"]);
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

// Si no es admin, validar contra la base de datos (tabla usuarios)
if ($conn === null) {
    echo json_encode(["status"=>"error","type"=>"danger","message"=>"⚠️ Error en la conexión a la base de datos."]);
    exit;
}

$stmt = $conn->prepare("SELECT id, email, password FROM usuarios WHERE email = ?");
if (!$stmt) {
    echo json_encode(["status"=>"error","type"=>"danger","message"=>"⚠️ Error en la consulta."]);
    exit;
}
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_email, $db_password);
    $stmt->fetch();

    if (password_verify($password, $db_password)) {
        $_SESSION["usuario"] = $db_email;
        $_SESSION["id"] = $id;
        // redirigir al index u otra página
        echo json_encode(["status"=>"success", "redirect"=>"index.php"]);
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
