<?php
// Configuracion/obtener_datos_graficos.php
header('Content-Type: application/json');

// Verificar si el archivo de conexión existe
$conexionFile = '../Conexion/conex.php';
if (!file_exists($conexionFile)) {
    echo json_encode(["error" => "Archivo de conexión no encontrado"]);
    exit;
}

require $conexionFile;

// Obtener fechas
$inicio = isset($_GET['inicio']) ? $_GET['inicio'] : null;
$final = isset($_GET['final']) ? $_GET['final'] : null;

// Si no hay fechas, usar semana actual
if (!$inicio || !$final || $inicio == 'null' || $final == 'null') {
    $inicio = date("Y-m-d", strtotime("monday this week"));
    $final  = date("Y-m-d", strtotime("sunday this week"));
}

// Validar conexión
if ($conn->connect_error) {
    echo json_encode(["error" => "Error de conexión a la base de datos"]);
    exit;
}

$response = [];

// ---------------------------------------------
// 1. KPIs (Key Performance Indicators)
// ---------------------------------------------
try {
    // Primero, obtener el símbolo de la moneda nacional
    $sqlMonedaNacional = "SELECT simbolo FROM Moneda WHERE tipo = 'nacional' AND estado = 'activo' LIMIT 1";
    $resultMoneda = $conn->query($sqlMonedaNacional);
    $simboloMoneda = "NIO"; // Valor por defecto
    if ($resultMoneda && $row = $resultMoneda->fetch_assoc()) {
        $simboloMoneda = $row['simbolo'];
    }
    
    // Ventas totales
    $sqlVentasTotal = "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha) BETWEEN ? AND ?";
    $stmt = $conn->prepare($sqlVentasTotal);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $ventasTotal = $row['total'] ? (float)$row['total'] : 0;
    
    $response['kpis']['ventasTotal'] = number_format($ventasTotal, 2);
    $response['kpis']['simboloMoneda'] = $simboloMoneda; // Agregar símbolo
    $response['kpis']['ventasTotalNum'] = $ventasTotal; // Valor numérico también
    $stmt->close();
    
    // Total devoluciones
    $sqlDevolTotal = "SELECT SUM(cantidad_devuelta) as total FROM devoluciones WHERE DATE(fecha_devolucion) BETWEEN ? AND ?";
    $stmt = $conn->prepare($sqlDevolTotal);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $response['kpis']['devolucionesTotal'] = $row['total'] ? (int)$row['total'] : 0;
    $stmt->close();
    
    // Productos vendidos
    $sqlProdVendidos = "SELECT SUM(cantidad) as total FROM productos_ventas pv 
                        JOIN ventas v ON pv.idVenta = v.id 
                        WHERE DATE(v.fecha) BETWEEN ? AND ?";
    $stmt = $conn->prepare($sqlProdVendidos);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $response['kpis']['productosVendidos'] = $row['total'] ? (int)$row['total'] : 0;
    $stmt->close();
    
    // Productos por vencer
    $sqlPorVencer = "SELECT COUNT(*) as total FROM productos 
                     WHERE fecha_vencimiento BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY) 
                     AND estado = 'activo'";
    $result = $conn->query($sqlPorVencer);
    $row = $result->fetch_assoc();
    $response['kpis']['porVencer'] = (int)$row['total'];
    
} catch (Exception $e) {
    error_log("Error en KPIs: " . $e->getMessage());
    $response['kpis'] = [
        'ventasTotal' => '0.00',
        'simboloMoneda' => 'NIO',
        'ventasTotalNum' => 0,
        'devolucionesTotal' => 0,
        'productosVendidos' => 0,
        'porVencer' => 0
    ];
}

// ---------------------------------------------
// 2. VENTAS POR DÍA DE LA SEMANA
// ---------------------------------------------
$diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
$ventasPorDia = array_fill(0, 7, 0);

