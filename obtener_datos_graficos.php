<?php
require 'Conexion/conex.php';

$inicio = $_GET['inicio'] ?? null;
$final = $_GET['final'] ?? null;

// Si no hay fechas, usar semana actual
if (!$inicio || !$final) {
    $inicio = date("Y-m-d", strtotime("monday this week"));
    $final  = date("Y-m-d", strtotime("sunday this week"));
}

// ---------------------------------------------
// 1. PRODUCTOS MÁS VENDIDOS
// ---------------------------------------------
$sqlVentas = "
    SELECT p.nombre, SUM(pv.cantidad) AS total_vendido
    FROM productos_ventas pv
    INNER JOIN ventas v ON pv.idVenta = v.id
    INNER JOIN productos p ON pv.idProducto = p.id
    WHERE DATE(v.fecha) BETWEEN '$inicio' AND '$final'
    GROUP BY p.nombre
    ORDER BY total_vendido DESC
    LIMIT 10
";
$resVentas = $conn->query($sqlVentas);

$productos = [];
$vendidos = [];

while ($row = $resVentas->fetch_assoc()) {
    $productos[] = $row['nombre'];
    $vendidos[] = $row['total_vendido'];
}

// ---------------------------------------------
// 2. PRODUCTOS CON MÁS DEVOLUCIONES
// ---------------------------------------------
$sqlDevol = "
    SELECT p.nombre, SUM(d.cantidad_devuelta) AS total_devueltos
    FROM devoluciones d
    INNER JOIN productos p ON d.idProducto = p.id
    WHERE DATE(d.fecha_devolucion) BETWEEN '$inicio' AND '$final'
    GROUP BY p.nombre
    ORDER BY total_devueltos DESC
    LIMIT 10
";
$resDevol = $conn->query($sqlDevol);

$productosDev = [];
$devueltos = [];

while ($row = $resDevol->fetch_assoc()) {
    $productosDev[] = $row['nombre'];
    $devueltos[] = $row['total_devueltos'];
}

// ---------------------------------------------
// 3. PRODUCTOS PRÓXIMOS A VENCER (30 días)
// ---------------------------------------------
$sqlVenc = "
    SELECT nombre, 
           DATEDIFF(fecha_vencimiento, CURDATE()) AS dias_restantes
    FROM productos
    WHERE fecha_vencimiento IS NOT NULL
      AND fecha_vencimiento >= CURDATE()
      AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
    ORDER BY dias_restantes ASC
";
$resVenc = $conn->query($sqlVenc);

$nomV = [];
$diasV = [];

while ($row = $resVenc->fetch_assoc()) {
    $nomV[] = $row["nombre"];
    $diasV[] = $row["dias_restantes"];
}

// RESPUESTA JSON
echo json_encode([
    "masVendidos" => [
        "productos" => $productos,
        "vendidos" => $vendidos
    ],
    "masDevueltos" => [
        "productos" => $productosDev,
        "devueltos" => $devueltos
    ],
    "porVencer" => [
        "productos" => $nomV,
        "dias" => $diasV
    ]
]);
?>
