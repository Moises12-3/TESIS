<?php
include '../Conexion/conex.php';

$numeroFactura = $_GET['id'] ?? '';
header('Content-Type: application/json');

// Evitar errores de PHP en la salida JSON
error_reporting(0);

$sql_factura = "SELECT v.*, u.nombre AS usuario_nombre, u.telefono AS usuario_telefono, u.email AS usuario_email,
c.nombre AS cliente_nombre, c.telefono AS cliente_telefono, c.direccion AS cliente_direccion
FROM ventas v
LEFT JOIN usuarios u ON v.idUsuario = u.id
LEFT JOIN clientes c ON v.idCliente = c.id
WHERE v.numeroFactura='$numeroFactura'";
$result_factura = $conn->query($sql_factura);

if($result_factura->num_rows > 0){
    $factura = $result_factura->fetch_assoc();

    $sql_productos = "SELECT pv.cantidad, pv.precio, p.codigo, p.nombre AS producto_nombre,
    up.nombre AS unidad FROM productos_ventas pv
    LEFT JOIN productos p ON pv.idProducto=p.id
    LEFT JOIN UnidadPeso up ON p.id_UnidadPeso=up.id
    WHERE pv.numeroFactura='$numeroFactura'";
    $result_productos = $conn->query($sql_productos);
    $productos = [];
    while($row = $result_productos->fetch_assoc()){
        // Asegurarse que cantidades y precios sean numéricos
        $row['cantidad'] = floatval($row['cantidad']);
        $row['precio'] = floatval($row['precio']);
        $productos[] = $row;
    }

    $sql_empresa = "SELECT * FROM empresa LIMIT 1";
    $result_empresa = $conn->query($sql_empresa);
    $empresa = $result_empresa->fetch_assoc();

    echo json_encode([
        'factura'=>$factura,
        'productos'=>$productos,
        'empresa'=>$empresa
    ]);
}else{
    echo json_encode(['error'=>'Factura no encontrada']);
}
?>
