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
    <title>Agregar Tipo de Pago</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="images/favicon.png">
    <link rel="shortcut icon" href="images/favicon.png">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
                        <div class="card">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-body">


                                    
<?php
require 'Conexion/conex.php';

$mesActual = date('m');
$anioActual = date('Y');

// Contar productos que vencen este mes
$sqlContador = "SELECT COUNT(*) AS total FROM productos 
                WHERE MONTH(fecha_vencimiento) = $mesActual 
                  AND YEAR(fecha_vencimiento) = $anioActual 
                  AND fecha_vencimiento IS NOT NULL";
$resContador = $conn->query($sqlContador);
$total_vencimientos = 0;

if ($resContador && $fila = $resContador->fetch_assoc()) {
    $total_vencimientos = $fila['total'];
}
?>

<?php if ($total_vencimientos > 0): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Atención:</strong> Tienes <?= $total_vencimientos ?> producto(s) que vencen este mes.
        <a href="#" class="alert-link" data-toggle="modal" data-target="#modalVencimientos">Ver detalles</a>
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>


<!-- Modal de productos por vencer -->
<div class="modal fade" id="modalVencimientos" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="tituloModal">Productos que vencen este mes</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        $sql_modal = "SELECT codigo, nombre, fecha_vencimiento 
                      FROM productos 
                      WHERE MONTH(fecha_vencimiento) = $mesActual 
                        AND YEAR(fecha_vencimiento) = $anioActual 
                      ORDER BY fecha_vencimiento ASC";
        $res_modal = $conn->query($sql_modal);
        if ($res_modal && $res_modal->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Fecha de Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $res_modal->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['codigo']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_vencimiento']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos que venzan este mes.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Requiere jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>





<?php
require 'Conexion/conex.php';

$mesActual = date('m');
$anioActual = date('Y');

// Contar productos que vencen este mes
$sqlContador = "SELECT COUNT(*) AS total FROM productos 
                WHERE MONTH(fecha_vencimiento) = $mesActual 
                  AND YEAR(fecha_vencimiento) = $anioActual 
                  AND fecha_vencimiento IS NOT NULL";
$resContador = $conn->query($sqlContador);
$total_vencimientos = 0;

if ($resContador && $fila = $resContador->fetch_assoc()) {
    $total_vencimientos = $fila['total'];
}
?>



<!-- Modal de productos por vencer -->
<div class="modal fade" id="modalVencimientos" tabindex="-1" role="dialog" aria-labelledby="tituloModal" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="tituloModal">Productos que vencen este mes</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
        $sql_modal = "SELECT codigo, nombre, fecha_vencimiento 
                      FROM productos 
                      WHERE MONTH(fecha_vencimiento) = $mesActual 
                        AND YEAR(fecha_vencimiento) = $anioActual 
                      ORDER BY fecha_vencimiento ASC";
        $res_modal = $conn->query($sql_modal);
        if ($res_modal && $res_modal->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Fecha de Vencimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $res_modal->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($fila['codigo']) ?></td>
                            <td><?= htmlspecialchars($fila['nombre']) ?></td>
                            <td><?= htmlspecialchars($fila['fecha_vencimiento']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay productos que venzan este mes.</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Requiere jQuery y Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



        <h1 class="text-center mb-4">💳 Registrar Tipo de Pago</h1>

        <!-- Formulario -->
        <form id="formTipoPago">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">
                            <span class="emoji-label">🏷️</span> Nombre del Tipo de Pago
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Efectivo, Tarjeta..." required>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label">
                            <span class="emoji-label">⚙️</span> Estado
                        </label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="activo">🟢 Activo</option>
                            <option value="inactivo">🔴 Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <span class="emoji-label">📝</span> Descripción
                        </label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="5" placeholder="Ej: Pago en efectivo, transferencia bancaria..."></textarea>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">
                    💾 Guardar
                </button>
            </div>
        </form>

        



<!-- Modal -->
<div class="modal fade" id="mensajeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📌 Mensaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center" id="mensajeModalBody">
        <!-- Aquí se mostrará el mensaje -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Tu formulario y modal (sin cambios) -->

<script>
document.getElementById('formTipoPago').addEventListener('submit', function(e){
    e.preventDefault(); // Evita recargar la página

    let formData = new FormData(this);

    fetch('Configuracion/procesar_tipopago.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const modalBody = document.getElementById('mensajeModalBody');
        if(data.success){
            modalBody.innerHTML = `<div class="text-success fs-5">${data.message}</div>`;
            document.getElementById('formTipoPago').reset();
        } else {
            modalBody.innerHTML = `<div class="text-danger fs-5">${data.message}</div>`;
        }

        // Mostrar modal
        const modalElement = document.getElementById('mensajeModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();

        // ⏳ Cerrar automáticamente después de 3 segundos
        setTimeout(() => {
            modal.hide(); // Usamos directamente hide() sobre la instancia creada
        }, 3000);
    })
    .catch(() => {
        const modalBody = document.getElementById('mensajeModalBody');
        modalBody.innerHTML = `<div class="text-danger fs-5">🚫 Error de conexión con el servidor.</div>`;
        const modal = new bootstrap.Modal(document.getElementById('mensajeModal'));
        modal.show();

        // ⏳ Cerrar automáticamente después de 3 segundos
        setTimeout(() => {
            modal.hide();
        }, 3000);
    });
});
</script>













                                    <hr>
<?php
include("Conexion/conex.php");

$tiposPago = [];

$sql = "SELECT * FROM `TipoPago` ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tiposPago[] = $row;
    }
}

$conn->close();
?>

<h3 class="mt-5">💳 Tipos de Pago Registrados</h3>

<table id="tablaTiposPago" class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th scope="col">🆔 ID</th>
            <th scope="col">🏷️ Nombre</th>
            <th scope="col">📝 Descripción</th>
            <th scope="col">⚙️ Estado</th>
            <th scope="col">📅 Fecha de Creación</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (count($tiposPago) > 0) {
            foreach ($tiposPago as $tipo) {
                $estadoEmoji = $tipo['estado'] === 'activo' ? '🟢' : '🔴';
                echo "<tr>";
                echo "<td>" . htmlspecialchars($tipo['id']) . "</td>";
                echo "<td>" . htmlspecialchars($tipo['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($tipo['descripcion']) . "</td>";
                echo "<td>" . $estadoEmoji . " " . htmlspecialchars($tipo['estado']) . "</td>";
                echo "<td>" . htmlspecialchars($tipo['fecha_creacion']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No hay tipos de pago registrados.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- DataTables CSS y JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tablaTiposPago').DataTable({
        "lengthMenu": [ [5, 15, 20, -1], [5, 15, 20, "Todos"] ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "No se encontraron registros",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "🔍 Buscar:",
            "paginate": {
                "first": "⏮️ Primera",
                "last": "⏭️ Última",
                "next": "➡️ Siguiente",
                "previous": "⬅️ Anterior"
            }
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
