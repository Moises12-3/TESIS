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
    <title>Inicio</title>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                        puede("Accesos.php", $permisos) ||
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

                            <?php if (puede("Accesos.php", $permisos)) : ?>
                            <li><i class="fa fa-lock"></i><a href="Accesos.php">Accesos</a></li>
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
<style>
/* ... tus estilos existentes ... */

/* Estilos para gráficos vacíos */
.chart-empty {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    color: #6c757d;
    font-style: italic;
}

/* Mejoras para responsividad de gráficos */
@media (max-width: 768px) {
    .kpi-card {
        margin-bottom: 15px;
    }
    
    .kpi-value {
        font-size: 2rem;
    }
    
    .chart-container, .chart-container-sm {
        height: 300px !important;
    }
}

/* Estilo para alerts */
.alert-custom {
    border-radius: 10px;
    margin: 15px 0;
}



/* Estilos específicos para gráfico de barras de tendencia */
#graficoTendenciaMensual {
    max-height: 400px;
}

/* Mejoras para gráficos de barras */
.bar-chart-container {
    position: relative;
    background: linear-gradient(180deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 10px;
    padding: 15px;
}

/* Animación para barras */
@keyframes barAnimation {
    from { transform: scaleY(0); }
    to { transform: scaleY(1); }
}

/* Responsividad mejorada */
@media (max-width: 768px) {
    #graficoTendenciaMensual {
        max-height: 300px;
    }
    
    .card-custom {
        padding: 15px;
    }
}



</style>




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




<h1>📊📈 Reportes de Productos</h1>

<style>
.card-custom {
    border-radius: 18px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    padding: 25px;
    background: white;
    margin-bottom: 25px;
}
.card-title-custom {
    font-weight: bold;
    font-size: 1.4rem;
    margin-bottom: 20px;
}
.chart-container {
    position: relative;
    width: 100%;
    height: 450px;
}
.chart-container-sm {
    height: 350px;
}
@media (max-width: 992px) {
    .chart-container { height: 350px; }
    .chart-container-sm { height: 300px; }
}
@media (max-width: 576px) {
    .chart-container { height: 300px; }
    .chart-container-sm { height: 250px; }
}

/* Nueva sección de KPIs */
.kpi-card {
    border-radius: 15px;
    padding: 20px;
    text-align: center;
    color: white;
    margin-bottom: 20px;
}
.kpi-value {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 10px 0;
}
.kpi-label {
    font-size: 1rem;
    opacity: 0.9;
}

/* Estilos para gráfico de dona de categorías */
.dona-chart-container {
    position: relative;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 15px;
    padding: 20px;
    border: 1px solid rgba(0,0,0,0.05);
}

#graficoVentasCategoria {
    max-height: 380px;
}

/* Mejoras para la leyenda */
.chart-legend {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
}

.chart-legend::-webkit-scrollbar {
    width: 6px;
}

.chart-legend::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.chart-legend::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

