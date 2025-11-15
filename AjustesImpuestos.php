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
    <title>Ajuste Impuestos</title>
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

$impuestoEditado = null;

// Obtener los impuestos registrados
$sql = "SELECT * FROM Impuesto";
$result = $conn->query($sql);
$impuestos = [];
if ($result && $result->num_rows > 0){
    while ($row = $result->fetch_assoc()){
        $impuestos[] = $row;
    }
}

// Obtener impuesto para editar
if (isset($_GET['edit'])){
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM Impuesto WHERE id=?");
    if ($stmt){
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $impuestoEditado = $result->fetch_assoc();
        $stmt->close();
    }
}

$conn->close();
?>



<!-- Modal de Mensaje -->
<div class="modal fade" id="mensajeModal" tabindex="-1" aria-labelledby="mensajeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mensajeModalLabel">Mensaje</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="mensajeModalBody"></div>
    </div>
  </div>
</div>

<!-- Formulario de Impuesto -->
<form id="formImpuesto" method="POST" class="mb-4">
    <?php if ($impuestoEditado): ?>
        <input type="hidden" name="id" value="<?php echo $impuestoEditado['id']; ?>">
        <h3>✏️ Editar Impuesto</h3>
    <?php else: ?>
        <h3>🆕 Nuevo Impuesto</h3>
    <?php endif; ?>

    <div id="mensaje-alerta"></div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="nombre">🏷️ Nombre del Impuesto</label>
                <input type="text" name="nombre" id="nombre" class="form-control input-borde-negro"
                       value="<?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['nombre']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="porcentaje">📊 Porcentaje</label>
                <input type="number" step="0.01" name="porcentaje" id="porcentaje"
                       class="form-control input-borde-negro"
                       value="<?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['porcentaje']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="tipo_impuesto">💰 Tipo de Impuesto</label>
                <select name="tipo_impuesto" id="tipo_impuesto" class="form-control input-borde-negro">
                    <option value="fijo" <?php echo $impuestoEditado && $impuestoEditado['tipo_impuesto']=='fijo' ? 'selected':''; ?>>Fijo</option>
                    <option value="porcentaje" <?php echo $impuestoEditado && $impuestoEditado['tipo_impuesto']=='porcentaje' ? 'selected':''; ?>>Porcentaje</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="descripcion">📝 Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control input-borde-negro"
                          rows="5"><?php echo $impuestoEditado ? htmlspecialchars($impuestoEditado['descripcion']) : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="estado">⚡ Estado</label>
                <select name="estado" id="estado" class="form-control input-borde-negro">
                    <option value="activo" <?php echo $impuestoEditado && $impuestoEditado['estado']=='activo' ? 'selected':''; ?>>Activo</option>
                    <option value="inactivo" <?php echo $impuestoEditado && $impuestoEditado['estado']=='inactivo' ? 'selected':''; ?>>Inactivo</option>
                </select>
            </div>
        </div>
    </div>

<div class="d-flex gap-2 mt-3">
    <button id="btnSubmit" type="submit" class="btn btn-primary">
        <?php echo $impuestoEditado ? 'Actualizar' : 'Guardar'; ?>
    </button>
    <a href="AjustesImpuestos.php" class="btn btn-danger">Limpiar</a>
</div>



</form>

<hr>

<h3>📋 Impuestos Registrados</h3>
<button id="exportExcel" class="btn btn-success mb-2">📥 Exportar a Excel</button>

<table id="tabla-impuestos" class="table table-striped">
    <thead>
        <tr>
            <th>🆔 ID</th>
            <th>🏷️ Nombre</th>
            <th>📊 Porcentaje</th>
            <th>📝 Descripción</th>
            <th>💰 Tipo</th>
            <th>⚡ Estado</th>
            <th>🗓️ Fecha Creación</th>
            <th>✏️ Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php if(count($impuestos) > 0): ?>
        <?php foreach($impuestos as $imp): ?>
            <tr>
                <td><?= $imp['id'] ?></td>
                <td><?= htmlspecialchars($imp['nombre']) ?></td>
                <td><?= $imp['porcentaje'] ?>%</td>
                <td><?= htmlspecialchars($imp['descripcion']) ?></td>
                <td><?= $imp['tipo_impuesto'] ?></td>
                <td><?= $imp['estado'] ?></td>
                <td><?= $imp['fecha_creacion'] ?></td>
                <td><a href="?edit=<?= $imp['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8">📭 No hay impuestos registrados.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<!-- Scripts (orden correcto) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- SheetJS para exportar Excel -->
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

