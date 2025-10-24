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
    <title>Editar Productos existente</title>
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

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
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
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-body">
<?php
require 'Conexion/conex.php'; // Conexión a la base de datos

// Verificar si se recibió el ID del producto
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "⚠️ID de producto no proporcionado.";
    exit;
}

$id = $_GET['id'];

// Obtener los datos del producto
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "❌Producto no encontrado.";
    exit;
}

$producto = $resultado->fetch_assoc();

// Verificar si el producto está activo
if ($producto['estado'] !== 'activo') {
    echo "<div class='alert alert-warning' style='margin: 20px;'>
            ⚠️ Este producto está inactivo y no se puede editar.<br><br>
            <strong>Código:</strong> " . htmlspecialchars($producto['codigo']) . "<br>
            <strong>Nombre:</strong> " . htmlspecialchars($producto['nombre']) . "
          </div>";
    echo "<a href='VerProductos.php' class='btn btn-secondary' style='margin: 20px;'>↩️ Volver</a>";
    exit; // Detener la ejecución para que no se muestre el formulario
}
?>



                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                const compra = document.getElementById("compra");
                                                const existencia = document.getElementById("existencia");
                                                const iva = document.getElementById("iva");
                                                const resultadoFinal = document.getElementById("resultadoFinal");

                                                function calcular() {
                                                    const valorCompra = parseFloat(compra.value) || 0;
                                                    const valorExistencia = parseFloat(existencia.value) || 1; // evita división por cero
                                                    const valorIVA = parseFloat(iva.value) || 0;

                                                    const resultado1 = valorCompra / valorExistencia;
                                                    const resultado2 = resultado1 * (valorIVA / 100);
                                                    const resultado3 = resultado1 + resultado2;

                                                    resultadoFinal.textContent = "₡" + resultado3.toFixed(2);
                                                }

                                                compra.addEventListener("input", calcular);
                                                existencia.addEventListener("input", calcular);
                                                iva.addEventListener("input", calcular);
                                            });
                                        </script>

                                            

<h2 class="mb-4">✏️ Editar Producto</h2>