/* Animación de entrada para el gráfico */
@keyframes donutEnter {
    0% { transform: scale(0.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.dona-chart-container canvas {
    animation: donutEnter 0.8s ease-out;
}

/* Etiquetas en el gráfico */
.chart-label {
    font-weight: 600;
    color: #2c3e50;
}

/* Responsividad */
@media (max-width: 768px) {
    .dona-chart-container {
        padding: 15px;
    }
    
    #graficoVentasCategoria {
        max-height: 300px;
    }
}
</style>

<!-- FILTROS -->
<div class="card-custom mb-4">
    <h4 class="card-title-custom mb-3">📅🗓️ Filtros de Fechas</h4>
    <div class="row">
        <div class="col-md-4">
            <label class="fw-bold">🟢 Fecha inicio:</label>
            <input type="date" id="fechaInicio" class="form-control shadow-sm">
        </div>
        <div class="col-md-4">
            <label class="fw-bold">🔴 Fecha final:</label>
            <input type="date" id="fechaFinal" class="form-control shadow-sm">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button id="btnFiltrar" class="btn btn-primary w-100 shadow-sm">
                🔍✨ Aplicar Filtro
            </button>
        </div>
    </div>
</div>

<!-- SECCIÓN DE KPIs (Tarjetas con resumen) -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="kpi-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-dollar-sign fa-2x"></i>
            <div class="kpi-value" id="kpiVentas">$0.00</div>
            <div class="kpi-label">VENTAS TOTALES</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <i class="fas fa-exchange-alt fa-2x"></i>
            <div class="kpi-value" id="kpiDevoluciones">0</div>
            <div class="kpi-label">DEVOLUCIONES</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <i class="fas fa-boxes fa-2x"></i>
            <div class="kpi-value" id="kpiProductos">0</div>
            <div class="kpi-label">PRODUCTOS VENDIDOS</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <i class="fas fa-calendar-exclamation fa-2x"></i>
            <div class="kpi-value" id="kpiPorVencer">0</div>
            <div class="kpi-label">POR VENCER (30 días)</div>
        </div>
    </div>
</div>

<!-- PRIMERA FILA DE GRÁFICOS -->
<div class="row g-4">
    <!-- Ventas por día de la semana -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-success card-title-custom">📅📈 Ventas por Día de la Semana</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoVentasSemana"></canvas>
            </div>
        </div>
    </div>

    <!-- Métodos de Pago más usados -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-info card-title-custom">💳💰 Métodos de Pago más Utilizados</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoMetodosPago"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- SEGUNDA FILA DE GRÁFICOS -->
<div class="row g-4 mt-4">
    <!-- Productos más vendidos -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-primary card-title-custom">📊🔥 Productos más vendidos (Top 10)</h4>
            <div class="chart-container">
                <canvas id="graficoVendidos"></canvas>
            </div>
        </div>
    </div>

    <!-- Ventas por categoría -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-warning card-title-custom">🏷️📦 Ventas por Categoría</h4>
            <div class="chart-container">
                <canvas id="graficoVentasCategoria"></canvas>
            </div>
        </div>
    </div>


    
</div>

<!-- TERCERA FILA DE GRÁFICOS -->
<div class="row g-4 mt-4">
    <!-- Productos con más devoluciones -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-danger card-title-custom">♻️📉 Productos con más devoluciones</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoDevoluciones"></canvas>
            </div>
        </div>
    </div>

    <!-- Eficiencia de ventas por hora -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-purple card-title-custom">⏰📊 Eficiencia de Ventas por Hora</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoVentasHora"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- CUARTA FILA DE GRÁFICOS -->
<div class="row g-4 mt-4">
    <!-- Productos próximos a vencer -->
    <div class="col-lg-12">
        <div class="card-custom">
            <h4 class="text-warning card-title-custom">⏳⚠️ Productos próximos a vencer (30 días)</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoVencimiento"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- QUINTA FILA DE GRÁFICOS -->
<div class="row g-4 mt-4">
    <!-- Top 10 Clientes -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-success card-title-custom">👥🏆 Top 10 Mejores Clientes</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoTopClientes"></canvas>
            </div>
        </div>
    </div>

    <!-- Tendencia de ventas mensual -->
    <div class="col-lg-6">
        <div class="card-custom">
            <h4 class="text-info card-title-custom">📈📅 Tendencia de Ventas Mensual</h4>
            <div class="chart-container chart-container-sm">
                <canvas id="graficoTendenciaMensual"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Loading overlay -->
<div class="loading-overlay">
    <div class="text-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
        <h4 class="mt-3">Cargando gráficos...</h4>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
// Variables para los gráficos
let graficos = {
    vendidos: null,
    devoluciones: null,
    vencimiento: null,
    ventasSemana: null,
    metodosPago: null,
    ventasCategoria: null,
    ventasHora: null,
    topClientes: null,
    tendenciaMensual: null
};

// AUTO-CARGAR SEMANA ACTUAL
function cargarSemanaActual() {
    const hoy = new Date();
    const primerDia = new Date(hoy.setDate(hoy.getDate() - hoy.getDay() + 1)); 
    const ultimoDia = new Date(hoy.setDate(primerDia.getDate() + 6));         

    document.getElementById("fechaInicio").valueAsDate = primerDia;
    document.getElementById("fechaFinal").valueAsDate = ultimoDia;
}

function cargarGraficos() {
    const inicio = document.getElementById("fechaInicio").value;
    const final = document.getElementById("fechaFinal").value;
    
    console.log("Cargando gráficos con fechas:", inicio, "a", final);
    
    // Mostrar mensaje de carga
    showLoading(true);

    fetch("Configuracion/obtener_datos_graficos.php?inicio=" + inicio + "&final=" + final)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Datos recibidos:", data);
            
            // Verificar si hay error
            if (data.error) {
                console.error("Error del servidor:", data.error);
                mostrarError(`Error del servidor: ${data.error}`);
                showLoading(false);
                return;
            }

            // Actualizar KPIs si existen
            if (data.kpis) {
                actualizarKPIs(data.kpis);
            }
            
            // Crear todos los gráficos con manejo de errores
            try {
                if (data.ventasPorDia) crearGraficoVentasSemana(data.ventasPorDia);
                if (data.metodosPago) crearGraficoMetodosPago(data.metodosPago);
                if (data.masVendidos) crearGraficoVendidos(data.masVendidos);
                if (data.ventasCategoria) crearGraficoVentasCategoria(data.ventasCategoria);
                if (data.masDevueltos) crearGraficoDevoluciones(data.masDevueltos);
                if (data.ventasPorHora) crearGraficoVentasHora(data.ventasPorHora);
                if (data.porVencer) crearGraficoVencimiento(data.porVencer);
                if (data.topClientes) crearGraficoTopClientes(data.topClientes);
                if (data.tendenciaMensual) crearGraficoTendenciaMensual(data.tendenciaMensual);
            } catch (error) {
                console.error("Error al crear gráficos:", error);
                mostrarError(`Error al crear gráficos: ${error.message}`);
            }

            showLoading(false);
        })
        .catch(error => {
            console.error("Error al cargar gráficos:", error);
            mostrarError(`Error al cargar los datos: ${error.message}`);
            showLoading(false);
        });
}

