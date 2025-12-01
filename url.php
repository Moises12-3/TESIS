<?php
session_start();

// Si no hay sesión → cerrar sesión
if (!isset($_SESSION["usuario"])) {
    header("Location: cerrar_sesion.php");
    exit();
}

$usuario = $_SESSION["usuario"];
$id_usuario = $_SESSION["id"] ?? null;

// =====================================================
// 1) VERIFICAR SI EXISTE CONEXIÓN A BASE DE DATOS
// =====================================================
$db_available = false;
$conn = null;

try {
    require 'Conexion/conex.php';
    if ($conn && !$conn->connect_error) {
        $db_available = true;
    }
} catch (Exception $e) {
    $db_available = false;
}

// =====================================================
// 2) SI NO HAY BD → VALIDAR JSON credencial.json
// =====================================================
if (!$db_available) {

    $jsonPath = __DIR__ . '/json/credencial.json';

    if (!file_exists($jsonPath)) {
        header("Location: cerrar_sesion.php");
        exit();
    }

    $json = json_decode(file_get_contents($jsonPath), true);

    if (!$json || !isset($json['usuarios'])) {
        header("Location: cerrar_sesion.php");
        exit();
    }

    $match = false;
    foreach ($json['usuarios'] as $u) {
        if (isset($u['email']) && strtolower($u['email']) === strtolower($usuario)) {
            $match = true;
            break;
        }
    }

    if (!$match) {
        header("Location: cerrar_sesion.php");
        exit();
    }

    // JSON válido → REDIRECCIÓN A backup.php
    header("Location: backup.php");
    exit();
}

// =====================================================
// 3) SI HAY BD → VALIDAR USUARIO Y PERMISOS
// =====================================================

// Obtener archivo actual
$current_file = basename($_SERVER['REQUEST_URI']);

// --- Validar que el usuario exista en la BD ---
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    header("Location: cerrar_sesion.php");
    exit();
}

$userDB = $res->fetch_assoc();
$id_usuario = $userDB['id'];

// =====================================================
// 4) Validar si la página existe en paginas_projectos
// =====================================================

$stmt = $conn->prepare("SELECT id FROM paginas_projectos WHERE pagina = ? LIMIT 1");
$stmt->bind_param("s", $current_file);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    // Página no registrada → denegar
    header("Location: index.php");
    exit();
}

$permiso_id = $res->fetch_assoc()['id'];

// =====================================================
// 5) Validar si el usuario TIENE PERMISO mediante permisos_usuario
// =====================================================

$stmt = $conn->prepare("
    SELECT id 
    FROM permisos_usuario 
    WHERE id_usuario = ? AND id_permiso = ? 
    LIMIT 1
");
$stmt->bind_param("ii", $id_usuario, $permiso_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    // Usuario no tiene permiso para esta página
    header("Location: cerrar_sesion.php");
    exit();
}

// -----------------------------------------------------
// SI LLEGÓ AQUÍ → SESIÓN Y PERMISOS CORRECTOS
// -----------------------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Archivo Actual</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="alert alert-primary" role="alert">
        <strong>Archivo Actual:</strong> <?php echo $current_file; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
