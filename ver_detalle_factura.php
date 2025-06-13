<?php
session_start();

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION["usuario"])) {
    header("Location: page-login.php");
    exit();
}

// Obtener datos de la sesión
$usuario = $_SESSION["usuario"];
$id_usuario = $_SESSION["id"];
?>


<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ela Admin - HTML5 Admin Template</title>
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
                <li class="menu-item-has-children dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-shopping-cart"></i>Vender
                    </a>
                    <ul class="sub-menu children dropdown-menu">
                        <li><i class="menu-icon fa fa-line-chart"></i><a href="Ventas.php">Vender</a></li>
                    </ul>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="javascript/html2pdf.js"></script>


<?php
include 'Conexion/conex.php';

$numeroFactura = $_GET['id'];

$sql_factura = "SELECT 
    v.id, 
    v.fecha, 
    v.numeroFactura, 
    v.total, 
    v.descuento AS venta_descuento,
    v.monto_pagado_cliente,
    v.monto_devuelto,
    u.nombre AS usuario_nombre, 
    u.telefono AS usuario_telefono,
    u.direccion AS usuario_direccion,
    u.email AS usuario_email,
    c.nombre AS cliente_nombre,
    c.direccion AS cliente_direccion,
    c.telefono AS cliente_telefono
    
FROM ventas v
LEFT JOIN usuarios u ON v.idUsuario = u.id
LEFT JOIN clientes c ON v.idCliente = c.id
WHERE v.numeroFactura = '$numeroFactura'";

$result_factura = $conn->query($sql_factura);

if ($result_factura->num_rows > 0) {
    $factura = $result_factura->fetch_assoc();

    $sql_productos = "SELECT 
        pv.cantidad, 
        pv.precio, 
        p.codigo,
        p.nombre AS producto_nombre
    FROM productos_ventas pv
    LEFT JOIN productos p ON pv.idProducto = p.id
    WHERE pv.numeroFactura = '$numeroFactura'";
    
    $result_productos = $conn->query($sql_productos);
} else {
    die("Factura no encontrada.");
}
?>


<style>
    .factura {
        font-family: Arial, sans-serif;
        font-size: 14px;
        margin: 20px;
    }
    .separador {
        border-top: 2px solid #000;
        margin: 15px 0;
    }
    .factura h2 {
        text-align: center;
    }
    .tabla-productos th, .tabla-productos td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
    }
    .tabla-productos {
        border-collapse: collapse;
        width: 100%;
        margin-top: 15px;
    }
</style>