function mostrarError(mensaje) {
    // Crear alerta de error
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show';
    alertDiv.innerHTML = `
        <strong>Error:</strong> ${mensaje}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    `;
    
    // Insertar después de los filtros
    const filtros = document.querySelector('.card-custom.mb-4');
    filtros.parentNode.insertBefore(alertDiv, filtros.nextSibling);
    
    // Auto-eliminar después de 5 segundos
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function actualizarKPIs(kpis) {
    // Obtener el símbolo de moneda (por defecto 'NIO')
    const simbolo = kpis.simboloMoneda || 'NIO';
    
    // Formatear el número con separadores de miles
    const ventasFormateadas = kpis.ventasTotalNum ? 
        kpis.ventasTotalNum.toLocaleString('es-NI', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) : '0.00';
    
    // Mostrar VENTAS TOTALES con símbolo de moneda
    document.getElementById('kpiVentas').innerHTML = 
        `<span class="kpi-simbolo">${simbolo}</span> ${ventasFormateadas}`;
    
    document.getElementById('kpiDevoluciones').textContent = kpis.devolucionesTotal;
    document.getElementById('kpiProductos').textContent = kpis.productosVendidos;
    document.getElementById('kpiPorVencer').textContent = kpis.porVencer;
}

function crearGraficoVentasSemana(datos) {
    const ctx = document.getElementById("graficoVentasSemana").getContext('2d');
    if (graficos.ventasSemana) graficos.ventasSemana.destroy();
    
    graficos.ventasSemana = new Chart(ctx, {
        type: "line",
        data: {
            labels: datos.dias,
            datasets: [{
                label: "Ventas por Día",
                data: datos.ventas,
                backgroundColor: "rgba(76, 175, 80, 0.2)",
                borderColor: "rgba(76, 175, 80, 1)",
                borderWidth: 3,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true },
                title: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Monto en Córdobas' }
                }
            }
        }
    });
}