<form action="./Configuracion/guardar_editar_producto.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

    <!-- Código y Nombre -->
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="codigo" class="form-label">📦 Código</label>
            <input type="text" class="form-control" id="codigo" name="codigo" 
                   value="<?php echo htmlspecialchars($producto['codigo']); ?>" readonly>
        </div>
        <div class="col-md-8">
            <label for="nombre" class="form-label">📝 Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" 
                   value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
        </div>
    </div>

    <!-- Compra y Venta -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="compra" class="form-label">💰 Precio de Compra</label>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" step="0.01" class="form-control" id="compra" name="compra" 
                       value="<?php echo htmlspecialchars($producto['compra']); ?>" required>
            </div>
            <small class="form-text text-muted">
                📊 Unitario con IVA: <strong id="resultadoFinal">₡0.00</strong>
            </small>
        </div>
        <div class="col-md-6">
            <label for="venta" class="form-label">🏷️ Precio de Venta</label>
            <div class="input-group">
                <span class="input-group-text">₡</span>
                <input type="number" step="0.01" class="form-control" id="venta" name="venta" 
                       value="<?php echo htmlspecialchars($producto['venta']); ?>" required>
            </div>
        </div>
    </div>

    <!-- IVA y Existencias -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="iva" class="form-label">⚖️ IVA (%)</label>
            <input type="number" step="0.01" class="form-control" id="iva" name="iva" 
                   value="<?php echo htmlspecialchars($producto['iva']); ?>" required>
        </div>
        <div class="col-md-6">
            <label for="existencia" class="form-label">📊 Existencias</label>
            <input type="number" class="form-control" id="existencia" name="existencia" 
                   value="<?php echo htmlspecialchars($producto['existencia']); ?>" required>
        </div>
    </div>

    <?php
        include("Conexion/conex.php");

        // Consultas para cada tabla
        $sqlMoneda = "SELECT id, nombre, simbolo FROM Moneda WHERE estado = 'activo'";
        $sqlUnidadPeso = "SELECT id, nombre, simbolo FROM UnidadPeso WHERE estado = 'activo'";
        $sqlTipoPago = "SELECT id, nombre FROM TipoPago";
        $sqlImpuesto = "SELECT id, nombre, porcentaje FROM Impuesto WHERE estado = 'Activo'";

        $monedas = $conn->query($sqlMoneda);
        $unidades = $conn->query($sqlUnidadPeso);
        $tiposPago = $conn->query($sqlTipoPago);
        $impuestos = $conn->query($sqlImpuesto);
    ?>
    <div class="row mb-3">
        <div class="col-md-6">
            <!-- Moneda -->
            <label for="moneda" class="form-label">💱 Moneda</label>
            <select class="form-select select2" id="moneda" name="moneda" required>
                <option value="">-- Selecciona Moneda --</option>
                <?php 
                $sqlMoneda = "SELECT id, nombre, simbolo, tipo FROM Moneda WHERE estado = 'activo'";
                $monedas = $conn->query($sqlMoneda);
                while($row = $monedas->fetch_assoc()): 
                    // Si el ID de la moneda coincide con el del producto, marcar como seleccionado
                    $selected = ($row['id'] == $producto['idMoneda']) ? "selected" : "";
                ?>
                    <option value="<?= $row['id'] ?>" <?= $selected ?>>
                        <?= $row['nombre'] ?> (<?= $row['simbolo'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-6">
            <!-- Unidad de Peso -->
            <label for="unidad" class="form-label">⚖️ Unidad de Peso</label>
            <select class="form-select select2" id="unidad" name="unidad" required>
                <option value="">-- Selecciona Unidad --</option>
                <?php 
                $sqlUnidadPeso = "SELECT id, nombre, simbolo FROM UnidadPeso WHERE estado = 'activo'";
                $unidades = $conn->query($sqlUnidadPeso);
                while($row = $unidades->fetch_assoc()):
                    $selected = ($row['id'] == $producto['id_UnidadPeso']) ? "selected" : "";
                ?>
                    <option value="<?= $row['id'] ?>" <?= $selected ?>>
                        <?= $row['nombre'] ?> (<?= $row['simbolo'] ?>)
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
                        // Inicializar Select2 en todos los selects con class="select2"
                        $('.select2').select2({
                            placeholder: "Selecciona una opción",
                            allowClear: true,
                            width: '100%'
                        });
                    });
        </script>


    </div>
    <!-- Vencimiento -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="tiene_vencimiento" class="form-label">⏳ ¿Tiene vencimiento?</label>
            <input 
                type="checkbox" 
                id="tiene_vencimiento" 
                name="tiene_vencimiento" 
                <?php echo !empty($producto['fecha_vencimiento']) ? 'checked' : ''; ?>>
        </div>
        <div class="col-md-6" id="fecha_vencimiento_div" 
            style="<?php echo !empty($producto['fecha_vencimiento']) ? '' : 'display:none;'; ?>">
            <label for="fecha_vencimiento" class="form-label">📅 Fecha de Vencimiento</label>
            <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" 
                value="<?php echo $producto['fecha_vencimiento']; ?>">
        </div>
    </div>

    <!-- Botones -->
    <div class="mt-4 text-end">
        <button type="submit" class="btn btn-success">💾 Guardar</button>
        <a href="VerProductos.php" class="btn btn-secondary">↩️ Cancelar</a>
    </div>
</form>


                                            
<script>
    // Mostrar u ocultar el campo de fecha según el checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const check = document.getElementById('tiene_vencimiento');
        const divFecha = document.getElementById('fecha_vencimiento_div');
        const campoFecha = document.getElementById('fecha_vencimiento');

        function toggleFecha() {
            if (check.checked) {
                divFecha.style.display = 'block';
                campoFecha.required = true;
            } else {
                divFecha.style.display = 'none';
                campoFecha.value = '';
                campoFecha.required = false;
            }
        }

        check.addEventListener('change', toggleFecha);
        toggleFecha();
    });
</script>




                                    <?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'actualizado') {
    echo "<div id='actualizado' class='alert alert-success' style='position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 10px; border-radius: 5px;'>Producto actualizado correctamente.</div>";
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var mensaje = document.getElementById('actualizado');
        if (mensaje) {
            setTimeout(function() {
                mensaje.style.display = 'none';
            }, 5000); // 5 segundos
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
