<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION["usuario"])) {
    header("Location: page-login.php");
    exit();
}

// Obtener dato de la sesión (se asume que aquí está el email)
$usuario = $_SESSION["usuario"];
$id_usuario = isset($_SESSION["id"]) ? $_SESSION["id"] : null;

// Ruta del JSON
$jsonPath = __DIR__ . '/json/credencial.json';

// Comprobar existencia del archivo JSON
if (!file_exists($jsonPath)) {
    // Si no existe el JSON, por seguridad podrías forzar cierre o permitir acceso.
//    header("Location: cerrar_sesion.php");
//    exit();
    // Por ahora permitimos continuar (o ajusta según tu necesidad)
} else {
    $jsonContent = file_get_contents($jsonPath);
    $data = json_decode($jsonContent, true);

    if ($data === null) {
        // JSON mal formado: mejor cerrar sesión por seguridad
        header("Location: cerrar_sesion.php");
        exit();
    }

    $match = false;
    if (isset($data['usuarios']) && is_array($data['usuarios'])) {
        foreach ($data['usuarios'] as $u) {
            // Normalizamos ambos a minúsculas por si acaso
            if (isset($u['email']) && mb_strtolower($u['email']) === mb_strtolower($usuario)) {
                $match = true;
                break;
            }
        }
    }

    // === COMPORTAMIENTO SOLICITADO ===
    // Si se encontró algún usuario con el mismo email que la sesión -> redirigir a cerrar_sesion.php
    if ($match) {
        header("Location: cerrar_sesion.php");
        exit();
    }

    /* 
    // === OPCIÓN ALTERNATIVA (MAS COMÚN) ===
    // Si quieres en cambio cerrar sesión cuando NO se encuentre el email en el JSON,
    // reemplaza la condición anterior por:
    if (!$match) {
        header("Location: cerrar_sesion.php");
        exit();
    }
    */
}

// Si llega aquí, la sesión es válida según la lógica actual y el script continúa...
?>


<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Configura Empresas</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="images/favicon.png">
    <link rel="shortcut icon" href="images/favicon.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

   <style>
    #weatherWidget .currentDesc {
        color: #ffffff!important;
    }
        .traffic-chart {
            min-height: 335px;
        }
        #flotPie1  {
            height: 150px;
        }
        #flotPie1 td {
            padding:3px;
        }
        #flotPie1 table {
            top: 20px!important;
            right: -10px!important;
        }
        .chart-container {
            display: table;
            min-width: 270px ;
            text-align: left;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        #flotLine5  {
             height: 105px;
        }

        #flotBarChart {
            height: 150px;
        }
        #cellPaiChart{
            height: 160px;
        }

    </style>
</head>

