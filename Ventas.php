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
    <title>Ventas</title>
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                        <div class="card">
                            <div class="row">
                                <div class="col-lg-11">
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




<br>
<h1 class="mb-4">🛒 Realizar Ventas</h1>

<div class="container mt-4">
    <div class="row">
        <!-- Columna izquierda: Cliente -->
        <div class="col-md-4">
            <!-- Información del cliente -->
            <div id="infoCliente" class="card p-3 shadow-sm">


                    <!-- Barra de búsqueda para escaneo -->
                <label for="descuentoCliente" class="form-label mt-2">💸<strong>Escanea el codigo del producto:</strong></label>
                <input type="text" id="buscadorVenta" class="form-control" placeholder="Escanea el código de producto..." autofocus>
                <br>
                <div id="mensajeVenta" class="alert d-none mt-3" role="alert"></div>
                <div id="mensajeError" class="alert d-none mt-3" role="alert"></div>


                <?php
                include("Conexion/conex.php");

                // Consultas para Moneda y Tipo de Pago
                $sqlCliente = "SELECT id, nombre FROM Clientes"; 
                $sqlMoneda = "SELECT id, nombre, simbolo, tipo FROM Moneda WHERE estado = 'activo'";
                $sqlTipoPago = "SELECT id, nombre FROM TipoPago";

                $clientes = $conn->query($sqlCliente);
                $monedas = $conn->query($sqlMoneda);
                $tiposPago = $conn->query($sqlTipoPago);
                ?>

                <!-- Selector de clientes -->
                <div class="mb-3">
                    <label for="clienteSeleccionado" class="form-label">👤 Cliente</label>
                    <select class="form-select select2" id="clienteSeleccionado" name="clienteSeleccionado" required>
                        <option value="">-- Selecciona Cliente --</option>
                        <?php while($row = $clientes->fetch_assoc()): ?>
                            <option value="<?= $row['id'] ?>">
                                <?= htmlspecialchars($row['nombre'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="descuentoCliente" class="form-label">💸 Descuento del Cliente (%)</label>
                    <input type="number" id="descuentoCliente" name="descuentoCliente"
                        class="form-control form-control-sm" placeholder="Descuento %" value="0" min="0" max="100">
                </div>

                <!-- Campo oculto para guardar el ID del cliente -->
                <input type="hidden" id="inputClienteId" name="inputClienteId">


                <!-- Moneda -->
                <div class="mb-3">
                    <label for="moneda" class="form-label">Moneda</label>
                    <select class="form-select select2" id="moneda" name="moneda" required>
                        <option value="">-- Selecciona Moneda --</option>
                        <?php 
                        while($row = $monedas->fetch_assoc()): 
                            $selected = ($row['tipo'] === 'nacional') ? "selected" : "";
                        ?>
                            <option value="<?= $row['id'] ?>" <?= $selected ?>>
                                <?= $row['nombre'] ?> (<?= $row['simbolo'] ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Tipo de Pago -->
                <div class="mb-3">
                    <label for="tipoPago" class="form-label">Tipo de Pago</label>
                    <select class="form-select select2" id="tipoPago" name="tipoPago" required>
                        <option value="">-- Selecciona Tipo de Pago --</option>
                        <?php 
                        while($row = $tiposPago->fetch_assoc()): 
                            $selected = (strtoupper($row['nombre']) === 'EFECTIVO') ? "selected" : "";
                        ?>
                            <option value="<?= $row['id'] ?>" <?= $selected ?>>
                                <?= $row['nombre'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializa Select2 (si no lo hiciste ya)
    $('#clienteSeleccionado').select2({
        placeholder: "Selecciona un cliente",
        allowClear: true,
        width: '100%'
    });
                    $(document).ready(function() {
                        // Inicializar Select2 en todos los selects con class="select2"
                        $('.select2').select2({
                            placeholder: "Selecciona una opción",
                            allowClear: true,
                            width: '100%'
                        });
                    });
    // Cargar clientes al iniciar
    cargarClientes();

    // Si tienes un input para buscar clientes con id="buscarCliente" lo usaremos;
    // si no existe, evitamos errores.
    if (document.getElementById('buscarCliente')) {
        document.getElementById('buscarCliente').addEventListener('input', function() {
            let q = this.value.trim();
            cargarClientes(q);
        });
    }

    // Manejar cambio de cliente
    $('#clienteSeleccionado').on('change', function() {
        let clienteId = $(this).val();
        if (clienteId) {
            mostrarInfoCliente(clienteId);
        } else {
            // Si se limpia selección, dejar 0
            $('#descuentoCliente').val(0);
            $('#inputClienteId').val('');
            actualizarTabla();
        }
    });
});
</script>





            </div>
        </div>

        
        <!-- Columna derecha: Resumen o futura tabla -->


        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                                 



    <!-- Tabla de productos seleccionados -->
    <h3>📊Productos seleccionados</h3>
    <div class="table-responsive">
    <table class="table table-dark table-bordered text-center align-middle w-100" style="table-layout: fixed; word-wrap: break-word;">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Unidad</th>
                <th>Precio Venta</th>
                <th>Cantidad</th>
                <th>Total Individual</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="productosSeleccionados"></tbody>
    </table>
</div>
    <!-- Botón para realizar la venta -->
    <button id="btnRealizarVenta" class="btn btn-success">Realizar Venta</button>

    <script>
        let productosSeleccionados = [];

        // Función para agregar productos escaneados
        function agregarProducto(id, codigo, nombre, precio, nombre_UnidadPeso) {
            let productoExistente = productosSeleccionados.find(p => p.id === id);

            if (productoExistente) {
                productoExistente.cantidad++;
            } else {
                productosSeleccionados.push({
                    id: id,
                    codigo: codigo,
                    nombre: nombre,
                    precio: parseFloat(precio),
                    cantidad: 1,                    
                    nombre_UnidadPeso: nombre_UnidadPeso  // <--- Agregar esto
                });
            }

            actualizarTabla();
        }

        // Función para eliminar producto de la lista
        function eliminarProducto(id) {
            productosSeleccionados = productosSeleccionados.filter(p => p.id !== id);
            actualizarTabla();
        }

        // Actualiza la tabla de productos seleccionados
        function actualizarTabla() {
    let tabla = document.getElementById("productosSeleccionados");
    tabla.innerHTML = "";

    let totalCantidad = 0;
    let subtotal = 0;

    productosSeleccionados.forEach(producto => {
        let totalProducto = producto.precio * producto.cantidad;
        totalCantidad += producto.cantidad;
        subtotal += totalProducto;

        let fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${producto.codigo}</td>
            <td>${producto.nombre}</td>
            <td>${producto.nombre_UnidadPeso || ''}</td>
            <td>$${producto.precio.toFixed(2)}</td>
            <td>
                <input type="number" class="form-control form-control-sm" value="${producto.cantidad}" min="1"
                    onchange="cambiarCantidad(${producto.id}, this.value)">
            </td>
            <td>$${totalProducto.toFixed(2)}</td>
            <td><button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">X</button></td>
        `;
        tabla.appendChild(fila);
    });

    if (productosSeleccionados.length > 0) {
        // Obtener el descuento (puede venir como texto, lo convertimos a número)
        let descuentoPorcentaje = parseFloat(document.getElementById("descuentoCliente").value) || 0;

        // Calcular descuento y total con descuento
        let montoDescuento = (subtotal * descuentoPorcentaje) / 100;
        let totalConDescuento = subtotal - montoDescuento;


// Guardar valor previo del input si existe
const montoClienteInput = document.getElementById("montoCliente");
const montoClienteValor = montoClienteInput ? montoClienteInput.value : '';

// Mostrar totales (regenerar tabla)
tabla.innerHTML += `
    <tr>
        <td colspan="3" style="text-align: left;"><strong>Subtotal</strong></td>
        <td><strong>${totalCantidad}</strong></td>
        <td><strong>$${subtotal.toFixed(2)}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;"><strong>Descuento (${descuentoPorcentaje}%)</strong></td>
        <td><strong>$${montoDescuento.toFixed(2)}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;"><strong>Total con Descuento</strong></td>
        <td><strong id="totalDescuento">$${totalConDescuento.toFixed(2)}</strong></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: left;"><strong>Monto Pagado Cliente</strong></td>
        <td colspan="2"><input type="number" id="montoCliente" oninput="calcularVuelto()" class="form-control form-control-sm" min="1" placeholder="Monto"/></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Vuelto</strong></td>
        <td colspan="2"><strong id="vueltoCliente">$0.00</strong></td>
    </tr>
`;

// Restaurar el valor del input si existía
document.getElementById("montoCliente").value = montoClienteValor;

// Calcular vuelto con el valor restaurado
calcularVuelto();


    }
}


function calcularVuelto() {
    const monto = parseFloat(document.getElementById("montoCliente").value) || 0;
    const totalTexto = document.getElementById("totalDescuento").textContent.replace('$', '');
    const total = parseFloat(totalTexto) || 0;

    const vuelto = monto - total;
    const vueltoFormateado = vuelto >= 0 ? `$${vuelto.toFixed(2)}` : `$0.00`;

    document.getElementById("vueltoCliente").textContent = vueltoFormateado;
}



        function cambiarCantidad(id, nuevaCantidad) {
    nuevaCantidad = parseInt(nuevaCantidad);
    if (!isNaN(nuevaCantidad) && nuevaCantidad > 0) {
        let producto = productosSeleccionados.find(p => p.id === id);
        if (producto) {
            producto.cantidad = nuevaCantidad;
            actualizarTabla(); // Opcional: si quieres refrescar la tabla
        }
    } else {
        mostrarMensajeError("La cantidad debe ser un número válido mayor que cero.");
    }
}


        // Captura el código escaneado y busca el producto
        document.getElementById("buscadorVenta").addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                let codigo = this.value.trim();
                if (codigo !== "") {
                    buscarProducto(codigo);
                    this.value = "";
                }
            }
        });

        function buscarProducto(codigo) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "Configuracion/buscar_producto.php?codigo=" + codigo, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let producto = JSON.parse(xhr.responseText);
                    console.log(producto); // <--- Verifica que tenga "nombre_UnidadPeso"
                    
                    if (producto) {
                        if (producto.error) {
                            mostrarMensajeError(producto.error);
                        } else {
                            agregarProducto(
                                producto.id,
                                producto.codigo,
                                producto.nombre,
                                producto.venta,
                                producto.nombre_UnidadPeso
                            );
                        }
                    } else {
                        mostrarMensajeError("Producto no encontrado.");
                    }
                }
            };
            xhr.send();
        }

        // Función para mostrar el mensaje en el div "mensajeError"
        function mostrarMensajeError(mensaje) {
            let mensajeError = document.getElementById("mensajeError");
            mensajeError.className = "alert alert-danger mt-3"; // Estilo de alerta de error
            mensajeError.innerHTML = mensaje;
            mensajeError.classList.remove("d-none"); // Muestra el mensaje

            // Oculta el mensaje después de 2 segundos
            setTimeout(() => {
                mensajeError.classList.add("d-none");
            }, 1000);
        }



document.getElementById("btnRealizarVenta").addEventListener("click", function() {
    if (productosSeleccionados.length > 0) {
        const clienteId = document.getElementById("inputClienteId").value;

        if (clienteId) {
            const montoPagadoInput = document.getElementById("montoCliente");
            const montoPagado = parseFloat(montoPagadoInput.value) || 0;
            const vuelto = parseFloat(document.getElementById("vueltoCliente").textContent.replace('$', '')) || 0;

            // 🚫 Validar que el campo de monto pagado no esté vacío ni sea 0
            if (montoPagado <= 0 || montoPagadoInput.value.trim() === "") {
                mostrarMensaje("Por favor, ingrese el monto pagado por el cliente.", "warning");
                montoPagadoInput.focus();
                return; // Detener el proceso de guardado
            }

            // Si todo está correcto, procesar la venta
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Configuracion/procesar_venta.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var respuesta = JSON.parse(xhr.responseText);

                    if (respuesta.status === "success") {
                        mostrarMensaje(respuesta.message, "success");
                        // Aquí ya no borramos la tabla, solo el mensaje
                    } else {
                        mostrarMensaje(respuesta.message, "error");
                    }
                }
            };

            const datosVenta = {
                productos: productosSeleccionados,
                clienteId: clienteId,
                descuento: parseFloat(document.getElementById("descuentoCliente").value) || 0,
                monto_pagado_cliente: montoPagado,
                monto_devuelto: vuelto
            };

            xhr.send(JSON.stringify(datosVenta));
        } else {
            mostrarMensaje("Por favor, seleccione un cliente.", "warning");
        }
    } else {
        mostrarMensaje("Por favor, seleccione al menos un producto.", "warning");
    }
});


        
        // Función para mostrar mensajes en la página y limpiar la pantalla después de 3 segundos

        // Función para mostrar mensajes en la página sin borrar la tabla
        function mostrarMensaje(mensaje, tipo) {
            let mensajeVenta = document.getElementById("mensajeVenta");
            let icono = "";

            switch (tipo) {
                case "success":
                    icono = "✅ ";
                    break;
                case "error":
                    icono = "❌ ";
                    break;
                case "warning":
                    icono = "⚠️ ";
                    break;
            }

            mensajeVenta.className = `alert alert-${tipo} mt-3`; 
            mensajeVenta.innerHTML = icono + mensaje;
            mensajeVenta.classList.remove("d-none");

            // Mantiene la tabla visible, solo oculta el mensaje después de 3 segundos
            setTimeout(() => {
                mensajeVenta.classList.add("d-none");
            }, 3000);
        }

    </script>



            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById("descuentoCliente").addEventListener("input", function () {
        actualizarTabla();
    });
</script>


<script>
// Función para cargar clientes en el select
function cargarClientes(query = "") {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "Configuracion/obtener_clientes.php?query=" + query, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            let clientes = JSON.parse(xhr.responseText);
            let select = document.getElementById("clienteSeleccionado");
            select.innerHTML = "<option value=''>Seleccione un cliente</option>"; // Limpiar el select antes de agregar opciones

            // Verificar que los clientes sean correctos
            if (clientes.length > 0) {
                clientes.forEach(cliente => {
                    let option = document.createElement("option");
                    option.value = cliente.id;
                    option.textContent = cliente.nombre; // Nombre del cliente
                    select.appendChild(option);
                });
            } else {
                let option = document.createElement("option");
                option.value = "";
                option.textContent = "No se encontraron clientes";
                select.appendChild(option);
            }
        }
    };
    xhr.send();
}

// Llamar la función cuando cargue la página
window.onload = function() {
    cargarClientes(); // Cargar todos los clientes al inicio

    // Filtrar clientes mientras escribes
    document.getElementById("buscarCliente").addEventListener("input", function() {
        let query = this.value.trim();
        cargarClientes(query);
    });
};

// Mostrar el ID y descuento del cliente seleccionado
document.getElementById("clienteSeleccionado").addEventListener("change", function() {
    let clienteId = this.value;
    if (clienteId) {
        mostrarInfoCliente(clienteId);
    } else {
        document.getElementById("clienteId").textContent = "";
        document.getElementById("descuentoCliente").value = "0";
    }
});



// Función para obtener y mostrar la información del cliente seleccionado
function mostrarInfoCliente(clienteId) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "Configuracion/obtener_info_cliente.php?id=" + encodeURIComponent(clienteId), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            // Log para depuración (puedes quitar luego)
            console.log("Respuesta obtener_info_cliente.php:", xhr.status, xhr.responseText);

            if (xhr.status === 200) {
                try {
                    let cliente = JSON.parse(xhr.responseText);
                    if (cliente) {
                        $('#descuentoCliente').val(cliente.descuento !== undefined ? cliente.descuento : 0);
                        $('#inputClienteId').val(cliente.id);
                        actualizarTabla();
                    } else {
                        // No existe cliente
                        $('#descuentoCliente').val(0);
                        $('#inputClienteId').val('');
                        actualizarTabla();
                    }
                } catch (e) {
                    console.error("Error al parsear JSON de cliente:", e);
                }
            } else {
                console.error("Error en la petición de cliente. Status:", xhr.status);
            }
        }
    };
    xhr.send();
}









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