function crearGraficoMetodosPago(datos) {
    const ctx = document.getElementById("graficoMetodosPago").getContext('2d');
    if (graficos.metodosPago) graficos.metodosPago.destroy();
    
    graficos.metodosPago = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: datos.metodos,
            datasets: [{
                label: "Cantidad de Ventas",
                data: datos.cantidades,
                backgroundColor: [
                    "#4CAF50", "#2196F3", "#FF9800", "#E91E63",
                    "#9C27B0", "#00BCD4", "#FF5722", "#795548"
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'right',
                    labels: { font: { size: 11 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw + ' ventas';
                        }
                    }
                }
            }
        }
    });
}

function crearGraficoVendidos(datos) {
    const ctx = document.getElementById("graficoVendidos").getContext('2d');
    if (graficos.vendidos) graficos.vendidos.destroy();
    
    graficos.vendidos = new Chart(ctx, {
        type: "bar",
        data: {
            labels: datos.productos,
            datasets: [{
                label: "Cantidad Vendida",
                data: datos.vendidos,
                backgroundColor: "rgba(25,118,210,0.5)",
                borderColor: "rgba(25,118,210,1)",
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true },
                title: { display: false }
            },
            scales: { 
                x: { 
                    ticks: { 
                        maxRotation: 45, 
                        minRotation: 0,
                        autoSkip: false
                    } 
                }, 
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad' }
                } 
            }
        }
    });
}

function crearGraficoVentasCategoria(datos) {
    console.log("Creando gráfico de dona para categorías...");
    
    const canvas = document.getElementById("graficoVentasCategoria");
    if (!canvas) {
        console.error("No se encontró el canvas para categorías");
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Limpiar y destruir gráfico anterior
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    if (graficos.ventasCategoria instanceof Chart) {
        try {
            graficos.ventasCategoria.destroy();
        } catch (e) {
            console.warn("No se pudo destruir gráfico anterior:", e);
        }
    }
    
    // Preparar datos
    let labels = ['Cervezas', 'Licores', 'Vinos', 'Refrescos', 'Snacks', 'Otros'];
    let valores = [30, 25, 15, 12, 10, 8];
    
    if (datos && datos.categorias && Array.isArray(datos.categorias) && datos.categorias.length > 0) {
        labels = datos.categorias;
        valores = datos.totales || new Array(labels.length).fill(1);
    }
    
    // Calcular total para porcentajes
    const total = valores.reduce((a, b) => a + b, 0);
    
    try {
        graficos.ventasCategoria = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: valores,
                    backgroundColor: [
                        '#FF6B6B', '#4ECDC4', '#FFD166', '#06D6A0', '#118AB2', '#EF476F',
                        '#073B4C', '#7209B7', '#F72585', '#3A86FF', '#FB5607', '#8338EC'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverBorderColor: '#2c3e50',
                    hoverBorderWidth: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12
                            },
                            color: '#2c3e50'
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label;
                                const value = context.raw;
                                const percentage = total > 0 ? 
                                    Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                    duration: 1200
                }
            }
        });
        
        console.log("✅ Gráfico de dona de categorías creado exitosamente");
        
    } catch (error) {
        console.error("❌ Error al crear gráfico de categorías:", error);
        
        // Mostrar mensaje de error
        ctx.fillStyle = '#f8f9fa';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.fillStyle = '#6c757d';
        ctx.font = '16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Error al cargar gráfico', canvas.width/2, canvas.height/2);
    }
}

