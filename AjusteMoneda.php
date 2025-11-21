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
    <title>🪙 Ajuste de Moneda</title>
    <meta name="description" content="Ela Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

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
                    <a href="Proveedor.php"><i class="menu-icon fa fa-truck"></i>Proveedor</a>
                </li>
                <li>
                    <a href="Ventas.php"><i class="menu-icon fa fa-shopping-cart"></i>Vender</a>
                </li>
                <li>
                    <a href="Devolucion.php"><i class="menu-icon fa fa-rotate-left"></i>Devoluciones</a>
                </li>
                <li>
                    <a href="VerDevolucion.php"><i class="menu-icon fa fa-list-alt"></i>Ver Devolucion</a>
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
include("Conexion/conex.php");

$mensaje = "";
$metaRefresh = ""; // Para la redirección sin usar header()

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $simbolo = trim($_POST["simbolo"]);
    $tipo = $_POST["tipo"];
    $pais = trim($_POST["pais"]);
    $estado = $_POST["estado"];
    $valor = ($tipo === "extranjera") ? trim($_POST["valor"]) : "0";

    $sql = "INSERT INTO Moneda (nombre, simbolo, tipo, pais, estado, valor) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {

        $stmt->bind_param("sssssd", $nombre, $simbolo, $tipo, $pais, $estado, $valor);

        if ($stmt->execute()) {

            // MENSAJE EXITOSO
            $mensaje = '
            <div id="mensaje-alerta" class="alert alert-success mt-3">
                ✅ Moneda guardada exitosamente.
            </div>';

            // REDIRECCIÓN SIN USAR HEADER()
            $metaRefresh = '<meta http-equiv="refresh" content="2;url=AjusteMoneda.php">';
            
        } else {
            $mensaje = '
            <div id="mensaje-alerta" class="alert alert-danger mt-3">
                ❌ Error al guardar la moneda.
            </div>';
        }

        $stmt->close();

    } else {
        $mensaje = '
        <div id="mensaje-alerta" class="alert alert-warning mt-3">
            ⚠️ Error al preparar la consulta.
        </div>';
    }
}

$conn->close();
?>

<!-- Meta Refresh para la redirección -->
<?php echo $metaRefresh; ?>

<style>
    h1 { color:#2c3e50; font-weight:bold; }
    .emoji-label { font-size:1.2rem; margin-right:5px; }
    .btn-primary {
        background: linear-gradient(90deg,#007bff,#0056b3);
        border:none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg,#0056b3,#003d80);
    }
    select.form-select {
        height:52px;
        font-size:1rem;
        padding:0.5rem 1rem;
        border-radius:10px;
        border:1px solid #ced4da;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
        transition: all .2s ease-in-out;
    }
    select.form-select:focus {
        border-color:#007bff;
        box-shadow:0 0 5px rgba(0,123,255,0.4);
    }
</style>

<h1 class="text-center mb-4">🪙 Ajuste de Moneda</h1>
<?php echo $mensaje; ?>

<form action="" method="POST">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label"><span class="emoji-label">🏷️</span>Nombre de la Moneda</label>
            <input type="text" class="form-control" name="nombre" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><span class="emoji-label">💲</span>Símbolo</label>
            <input type="text" class="form-control" name="simbolo" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><span class="emoji-label">🌍</span>Tipo</label>
            <select class="form-select" id="tipo" name="tipo" onchange="mostrarValor()" required>
                <option value="nacional">🏠 Nacional</option>
                <option value="extranjera">✈️ Extranjera</option>
            </select>
        </div>

        <div class="col-md-6" id="campo-valor" style="display:none;">
            <label class="form-label"><span class="emoji-label">📈</span>Valor</label>
            <input type="number" class="form-control" name="valor" step="0.01" min="0">
        </div>

        <div class="col-md-6">
            <label class="form-label"><span class="emoji-label">🌎</span>País</label>
            <input type="text" class="form-control" name="pais" required>
        </div>

        <div class="col-md-6">
            <label class="form-label"><span class="emoji-label">✅</span>Estado</label>
            <select class="form-select" name="estado">
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>
        </div>

        <div class="col-md-6">
            <button class="btn btn-primary btn-lg px-5">💾 Guardar Moneda</button>
        </div>
    </div>
</form>




    <br><br>
<h2 class="text-center mb-3">📋 Lista de Monedas</h2>

<div class="card mb-4 shadow-sm">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <!-- Buscador -->
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">🔍</span>
                    <input type="text" id="busqueda" class="form-control border-start-0" placeholder="Buscar moneda por nombre o símbolo...">
                </div>
            </div>

            <!-- Selector de cantidad y botón Excel -->
            <div class="col-md-6 d-flex justify-content-md-end justify-content-start gap-2 flex-wrap">
                <div class="input-group w-auto">
                    <label class="input-group-text" for="por_pagina">Mostrar</label>
                    <select id="por_pagina" class="form-select">
                        <option value="5">5</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <button id="exportarExcel" class="btn btn-success d-flex align-items-center gap-1">
                    📥 Exportar a Excel
                </button>
            </div>
        </div>
    </div>
</div>




<div id="tabla-monedas">
    <!-- Aquí se cargará la tabla vía AJAX -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
// Función para exportar la tabla a Excel
$('#exportarExcel').click(function() {
    var tablaOriginal = document.querySelector('#tabla-monedas table');
    if (!tablaOriginal) {
        alert("No hay datos para exportar.");
        return;
    }

    // Clonar la tabla
    var tablaClon = tablaOriginal.cloneNode(true);

    // Función para quitar todos los emojis y caracteres no ASCII básicos
    function quitarEmojis(texto) {
        return texto.replace(/[^\x00-\x7F]/g, '').trim();
    }

    // Limpiar encabezados y celdas
    tablaClon.querySelectorAll('th, td').forEach(function(celda) {
        celda.textContent = quitarEmojis(celda.textContent);
    });

    // Convertir tabla limpia a Excel
    var wb = XLSX.utils.table_to_book(tablaClon, { sheet: "Monedas" });
    XLSX.writeFile(wb, "Lista_Monedas.xlsx");
});



</script>


<script>
function cargarMonedas(pagina = 1) {
    const por_pagina = $('#por_pagina').val();
    const busqueda = $('#busqueda').val();
    $.ajax({
        url: 'Configuracion/ver_monedas_ajax.php',
        type: 'GET',
        data: { pagina, por_pagina, busqueda },
        success: function(data) {
            $('#tabla-monedas').html(data);
        }
    });
}

$(document).ready(function() {
    // Cargar la tabla al iniciar
    cargarMonedas();

    // Cambiar cantidad por página
    $('#por_pagina').change(function() {
        cargarMonedas();
    });

    // Buscar mientras escribe
    $('#busqueda').on('input', function() {
        cargarMonedas();
    });

    // Delegar clics en paginación
    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        const pagina = $(this).data('pagina');
        cargarMonedas(pagina);
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