try {
    $sqlVentasDia = "
        SELECT DAYOFWEEK(fecha) as dia, SUM(total) as total
        FROM ventas 
        WHERE DATE(fecha) BETWEEN ? AND ?
        GROUP BY DAYOFWEEK(fecha)
        ORDER BY DAYOFWEEK(fecha)
    ";
    
    $stmt = $conn->prepare($sqlVentasDia);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $index = $row['dia'] - 1; // MySQL: 1=Domingo, ajustamos a 0=Lunes
        $ventasPorDia[$index] = (float)$row['total'];
    }
    
    // Reorganizar para que Lunes sea índice 0
    $domingo = array_shift($ventasPorDia);
    $ventasPorDia[] = $domingo;
    
    $response['ventasPorDia'] = [
        'dias' => $diasSemana,
        'ventas' => $ventasPorDia
    ];
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en ventas por día: " . $e->getMessage());
    $response['ventasPorDia'] = [
        'dias' => $diasSemana,
        'ventas' => array_fill(0, 7, rand(500, 2000))
    ];
}

// ---------------------------------------------
// 3. MÉTODOS DE PAGO MÁS USADOS
// ---------------------------------------------
try {
    $sqlMetodosPago = "
        SELECT tp.nombre, COUNT(v.id) as cantidad
        FROM ventas v
        JOIN TipoPago tp ON v.id_tipoPago = tp.id
        WHERE DATE(v.fecha) BETWEEN ? AND ?
        GROUP BY tp.id, tp.nombre
        ORDER BY cantidad DESC
        LIMIT 8
    ";
    
    $stmt = $conn->prepare($sqlMetodosPago);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $metodos = [];
    $cantidades = [];
    
    while ($row = $result->fetch_assoc()) {
        $metodos[] = $row['nombre'];
        $cantidades[] = (int)$row['cantidad'];
    }
    
    $response['metodosPago'] = [
        'metodos' => $metodos,
        'cantidades' => $cantidades
    ];
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en métodos de pago: " . $e->getMessage());
    $response['metodosPago'] = [
        'metodos' => ['Efectivo', 'Tarjeta', 'Transferencia'],
        'cantidades' => [45, 30, 25]
    ];
}

// ---------------------------------------------
// 4. PRODUCTOS MÁS VENDIDOS (EXISTENTE)
// ---------------------------------------------
$productos = [];
$vendidos = [];

try {
    $sqlVentas = "
        SELECT p.nombre, SUM(pv.cantidad) AS total_vendido
        FROM productos_ventas pv
        INNER JOIN ventas v ON pv.idVenta = v.id
        INNER JOIN productos p ON pv.idProducto = p.id
        WHERE DATE(v.fecha) BETWEEN ? AND ?
        GROUP BY p.id, p.nombre
        ORDER BY total_vendido DESC
        LIMIT 10
    ";
    
    $stmt = $conn->prepare($sqlVentas);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row['nombre'];
        $vendidos[] = (int)$row['total_vendido'];
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error en consulta de ventas: " . $e->getMessage());
}

if (empty($productos)) {
    $productos = ["Flor de Caña 4 años", "Toña Lata", "Vodka Nacional", "Coca-Cola", "Maní Salado"];
    $vendidos = [150, 120, 85, 200, 90];
}

$response['masVendidos'] = [
    "productos" => $productos,
    "vendidos" => $vendidos
];

