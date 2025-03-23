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
                        <li><i class="menu-icon fa fa-map-o"></i><a href="VerReportes.php">Visualizar Reportes</a></li>
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
                        <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
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

                                        // Ocultar el mensaje después de 3 segundos
                                        setTimeout(function() {
                                            var mensaje = document.getElementById("mensaje-alerta");
                                            if (mensaje) {
                                                mensaje.style.display = "none";
                                            }
                                        }, 3000);
                                        </script>



                                        <?php
                                        include("Conexion/conex.php");

                                        $mensaje = ""; // Inicializar mensaje
                                        $impuestoEditado = null; // Para almacenar un impuesto editado

                                        // Verificar si el formulario ha sido enviado para insertar o actualizar
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                            // Obtener los valores del formulario
                                            $nombre = trim($_POST["nombre"]);
                                            $porcentaje = $_POST["porcentaje"];
                                            $descripcion = trim($_POST["descripcion"]);
                                            $tipo_impuesto = $_POST["tipo_impuesto"];
                                            $estado = $_POST["estado"];
                                            $id = isset($_POST["id"]) ? $_POST["id"] : null;

                                            if ($id) {
                                                // Actualizar el impuesto
                                                $sql = "UPDATE Impuesto SET nombre = ?, porcentaje = ?, descripcion = ?, tipo_impuesto = ?, estado = ? WHERE id = ?";
                                                $stmt = $conn->prepare($sql);
                                                if ($stmt) {
                                                    $stmt->bind_param("sssssi", $nombre, $porcentaje, $descripcion, $tipo_impuesto, $estado, $id);
                                                    if ($stmt->execute()) {
                                                        $mensaje = '<div id="mensaje-alerta" class="alert alert-success mt-3">✅ Impuesto actualizado exitosamente.</div>';
                                                    } else {
                                                        $mensaje = '<div id="mensaje-alerta" class="alert alert-danger mt-3">❌ Error al actualizar el impuesto.</div>';
                                                    }
                                                    $stmt->close();
                                                }
                                            } else {
                                                // Insertar un nuevo impuesto
                                                $sql = "INSERT INTO Impuesto (nombre, porcentaje, descripcion, tipo_impuesto, estado) VALUES (?, ?, ?, ?, ?)";
                                                $stmt = $conn->prepare($sql);
                                                if ($stmt) {
                                                    $stmt->bind_param("sssss", $nombre, $porcentaje, $descripcion, $tipo_impuesto, $estado);
                                                    if ($stmt->execute()) {
                                                        $mensaje = '<div id="mensaje-alerta" class="alert alert-success mt-3">✅ Impuesto guardado exitosamente.</div>';
                                                    } else {
                                                        $mensaje = '<div id="mensaje-alerta" class="alert alert-danger mt-3">❌ Error al guardar el impuesto.</div>';
                                                    }
                                                    $stmt->close();
                                                }
                                            }
                                        }

                                        // Obtener los impuestos registrados
                                        $sql = "SELECT * FROM Impuesto";
                                        $result = $conn->query($sql);
                                        $impuestos = [];

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $impuestos[] = $row;
                                            }
                                        }

                                        // Obtener el impuesto para editar (si existe)
                                        if (isset($_GET['edit'])) {
                                            $id = $_GET['edit'];
                                            $sql = "SELECT * FROM Impuesto WHERE id = ?";
                                            $stmt = $conn->prepare($sql);
                                            if ($stmt) {
                                                $stmt->bind_param("i", $id);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                $impuestoEditado = $result->fetch_assoc();
                                                $stmt->close();
                                            }
                                        }

                                        $conn->close();
                                        ?>


                                        <!-- Formulario para ingresar o editar el impuesto -->
                                        <form action="" method="POST">
                                            <?php if ($impuestoEditado): ?>
                                                <input type="hidden" name="id" value="<?php echo $impuestoEditado['id']; ?>">
                                                <h3>Editar Impuesto</h3>
                                            <?php else: ?>
                                                <h3>Nuevo Impuesto</h3>
                                            <?php endif; ?>

                                        <!-- Mostrar mensaje si existe -->
                                        <?php echo $mensaje; ?>

                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre del Impuesto</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['nombre']) : ''; ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="porcentaje" class="form-label">Porcentaje</label>
                                                <input type="number" step="0.01" class="form-control" id="porcentaje" name="porcentaje" value="<?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['porcentaje']) : ''; ?>" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción</label>
                                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['descripcion']) : ''; ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tipo_impuesto" class="form-label">Tipo de Impuesto</label>
                                                <select class="form-control" id="tipo_impuesto" name="tipo_impuesto">
                                                    <option value="fijo" <?php echo $impuestoEditado && $impuestoEditado['tipo_impuesto'] == 'fijo' ? 'selected' : ''; ?>>Fijo</option>
                                                    <option value="porcentaje" <?php echo $impuestoEditado && $impuestoEditado['tipo_impuesto'] == 'porcentaje' ? 'selected' : ''; ?>>Porcentaje</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="estado" class="form-label">Estado</label>
                                                <select class="form-control" id="estado" name="estado">
                                                    <option value="activo" <?php echo $impuestoEditado && $impuestoEditado['estado'] == 'activo' ? 'selected' : ''; ?>>Activo</option>
                                                    <option value="inactivo" <?php echo $impuestoEditado && $impuestoEditado['estado'] == 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary"><?php echo $impuestoEditado ? 'Actualizar' : 'Guardar'; ?></button>
                                        </form>

                                        <hr>

                                        <!-- Tabla de impuestos registrados -->
                                        <h3 class="mt-5">Impuestos Registrados</h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Nombre</th>
                                                    <th scope="col">Porcentaje</th>
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Tipo</th>
                                                    <th scope="col">Estado</th>
                                                    <th scope="col">Fecha de Creación</th>
                                                    <th scope="col">Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (count($impuestos) > 0) {
                                                    foreach ($impuestos as $impuesto) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($impuesto['id']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['nombre']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['porcentaje']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['descripcion']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['tipo_impuesto']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['estado']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($impuesto['fecha_creacion']) . "</td>";
                                                        echo "<td><a href='?edit=" . $impuesto['id'] . "' class='btn btn-warning btn-sm'>Editar</a></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='8'>No hay impuestos registrados.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>

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
