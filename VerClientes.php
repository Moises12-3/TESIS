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
    <title>Visualizar Clientes</title>
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




                                    <h1>Clientes</h1>
                                    <div class="d-flex align-items-center">
                                        
                                        <input type="text" id="buscadorClientes" class="form-control" placeholder="Buscar cliente..." style="width: 300px;">
                                        
                                        <button class="btn btn-success mt-3 ml-3" onclick="exportarExcel()">Exportar a Excel</button>
                                        
                                    </div>
                                        
                                    <!-- Filtro para seleccionar cuántos elementos mostrar -->
                                    <div class="d-flex justify-content-end mb-3">
                                        <label class="mr-2 mt-2">Mostrar:</label>
                                        <select id="selectFilas" class="form-control w-auto">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                        </select>
                                    </div>
                                    
                                    <script>
document.addEventListener("DOMContentLoaded", function () {
    const filas = Array.from(document.querySelectorAll("#tablaClientes tbody tr"));
    const selectFilas = document.getElementById("selectFilas");
    let filasPorPagina = parseInt(selectFilas.value);
    let paginaActual = 1;

    function mostrarPagina(pagina) {
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;

        filas.forEach((fila, index) => {
            fila.style.display = index >= inicio && index < fin ? "" : "none";
        });

        document.getElementById("paginacionClientes").innerHTML = generarPaginacion(pagina);
        paginaActual = pagina;
    }

    function generarPaginacion(pagina) {
        const totalPaginas = Math.ceil(filas.length / filasPorPagina);
        let html = '';

        html += `<li class="page-item ${pagina === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="mostrarPagina(${pagina - 1}); return false;">Anterior</a>
                 </li>`;

        for (let i = 1; i <= totalPaginas; i++) {
            html += `<li class="page-item ${pagina === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="mostrarPagina(${i}); return false;">${i}</a>
                     </li>`;
        }

        html += `<li class="page-item ${pagina === totalPaginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="mostrarPagina(${pagina + 1}); return false;">Siguiente</a>
                 </li>`;

        return html;
    }

    // Cambiar cantidad de filas por página dinámicamente
    selectFilas.addEventListener("change", function () {
        filasPorPagina = parseInt(this.value);
        mostrarPagina(1); // Reiniciar desde la página 1
    });

    // Hacer accesible la función desde otros scripts
    window.mostrarPagina = mostrarPagina;

    mostrarPagina(paginaActual); // Mostrar la primera página al cargar
});
</script>


                                     <script>
                                    // Buscador en tiempo real
                                    document.getElementById("buscadorClientes").addEventListener("keyup", function() {
                                        var filtro = this.value.toLowerCase();
                                        var filas = document.querySelectorAll("#tablaClientes tbody tr");

                                        filas.forEach(function(fila) {
                                            var textoFila = fila.textContent.toLowerCase();
                                            fila.style.display = textoFila.includes(filtro) ? "" : "none";
                                        });
                                    });
                                    </script>

                                    <!-- SheetJS CDN -->
                                    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

                                    <script>
                                    // Función para exportar tabla a Excel
                                    function exportarExcel() {
                                        var tabla = document.getElementById('tablaClientes');
                                        var wb = XLSX.utils.table_to_book(tabla, { sheet: "Clientes" });
                                        XLSX.writeFile(wb, 'clientes.xlsx');
                                    }
                                    </script>
                                    
                                    <?php
                                        require 'Conexion/conex.php'; // Incluir la conexión a la base de datos

                                        // Configuración para la paginación
                                        $filasPorPagina = isset($_GET['filas']) ? (int)$_GET['filas'] : 10; // Número de filas por página, por defecto 10
                                        $página = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual, por defecto 1

                                        // Calcular el inicio de la consulta
                                        $inicio = ($página - 1) * $filasPorPagina;

                                        // Consulta para obtener los clientes con paginación
                                        $sql = "SELECT id, nombre, cedula, telefono, direccion, descuento FROM clientes ";
                                        $resultado = $conn->query($sql);

                                        // Consulta para obtener el total de registros (sin paginación)
                                        $sqlTotal = "SELECT COUNT(id) AS total FROM clientes";
                                        $resultadoTotal = $conn->query($sqlTotal);
                                        $filaTotal = $resultadoTotal->fetch_assoc();
                                        $totalClientes = $filaTotal['total'];

                                    ?>

                                    <table class="table" id="tablaClientes">

                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Cédula</th> <!-- Nueva columna para Cédula -->
                                                <th scope="col">Teléfono</th>
                                                <th scope="col">Dirección</th>
                                                <th scope="col">Descuento</th> <!-- Nueva columna para Descuento -->
                                                <th scope="col">Editar</th>
                                                <th scope="col">Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($resultado->num_rows > 0) {
                                                while ($fila = $resultado->fetch_assoc()) {
                                                    echo "<tr>";
                                                    echo "<td>" . htmlspecialchars($fila["nombre"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($fila["cedula"]) . "</td>"; 
                                                    echo "<td>" . htmlspecialchars($fila["telefono"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($fila["direccion"]) . "</td>";
                                                    echo "<td>" . htmlspecialchars($fila["descuento"]) . "</td>"; 
                                                    echo "<td><a href='EditarCliente.php?id=" . $fila["id"] . "' class='btn btn-primary'>Editar</a></td>";
                                                    echo "<td><a href='Configuracion/eliminar_cliente.php?id=" . $fila["id"] . "' class='btn btn-danger' onclick='return confirm(\"¿Seguro que deseas eliminar este cliente?\");'>Eliminar</a></td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='5'>No hay clientes registrados.</td></tr>";
                                            }
                                            ?>
                                        </tbody>

                                    </table>


<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>

<!-- Paginación -->
<?php
$totalPaginas = ceil($totalClientes / $filasPorPagina);
?>


<script>
// Mostrar solo X filas de la tabla sin recargar la página
document.addEventListener("DOMContentLoaded", function () {
    const filas = Array.from(document.querySelectorAll("#tablaClientes tbody tr"));
    const filasPorPagina = 10;
    let paginaActual = 1;

    function mostrarPagina(pagina) {
        const inicio = (pagina - 1) * filasPorPagina;
        const fin = inicio + filasPorPagina;

        filas.forEach((fila, index) => {
            fila.style.display = index >= inicio && index < fin ? "" : "none";
        });

        document.getElementById("paginacionClientes").innerHTML = generarPaginacion(pagina);
        paginaActual = pagina;
    }

    function generarPaginacion(pagina) {
        const totalPaginas = Math.ceil(filas.length / filasPorPagina);
        let html = '';

        html += `<li class="page-item ${pagina === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="mostrarPagina(${pagina - 1}); return false;">Anterior</a>
                 </li>`;

        for (let i = 1; i <= totalPaginas; i++) {
            html += `<li class="page-item ${pagina === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="mostrarPagina(${i}); return false;">${i}</a>
                     </li>`;
        }

        html += `<li class="page-item ${pagina === totalPaginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="mostrarPagina(${pagina + 1}); return false;">Siguiente</a>
                 </li>`;

        return html;
    }

    window.mostrarPagina = mostrarPagina; // hacerla accesible desde los enlaces
    mostrarPagina(1); // iniciar en la primera página
});
</script>

<?php
if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'eliminado') {
    echo "<div id='mensajeEliminado' class='alert alert-success'>Cliente eliminado correctamente.</div>";
}
?>

<script type="text/javascript">
// Verifica si el mensaje con id 'mensajeEliminado' existe en la página
window.onload = function() {
    var mensaje = document.getElementById('mensajeEliminado');
    if (mensaje) {
        // Oculta el mensaje después de 5 segundos
        setTimeout(function() {
            mensaje.style.display = 'none';
        }, 5000); // 5000 milisegundos = 5 segundos
    }
};
</script>
<nav>
    <ul id="paginacionClientes" class="pagination justify-content-center"></ul>
</nav>




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