// ---------------------------------------------
// 5. VENTAS POR CATEGORÍA
// ---------------------------------------------
try {
    $sqlCategorias = "
        SELECT 
            CASE 
                WHEN p.nombre LIKE '%Flor de Caña%' OR p.nombre LIKE '%Ron%' THEN 'Ron Nacional'
                WHEN p.nombre LIKE '%Toña%' OR p.nombre LIKE '%Victoria%' OR p.nombre LIKE '%Premium%' THEN 'Cerveza'
                WHEN p.nombre LIKE '%Vodka%' THEN 'Vodka'
                WHEN p.nombre LIKE '%Vino%' OR p.nombre LIKE '%Champagne%' THEN 'Vinos'
                WHEN p.nombre LIKE '%Coca-Cola%' OR p.nombre LIKE '%Sprite%' OR p.nombre LIKE '%Fanta%' THEN 'Refrescos'
                WHEN p.nombre LIKE '%Maní%' OR p.nombre LIKE '%Papas%' OR p.nombre LIKE '%Queso%' THEN 'Snacks'
                ELSE 'Otros'
            END as categoria,
            SUM(pv.cantidad) as total,
            SUM(pv.cantidad * pv.precio) as monto
        FROM productos_ventas pv
        JOIN productos p ON pv.idProducto = p.id
        JOIN ventas v ON pv.idVenta = v.id
        WHERE DATE(v.fecha) BETWEEN ? AND ?
        GROUP BY categoria
        ORDER BY total DESC
    ";
    
    $stmt = $conn->prepare($sqlCategorias);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $categorias = [];
    $totalesCategoria = [];
    
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row['categoria'];
        $totalesCategoria[] = (int)$row['total'];
    }
    
    $response['ventasCategoria'] = [
        'categorias' => $categorias,
        'totales' => $totalesCategoria
    ];
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en categorías: " . $e->getMessage());
    $response['ventasCategoria'] = [
        'categorias' => ['Cerveza', 'Ron', 'Snacks', 'Refrescos'],
        'totales' => [300, 150, 120, 200]
    ];
}

// ---------------------------------------------
// 6. PRODUCTOS CON MÁS DEVOLUCIONES (EXISTENTE)
// ---------------------------------------------
$productosDev = [];
$devueltos = [];

try {
    $sqlDevol = "
        SELECT p.nombre, SUM(d.cantidad_devuelta) AS total_devueltos
        FROM devoluciones d
        INNER JOIN productos p ON d.idProducto = p.id
        WHERE DATE(d.fecha_devolucion) BETWEEN ? AND ?
        GROUP BY p.id, p.nombre
        ORDER BY total_devueltos DESC
        LIMIT 10
    ";
    
    $stmt = $conn->prepare($sqlDevol);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $productosDev[] = $row['nombre'];
        $devueltos[] = (int)$row['total_devueltos'];
    }
    $stmt->close();
} catch (Exception $e) {
    error_log("Error en consulta de devoluciones: " . $e->getMessage());
}

if (empty($productosDev)) {
    $productosDev = ["Producto A", "Producto B", "Producto C"];
    $devueltos = [5, 3, 2];
}

$response['masDevueltos'] = [
    "productos" => $productosDev,
    "devueltos" => $devueltos
];

// ---------------------------------------------
// 7. VENTAS POR HORA DEL DÍA
// ---------------------------------------------
$horas = range(8, 22); // De 8 AM a 10 PM
$ventasPorHora = array_fill(0, count($horas), 0);

try {
    $sqlVentasHora = "
        SELECT HOUR(fecha) as hora, COUNT(*) as cantidad
        FROM ventas 
        WHERE DATE(fecha) BETWEEN ? AND ?
        AND HOUR(fecha) BETWEEN 8 AND 22
        GROUP BY HOUR(fecha)
        ORDER BY hora
    ";
    
    $stmt = $conn->prepare($sqlVentasHora);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $index = $row['hora'] - 8; // Ajustar índice
        if ($index >= 0 && $index < count($ventasPorHora)) {
            $ventasPorHora[$index] = (int)$row['cantidad'];
        }
    }
    
    $response['ventasPorHora'] = [
        'horas' => array_map(function($h) { return $h . ':00'; }, $horas),
        'ventas' => $ventasPorHora
    ];
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en ventas por hora: " . $e->getMessage());
    $response['ventasPorHora'] = [
        'horas' => array_map(function($h) { return $h . ':00'; }, $horas),
        'ventas' => array_map(function() { return rand(1, 15); }, $horas)
    ];
}

// ---------------------------------------------
// 8. PRODUCTOS PRÓXIMOS A VENCER (EXISTENTE)
// ---------------------------------------------
$nomV = [];
$diasV = [];

try {
    $sqlVenc = "
        SELECT nombre, 
               DATEDIFF(fecha_vencimiento, CURDATE()) AS dias_restantes
        FROM productos
        WHERE fecha_vencimiento IS NOT NULL
          AND fecha_vencimiento >= CURDATE()
          AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
          AND estado = 'activo'
        ORDER BY dias_restantes ASC
        LIMIT 10
    ";
    
    $result = $conn->query($sqlVenc);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $nomV[] = $row["nombre"];
            $diasV[] = (int)$row["dias_restantes"];
        }
    }
} catch (Exception $e) {
    error_log("Error en consulta de vencimientos: " . $e->getMessage());
}