<script>
$(document).ready(function(){

    
    // Inicializar DataTable y guardamos referencia
    var tabla = $('#tabla-impuestos').DataTable({
        dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 text-end'f>>" + "rtip",
        "lengthMenu": [ [5, 10, 20, 25], [5, 10, 20, 25] ],
        "pageLength": 5,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "📭 No se encontraron resultados",
            "info": "Mostrando _START_ a _END_ de _TOTAL_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "🔍 Buscar:",
            "paginate": { "first": "Primero", "last": "Último", "next": "Siguiente", "previous": "Anterior" }
        }
    });

    // Exportar tabla a Excel
    $('#exportExcel').click(function(){
        var table = document.getElementById('tabla-impuestos');
        var wb = XLSX.utils.book_new();
        var ws_data = [];

        // Cabecera limpia (sin emojis)
        ws_data.push(["ID","Nombre","Porcentaje","Descripción","Tipo","Estado","Fecha Creación"]);

        var tbody = table.tBodies[0];
        for(var r=0; r<tbody.rows.length; r++){
            var tr = tbody.rows[r];
            // Ignorar fila de "No hay registros"
            if(tr.cells.length < 7) continue;

            var row = [];
            for(var c=0; c<7; c++){ // Solo primeras 7 columnas
                var text = tr.cells[c].innerText || tr.cells[c].textContent || "";
                // Quitar emojis simples y signos extraños
                text = text.replace(/[\u{1F600}-\u{1F64F}\u{2700}-\u{27BF}\u{1F300}-\u{1F5FF}\u{1F680}-\u{1F6FF}]/gu,"");
                // Quitar % del porcentaje
                if(c === 2) text = text.replace('%','').trim();
                row.push(text.trim());
            }
            ws_data.push(row);
        }

        var ws = XLSX.utils.aoa_to_sheet(ws_data);
        XLSX.utils.book_append_sheet(wb, ws, "Impuestos");
        XLSX.writeFile(wb, "Impuestos.xlsx");
    });


    // AJAX para guardar/editar impuesto
    $("#formImpuesto").submit(function(e){
        e.preventDefault();
        $("#btnSubmit").prop('disabled', true);

        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "Configuracion/guardar_impuesto_ajax.php",
            data: formData,
            dataType: "json",
            success: function(res){
                $("#mensajeModalBody").html(res.message);
                var modalEl = document.getElementById('mensajeModal');
                var bootstrapModal = new bootstrap.Modal(modalEl);
                bootstrapModal.show();
                setTimeout(function(){ bootstrapModal.hide(); }, 2000);

                // Si fue éxito y es NUEVO (no hay ID en el form)
                if(res.status === "success" && !$("input[name='id']").val()){
                    // Agregar nueva fila a la tabla
                    tabla.row.add([
                        res.data.id,
                        res.data.nombre,
                        res.data.porcentaje + "%",
                        res.data.descripcion,
                        res.data.tipo_impuesto,
                        res.data.estado,
                        res.data.fecha_creacion,
                        "<a href='?edit="+res.data.id+"' class='btn btn-warning btn-sm'>✏️ Editar</a>"
                    ]).draw(false);
                        

                    // Limpiar formulario COMPLETAMENTE
                    $("#formImpuesto")[0].reset(); // limpia inputs y textarea
                    $("select").each(function(){ this.selectedIndex = 0; }); // limpia selects

                    // Quitar hidden input si existía
                    $("input[name='id']").remove();
                    
                }

                // Si fue éxito y es ACTUALIZACIÓN
                if(res.status === "success" && $("input[name='id']").val()){
                    // Para simplificar: recargamos la página (porque actualizar fila puntual es más largo)
                    window.location.href = "AjustesImpuestos.php";
                }

                $("#btnSubmit").prop('disabled', false);
            },
            error: function(){
                $("#mensajeModalBody").html("❌ Error en la conexión al servidor.");
                var modalEl = document.getElementById('mensajeModal');
                var bootstrapModal = new bootstrap.Modal(modalEl);
                bootstrapModal.show();
                setTimeout(function(){ bootstrapModal.hide(); }, 2000);
                $("#btnSubmit").prop('disabled', false);
            }
        });
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