<body>
    <!-- Left Panel -->
    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="index.php"><i class="menu-icon fa fa-home"></i>Inicio</a>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cube"></i>Productos
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-cube"></i><a href="VerProductos.php">Ver Productos</a></li>
                        <li><i class="fa fa-plus-circle"></i><a href="AgregarProductos.php">Agregar Productos</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-users"></i>Usuarios
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-users"></i><a href="VerUsuario.php">Ver Usuarios</a></li>
                        <li><i class="menu-icon fa fa-user-plus"></i><a href="AgregarUsuario.php">Agregar Usuario</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-users"></i>Clientes
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-address-book"></i><a href="VerClientes.php">Ver Clientes</a></li>
                        <li><i class="menu-icon fa fa-user-plus"></i><a href="AgregarClientes.php">Nuevo Cliente</a></li>
                    </ul>
                </li>
                <li>
                    <a href="Ventas.php"><i class="menu-icon fa fa-shopping-cart"></i>Vender</a>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-pie-chart"></i>Reportes de ventas
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-map"></i><a href="VerReportes.php">Visualizar Reportes</a></li>                    
                        <li><i class="menu-icon fa fa-file-invoice"></i><a href="ver_facturas.php">Ver facturas</a></li>                
                        <li><i class="menu-icon fa fa-clock"></i><a href="verfechavencimiento.php">Ver Fecha Vencimiento</a></li>
                    </ul>
                </li>
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-cogs"></i>Configuración
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="fa fa-money"></i><a href="AjusteMoneda.php">Moneda</a></li>
                        <li><i class="fa fa-credit-card"></i><a href="AjustesTipoPago.php">Tipo Pago</a></li>
                        <li><i class="fa fa-balance-scale"></i><a href="AjusteUnidad.php">Unidad de peso</a></li>
                        <li><i class="fa fa-calculator"></i><a href="AjustesImpuestos.php">Impuestos</a></li>
                        <li><i class="fa fa-building"></i><a href="ConfigurarEmpresas.php">Configurar Empresas</a></li>
                    </ul>
                </li>
            </ul>

            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>
    <!-- /#left-panel -->
    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header">
                    <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
                <div class="header-menu">
                    <div class="header-left">


                        <div class="dropdown for-message">
                            <a class="nav-link" href="#" onclick="toggleFullscreen()">
                                    <i class="fa fa-expand"></i>Ver Pantalla completa
                                </a>

                                <script src>
                                // Comprueba el estado de pantalla completa al cargar la página
                                document.addEventListener('DOMContentLoaded', function () {
                                    if (localStorage.getItem('fullscreen') === 'true') {
                                        enableFullscreen();
                                    }
                                });

                                // Función para activar el modo de pantalla completa
                                function toggleFullscreen() {
                                    if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
                                        enableFullscreen();
                                    } else {
                                        disableFullscreen();
                                    }
                                }

                                // Activar pantalla completa
                                function enableFullscreen() {
                                    if (document.documentElement.requestFullscreen) {
                                        document.documentElement.requestFullscreen();
                                    } else if (document.documentElement.mozRequestFullScreen) {
                                        document.documentElement.mozRequestFullScreen(); // Firefox
                                    } else if (document.documentElement.webkitRequestFullscreen) {
                                        document.documentElement.webkitRequestFullscreen(); // Chrome, Safari y Opera
                                    } else if (document.documentElement.msRequestFullscreen) {
                                        document.documentElement.msRequestFullscreen(); // IE/Edge
                                    }
                                    
                                    // Guardamos en el localStorage que el modo pantalla completa está activado
                                    localStorage.setItem('fullscreen', 'true');
                                }

                                // Desactivar pantalla completa
                                function disableFullscreen() {
                                    if (document.exitFullscreen) {
                                        document.exitFullscreen();
                                    } else if (document.mozCancelFullScreen) {
                                        document.mozCancelFullScreen(); // Firefox
                                    } else if (document.webkitExitFullscreen) {
                                        document.webkitExitFullscreen(); // Chrome, Safari y Opera
                                    } else if (document.msExitFullscreen) {
                                        document.msExitFullscreen(); // IE/Edge
                                    }
                                    
                                    // Guardamos en el localStorage que el modo pantalla completa está desactivado
                                    localStorage.setItem('fullscreen', 'false');
                                }

                                </script>                           
                        </div>
                    </div>

                    <div class="user-area dropdown float-right">

                        <?php
                        require 'Conexion/conex.php'; // Conexión a la base de datos
                        // session_start(); // Asegúrate de tener esto activado si usas sesiones

                        $id_usuario = $_SESSION['id'] ?? null;

                        $foto = 'images/favicon.png'; // Imagen por defecto

                        if ($id_usuario) {
                            $sql = "SELECT foto_perfil FROM usuarios WHERE id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $id_usuario);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result && $row = $result->fetch_assoc()) {
                                if (!empty($row['foto_perfil'])) {
                                    $foto = $row['foto_perfil']; // Usar la ruta guardada en la base de datos
                                }
                            }
                            $stmt->close();
                        }
                        ?>

                        <a href="MyProfile.php" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="<?php echo htmlspecialchars($foto); ?>" alt="Foto de perfil">
                        </a>


                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="MyProfile.php"><i class="fa fa- user"></i>My Profile</a>

                            <a class="nav-link" href="cerrar_sesion.php"><i class="fa fa-power -off"></i>Logout</a>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- /#header -->
        <!-- Content -->
        <div class="content">
            <!-- Animated -->
            <div class="animated fadeIn">
                <!--  Traffic  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-body">



































                                    

