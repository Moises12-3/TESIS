<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION["usuario"])) {
    header("Location: page-login.php");
    exit();
}

// Verificar que solo admin pueda acceder
if (strtolower($_SESSION["usuario"]) !== "admin@ventasphp.com") {
    header("Location: index.php");
    exit();
}

// === LEER CONFIGURACIÓN DESDE JSON ===
$configPath = __DIR__ . '/conexion/conexion.json';

if (!file_exists($configPath)) {
    die("❌ Error: No se encontró el archivo de configuración JSON.");
}

$config = json_decode(file_get_contents($configPath), true);

if ($config === null) {
    die("❌ Error: No se pudo leer el archivo JSON. Verifica su formato.");
}

$servername = $config['servername'];
$username   = $config['username'];
$password   = $config['password'];
$database   = $config['database'];
$port       = $config['port'];

$mensaje = "";

// ELIMINAR RESPALDO
if(isset($_GET['delete'])){
    $file_to_delete = "Backup/" . basename($_GET['delete']);
    if(file_exists($file_to_delete)){
        unlink($file_to_delete);
        $mensaje = "🗑️ Respaldo eliminado: " . basename($file_to_delete);
    } else {
        $mensaje = "❌ El archivo no existe.";
    }
}

// CREAR BASE DE DATOS DESDE ARCHIVOS SQL
if(isset($_POST['crear_bd'])){
    $conn = new mysqli($servername, $username, $password, "", $port);
    if($conn->connect_error){
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Crear base de datos
    $conn->query("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $conn->close();

    // Archivos SQL a ejecutar
    $sql_files = ["BaseDeDatos/ventas_php.sql", "BaseDeDatos/datos.sql"];

    foreach($sql_files as $sql_file){
        if(file_exists($sql_file)){
            // Usar mysql directamente con shell_exec
            $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < \"{$sql_file}\" 2>&1";
            
            $output = shell_exec($command);
            $return_var = 0;

            if($output !== null && trim($output) !== ''){
                $mensaje .= "⚠️ Advertencia al ejecutar " . basename($sql_file) . ": " . $output . "<br>";
            }
        } else {
            $mensaje .= "❌ Archivo no encontrado: " . basename($sql_file) . "<br>";
        }
    }

    if($mensaje == "" || strpos($mensaje, "Advertencia") !== false){
        $mensaje = "✅ Base de datos creada e inicializada desde archivos SQL.";
    }
}

// RESPALDO DE BASE DE DATOS
if(isset($_POST['backup'])){
    // Verificar si existe la carpeta Backup, si no crearla
    if (!is_dir('Backup')) {
        mkdir('Backup', 0777, true);
    }
    
    $backup_file = "Backup/backup_" . date("Y-m-d_H-i-s") . ".sql";
    
    // Usar la ruta correcta con comillas
    $command = "\"C:\\xampp\\mysql\\bin\\mysqldump\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} > \"{$backup_file}\" 2>&1";
    
    $output = shell_exec($command);
    
    if(file_exists($backup_file) && filesize($backup_file) > 0){
        $file_size = round(filesize($backup_file) / 1024, 2); // Tamaño en KB
        $mensaje = "✅ Respaldo creado exitosamente (" . $file_size . " KB): <a href='{$backup_file}' target='_blank'>⬇️ Descargar</a>";
    } else {
        $mensaje = "❌ Error al crear el respaldo.";
        if($output){
            $mensaje .= " Detalles: " . $output;
        }
    }
}

// RESTAURAR BASE DE DATOS DESDE ARCHIVO
if(isset($_POST['restore'])){
    $restore_file = $_FILES['restore_file']['tmp_name'];
    $original_name = $_FILES['restore_file']['name'];

    if($restore_file && pathinfo($original_name, PATHINFO_EXTENSION) === 'sql'){
        $conn = new mysqli($servername, $username, $password, "", $port);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Eliminar base de datos existente y crear nueva
        $conn->query("DROP DATABASE IF EXISTS {$database}");
        $conn->query("CREATE DATABASE {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->close();

        // Restaurar desde el archivo SQL subido
        $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < \"{$restore_file}\" 2>&1";
        
        $output = shell_exec($command);
        
        if($output !== null && trim($output) !== ''){
            $mensaje = "⚠️ La restauración se completó con advertencias: " . $output;
        } else {
            $mensaje = "✅ Restauración completada exitosamente desde: " . htmlspecialchars($original_name);
        }
    } else {
        $mensaje = "❌ Por favor selecciona un archivo SQL válido para restaurar.";
    }
}

// OBTENER TODAS LAS IMÁGENES DE LA CARPETA fondo/
$fondo_images = glob("fondo/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
$background_image = "";
if($fondo_images){
    $background_image = $fondo_images[array_rand($fondo_images)];
}

// Obtener listado de respaldos existentes
$files = glob("Backup/*.sql");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>💾 Respaldo y Restauración de Base de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?= $background_image ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-action {
            margin: 2px;
        }
    </style>
    <link rel="apple-touch-icon" href="images/favicon.png">
    <link rel="shortcut icon" href="images/favicon.png">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">💾 Respaldo y Restauración de Base de Datos</h4>
                <small>Base de datos: <strong><?= $database ?></strong> | Servidor: <strong><?= $servername ?></small></strong>
            </div>
            <div>
                <a href="cerrar_sesion_backup.php" class="btn btn-outline-light btn-sm">⬅️ Volver</a>
            </div>
        </div>

        <div class="card-body">
            <?php if($mensaje != ""): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $mensaje ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Panel de acciones rápidas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">🛠️ Recrear Base de Datos</h5>
                        </div>
                        <div class="card-body">
                            <p>Recrea la base de datos desde los archivos SQL originales:</p>
                            <form method="post">
                                <button type="submit" name="crear_bd" class="btn btn-info w-100" 
                                        onclick="return confirm('¿Estás seguro? Esto eliminará todos los datos actuales y recreará la BD desde cero.')">
                                    🛠️ Recrear BD desde archivos SQL
                                </button>
                            </form>
                            <small class="text-muted mt-2 d-block">
                                Archivos: ventas_php.sql + datos.sql
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">📦 Crear Respaldo</h5>
                        </div>
                        <div class="card-body">
                            <p>Crea un nuevo respaldo de la base de datos actual:</p>
                            <form method="post">
                                <button type="submit" name="backup" class="btn btn-success w-100">
                                    📦 Crear Nuevo Respaldo
                                </button>
                            </form>
                            <small class="text-muted mt-2 d-block">
                                Se guardará en: Backup/backup_fecha.sql
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de restauración -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">🔄 Restaurar Base de Datos</h5>
                </div>
                <div class="card-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="restore_file" class="form-label">📂 Selecciona archivo SQL para restaurar:</label>
                            <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".sql" required>
                            <div class="form-text">
                                ⚠️ <strong>Advertencia:</strong> Esta acción eliminará toda la base de datos actual y la reemplazará con los datos del archivo seleccionado.
                            </div>
                        </div>
                        <button type="submit" name="restore" class="btn btn-warning w-100" 
                                onclick="return confirm('¿Estás seguro de restaurar? Se perderán todos los datos actuales.')">
                            🔄 Restaurar Base de Datos
                        </button>
                    </form>
                </div>
            </div>

            <!-- Listado de respaldos existentes -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📄 Respaldos existentes:</h5>
                </div>
                <div class="card-body">
                    <?php if($files && count($files) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre del Archivo</th>
                                        <th>Tamaño</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($files as $file): 
                                        $file_name = basename($file);
                                        $file_size = round(filesize($file) / 1024, 2); // KB
                                        $file_date = date("Y-m-d H:i:s", filemtime($file));
                                    ?>
                                        <tr>
                                            <td><?= $file_name ?></td>
                                            <td><?= $file_size ?> KB</td>
                                            <td><?= $file_date ?></td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= $file ?>" class="btn btn-sm btn-primary btn-action" target="_blank" title="Descargar">
                                                        ⬇️ Descargar
                                                    </a>
                                                    <a href="?delete=<?= $file_name ?>" class="btn btn-sm btn-danger btn-action" 
                                                       onclick="return confirm('¿Eliminar el respaldo: <?= $file_name ?>?')" title="Eliminar">
                                                        🗑️ Eliminar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No hay respaldos disponibles. Crea tu primer respaldo usando el botón "📦 Crear Nuevo Respaldo".
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto cerrar alertas después de 5 segundos
setTimeout(function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        var bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);
</script>
</body>
</html>