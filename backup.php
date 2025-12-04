<?php
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
            // Redirigir salida y errores a nul para que no se muestre en pantalla
            $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < {$sql_file} > nul 2>&1";
            // $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < {$sql_file} > nul 2>&1";
            
            system($command, $return_var);

            if($return_var !== 0){
                $mensaje .= "❌ Error al ejecutar el archivo: " . basename($sql_file) . "<br>";
            }
        } else {
            $mensaje .= "❌ Archivo no encontrado: " . basename($sql_file) . "<br>";
        }
    }

    $mensaje = "✅ Base de datos creada e inicializada desde archivos SQL.";
}

// RESPALDO DE BASE DE DATOS
if(isset($_POST['backup'])){
    $backup_file = "Backup/backup_" . date("Y-m-d_H-i-s") . ".sql";

    $command = "\"C:\\xampp\\mysql\\bin\\mysqldump\" --user={$username} --password={$password} --host={$servername} --port={$port} {$database} > {$backup_file}";
    system($command, $output);

    if(file_exists($backup_file)){
        $mensaje = "✅ Respaldo creado exitosamente: <a href='{$backup_file}' target='_blank'>Ver respaldo</a>";
    } else {
        $mensaje = "❌ Error al crear el respaldo.";
    }
}

// RESTAURAR BASE DE DATOS
if(isset($_POST['restore'])){
    $restore_file = $_FILES['restore_file']['tmp_name'];

    if($restore_file){
        $conn = new mysqli($servername, $username, $password, "", $port);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $conn->query("CREATE DATABASE IF NOT EXISTS {$database} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $conn->close();

        $command = "\"C:\\xampp\\mysql\\bin\\mysql\" --default-character-set=utf8mb4 --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < {$sql_file} > nul 2>&1";
        //$command = "\"C:\\xampp\\mysql\\bin\\mysql\" --user={$username} --password={$password} --host={$servername} --port={$port} {$database} < {$restore_file}";
        system($command, $output);

        $mensaje = "🔄 Restauración completada.";
    } else {
        $mensaje = "❌ Por favor selecciona un archivo SQL para restaurar.";
    }
}

// OBTENER TODAS LAS IMÁGENES DE LA CARPETA fondo/
$fondo_images = glob("fondo/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
$background_image = "";
if($fondo_images){
    $background_image = $fondo_images[array_rand($fondo_images)];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>💾 Respaldo y Restauración de Base de Datos</title>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?= $background_image ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .list-group-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    
<link rel="apple-touch-icon" href="images/favicon.png">
<link rel="shortcut icon" href="images/favicon.png">

</head>
<body>
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            💾 Respaldo y Restauración de Base de Datos
            <a href="cerrar_sesion_backup.php" class="text-white fw-bold" style="text-decoration:none;">⬅️ Volver</a>
        </div>

        <div class="card-body">
            <?php if($mensaje != ""): ?>
                <div class="alert alert-info"><?= $mensaje ?></div>
            <?php endif; ?>

            <!-- Crear Base de Datos desde archivos SQL -->
            <form method="post" class="mb-4">
                <button type="submit" name="crear_bd" class="btn btn-info">🛠️ Crear Base de Datos desde SQL</button>
            </form>

            <!-- Formulario de respaldo -->
            <form method="post" class="mb-4">
                <button type="submit" name="backup" class="btn btn-success">📦 Crear Respaldo</button>
            </form>

            <!-- Formulario de restauración -->
            <form method="post" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label for="restore_file" class="form-label">📂 Selecciona archivo SQL para restaurar:</label>
                    <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".sql" required>
                </div>
                <button type="submit" name="restore" class="btn btn-warning">🔄 Restaurar Base de Datos</button>
            </form>

            <!-- Listado de respaldos existentes -->
            <hr>
            <h5>📄 Respaldos existentes:</h5>
            <ul class="list-group">
                <?php
                $files = glob("Backup/*.sql");
                if($files){
                    foreach($files as $file){
                        echo '<li class="list-group-item">';
                        echo basename($file);
                        echo '<div>';
                        echo '<a href="'.$file.'" class="btn btn-sm btn-primary me-2" target="_blank">⬇️ Descargar</a>';
                        echo '<a href="?delete='.basename($file).'" class="btn btn-sm btn-danger" onclick="return confirm(\'¿Eliminar este respaldo?\')">🗑️ Eliminar</a>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">No hay respaldos disponibles.</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
</body>
</html>
