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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('<?= $background_image ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
        }
        .btn-action {
            margin: 2px;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .progress {
            height: 20px;
            margin-top: 10px;
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
                <small id="db-info">Cargando información...</small>
            </div>
            <div>
                <a href="cerrar_sesion_backup.php" class="btn btn-outline-light btn-sm">⬅️ Volver</a>
            </div>
        </div>

        <div class="card-body">
            <!-- Alertas dinámicas -->
            <div id="alert-container"></div>

            <!-- Panel de acciones rápidas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">🛠️ Recrear Base de Datos</h5>
                        </div>
                        <div class="card-body">
                            <p>Recrea la base de datos desde los archivos SQL originales:</p>
                            <button type="button" id="btn-recrear" class="btn btn-info w-100" 
                                    onclick="crearBaseDatos()">
                                🛠️ Recrear BD desde archivos SQL
                            </button>
                            <small class="text-muted mt-2 d-block">
                                Archivos: ventas_php.sql + datos.sql
                            </small>
                            <div class="progress d-none" id="progress-recrear">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
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
                            <button type="button" id="btn-backup" class="btn btn-success w-100"
                                    onclick="crearRespaldo()">
                                📦 Crear Nuevo Respaldo
                            </button>
                            <small class="text-muted mt-2 d-block">
                                Se guardará en: Backup/backup_fecha.sql
                            </small>
                            <div class="progress d-none" id="progress-backup">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                     role="progressbar" style="width: 0%"></div>
                            </div>
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
                    <form id="form-restore" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="restore_file" class="form-label">📂 Selecciona archivo SQL para restaurar:</label>
                            <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".sql" required>
                            <div class="form-text">
                                ⚠️ <strong>Advertencia:</strong> Esta acción eliminará toda la base de datos actual y la reemplazará con los datos del archivo seleccionado.
                            </div>
                        </div>
                        <button type="submit" id="btn-restore" class="btn btn-warning w-100">
                            🔄 Restaurar Base de Datos
                        </button>
                    </form>
                    <div class="progress d-none mt-3" id="progress-restore">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Listado de respaldos existentes -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">📄 Respaldos existentes: <button class="btn btn-sm btn-light float-end" onclick="cargarRespaldos()">🔄 Actualizar</button></h5>
                </div>
                <div class="card-body">
                    <div id="respaldos-container">
                        <div class="text-center">
                            <div class="spinner-border text-secondary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p>Cargando respaldos...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // Cargar información de la BD
    cargarInfoBD();
    
    // Cargar lista de respaldos
    cargarRespaldos();
    
    // Manejar envío del formulario de restauración
    $('#form-restore').on('submit', function(e) {
        e.preventDefault();
        restaurarBaseDatos();
    });
});

function cargarInfoBD() {
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=info',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                $('#db-info').html('Base de datos: <strong>' + response.data.database + 
                                 '</strong> | Servidor: ' + response.data.servername);
            }
        }
    });
}

function crearBaseDatos() {
    if(!confirm('¿Estás seguro? Esto eliminará todos los datos actuales y recreará la BD desde cero.')) {
        return;
    }
    
    const btn = $('#btn-recrear');
    const progress = $('#progress-recrear');
    
    btn.prop('disabled', true).html('<span class="loading"></span> Procesando...');
    progress.removeClass('d-none').find('.progress-bar').css('width', '10%');
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=crear_bd',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            progress.find('.progress-bar').css('width', '100%');
            
            setTimeout(function() {
                showAlert(response.success ? 'success' : 'danger', response.message);
                btn.prop('disabled', false).html('🛠️ Recrear BD desde archivos SQL');
                progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                
                // Recargar lista de respaldos después de recrear BD
                if(response.success) {
                    setTimeout(cargarRespaldos, 1000);
                }
            }, 500);
        },
        error: function() {
            showAlert('danger', '❌ Error en la conexión con el servidor.');
            btn.prop('disabled', false).html('🛠️ Recrear BD desde archivos SQL');
            progress.addClass('d-none');
        }
    });
}

function crearRespaldo() {
    const btn = $('#btn-backup');
    const progress = $('#progress-backup');
    
    btn.prop('disabled', true).html('<span class="loading"></span> Creando respaldo...');
    progress.removeClass('d-none').find('.progress-bar').css('width', '10%');
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=backup',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            progress.find('.progress-bar').css('width', '100%');
            
            setTimeout(function() {
                showAlert(response.success ? 'success' : 'danger', response.message);
                btn.prop('disabled', false).html('📦 Crear Nuevo Respaldo');
                progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                
                // Recargar lista de respaldos
                if(response.success) {
                    setTimeout(cargarRespaldos, 1000);
                }
            }, 500);
        },
        error: function() {
            showAlert('danger', '❌ Error en la conexión con el servidor.');
            btn.prop('disabled', false).html('📦 Crear Nuevo Respaldo');
            progress.addClass('d-none');
        }
    });
}

