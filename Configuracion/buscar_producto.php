<?php
require '../Conexion/conex.php';

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    // Consulta para obtener los datos del producto incluyendo el estado
    $sql = "SELECT id, codigo, nombre, venta, estado FROM productos WHERE codigo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        // Verificar si el producto está activo
        if (strtolower($fila['estado']) === 'activo') {
            echo json_encode([
                'id' => $fila['id'],
                'codigo' => $fila['codigo'],
                'nombre' => $fila['nombre'],
                'venta' => $fila['venta']
            ]);
        } else {
            // Producto inactivo
            echo json_encode([
                'error' => 'El producto está inactivo y no puede ser vendido.'
            ]);
        }
    } else {
        echo json_encode(null); // Producto no encontrado
    }

    $conn->close();
}
?>