<script>
// Subir logo
$('#formLogo').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: 'Configuracion/subir_logo_empresa.php', // ruta correcta
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            mostrarModal(response);
            $('#formLogo')[0].reset();
            $('#preview').hide();
        },
        error: function(xhr) {
            mostrarModal("❌ Error: " + xhr.responseText);
        }
    });
});

</script>


<?php
include("Conexion/conex.php"); // Conexión a la base de datos
?>

<?php
include("Conexion/conex.php"); // Conexión a la base de datos

// Obtener datos de la empresa si existe
$empresa = null;
$resultado = $conn->query("SELECT * FROM empresa ORDER BY id DESC LIMIT 1");
if ($resultado && $resultado->num_rows > 0) {
    $empresa = $resultado->fetch_assoc();
}
?>

<div class="container py-4">
    <h2 class="mb-4 text-primary">🏢 Configuración de Empresa y Usuario Administrador</h2>

    <!-- Subir Logo -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            📷 Subir Logo de la Empresa
        </div>
        <div class="card-body">
            <form id="formLogo" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="file" class="form-control" name="logo" id="logo" accept="image/*" <?= $empresa ? '' : 'required' ?>>
                    <?php if($empresa && $empresa['foto_perfil']): ?>
                        <img id="preview" src="<?= $empresa['foto_perfil'] ?>" alt="Vista previa del logo" style="max-height:150px; margin-top:10px; border:1px solid #ccc; padding:5px; border-radius:5px;">
                    <?php else: ?>
                        <img id="preview" alt="Vista previa del logo" style="display:none; max-height:150px; margin-top:10px; border:1px solid #ccc; padding:5px; border-radius:5px;">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-success">✅ Subir Logo</button>
            </form>
        </div>
    </div>

    <!-- Datos de Empresa -->
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            📝 Datos de la Empresa
        </div>
        <div class="card-body">
            <form id="formEmpresa">
                <input type="hidden" name="id" value="<?= $empresa ? $empresa['id'] : '' ?>">
                <div class="row">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">🏢 Nombre de la Empresa</label>
                            <input type="text" class="form-control" name="nombre" placeholder="Ej: Mi Empresa S.A." required value="<?= $empresa['nombre'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">📧 Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo" placeholder="correo@empresa.com" required value="<?= $empresa['correo'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">📠 Fax</label>
                            <input type="text" class="form-control" name="fax" placeholder="Fax de la empresa" value="<?= $empresa['fax'] ?? '' ?>">
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">📍 Dirección</label>
                            <input type="text" class="form-control" name="direccion" placeholder="Dirección de la empresa" required value="<?= $empresa['direccion'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">📞 Teléfono</label>
                            <input type="text" class="form-control" name="telefono" placeholder="8888-8888" value="<?= $empresa['telefono'] ?? '' ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">🆔 Identidad Jurídica</label>
                            <input type="text" class="form-control" name="identidad_juridica" placeholder="Registro legal" value="<?= $empresa['identidad_juridica'] ?? '' ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3"><?= $empresa ? '✏️ Actualizar Empresa' : '💾 Guardar Empresa' ?></button>
            </form>
        </div>
    </div>
</div>






<!-- Modal Notificación Mejorado -->
<div class="modal fade" id="modalMensaje" tabindex="-1" aria-labelledby="modalMensajeLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">

      <!-- Encabezado del Modal -->
      <div class="modal-header" id="modalHeader" style="justify-content:center; background-color:#0d6efd; color:white;">
        <h5 class="modal-title w-100 text-center" id="modalTitulo">Mensajes</h5>
      </div>

      <!-- Cuerpo del Modal -->
      <div class="modal-body p-4 text-center" id="modalContenido" 
           style="font-size:1rem; background-color:#f8f9fa; border-radius:0 0 10px 10px;">
        <!-- Mensaje dinámico -->
      </div>

    </div>
  </div>
</div>







<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const modalMensaje = new bootstrap.Modal(document.getElementById('modalMensaje'));

function mostrarModal(titulo, mensaje, tipo = "success") {
    // Configuración según el tipo de mensaje
    let bgHeader = "#198754"; // verde
    let textHeader = "#fff";
    switch(tipo) {
        case "error":
            bgHeader = "#dc3545"; // rojo
            break;
        case "warning":
            bgHeader = "#ffc107"; // amarillo
            textHeader = "#000";
            break;
        case "info":
            bgHeader = "#0dcaf0"; // azul
            break;
    }

    // Título y mensaje
    $('#modalTitulo').html(titulo);
    $('#modalContenido').html(mensaje);

    // Estilos
    $('#modalHeader').css({
        'background-color': bgHeader,
        'color': textHeader,
        'font-weight': '600',
        'text-align': 'center'
    });
    $('#modalContenido').css({
        'background-color': '#f8f9fa',
        'color': '#212529',
        'border-radius': '0 0 10px 10px',
        'padding': '20px',
        'margin': '0'
    });

    modalMensaje.show();

    // Desaparece después de 3 segundos
    setTimeout(() => { modalMensaje.hide(); }, 3000);
}

</script>







<script>

// Vista previa del logo
document.getElementById("logo").addEventListener("change", function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById("preview");
    if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = "block";
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = "none";
    }
});