function agregarTextoCentroDonut(canvas, total) {
    const ctx = canvas.getContext('2d');
    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;
    
    // Guardar el contexto
    ctx.save();
    
    // Dibujar fondo circular
    ctx.beginPath();
    ctx.arc(centerX, centerY, 50, 0, Math.PI * 2);
    ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
    ctx.fill();
    ctx.strokeStyle = 'rgba(0, 0, 0, 0.1)';
    ctx.stroke();
    
    // Texto principal
    ctx.font = 'bold 20px Arial';
    ctx.fillStyle = '#2c3e50';
    ctx.textAlign = 'center';
    ctx.fillText('Total', centerX, centerY - 15);
    
    // Número total
    ctx.font = 'bold 24px Arial';
    ctx.fillStyle = '#e74c3c';
    ctx.fillText(total.toString(), centerX, centerY + 15);
    
    // Restaurar contexto
    ctx.restore();
}

// Función de respaldo básica
function crearGraficoCategoriasBasico(ctx, labels, valores) {
    graficos.ventasCategoria = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: valores,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
}

function crearGraficoDevoluciones(datos) {
    const ctx = document.getElementById("graficoDevoluciones").getContext('2d');
    if (graficos.devoluciones) graficos.devoluciones.destroy();
    
    graficos.devoluciones = new Chart(ctx, {
        type: "bar",
        data: {
            labels: datos.productos,
            datasets: [{
                label: "Cantidad Devuelta",
                data: datos.devueltos,
                backgroundColor: "rgba(244,67,54,0.5)",
                borderColor: "rgba(244,67,54,1)",
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: true },
                title: { display: false }
            },
            scales: { 
                y: { 
                    beginAtZero: true,
                    title: { display: true, text: 'Cantidad' }
                } 
            }
        }
    });
}

function crearGraficoVentasHora(datos) {
    const ctx = document.getElementById("graficoVentasHora").getContext('2d');
    if (graficos.ventasHora) graficos.ventasHora.destroy();
    
    graficos.ventasHora = new Chart(ctx, {
        type: "radar",
        data: {
            labels: datos.horas,
            datasets: [{
                label: "Ventas por Hora",
                data: datos.ventas,
                backgroundColor: "rgba(156, 39, 176, 0.2)",
                borderColor: "rgba(156, 39, 176, 1)",
                borderWidth: 2,
                pointBackgroundColor: "rgba(156, 39, 176, 1)"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: { display: false }
                }
            },
            plugins: {
                legend: { display: true }
            }
        }
    });
}

