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
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h1>Realizar Ventas</h1>
                                        
                                        <br>

<!-- Selector de clientes -->

<label for="clienteSeleccionado"><strong>Cliente:</strong></label>
<!-- Buscador de clientes -->
<input type="text" id="buscarCliente" class="form-control" placeholder="Buscar cliente...">

<!-- Select para mostrar los clientes -->
<select id="clienteSeleccionado" class="form-control mt-3">
    <option value="" disabled selected>Seleccione un cliente</option>
</select>

<!-- Información del cliente seleccionado -->
<div id="infoCliente" class="mt-3">
    <strong>ID Cliente: </strong><span id="clienteId"></span><br>
    <strong>Descuento: </strong><span id="descuentoCliente"></span><br>

    <!-- El input hidden para enviar el ID -->
    <input type="hidden" id="inputClienteId" name="cliente_id">

</div>




<br>
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
        document.getElementById("descuentoCliente").textContent = "";
    }
});

// Función para obtener y mostrar la información del cliente seleccionado
function mostrarInfoCliente(clienteId) {
    // Primero vaciamos el campo inputClienteId al seleccionar un cliente
    document.getElementById("inputClienteId").value = ""; 

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "Configuracion/obtener_info_cliente.php?id=" + clienteId, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                let cliente = JSON.parse(xhr.responseText);
                if (cliente) {
                    // Si el cliente es encontrado, mostramos su información
                    document.getElementById("clienteId").textContent = cliente.id;
                    document.getElementById("descuentoCliente").textContent = cliente.descuento + "%";

                    // Actualizamos el input con el ID del cliente seleccionado
                    document.getElementById("inputClienteId").value = cliente.id;
                } else {
                    // Si no se encuentra el cliente, limpiamos el input
                    document.getElementById("clienteId").textContent = "No encontrado";
                    document.getElementById("descuentoCliente").textContent = "N/A";
                    document.getElementById("inputClienteId").value = ""; // Aseguramos que quede vacío
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e);
                // En caso de error, también vaciamos el input
                document.getElementById("inputClienteId").value = "";
            }
        }
    };
    xhr.send();
}



// Evento para detectar el cambio en el select de clientes
document.getElementById("clienteSelect").addEventListener("change", function() {
    const clienteId = this.value; // Obtener el ID del cliente seleccionado
    mostrarInfoCliente(clienteId); // Llamar a la función para actualizar la información
});




</script>

                         
    <!-- Barra de búsqueda para escaneo -->
    <input type="text" id="buscadorVenta" class="form-control" placeholder="Escanea el código de producto..." autofocus>
    <br>
    <div id="mensajeVenta" class="alert d-none mt-3" role="alert"></div>
    <div id="mensajeError" class="alert d-none mt-3" role="alert"></div>


    <!-- Tabla de productos seleccionados -->
    <h3>Productos seleccionados</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Precio Venta</th>
                <th>Cantidad</th>
                <th>Total Individual</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody id="productosSeleccionados"></tbody>
    </table>

    <!-- Botón para realizar la venta -->
    <button id="btnRealizarVenta" class="btn btn-success">Realizar Venta</button>

    <script>
        let productosSeleccionados = [];

        // Función para agregar productos escaneados
        function agregarProducto(id, codigo, nombre, precio) {
            let productoExistente = productosSeleccionados.find(p => p.id === id);

            if (productoExistente) {
                productoExistente.cantidad++;
            } else {
                productosSeleccionados.push({
                    id: id,
                    codigo: codigo,
                    nombre: nombre,
                    precio: parseFloat(precio),
                    cantidad: 1
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
            let totalVenta = 0;

            productosSeleccionados.forEach(producto => {
                let subtotal = producto.precio * producto.cantidad;
                totalCantidad += producto.cantidad;
                totalVenta += subtotal;

                let fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${producto.codigo}</td>
                    <td>${producto.nombre}</td>
                    <td>$${producto.precio.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm" value="${producto.cantidad}" min="1"
                            onchange="cambiarCantidad(${producto.id}, this.value)">
                    </td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td><button class="btn btn-danger btn-sm" onclick="eliminarProducto(${producto.id})">X</button></td>
                `;
                tabla.appendChild(fila);
            });

            // Fila de totales
            if (productosSeleccionados.length > 0) {
                let filaTotales = document.createElement("tr");
                filaTotales.innerHTML = `
                    <td colspan="3"><strong>Totales</strong></td>
                    <td><strong>${totalCantidad}</strong></td>
                    <td><strong>$${totalVenta.toFixed(2)}</strong></td>
                    <td></td>
                `;
                tabla.appendChild(filaTotales);
            }
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

        // Realiza la búsqueda en la BD
        function buscarProducto(codigo) {
            let productoEncontrado = false; // Simula que el producto no se encuentra

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "Configuracion/buscar_producto.php?codigo=" + codigo, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    let producto = JSON.parse(xhr.responseText);
                    if (producto) {
                        agregarProducto(producto.id, producto.codigo, producto.nombre, producto.venta);
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
        // Obtener el ID del cliente
        const clienteId = document.getElementById("inputClienteId").value;

        if (clienteId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Configuracion/procesar_venta.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Parsear la respuesta JSON
                    var respuesta = JSON.parse(xhr.responseText);

                    // Verificar el estado de la respuesta
                    if (respuesta.status === "success") {
                        // Mostrar mensaje de éxito
                        mostrarMensaje(respuesta.message, "success");

                        // Limpiar la lista de productos seleccionados
                        productosSeleccionados = [];
                        mostrarProductosSeleccionados();
                        document.getElementById("productosTabla").innerHTML = ""; 

                        // Limpiar el select de clientes y establecer la opción predeterminada
                        const selectCliente = document.getElementById("selectCliente");
                        selectCliente.value = ""; // Esto selecciona la opción predeterminada (vacía)
                    } else if (respuesta.status === "error") {
                        // Mostrar mensaje de error
                        mostrarMensaje(respuesta.message, "error");
                    }
                }
            };

            // Enviar los productos seleccionados y el clienteId
            const datosVenta = {
                productos: productosSeleccionados,
                clienteId: clienteId // Incluye el clienteId
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

            setTimeout(() => {
                mensajeVenta.classList.add("d-none");
                productosSeleccionados = [];
                actualizarTabla();
                document.getElementById("buscadorVenta").value = "";
            }, 3000);
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