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
$fondo_images = glob("fondo/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
$background_image = "";
if($fondo_images){
    $background_image = $fondo_images[array_rand($fondo_images)];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💾 Respaldo y Restauración de Base de Datos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --card-border: 2px solid;
            --card-shadow: 0 5px 15px rgba(0,0,0,0.2);
            --transition: all 0.3s ease;
        }
        
        body {
            background-image: url('<?= $background_image ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: -1;
        }
        
        .container {
            max-width: 800px; /* REDUCIDO de 1200px a 800px */
            margin: 20px auto;
        }
        
        .main-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            border: var(--card-border) #0d6efd;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin: 0 auto;
            width: 100%;
        }
        
        .card-header {
            border-bottom: var(--card-border) #0d6efd;
            font-weight: bold;
            padding: 15px 20px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .section-card {
            border: var(--card-border);
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: var(--transition);
            margin-bottom: 15px;
            overflow: hidden;
        }
        
        .section-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        }
        
        .card-info {
            border-color: #0dcaf0;
        }
        
        .card-success {
            border-color: #198754;
        }
        
        .card-warning {
            border-color: #ffc107;
        }
        
        .card-secondary {
            border-color: #6c757d;
        }
        
        .section-card .card-header {
            padding: 10px 15px;
            font-size: 0.95rem;
        }
        
        .section-card .card-body {
            padding: 15px;
        }
        
        .btn-action {
            margin: 2px;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 5px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .progress {
            height: 12px;
            margin-top: 8px;
            border-radius: 6px;
            overflow: hidden;
            background-color: #e9ecef;
        }
        
        .progress-bar {
            border-radius: 6px;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            padding: 8px;
        }
        
        .table td {
            padding: 8px;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        
        .alert {
            border-radius: 8px;
            border: 1px solid transparent;
            margin-bottom: 10px;
            padding: 10px 15px;
            font-size: 0.9rem;
        }
        
        /* Animaciones */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
            70% { box-shadow: 0 0 0 6px rgba(13, 110, 253, 0); }
            100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
        }
        
        /* Fondo slideshow */
        .background-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            overflow: hidden;
        }
        
        .background-slideshow img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }
        
        .background-slideshow img.active {
            opacity: 1;
        }
        
        /* Estilos para pantallas pequeñas */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
                margin: 10px auto;
                max-width: 95%;
            }
            
            .main-card {
                border-radius: 12px;
            }
            
            .card-header h4 {
                font-size: 1rem;
            }
            
            .card-header h5 {
                font-size: 0.95rem;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
            
            .table-responsive {
                font-size: 0.8rem;
            }
        }
        
        @media (max-width: 576px) {
            body {
                padding: 10px 0;
            }
            
            .container {
                padding: 5px;
                max-width: 98%;
            }
            
            .main-card {
                border-radius: 10px;
            }
            
            .card-body {
                padding: 15px;
            }
            
            .card-header {
                padding: 12px 15px;
            }
            
            .section-card .card-body {
                padding: 12px;
            }
        }
        
        /* Mejoras visuales */
        .text-muted {
            font-size: 0.8rem;
        }
        
        .form-control {
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        .btn {
            border-radius: 6px;
            font-size: 0.9rem;
        }
        
        /* Modal responsive */
        .modal-dialog {
            max-width: 95%;
            margin: 10px auto;
        }
        
        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 400px;
            }
        }
        
        /* Ajustes específicos para el contenido */
        h4 {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        h5 {
            font-size: 1rem;
            margin-bottom: 0;
        }
        
        .card-text {
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        small {
            font-size: 0.8rem;
        }
        
        /* Mejorar visibilidad del fondo */
        .card {
            background-color: rgba(255, 255, 255, 0.92);
        }
        
        /* Ajustar espaciado */
        .row-cards {
            margin-bottom: 15px;
        }
        
        .mb-4 {
            margin-bottom: 1rem !important;
        }
    </style>
    <link rel="apple-touch-icon" href="images/favicon.png">
    <link rel="shortcut icon" href="images/favicon.png">
</head>
<body>
    <!-- Fondo slideshow -->
    <div class="background-slideshow" id="backgroundSlideshow">
        <?php foreach ($fondo_images as $index => $img): ?>
            <img src="<?= $img ?>" class="<?= $index === 0 ? 'active' : '' ?>" data-index="<?= $index ?>">
        <?php endforeach; ?>
    </div>

    <div class="container fade-in">
        <div class="main-card">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="text-center text-md-start mb-2 mb-md-0">
                    <h4 class="mb-1"><i class="fas fa-database me-2"></i>💾 Respaldo y Restauración</h4>
                    <small id="db-info" class="d-block">Cargando información...</small>
                </div>
                <div>
                    <a href="cerrar_sesion.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Volver
                    </a>
                </div>
            </div>

            <div class="card-body">
                <!-- Alertas dinámicas -->
                <div id="alert-container"></div>

                <!-- Panel de acciones rápidas -->
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="section-card card-info h-100">
                            <div class="card-header bg-info text-white d-flex align-items-center">
                                <i class="fas fa-tools me-2"></i>
                                <h5 class="mb-0">🛠️ Recrear BD</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-2">Recrea la base de datos desde archivos SQL:</p>
                                <button type="button" id="btn-recrear" class="btn btn-info w-100 pulse" 
                                        onclick="crearBaseDatos()">
                                    <i class="fas fa-hammer me-1"></i>🛠️ Recrear
                                </button>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-file-code me-1"></i>ventas_php.sql + datos.sql
                                </small>
                                <div class="progress d-none mt-2" id="progress-recrear">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" 
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="section-card card-success h-100">
                            <div class="card-header bg-success text-white d-flex align-items-center">
                                <i class="fas fa-box me-2"></i>
                                <h5 class="mb-0">📦 Crear Respaldo</h5>
                            </div>
                            <div class="card-body">
                                <p class="card-text mb-2">Crea un nuevo respaldo:</p>
                                <button type="button" id="btn-backup" class="btn btn-success w-100"
                                        onclick="crearRespaldo()">
                                    <i class="fas fa-save me-1"></i>📦 Crear
                                </button>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-folder me-1"></i>Backup/backup_fecha.sql
                                </small>
                                <div class="progress d-none mt-2" id="progress-backup">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de restauración -->
                <div class="section-card card-warning mb-3">
                    <div class="card-header bg-warning text-dark d-flex align-items-center">
                        <i class="fas fa-sync-alt me-2"></i>
                        <h5 class="mb-0">🔄 Restaurar BD</h5>
                    </div>
                    <div class="card-body">
                        <form id="form-restore" enctype="multipart/form-data">
                            <div class="mb-2">
                                <label for="restore_file" class="form-label">
                                    <i class="fas fa-file-upload me-1"></i>📂 Seleccionar archivo SQL:
                                </label>
                                <input type="file" class="form-control" id="restore_file" name="restore_file" accept=".sql" required>
                                <div class="form-text mt-1">
                                    <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                    <strong>Advertencia:</strong> Esta acción eliminará todos los datos actuales.
                                </div>
                            </div>
                            <button type="submit" id="btn-restore" class="btn btn-warning w-100">
                                <i class="fas fa-redo me-1"></i>🔄 Restaurar
                            </button>
                        </form>
                        <div class="progress d-none mt-2" id="progress-restore">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" 
                                 role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Listado de respaldos existentes -->
                <div class="section-card card-secondary">
                    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list me-2"></i>
                            <h5 class="mb-0">📄 Respaldos</h5>
                        </div>
                        <div>
                            <button class="btn btn-light btn-sm" onclick="cargarRespaldos()" title="Actualizar">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="respaldos-container">
                            <div class="text-center py-2">
                                <div class="spinner-border text-secondary" style="width: 1.5rem; height: 1.5rem;" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                                <p class="mt-2">Cargando respaldos...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light text-center text-muted py-2">
                <small><i class="fas fa-info-circle me-1"></i>Sistema de respaldo - <?= date('Y') ?></small>
            </div>
        </div>
    </div>

    <!-- Modal de notificación -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    // Variables globales
    let currentBgIndex = 0;
    let bgImages = [];
    
    $(document).ready(function() {
        // Inicializar slideshow de fondo
        initBackgroundSlideshow();
        
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
    
    function initBackgroundSlideshow() {
        bgImages = $('.background-slideshow img');
        if (bgImages.length > 1) {
            setInterval(() => {
                bgImages.removeClass('active');
                currentBgIndex = (currentBgIndex + 1) % bgImages.length;
                $(bgImages[currentBgIndex]).addClass('active');
            }, 5000);
        }
    }
        
    function cargarInfoBD() {
        // Configurar zona horaria para Nicaragua
        const ahora = new Date();
        const opciones = {
            timeZone: 'America/Managua',
            timeZoneName: 'short'
        };
        const zonaHoraria = new Intl.DateTimeFormat('es-NI', opciones).formatToParts(ahora)
            .find(part => part.type === 'timeZoneName').value;
        
        $.ajax({
            url: 'Configuracion/Conf_backup.php?action=info',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#db-info').html(
                        '<i class="fas fa-server me-1"></i>Base de datos: <strong>' + response.data.database + 
                        '</strong> | <i class="fas fa-network-wired me-1"></i>Servidor: ' + response.data.servername +
                        ' | <i class="fas fa-clock me-1"></i>Zona horaria: ' + zonaHoraria
                    );
                }
            },
            error: function() {
                $('#db-info').html('<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Error al cargar información</span>');
            }
        });
    }
    
    function crearBaseDatos() {
        showConfirmModal(
            'Confirmar recreación',
            '⚠️ <strong>¿Estás seguro?</strong><br><br>' +
            'Esta acción eliminará <span class="text-danger">TODOS LOS DATOS</span> y recreará la BD desde cero.<br><br>' +
            '<small class="text-muted">Esta operación no se puede deshacer.</small>',
            function() {
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
                            showNotification(
                                response.success ? 'success' : 'error',
                                response.success ? '✅ Completado' : '❌ Error',
                                response.message
                            );
                            
                            btn.prop('disabled', false).html('<i class="fas fa-hammer me-1"></i>🛠️ Recrear');
                            progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                            
                            if(response.success) {
                                setTimeout(cargarRespaldos, 1000);
                            }
                        }, 500);
                    },
                    error: function() {
                        showNotification('error', '❌ Error', 'No se pudo conectar con el servidor.');
                        btn.prop('disabled', false).html('<i class="fas fa-hammer me-1"></i>🛠️ Recrear');
                        progress.addClass('d-none');
                    }
                });
            }
        );
    }
        
    function crearRespaldo() {
        const btn = $('#btn-backup');
        const progress = $('#progress-backup');
        
        btn.prop('disabled', true).html('<span class="loading"></span> <i class="fas fa-save me-1"></i>Creando respaldo...');
        progress.removeClass('d-none').find('.progress-bar').css('width', '10%');
        
        // Mostrar hora actual de Nicaragua
        const ahora = new Date();
        const opciones = {
            timeZone: 'America/Managua',
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        const horaNicaragua = new Intl.DateTimeFormat('es-NI', opciones).format(ahora);
        
        $.ajax({
            url: 'Configuracion/Conf_backup.php?action=backup',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                progress.find('.progress-bar').css('width', '100%');
                
                setTimeout(function() {
                    // Agregar hora de creación al mensaje
                    let mensaje = response.message;
                    if (response.success) {
                        mensaje = '✅ Respaldo creado exitosamente ' + horaNicaragua + '<br>' + 
                                'Tamaño: ' + response.message.match(/\(([^)]+)\)/)[1] + '<br>' +
                                '<a href="' + response.message.match(/href="([^"]+)"/)[1] + '" target="_blank">⬇️ Descargar</a>';
                    }
                    
                    showNotification(
                        response.success ? 'success' : 'error',
                        response.success ? '✅ Respaldo creado' : '❌ Error',
                        mensaje
                    );
                    
                    btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>📦 Crear Respaldo');
                    progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                    
                    // Recargar lista de respaldos
                    if(response.success) {
                        setTimeout(cargarRespaldos, 1000);
                    }
                }, 500);
            },
            error: function() {
                showNotification('error', '❌ Error de conexión', 'No se pudo conectar con el servidor.');
                btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>📦 Crear Respaldo');
                progress.addClass('d-none');
            }
        });
    }
    
    function restaurarBaseDatos() {
        const fileInput = $('#restore_file')[0];
        const btn = $('#btn-restore');
        const progress = $('#progress-restore');
        
        if(!fileInput.files.length) {
            showNotification('warning', '⚠️ Archivo requerido', 'Selecciona un archivo SQL.');
            return;
        }
        
        const fileName = fileInput.files[0].name;
        
        showConfirmModal(
            'Confirmar restauración',
            '⚠️ <strong>¿Estás seguro?</strong><br><br>' +
            'Archivo: <strong>' + fileName + '</strong><br><br>' +
            'Esta acción <span class="text-danger">ELIMINARÁ TODOS LOS DATOS</span> actuales.<br><br>' +
            '<small class="text-muted">Esta operación no se puede deshacer.</small>',
            function() {
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
                            showNotification(
                                response.success ? 'success' : 'warning',
                                response.success ? '✅ Completado' : '⚠️ Advertencia',
                                response.message
                            );
                            
                            btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i>🔄 Restaurar');
                            progress.addClass('d-none').find('.progress-bar').css('width', '0%');
                            
                            $('#restore_file').val('');
                            
                            if(response.success) {
                                setTimeout(cargarRespaldos, 1000);
                            }
                        }, 500);
                    },
                    error: function() {
                        showNotification('error', '❌ Error', 'Error de conexión.');
                        btn.prop('disabled', false).html('<i class="fas fa-redo me-1"></i>🔄 Restaurar');
                        progress.addClass('d-none');
                    }
                });
            }
        );
    }
    
    function cargarRespaldos() {
        $('#respaldos-container').html(`
            <div class="text-center py-2">
                <div class="spinner-border text-secondary" style="width: 1.5rem; height: 1.5rem;" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando respaldos...</p>
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
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-file me-1"></i>Archivo</th>
                                        <th><i class="fas fa-weight-hanging me-1"></i>Tamaño</th>
                                        <th><i class="fas fa-calendar me-1"></i>Fecha</th>
                                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        
                        response.data.forEach(function(file) {
                            html += `
                                <tr id="file-${file.name.replace(/\./g, '_')}">
                                    <td><i class="fas fa-file-code text-primary me-1"></i>${file.name}</td>
                                    <td>${file.size}</td>
                                    <td>${file.date}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="${file.path}" class="btn btn-primary btn-action" target="_blank" title="Descargar">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button class="btn btn-danger btn-action" 
                                                    onclick="eliminarRespaldo('${file.name}')" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>`;
                        });
                        
                        html += `</tbody></table></div>`;
                    } else {
                        html = `<div class="alert alert-info text-center py-2">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <p class="mb-1">No hay respaldos</p>
                                    <small class="text-muted">Crea un respaldo con el botón "📦 Crear"</small>
                                </div>`;
                    }
                    
                    $('#respaldos-container').html(html);
                } else {
                    showNotification('error', '❌ Error', response.message);
                }
            },
            error: function() {
                showNotification('error', '❌ Error', 'Error al cargar.');
            }
        });
    }
    
    function eliminarRespaldo(fileName) {
        showConfirmModal(
            'Confirmar eliminación',
            '🗑️ <strong>¿Eliminar el respaldo?</strong><br><br>' +
            'Archivo: <strong>' + fileName + '</strong><br><br>' +
            'Esta acción no se puede deshacer.',
            function() {
                const safeFileName = fileName.replace(/\./g, '_');
                const row = $(`#file-${safeFileName}`);
                row.find('td:last').html('<span class="loading"></span>');
                
                $.ajax({
                    url: 'Configuracion/Conf_backup.php?action=delete&file=' + encodeURIComponent(fileName),
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            row.fadeOut(200, function() {
                                $(this).remove();
                                
                                if($('#respaldos-container tbody tr').length === 0) {
                                    $('#respaldos-container').html(`
                                        <div class="alert alert-info text-center py-2">
                                            <i class="fas fa-info-circle mb-2"></i>
                                            <p class="mb-1">No hay respaldos</p>
                                            <small class="text-muted">Crea un respaldo con el botón "📦 Crear"</small>
                                        </div>
                                    `);
                                }
                            });
                            
                            showNotification('success', '✅ Eliminado', response.message);
                        } else {
                            row.find('td:last').html(`
                                <div class="btn-group btn-group-sm">
                                    <a href="Backup/${fileName}" class="btn btn-primary btn-action" target="_blank" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button class="btn btn-danger btn-action" 
                                            onclick="eliminarRespaldo('${fileName}')" title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `);
                            showNotification('error', '❌ Error', response.message);
                        }
                    },
                    error: function() {
                        row.find('td:last').html(`
                            <div class="btn-group btn-group-sm">
                                <a href="Backup/${fileName}" class="btn btn-primary btn-action" target="_blank" title="Descargar">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="btn btn-danger btn-action" 
                                        onclick="eliminarRespaldo('${fileName}')" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `);
                        showNotification('error', '❌ Error', 'Error al eliminar.');
                    }
                });
            }
        );
    }
    
    function showNotification(type, title, message) {
        const modal = $('#notificationModal');
        const modalTitle = $('#modalTitle');
        const modalBody = $('#modalBody');
        
        let icon = '';
        let colorClass = '';
        
        switch(type) {
            case 'success':
                icon = '<i class="fas fa-check-circle text-success mb-2"></i>';
                colorClass = 'text-success';
                break;
            case 'error':
                icon = '<i class="fas fa-times-circle text-danger mb-2"></i>';
                colorClass = 'text-danger';
                break;
            case 'warning':
                icon = '<i class="fas fa-exclamation-triangle text-warning mb-2"></i>';
                colorClass = 'text-warning';
                break;
            default:
                icon = '<i class="fas fa-info-circle text-info mb-2"></i>';
                colorClass = 'text-info';
        }
        
        modalTitle.html('<span class="' + colorClass + '">' + title + '</span>');
        modalBody.html(`
            <div class="text-center">
                ${icon}
                <div>${message}</div>
            </div>
        `);
        
        const modalInstance = new bootstrap.Modal(modal[0]);
        modalInstance.show();
    }
    
    function showConfirmModal(title, message, callback) {
        const modalHtml = `
            <div class="modal fade" id="confirmModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>${title}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ${message}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </button>
                            <button type="button" class="btn btn-danger" id="confirmAction">
                                <i class="fas fa-check me-1"></i>Confirmar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#confirmModal').remove();
        $('body').append(modalHtml);
        
        const modal = new bootstrap.Modal($('#confirmModal')[0]);
        modal.show();
        
        $('#confirmAction').off('click').on('click', function() {
            modal.hide();
            callback();
        });
        
        $('#confirmModal').on('hidden.bs.modal', function() {
            $(this).remove();
        });
    }
    
    function showAlert(type, message) {
        const alertClass = {
            'success': 'alert-success',
            'danger': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        };
        
        const icon = {
            'success': '<i class="fas fa-check-circle me-2"></i>',
            'danger': '<i class="fas fa-times-circle me-2"></i>',
            'warning': '<i class="fas fa-exclamation-triangle me-2"></i>',
            'info': '<i class="fas fa-info-circle me-2"></i>'
        };
        
        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert ${alertClass[type]} alert-dismissible fade show" role="alert">
                ${icon[type]} ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        $('#alert-container').append(alertHtml);
        
        setTimeout(function() {
            $(`#${alertId}`).alert('close');
        }, 5000);
    }
    </script>
</body>
</html>