// Función para mostrar modal
function mostrarModal(mensaje) {
    $('#modalContenido').html(mensaje);
    modalMensaje.show();
    setTimeout(() => { modalMensaje.hide(); }, 3000); // Desaparece a los 3 segundos
}

// Guardar datos de la empresa
$('#formEmpresa').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    $.ajax({
        url: 'Configuracion/guardar_configurar_empresas.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            mostrarModal("✅ Éxito", response, "success");

            // Recargar la página después de 1.5 segundos
            setTimeout(() => { location.reload(); }, 1500);
        },
        error: function(xhr) {
            mostrarModal("❌ Error", xhr.responseText, "error");
        }
    });
});



// Subir logo
$('#formLogo').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: 'Configuracion/subir_logo_empresa.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            mostrarModal(response);
            $('#formLogo')[0].reset();
            $('#preview').hide();
        },
        error: function(xhr) {
            mostrarModal("❌ Error: " + xhr.responseText);
        }
    });
});
</script>






























                                    </div>
                                </div>
                            </div> <!-- /.row -->
                            <div class="card-body"></div>
                        </div>
                    </div><!-- /# column -->
                </div>
                <!--  /Traffic -->
            </div>
            <!-- .animated -->
        </div>
        <!-- /.content -->
        <div class="clearfix"></div>
        <!-- Footer -->
        <footer class="site-footer">
            <div class="footer-inner bg-white">
                <div class="row">
                    <div class="col-sm-6">
                        Copyright &copy; 2025 Aaron Carrasco
                    </div>
                    <div class="col-sm-6 text-right">
                        Desarrollado por <a href="https://colorlib.com">Aaron Moises Carrasco Thomas</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- /.site-footer -->
    </div>
    <!-- /#right-panel -->

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!--  Chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

    <!--Chartist Chart-->
    <script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
    <script src="assets/js/init/weather-init.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
    <script src="assets/js/init/fullcalendar-init.js"></script>

</body>
</html>