if (empty($nomV)) {
    $nomV = ["Leche", "Yogurt", "Queso"];
    $diasV = [5, 10, 25];
}

$response['porVencer'] = [
    "productos" => $nomV,
    "dias" => $diasV
];

// ---------------------------------------------
// 9. TOP 10 MEJORES CLIENTES
// ---------------------------------------------
try {
    $sqlTopClientes = "
        SELECT c.nombre, COUNT(v.id) as compras, SUM(v.total) as total_gastado
        FROM ventas v
        JOIN clientes c ON v.idCliente = c.id
        WHERE DATE(v.fecha) BETWEEN ? AND ?
        GROUP BY c.id, c.nombre
        ORDER BY total_gastado DESC
        LIMIT 10
    ";
    
    $stmt = $conn->prepare($sqlTopClientes);
    $stmt->bind_param("ss", $inicio, $final);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $clientes = [];
    $compras = [];
    $montos = [];
    
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row['nombre'];
        $compras[] = (int)$row['compras'];
        $montos[] = (float)$row['total_gastado'];
    }
    
    $response['topClientes'] = [
        'clientes' => $clientes,
        'compras' => $compras,
        'montos' => $montos
    ];
    $stmt->close();
    
} catch (Exception $e) {
    error_log("Error en top clientes: " . $e->getMessage());
    $response['topClientes'] = [
        'clientes' => ['Cliente A', 'Cliente B', 'Cliente C'],
        'compras' => [15, 12, 8],
        'montos' => [15000, 12000, 8000]
    ];
}

// ---------------------------------------------
// 10. TENDENCIA DE VENTAS MENSUAL
// ---------------------------------------------
try {
    $sqlTendencia = "
        SELECT 
            DATE_FORMAT(fecha, '%Y-%m') as mes,
            DATE_FORMAT(fecha, '%b %Y') as mes_formateado,
            COUNT(*) as cantidad_ventas,
            SUM(total) as total_ventas
        FROM ventas
        WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(fecha, '%Y-%m')
        ORDER BY mes ASC
    ";
    
    $result = $conn->query($sqlTendencia);
    
    $meses = [];
    $mesesFormateados = [];
    $cantidadVentas = [];
    $totalVentas = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $meses[] = $row['mes'];
            $mesesFormateados[] = $row['mes_formateado'];
            $cantidadVentas[] = (int)$row['cantidad_ventas'];
            $totalVentas[] = (float)$row['total_ventas'];
        }
        
        $response['tendenciaMensual'] = [
            'meses' => $mesesFormateados, // Usamos los meses formateados
            'cantidad' => $cantidadVentas,
            'total' => $totalVentas
        ];
    } else {
        // Si no hay datos, crear datos de ejemplo para los últimos 6 meses
        $mesesFormateados = [];
        $cantidadVentas = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $mesesFormateados[] = date('M Y', strtotime("-$i months"));
            $cantidadVentas[] = 0; // O rand(0, 50) para pruebas
        }
        
        $response['tendenciaMensual'] = [
            'meses' => $mesesFormateados,
            'cantidad' => $cantidadVentas,
            'total' => array_fill(0, count($mesesFormateados), 0)
        ];
    }
    
} catch (Exception $e) {
    error_log("Error en tendencia mensual: " . $e->getMessage());
    
    // Datos de ejemplo en caso de error
    $mesesFormateados = [];
    for ($i = 6; $i >= 0; $i--) {
        $mesesFormateados[] = date('M Y', strtotime("-$i months"));
    }
    
    $response['tendenciaMensual'] = [
        'meses' => $mesesFormateados,
        'cantidad' => array_map(function() { return rand(0, 50); }, $mesesFormateados),
        'total' => array_map(function() { return rand(0, 5000); }, $mesesFormateados)
    ];
}

// Cerrar conexión
$conn->close();

// Enviar respuesta
echo json_encode($response);
?>