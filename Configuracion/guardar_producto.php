<?php
require '../Conexion/conex.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura de datos del formulario
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];
    $iva = $_POST['iva'];
    $idMoneda = $_POST['moneda'];
    $idUnidadPeso = $_POST['unidad'];
    $idProveedor = $_POST['proveedor']; // 🏭 Nuevo campo
    $vencimiento = isset($_POST['tiene_vencimiento']) && $_POST['tiene_vencimiento'] == 'on' ? $_POST['vencimiento'] : NULL;

    // Validaciones básicas
    if (empty($codigo) || empty($nombre) || empty($compra) || empty($venta) || empty($existencia) || empty($idMoneda) || empty($idUnidadPeso) || empty($idProveedor)) {
        echo "Por favor, complete todos los campos obligatorios.";
        exit();
    }

    if (!is_numeric($iva) || $iva < 0) {
        echo "El IVA debe ser un número positivo.";
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9]+$/", $codigo)) {
        echo "El código debe ser alfanumérico.";
        exit();
    }

    if (preg_match("/\\d/", $nombre)) {
        echo "El nombre del producto no debe contener números.";
        exit();
    }

    if (!is_numeric($compra) || $compra <= 0) {
        echo "El precio de compra debe ser un número positivo.";
        exit();
    }

    if (!is_numeric($venta) || $venta <= 0) {
        echo "El precio de venta debe ser un número positivo.";
        exit();
    }

    if (!is_numeric($existencia) || $existencia < 0) {
        echo "Las existencias deben ser un número entero positivo o cero.";
        exit();
    }

    if ($vencimiento && !strtotime($vencimiento)) {
        echo "La fecha de vencimiento no es válida.";
        exit();
    }

    // Verificar si el código ya existe
    $sql_check = "SELECT COUNT(*) FROM productos WHERE codigo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $codigo);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count > 0) {
        echo "El código de barras ya está registrado. Por favor, ingrese otro.";
        exit();
    }

    // 🔹 Obtener nombre de la moneda seleccionada
    $sqlMoneda = "SELECT nombre, simbolo FROM Moneda WHERE id = ?";
    $stmtMoneda = $conn->prepare($sqlMoneda);
    $stmtMoneda->bind_param("i", $idMoneda);
    $stmtMoneda->execute();
    $stmtMoneda->bind_result($nombreMoneda, $simboloMoneda);
    $stmtMoneda->fetch();
    $stmtMoneda->close();

    // 🔹 Obtener nombre de la unidad seleccionada
    $sqlUnidad = "SELECT nombre, simbolo FROM UnidadPeso WHERE id = ?";
    $stmtUnidad = $conn->prepare($sqlUnidad);
    $stmtUnidad->bind_param("i", $idUnidadPeso);
    $stmtUnidad->execute();
    $stmtUnidad->bind_result($nombreUnidad, $simboloUnidad);
    $stmtUnidad->fetch();
    $stmtUnidad->close();

    // 🔹 Obtener nombre del proveedor seleccionado
    $sqlProveedor = "SELECT nombre FROM proveedores WHERE id = ?";
    $stmtProveedor = $conn->prepare($sqlProveedor);
    $stmtProveedor->bind_param("i", $idProveedor);
    $stmtProveedor->execute();
    $stmtProveedor->bind_result($nombreProveedor);
    $stmtProveedor->fetch();
    $stmtProveedor->close();

    // Cálculo del precio unitario con IVA
    $precioUnitario = $compra / max($existencia, 1);
    $ivaCalculado = $precioUnitario * ($iva / 100);
    $precioConIVA = $precioUnitario + $ivaCalculado;

    if ($venta < $precioConIVA) {
        echo "El precio de venta no puede ser menor que el precio unitario con IVA (" . number_format($precioConIVA, 2) . ").";
        exit();
    }

    // 🔹 Insertar producto completo (con proveedor)
    $sql = "INSERT INTO productos (
                codigo, nombre, compra, venta, existencia, fecha_vencimiento, iva,
                idMoneda, nombre_moneda, id_UnidadPeso, nombre_UnidadPeso,
                idProveedor, nombre_proveedor
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo "Error al preparar la consulta: " . $conn->error;
        exit();
    }

    $stmt->bind_param(
        "ssddisdisisis",
        $codigo,
        $nombre,
        $compra,
        $venta,
        $existencia,
        $vencimiento,
        $iva,
        $idMoneda,
        $nombreMoneda,
        $idUnidadPeso,
        $nombreUnidad,
        $idProveedor,
        $nombreProveedor
    );

    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=producto_agregado");
        exit();
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
