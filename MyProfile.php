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
    <title>👤 Mi Perfil</title>
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

        <?php
        // session_start();

        // Si no hay sesión activa, redirigir al login
        if (!isset($_SESSION["usuario"])) {
            header("Location: page-login.php");
            exit();
        }

        $id_usuario = $_SESSION["id"];

        require 'Conexion/conex.php';

        // Obtener páginas permitidas
        $sql = "SELECT p.pagina 
                FROM permisos_usuario pu
                INNER JOIN paginas_projectos p ON pu.id_permiso = p.id
                WHERE pu.id_usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $permisos = [];
        while ($row = $result->fetch_assoc()) {
            $permisos[] = strtolower($row['pagina']); // Normalizamos para evitar errores
        }

        $stmt->close();
        $conn->close();

        // Función para validar permiso
        function puede($pagina, $permisos) {
            return in_array(strtolower($pagina), $permisos);
        }
        ?>

        <nav class="navbar navbar-expand-sm navbar-default">
            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">

                    <!-- INICIO -->
                    <?php if (puede("index.php", $permisos)) : ?>
                    <li class="active">
                        <a href="index.php"><i class="menu-icon fa fa-home"></i>Inicio</a>
                    </li>
                    <?php endif; ?>

                    <!-- PRODUCTOS -->
                    <?php if (puede("VerProductos.php", $permisos) || puede("AgregarProductos.php", $permisos)) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="menu-icon fa fa-cube"></i>Productos
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php if (puede("VerProductos.php", $permisos)) : ?>
                            <li><i class="fa fa-cube"></i><a href="VerProductos.php">Ver Productos</a></li>
                            <?php endif; ?>

                            <?php if (puede("AgregarProductos.php", $permisos)) : ?>
                            <li><i class="fa fa-plus-circle"></i><a href="AgregarProductos.php">Agregar Productos</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- USUARIOS -->
                    <?php if (puede("VerUsuario.php", $permisos) || puede("AgregarUsuario.php", $permisos)) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="menu-icon fa fa-users"></i>Usuarios
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php if (puede("VerUsuario.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-users"></i><a href="VerUsuario.php">Ver Usuarios</a></li>
                            <?php endif; ?>

                            <?php if (puede("AgregarUsuario.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-user-plus"></i><a href="AgregarUsuario.php">Agregar Usuario</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- CLIENTES -->
                    <?php if (puede("VerClientes.php", $permisos) || puede("AgregarClientes.php", $permisos)) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="menu-icon fa fa-users"></i>Clientes
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php if (puede("VerClientes.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-address-book"></i><a href="VerClientes.php">Ver Clientes</a></li>
                            <?php endif; ?>

                            <?php if (puede("AgregarClientes.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-user-plus"></i><a href="AgregarClientes.php">Nuevo Cliente</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- PROVEEDOR -->
                    <?php if (puede("Proveedor.php", $permisos)) : ?>
                    <li>
                        <a href="Proveedor.php"><i class="menu-icon fa fa-truck"></i>Proveedor</a>
                    </li>
                    <?php endif; ?>

                    <!-- VENTAS -->
                    <?php if (puede("Ventas.php", $permisos)) : ?>
                    <li>
                        <a href="Ventas.php"><i class="menu-icon fa fa-shopping-cart"></i>Vender</a>
                    </li>
                    <?php endif; ?>

                    <!-- DEVOLUCIONES -->
                    <?php if (puede("Devolucion.php", $permisos)) : ?>
                    <li>
                        <a href="Devolucion.php"><i class="menu-icon fa fa-rotate-left"></i>Devoluciones</a>
                    </li>
                    <?php endif; ?>

                    <?php if (puede("VerDevolucion.php", $permisos)) : ?>
                    <li>
                        <a href="VerDevolucion.php"><i class="menu-icon fa fa-list-alt"></i>Ver Devolucion</a>
                    </li>
                    <?php endif; ?>

                    <!-- REPORTES -->
                    <?php if (
                        puede("VerReportes.php", $permisos) ||
                        puede("ver_facturas.php", $permisos) ||
                        puede("verfechavencimiento.php", $permisos)
                    ) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="menu-icon fa fa-pie-chart"></i>Reportes de ventas
                        </a>
                        <ul class="sub-menu children dropdown-menu">
                            <?php if (puede("VerReportes.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-map"></i><a href="VerReportes.php">Visualizar Reportes</a></li>
                            <?php endif; ?>

                            <?php if (puede("ver_facturas.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-file-invoice"></i><a href="ver_facturas.php">Ver facturas</a></li>
                            <?php endif; ?>

                            <?php if (puede("verfechavencimiento.php", $permisos)) : ?>
                            <li><i class="menu-icon fa fa-clock"></i><a href="verfechavencimiento.php">Ver Fecha Vencimiento</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <!-- CONFIGURACIÓN -->
                    <?php if (
                        puede("AjusteMoneda.php", $permisos) ||
                        puede("AjustesTipoPago.php", $permisos) ||
                        puede("AjusteUnidad.php", $permisos) ||
                        puede("accesos.php", $permisos) ||
                        puede("ConfigurarEmpresas.php", $permisos)
                    ) : ?>
                    <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="menu-icon fa fa-cogs"></i>Configuración
                        </a>
                        <ul class="sub-menu children dropdown-menu">

                            <?php if (puede("AjusteMoneda.php", $permisos)) : ?>
                            <li><i class="fa fa-money"></i><a href="AjusteMoneda.php">Moneda</a></li>
                            <?php endif; ?>

                            <?php if (puede("AjustesTipoPago.php", $permisos)) : ?>
                            <li><i class="fa fa-credit-card"></i><a href="AjustesTipoPago.php">Tipo Pago</a></li>
                            <?php endif; ?>

                            <?php if (puede("AjusteUnidad.php", $permisos)) : ?>
                            <li><i class="fa fa-balance-scale"></i><a href="AjusteUnidad.php">Unidad de peso</a></li>
                            <?php endif; ?>

                            <?php if (puede("accesos.php", $permisos)) : ?>
                            <li><i class="fa fa-lock"></i><a href="accesos.php">Accesos</a></li>
                            <?php endif; ?>

                            <?php if (puede("ConfigurarEmpresas.php", $permisos)) : ?>
                            <li><i class="fa fa-building"></i><a href="ConfigurarEmpresas.php">Configurar Empresas</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>

                </ul>
            </div>
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
                            <a class="nav-link" href="#" onclick="toggleFullscreen(event)">
                                <i class="fa fa-expand" id="fullscreenIcon"></i> Ver Pantalla completa
                            </a>                   
                        </div>

                        <script>
                        function toggleFullscreen(event) {
                            event.preventDefault();

                            if (!document.fullscreenElement) {
                                document.documentElement.requestFullscreen()
                                    .then(() => {
                                        sessionStorage.setItem('fullscreenActive', 'true');
                                        updateIcon(true);
                                    })
                                    .catch((err) => {
                                        alert(`Error: ${err.message} (${err.name})`);
                                    });
                            } else {
                                document.exitFullscreen()
                                    .then(() => {
                                        sessionStorage.setItem('fullscreenActive', 'false');
                                        updateIcon(false);
                                    });
                            }
                        }

                        function updateIcon(isFullscreen) {
                            const icon = document.getElementById('fullscreenIcon');
                            if (isFullscreen) {
                                icon.classList.remove('fa-expand');
                                icon.classList.add('fa-compress');
                            } else {
                                icon.classList.remove('fa-compress');
                                icon.classList.add('fa-expand');
                            }
                        }

                        // Al cargar la página, verifica si el usuario quería pantalla completa
                        document.addEventListener('DOMContentLoaded', () => {
                            if (sessionStorage.getItem('fullscreenActive') === 'true') {
                                // Solo se puede activar tras interacción, así que muestra un mensaje o botón para que el usuario lo active
                                // Aquí solo actualizamos el icono para reflejar la intención
                                updateIcon(true);
                                // Opcional: mostrar mensaje para pedir que active pantalla completa manualmente
                                console.log("Recuerda activar pantalla completa con el botón si quieres continuar.");
                            }
                        });

                        // Detecta cambios en pantalla completa para actualizar el icono
                        document.addEventListener('fullscreenchange', () => {
                            updateIcon(!!document.fullscreenElement);
                            if (!document.fullscreenElement) {
                                sessionStorage.setItem('fullscreenActive', 'false');
                            }
                        });
                        </script>


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
                                <div class="col-lg-10">
                                    <div class="card-body">
                                        

<h1 class="text-center my-4">👤 Bienvenido a tu Perfil 🖼️</h1>































    
                                    



















<?php
require_once "Conexion/conex.php";
// Validar sesión
if (!isset($_SESSION['id']) || !isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['id'];

// Obtener usuario si existe
$sql = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<div class="container py-4">
    <!-- FORMULARIO FOTO PERFIL -->
    <div class="card shadow-lg border-0 rounded-3 mb-4">
        <div class="card-header bg-gradient bg-secondary text-white text-center py-3">
            <h4 class="mb-0">🖼️ Foto de Perfil</h4>
            <small class="text-light">Selecciona tu foto de perfil (JPG, PNG)</small>
        </div>
        <div class="card-body p-4 text-center">
            <?php
            $fotoPerfil = !empty($usuario['foto_perfil']) ? $usuario['foto_perfil'] : 'default.png';
            ?>
            <img src="images/photo_perfil/<?php echo $fotoPerfil; ?>" class="rounded-circle mb-3" width="120" height="120" id="previewFoto">

            <form id="formFoto" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                <div class="mb-3">
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png" required id="inputFoto">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">📤 Subir Foto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- FORMULARIO DATOS PERSONALES Y CONTRASEÑA -->
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-header bg-gradient bg-primary text-white text-center py-3">
            <h4 class="mb-0">👤 Mi Perfil</h4>
            <small class="text-light">Aquí puedes actualizar tu información personal y contraseña</small>
        </div>
        <div class="card-body p-4">
            <form id="formPerfil">
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">

                <div class="row g-4">
                    <!-- Columna izquierda -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">🆔 Usuario</label>
                            <input type="text" class="form-control" value="<?php echo $usuario['usuario']; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">📛 Nombre completo</label>
                            <input type="text" class="form-control" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">🪪 Cédula</label>
                            <input type="text" class="form-control" name="cedula" value="<?php echo $usuario['cedula']; ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">📞 Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="<?php echo $usuario['telefono']; ?>">
                        </div>
                    </div>

                    <!-- Columna derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">🏠 Dirección</label>
                            <textarea class="form-control" name="direccion" rows="1"><?php echo $usuario['direccion']; ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">📧 Correo electrónico</label>
                            <input type="text" class="form-control" value="<?php echo $usuario['email']; ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">🛡️ Rol de usuario</label>
                            <input type="text" class="form-control" value="<?php 
                                echo $usuario['rol']=="admin"?"👑 Administrador":
                                     ($usuario['rol']=="editor"?"✏️ Editor":"🙋 Usuario"); ?>" readonly>
                        </div>
                    </div>
                    <!-- Campos de contraseña alineados en fila -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-bold">🔑 Nueva contraseña</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Dejar vacío si no desea cambiar">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" onclick="togglePassword('password', this)">
                                👁️
                            </button>
                        </div>

                        <div class="col-md-6 position-relative">
                            <label class="form-label fw-bold">🔑 Confirmar contraseña</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña">
                            <button type="button" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" onclick="togglePassword('confirm_password', this)">
                                👁️
                            </button>
                            <div id="mensajePassword" class="form-text text-danger mt-1"></div>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" id="btnGuardar" class="btn btn-success btn-lg shadow-sm">
                        💾 Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePassword(idInput, btn) {
    const input = document.getElementById(idInput);
    if(input.type === "password") {
        input.type = "text";
        btn.textContent = "🙈"; // cambia icono cuando está visible
    } else {
        input.type = "password";
        btn.textContent = "👁️"; // icono original
    }
}

</script>

<!-- Modal de notificación -->
<div class="modal fade" id="modalNotificacion" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4" id="modalMensaje">
        <!-- El mensaje se insertará dinámicamente -->
      </div>
    </div>
  </div>
</div>





<script>
$(document).ready(function(){
    // Vista previa inmediata de la foto seleccionada
    $("#inputFoto").change(function(){
        const file = this.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(e){
                $("#previewFoto").attr("src", e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // Formulario foto perfil
    $("#formFoto").on("submit", function(e){
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: "Configuracion/SubirFotoPerfil.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(respuesta){
                $("#previewFoto").attr("src", "images/photo_perfil/" + respuesta + "?t=" + new Date().getTime());
                mostrarModal("✅ Foto de perfil actualizada correctamente");
            },
            error: function(){
                mostrarModal("❌ Error al subir foto");
            }
        });
    });

    // Validación en tiempo real de contraseñas
    $("#password, #confirm_password").on("keyup", function(){
        const pass = $("#password").val();
        const confirm = $("#confirm_password").val();
        const btnGuardar = $("#btnGuardar");
        const mensaje = $("#mensajePassword");

        if(pass !== confirm){
            mensaje.text("Las contraseñas no coinciden");
            $("#confirm_password").addClass("is-invalid");
            btnGuardar.prop("disabled", true);
        } else {
            mensaje.text("");
            $("#confirm_password").removeClass("is-invalid");
            btnGuardar.prop("disabled", false);
        }
    });

    // Formulario datos personales y contraseña
    $("#formPerfil").on("submit", function(e){
        e.preventDefault();
        var btnGuardar = $("#btnGuardar");
        btnGuardar.prop("disabled", true); // evitar doble envío

        // Obtener valores
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();

        // Validar que coincidan las contraseñas si alguna fue ingresada
        if(password || confirm_password){
            if(password !== confirm_password){
                $("#mensajePassword").text("Las contraseñas no coinciden");
                btnGuardar.prop("disabled", false);
                return; // salir sin enviar
            }
        }

        // Preparar datos a enviar
        var datos = $(this).serializeArray();
        // Si la contraseña está vacía, removerla para no actualizarla
        if(!password){
            datos = datos.filter(function(item){
                return item.name !== "password" && item.name !== "confirm_password";
            });
        }

        $.ajax({
            url: "Configuracion/ActualizarPerfil.php",
            type: "POST",
            data: $.param(datos), // enviar los datos filtrados
            success: function(respuesta){
                mostrarModal("✅ Perfil actualizado correctamente");
                btnGuardar.prop("disabled", false);
                $("#password, #confirm_password").val(""); // limpiar campos
            },
            error: function(){
                mostrarModal("❌ Error al actualizar perfil");
                btnGuardar.prop("disabled", false);
            }
        });
    });


    // Función para mostrar modal y cerrarlo automáticamente en 3 segundos
    function mostrarModal(mensaje){
        $("#modalMensaje").text(mensaje);
        var modal = new bootstrap.Modal(document.getElementById('modalNotificacion'));
        modal.show();
        setTimeout(function(){
            modal.hide();
        }, 3000);
    }
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