function crearGraficoVencimiento(datos) {
    const ctx = document.getElementById("graficoVencimiento").getContext('2d');
    if (graficos.vencimiento) graficos.vencimiento.destroy();
    
    graficos.vencimiento = new Chart(ctx, {
        type: "pie",
        data: {
            labels: datos.productos.map((p, i) => `${p} (${datos.dias[i]} días)`),
            datasets: [{
                label: "Días restantes",
                data: datos.dias,
                backgroundColor: [
                    "#FFB300", "#FF7043", "#AB47BC", "#42A5F5", "#26A69A",
                    "#9CCC65", "#FFCA28", "#EC407A", "#5C6BC0", "#8D6E63"
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'right',
                    labels: { font: { size: 12 } }
                },
                tooltip: { 
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} días`;
                        }
                    }
                }
            }
        }
    });
}

function crearGraficoTopClientes(datos) {
    const ctx = document.getElementById("graficoTopClientes").getContext('2d');
    if (graficos.topClientes) graficos.topClientes.destroy();
    
    // Verificar si tenemos datos válidos
    if (!datos.clientes || datos.clientes.length === 0) {
        // Crear gráfico vacío
        graficos.topClientes = new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Sin datos"],
                datasets: [{
                    label: "Compras",
                    data: [0],
                    backgroundColor: "rgba(200, 200, 200, 0.7)"
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        return;
    }
    
    // Crear gráfico simplificado - SOLO barras
    graficos.topClientes = new Chart(ctx, {
        type: "bar",
        data: {
            labels: datos.clientes,
            datasets: [{
                label: "Compras",
                data: datos.compras,
                backgroundColor: "rgba(76, 175, 80, 0.7)",
                borderColor: "rgba(76, 175, 80, 1)",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Número de Compras' }
                }
            }
        }
    });
}

function crearGraficoTendenciaMensual(datos) {
    console.log("Creando gráfico de barras de tendencia...");
    
    const canvas = document.getElementById("graficoTendenciaMensual");
    if (!canvas) {
        console.error("No se encontró el canvas para tendencia mensual");
        return;
    }
    
    const ctx = canvas.getContext('2d');
    
    // Limpiar canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Destruir gráfico anterior
    if (graficos.tendenciaMensual instanceof Chart) {
        try {
            graficos.tendenciaMensual.destroy();
        } catch (e) {
            console.warn("No se pudo destruir gráfico anterior:", e);
        }
    }
    
    // Preparar datos
    let labels = [];
    let valores = [];
    
    // Verificar y preparar datos
    if (datos && typeof datos === 'object') {
        if (datos.meses && Array.isArray(datos.meses)) {
            labels = datos.meses;
        }
        
        if (datos.cantidad && Array.isArray(datos.cantidad)) {
            valores = datos.cantidad;
        } else if (datos.total && Array.isArray(datos.total)) {
            valores = datos.total;
        }
    }
    
    // Si no hay datos, usar datos de ejemplo
    if (labels.length === 0) {
        labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
        valores = [12, 19, 8, 15, 22, 17];
        console.log("Usando datos de ejemplo para gráfico de tendencia");
    }
    
    // Asegurar que labels y valores tengan la misma longitud
    if (valores.length !== labels.length) {
        valores = new Array(labels.length).fill(0);
    }
    
    console.log("Datos finales para gráfico:", { labels, valores });
    
    try {
        // Crear el gráfico de barras
        graficos.tendenciaMensual = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas Mensuales',
                    data: valores,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Ventas: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    }
                }
            }
        });
        
        console.log("✅ Gráfico de barras de tendencia creado exitosamente");
        
    } catch (error) {
        console.error("❌ Error al crear gráfico de barras:", error);
        
        // Mostrar mensaje de error en el canvas
        ctx.fillStyle = '#f8d7da';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.fillStyle = '#721c24';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('Error al crear gráfico', canvas.width/2, canvas.height/2 - 10);
        ctx.fillText('Intenta recargar la página', canvas.width/2, canvas.height/2 + 10);
    }
}

// Función de respaldo básica
function crearGraficoTendenciaBasico(ctx, labels, data) {
    graficos.tendenciaMensual = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Ventas",
                data: data,
                backgroundColor: "rgba(54, 162, 235, 0.7)"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function showLoading(show) {
    const btn = document.getElementById("btnFiltrar");
    const overlay = document.querySelector('.loading-overlay');
    
    if (show) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cargando...';
        btn.disabled = true;
        overlay.style.display = 'flex';
    } else {
        btn.innerHTML = '🔍✨ Aplicar Filtro';
        btn.disabled = false;
        overlay.style.display = 'none';
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM cargado, inicializando gráficos...");
    cargarSemanaActual();
    cargarGraficos();
    
    document.getElementById("btnFiltrar").addEventListener("click", cargarGraficos);
});    


// Evento para reintentar gráfico de tendencia
document.getElementById("btnReintentarTendencia")?.addEventListener("click", function() {
    console.log("Reintentando gráfico de tendencia...");
    
    // Obtener datos actuales
    const inicio = document.getElementById("fechaInicio").value;
    const final = document.getElementById("fechaFinal").value;
    
    fetch("Configuracion/obtener_datos_graficos.php?inicio=" + inicio + "&final=" + final + "&t=" + Date.now())
        .then(response => response.json())
        .then(data => {
            if (data.tendenciaMensual) {
                crearGraficoTendenciaMensual(data.tendenciaMensual);
                document.getElementById("tendenciaError").style.display = "none";
            }
        })
        .catch(error => {
            console.error("Error al reintentar:", error);
        });
});

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM cargado, inicializando gráficos...");
    cargarSemanaActual();
    cargarGraficos();
    
    document.getElementById("btnFiltrar").addEventListener("click", cargarGraficos);
});


</script>







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
