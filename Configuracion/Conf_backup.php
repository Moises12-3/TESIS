<?php
session_start();
header('Content-Type: application/json');

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION["usuario"]) || strtolower($_SESSION["usuario"]) !== "admin@ventasphp.com") {
    echo json_encode([
        'success' => false,
        'message' => '❌ Acceso denegado. Solo administradores pueden realizar esta acción.'
    ]);
    exit;
}

// === LEER CONFIGURACIÓN DESDE JSON ===
$configPath = __DIR__ . '/../conexion/conexion.json';

if (!file_exists($configPath)) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Error: No se encontró el archivo de configuración JSON.'
    ]);
    exit;
}

$config = json_decode(file_get_contents($configPath), true);

if ($config === null) {
    echo json_encode([
        'success' => false,
        'message' => '❌ Error: No se pudo leer el archivo JSON. Verifica su formato.'
    ]);
    exit;
}

$servername = $config['servername'];
$username   = $config['username'];
$password   = $config['password'];
$database   = $config['database'];
$port       = $config['port'];

// Directorios base
$base_dir = dirname(__DIR__); // Sube un nivel desde Configuracion/
$backup_dir = $base_dir . '/Backup';
$basedatos_dir = $base_dir . '/BaseDeDatos';

// Obtener acción
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'info':
        infoBD();
        break;
    case 'crear_bd':
        crearBaseDatos();
        break;
    case 'backup':
        crearRespaldo();
        break;
    case 'restore':
        restaurarBaseDatos();
        break;
    case 'listar':
        listarRespaldos();
        break;
    case 'delete':
        eliminarRespaldo();
        break;
    default:
        echo json_encode([
            'success' => false,
            'message' => '❌ Acción no válida.'
        ]);
}

function infoBD() {
    global $servername, $database;
    
    echo json_encode([
        'success' => true,
        'data' => [
            'servername' => $servername,
            'database' => $database
        ]
    ]);
}

function crearBaseDatos() {
    global $servername, $username, $password, $database, $port, $basedatos_dir;
    
    $conn = new mysqli($servername, $username, $password, "", $port);
    if($conn->connect_error){
        echo json_encode([
            'success' => false,
            'message' => '❌ Conexión fallida: ' . $conn->connect_error
        ]);
        exit;
    }

    // Crear base de datos
    $conn->query("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->close();

    // Archivos SQL a ejecutar
    $sql_files = [
        $basedatos_dir . "/ventas_php.sql", 
        $basedatos_dir . "/datos.sql"
    ];
    
    $errores = [];

    foreach($sql_files as $sql_file){
        if(file_exists($sql_file)){
            $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < \"{$sql_file}\" 2>&1";
            
            $output = shell_exec($command);

            if($output !== null && trim($output) !== ''){
                $errores[] = "Advertencia en " . basename($sql_file) . ": " . $output;
            }
        } else {
            $errores[] = "Archivo no encontrado: " . $sql_file;
        }
    }

    if(count($errores) > 0) {
        echo json_encode([
            'success' => true,
            'message' => '✅ Base de datos creada con algunas advertencias.<br>' . implode('<br>', $errores)
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => '✅ Base de datos creada e inicializada desde archivos SQL.'
        ]);
    }
}

function crearRespaldo() {
    global $servername, $username, $password, $database, $port, $backup_dir;
    
    // Verificar si existe la carpeta Backup, si no crearla
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir, 0777, true);
    }
    
    $backup_file = $backup_dir . "/backup_" . date("Y-m-d_H-i-s") . ".sql";
    
    $command = "\"C:\\xampp\\mysql\\bin\\mysqldump\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} > \"{$backup_file}\" 2>&1";
    
    $output = shell_exec($command);
    
    if(file_exists($backup_file) && filesize($backup_file) > 0){
        $file_size = round(filesize($backup_file) / 1024, 2); // KB
        $filename = basename($backup_file);
        
        echo json_encode([
            'success' => true,
            'message' => '✅ Respaldo creado exitosamente (' . $file_size . ' KB): <a href="Backup/' . $filename . '" target="_blank">⬇️ Descargar</a>',
            'filename' => $filename
        ]);
    } else {
        $error_msg = '❌ Error al crear el respaldo.';
        if($output){
            $error_msg .= ' Detalles: ' . $output;
        }
        echo json_encode([
            'success' => false,
            'message' => $error_msg
        ]);
    }
}

function restaurarBaseDatos() {
    global $servername, $username, $password, $database, $port;
    
    $restore_file = $_FILES['restore_file']['tmp_name'];
    $original_name = $_FILES['restore_file']['name'];

    if($restore_file && pathinfo($original_name, PATHINFO_EXTENSION) === 'sql'){
        $conn = new mysqli($servername, $username, $password, "", $port);
        if ($conn->connect_error) {
            echo json_encode([
                'success' => false,
                'message' => '❌ Conexión fallida: ' . $conn->connect_error
            ]);
            exit;
        }

        // Eliminar base de datos existente y crear nueva
        $conn->query("DROP DATABASE IF EXISTS {$database}");
        $conn->query("CREATE DATABASE {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->close();

        // Restaurar desde el archivo SQL subido
        $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < \"{$restore_file}\" 2>&1";
        
        $output = shell_exec($command);
        
        if($output !== null && trim($output) !== ''){
            echo json_encode([
                'success' => true,
                'message' => '⚠️ Restauración completada con advertencias: ' . $output
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => '✅ Restauración completada exitosamente desde: ' . htmlspecialchars($original_name)
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ Por favor selecciona un archivo SQL válido para restaurar.'
        ]);
    }
}

function listarRespaldos() {
    global $backup_dir;
    
    // Verificar si la carpeta existe
    if (!is_dir($backup_dir)) {
        echo json_encode([
            'success' => true,
            'data' => []
        ]);
        return;
    }
    
    $files = glob($backup_dir . "/*.sql");
    $respaldos = [];
    
    if($files){
        foreach($files as $file){
            $respaldos[] = [
                'name' => basename($file),
                'size' => round(filesize($file) / 1024, 2) . ' KB',
                'date' => date("Y-m-d H:i:s", filemtime($file)),
                'path' => 'Backup/' . basename($file) // Ruta relativa desde backup.php
            ];
        }
    }
    
    // Ordenar por fecha (más reciente primero)
    usort($respaldos, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    echo json_encode([
        'success' => true,
        'data' => $respaldos
    ]);
}

function eliminarRespaldo() {
    global $backup_dir;
    
    $file_name = $_GET['file'] ?? '';
    
    if(empty($file_name)) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Nombre de archivo no especificado.'
        ]);
        exit;
    }
    
    // Prevenir path traversal - solo permitir nombres de archivo seguros
    $file_name = basename($file_name);
    
    // Verificar que sea un archivo .sql
    if (pathinfo($file_name, PATHINFO_EXTENSION) !== 'sql') {
        echo json_encode([
            'success' => false,
            'message' => '❌ Tipo de archivo no permitido.'
        ]);
        exit;
    }
    
    $file_to_delete = $backup_dir . "/" . $file_name;
    
    if(file_exists($file_to_delete)){
        if(unlink($file_to_delete)){
            echo json_encode([
                'success' => true,
                'message' => '🗑️ Respaldo eliminado: ' . $file_name
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => '❌ No se pudo eliminar el archivo. Verifica los permisos.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => '❌ El archivo no existe: ' . $file_name
        ]);
    }
}
?>