<div class="factura" id="factura-content">
    <h2>Factura Electrónica N.º <?= $factura['numeroFactura'] ?></h2>
    
    <p><strong>Nombre del Cliente:</strong> <?= $factura['cliente_nombre'] ?></p>
    <p><strong>Ident. Dimex:</strong> 1558</p>

    <div class="separador"></div>

    <table style="width:100%;">
        <tr>
            <td><strong>Dirección:</strong> <?= $factura['cliente_direccion'] ?></td>
            <td><strong>Clave Numérica:</strong> 1558</td>
        </tr>
        <tr>
            <td><strong>Fecha de Emisión:</strong> <?= date("d-m-Y H:i", strtotime($factura['fecha'])) ?></td>
            <td><strong>Teléfono:</strong> <?= $factura['cliente_telefono'] ?></td>
        </tr>
        <tr>
            <td><strong>Condición de venta:</strong> Contado</td>
            <td><strong>Correo:</strong> prueba@gmail.com</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Medio de pago:</strong> Efectivo</td>
        </tr>
    </table>

    <div class="separador"></div>

    <style>
        .info-receptor {
            display: grid;
            grid-template-columns: 1fr 1fr; /* dos columnas iguales */
            gap: 10px 40px; /* espacio entre filas y columnas */
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .info-receptor .full-row {
            grid-column: 1 / -1; /* que ocupe toda la fila */
            font-weight: bold;
            margin-bottom: 10px;
        }
        .info-receptor p {
            margin: 4px 0;
        }
    </style>

    <div class="info-receptor">
    <p class="full-row"><strong>Receptor:</strong> VERDULERIA Y FRUTAS - CAMPO FERTIL</p>
    <p><strong>Ident. Jurídica:</strong> [Especificar]</p>
    <p><strong>Código Interno:</strong> 1</p>
    <p><strong>Teléfono:</strong> (+506)6244-9726</p>
    <p><strong>Correo:</strong> CampoFertil@gmail.com</p>
    <p><strong>Destinatario:</strong> Asef G 1 y 2</p>
    <p><strong>Dirección:</strong> campo fertil</p>
    </div>

    <div class="separador"></div>

    <!-- TABLA DE PRODUCTOS -->
    <table class="tabla-productos">
        <thead>
            <tr>
                <th>Código</th>
                <th>Cantidad</th>
                <th>Descripción</th>
                <th>Precio Unitario</th>
                <th>Descuento</th>
                <th>Subtotal</th>
                <th>IVA</th>
                <th>Otros Imp.</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_productos->num_rows > 0) {
                while ($prod = $result_productos->fetch_assoc()) {
                    $subtotal = $prod['cantidad'] * $prod['precio'];
                    $descuento = $factura['venta_descuento'];
                    echo "<tr>
                        <td>{$prod['codigo']}</td>
                        <td>{$prod['cantidad']}</td>
                        <td>{$prod['producto_nombre']}</td>
                        <td>$" . number_format($prod['precio'], 2) . "</td>
                        <td>{$descuento}%</td>
                        <td>$" . number_format($subtotal, 2) . "</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No hay productos</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="separador"></div>

    <!-- RESUMEN -->
    <table style="width: 100%; margin-top: 15px;">
        <tr>
            <td><strong>Subtotal Neto:</strong></td>
            <td>$<?= number_format($factura['total'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>Descuento:</strong></td>
            <td><?= number_format($factura['venta_descuento'], 2) ?>%</td>
        </tr>
        <tr>
            <td><strong>Monto devuelto:</strong></td>
            <td>$<?= number_format($factura['monto_devuelto'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>Monto pagado cliente:</strong></td>
            <td>$<?= number_format($factura['monto_pagado_cliente'], 2) ?></td>
        </tr>
        <tr>
            <td><strong>Total Factura:</strong></td>
            <td><strong>$<?= number_format($factura['total'], 2) ?></strong></td>
        </tr>
    </table>
</div>


<style>
    .btn-pdf {
        background-color: #28a745;
        color: white;
        font-size: 16px;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }
</style>

        <!-- Botón para Descargar PDF -->
<div class="text-center mt-4">
    <button class="btn btn-pdf btn-lg" onclick="descargarFactura()">Descargar PDF</button>
    <button class="btn btn-imprimir btn-lg" onclick="imprimirFactura()">Imprimir</button>
</div>
<style>
    .btn-imprimir {
        background-color: #007bff; /* Azul */
        color: white;
        font-size: 16px;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }
</style>

<script>
function descargarFactura() {
    const elemento = document.getElementById('factura-content');

    const opciones = {
        margin: [0.3, 0.3, 0.3, 0.3], // top, left, bottom, right (en pulgadas)
        filename: 'Factura_<?= $factura['numeroFactura'] ?>.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: {
            scale: 2,
            useCORS: true,
            scrollY: 0
        },
        jsPDF: {
            unit: 'in',
            format: 'letter',
            orientation: 'portrait'
        }
    };

    html2pdf().set(opciones).from(elemento).save();
}

function imprimirFactura() {
    const contenido = document.getElementById('factura-content').innerHTML;
    const ventana = window.open('', '_blank');
    ventana.document.write(`
        <html>
        <head>
            <title>Factura <?= $factura['numeroFactura'] ?></title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 14px; }
                .tabla-productos { border-collapse: collapse; width: 100%; }
                .tabla-productos th, .tabla-productos td {
                    border: 1px solid #000;
                    padding: 5px;
                    text-align: center;
                }
                .separador { border-top: 2px solid #000; margin: 15px 0; }
            </style>
        </head>
        <body onload="window.print(); setTimeout(() => window.close(), 100);">
            ${contenido}
        </body>
        </html>
    `);
    ventana.document.close();
}
</script>







    <div class="mt-4">
        <a href="ver_facturas.php" class="btn btn-secondary">Volver al Listado</a>
        <a href="Ventas.php" class="btn btn-warning">Volver a Ventas</a>
    </div>

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