function restaurarBaseDatos() {
    const fileInput = $('#restore_file')[0];
    const btn = $('#btn-restore');
    const progress = $('#progress-restore');
    
    if(!fileInput.files.length) {
        showAlert('warning', '❌ Por favor selecciona un archivo SQL.');
        return;
    }
    
    if(!confirm('¿Estás seguro de restaurar? Se perderán todos los datos actuales.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('restore_file', fileInput.files[0]);
    
    btn.prop('disabled', true).html('<span class="loading"></span> Restaurando...');
    progress.removeClass('d-none').find('.progress-bar').css('width', '10%');
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=restore',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            progress.find('.progress-bar').css('width', '100%');
            
            setTimeout(function() {
                showAlert(response.success ? 'success' : 'warning', response.message);
                btn.prop('disabled', false).html('🔄 Restaurar Base de Datos');
                progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                
                // Limpiar formulario
                $('#restore_file').val('');
                
                // Recargar lista de respaldos
                if(response.success) {
                    setTimeout(cargarRespaldos, 1000);
                }
            }, 500);
        },
        error: function() {
            showAlert('danger', '❌ Error en la conexión con el servidor.');
            btn.prop('disabled', false).html('🔄 Restaurar Base de Datos');
            progress.addClass('d-none');
        }
    });
}

function cargarRespaldos() {
    $('#respaldos-container').html(`
        <div class="text-center">
            <div class="spinner-border text-secondary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p>Cargando respaldos...</p>
        </div>
    `);
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=listar',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                let html = '';
                
                if(response.data.length > 0) {
                    html = `
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
                            <tbody>`;
                    
                    response.data.forEach(function(file) {
                        html += `
                            <tr id="file-${file.name}">
                                <td>${file.name}</td>
                                <td>${file.size}</td>
                                <td>${file.date}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="${file.path}" class="btn btn-sm btn-primary btn-action" target="_blank" title="Descargar">
                                            ⬇️ Descargar
                                        </a>
                                        <button class="btn btn-sm btn-danger btn-action" 
                                                onclick="eliminarRespaldo('${file.name}')" title="Eliminar">
                                            🗑️ Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                    });
                    
                    html += `</tbody></table></div>`;
                } else {
                    html = `<div class="alert alert-warning">
                                No hay respaldos disponibles. Crea tu primer respaldo usando el botón "📦 Crear Nuevo Respaldo".
                            </div>`;
                }
                
                $('#respaldos-container').html(html);
            } else {
                showAlert('danger', response.message);
            }
        },
        error: function() {
            showAlert('danger', '❌ Error al cargar los respaldos.');
        }
    });
}

function verificarRutas() {
    console.log('Verificando rutas...');
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=listar',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            
            if(response.success) {
                console.log('Archivos encontrados:', response.data);
                
                // Verificar si la carpeta Backup existe
                $.ajax({
                    url: 'Backup/',
                    type: 'HEAD',
                    error: function() {
                        console.warn('La carpeta Backup/ no existe o no es accesible');
                        showAlert('warning', '⚠️ La carpeta Backup/ no existe. Se creará automáticamente al crear el primer respaldo.');
                    },
                    success: function() {
                        console.log('Carpeta Backup/ existe');
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al verificar rutas:', error);
        }
    });
}

// Llamar a verificarRutas al cargar la página
$(document).ready(function() {
    // ... código existente ...
    
    // Verificar rutas (opcional, para depuración)
    // verificarRutas();
});

function eliminarRespaldo(fileName) {
    if(!confirm(`¿Eliminar el respaldo: ${fileName}?`)) {
        return;
    }
    
    // Mostrar indicador de carga
    const row = $(`#file-${fileName.replace(/\./g, '\\.')}`);
    row.find('td:last').html('<span class="loading"></span> Eliminando...');
    
    $.ajax({
        url: 'Configuracion/Conf_backup.php?action=delete&file=' + encodeURIComponent(fileName),
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                // Remover la fila de la tabla con animación
                row.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Si no quedan archivos, mostrar mensaje
                    if($('#respaldos-container tbody tr').length === 0) {
                        $('#respaldos-container').html(`
                            <div class="alert alert-warning">
                                No hay respaldos disponibles. Crea tu primer respaldo usando el botón "📦 Crear Nuevo Respaldo".
                            </div>
                        `);
                    }
                });
                
                showAlert('success', response.message);
            } else {
                // Restaurar botones si falló
                row.find('td:last').html(`
                    <div class="btn-group">
                        <a href="Backup/${fileName}" class="btn btn-sm btn-primary btn-action" target="_blank" title="Descargar">
                            ⬇️ Descargar
                        </a>
                        <button class="btn btn-sm btn-danger btn-action" 
                                onclick="eliminarRespaldo('${fileName}')" title="Eliminar">
                            🗑️ Eliminar
                        </button>
                    </div>
                `);
                showAlert('danger', response.message);
            }
        },
        error: function() {
            // Restaurar botones si hubo error
            row.find('td:last').html(`
                <div class="btn-group">
                    <a href="Backup/${fileName}" class="btn btn-sm btn-primary btn-action" target="_blank" title="Descargar">
                        ⬇️ Descargar
                    </a>
                    <button class="btn btn-sm btn-danger btn-action" 
                            onclick="eliminarRespaldo('${fileName}')" title="Eliminar">
                        🗑️ Eliminar
                    </button>
                </div>
            `);
            showAlert('danger', '❌ Error al eliminar el respaldo.');
        }
    });
}

function showAlert(type, message) {
    const alertClass = {
        'success': 'alert-success',
        'danger': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const alertId = 'alert-' + Date.now();
    const alertHtml = `
        <div id="${alertId}" class="alert ${alertClass[type]} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    $('#alert-container').append(alertHtml);
    
    // Auto cerrar después de 5 segundos
    setTimeout(function() {
        $(`#${alertId}`).alert('close');
    }, 5000);
}
</script>
</body>
</html>