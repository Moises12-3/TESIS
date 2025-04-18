<?php
require '../Conexion/conex.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validamos que los campos estén presentes
    $codigo = trim($_POST['codigo']);
    $nombre = trim($_POST['nombre']);
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];
    // Recuperar el valor del IVA
    $iva = $_POST['iva'];
    
    // Validamos si se marcó la casilla de vencimiento
    $vencimiento = isset($_POST['tiene_vencimiento']) && $_POST['tiene_vencimiento'] == 'on' ? $_POST['vencimiento'] : NULL;

    // Validaciones:
    if (empty($codigo) || empty($nombre) || empty($compra) || empty($venta) || empty($existencia)) {
        echo "Por favor, complete todos los campos obligatorios.";
        exit();
    }

    // Validar que el campo del IVA no esté vacío y sea un número positivo
    if (!is_numeric($iva) || $iva < 0) {
        echo "El IVA debe ser un número positivo.";
        exit();
    }

    // Verificamos que el código sea alfanumérico
    if (!preg_match("/^[a-zA-Z0-9]+$/", $codigo)) {
        echo "El código debe ser alfanumérico.";
        exit();
    }

    // Verificamos que el nombre del producto no contenga números
    if (preg_match("/\d/", $nombre)) {
        echo "El nombre del producto no debe contener números.";
        exit();
    }

    // Validamos que los precios sean números positivos
    if (!is_numeric($compra) || $compra <= 0) {
        echo "El precio de compra debe ser un número positivo.";
        exit();
    }

    if (!is_numeric($venta) || $venta <= 0) {
        echo "El precio de venta debe ser un número positivo.";
        exit();
    }

    // Validamos las existencias
    if (!is_numeric($existencia) || $existencia < 0) {
        echo "Las existencias deben ser un número entero positivo o cero.";
        exit();
    }

    // Si hay fecha de vencimiento, la validamos
    if ($vencimiento && !strtotime($vencimiento)) {
        echo "La fecha de vencimiento no es válida.";
        exit();
    }



    // Comprobamos si el código de barras ya existe
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

    // Preparar la consulta SQL para insertar el producto con el IVA
    $sql = "INSERT INTO productos (codigo, nombre, compra, venta, existencia, fecha_vencimiento, iva, idMoneda) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

    // Preparamos la sentencia
    $stmt = $conn->prepare($sql);

    // Validar si los campos de precios y existencia son números válidos
    if (!is_numeric($compra) || !is_numeric($venta) || !is_numeric($existencia)) {
        echo "El precio de compra, el precio de venta y las existencias deben ser valores numéricos.";
        exit();
    }

    // Validación básica de que los campos no estén vacíos
    if (empty($codigo) || empty($nombre) || empty($compra) || empty($venta) || empty($existencia)) {
        echo "Por favor, complete todos los campos obligatorios.";
        exit();
    }

    // Calcular el Precio Unitario con IVA
    $precioUnitario = $compra / $existencia;
    $ivaCalculado = $precioUnitario * ($iva / 100);
    $precioConIVA = $precioUnitario + $ivaCalculado;

    // Validar que el precio de venta no sea menor que el precio unitario con IVA
    if ($venta < $precioConIVA) {
        echo "El precio de venta no puede ser menor que el precio unitario con IVA (" . number_format($precioConIVA, 2) . ").";
        exit();
    }


    // Validar que el precio de venta no sea menor que el precio de compra
    //if ($compra >= $venta) {
    //    echo "El precio de venta no puede ser menor que el precio de compra.";
    //    exit();
    //}
    // Validar que las existencias sean un número positivo
    if ($existencia < 0) {
        echo "Las existencias deben ser un número positivo.";
        exit();
    }

    // Comprobamos si la preparación fue exitosa
    if ($stmt === false) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit();
    }

    // Asociar los parámetros
    $stmt->bind_param("ssddisd", $codigo, $nombre, $compra, $venta, $existencia, $vencimiento, $iva);
    
    // Ejecutamos la consulta
    if ($stmt->execute()) {
        header("Location: ../VerProductos.php?mensaje=producto_agregado");
        exit();
    } else {
        echo "Error al guardar el producto: " . $stmt->error;
    }

    // Cerramos la conexión
    $stmt->close();
    $conn->close();
}
?>