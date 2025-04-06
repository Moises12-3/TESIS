<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

// Verificar si se recibieron los datos del formulario
if (isset($_POST['id'], $_POST['nombre_cliente'], $_POST['telefono_cliente'], $_POST['direccion_cliente'], $_POST['descuento_cliente'], $_POST['cedula_cliente'])) {
    $id_cliente = $_POST['id'];
    $nombre_cliente = $_POST['nombre_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];
    $direccion_cliente = $_POST['direccion_cliente'];
    $descuento_cliente = $_POST['descuento_cliente']; // Recibimos el descuento
    $cedula_cliente = $_POST['cedula_cliente']; // Recibimos la cédula

    // Verificar si ya existe la cédula o teléfono (excepto para el cliente que estamos editando)
    $sql = "SELECT id FROM clientes WHERE (cedula = ? OR telefono = ?) AND id != ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $cedula_cliente, $telefono_cliente, $id_cliente);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Si hay un registro duplicado, redirigir con mensaje de duplicado
        header("Location: ../AgregarClientes.php?mensaje=duplicado");
        exit;
    }

    // Consulta para actualizar los datos del cliente, incluyendo la cédula y el descuento
    $sql = "UPDATE clientes SET nombre = ?, cedula = ?, telefono = ?, direccion = ?, descuento = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssdi', $nombre_cliente, $cedula_cliente, $telefono_cliente, $direccion_cliente, $descuento_cliente, $id_cliente);

    if ($stmt->execute()) {
        // Redirigir a la página de clientes después de actualizar
        header("Location: ../VerClientes.php");
        exit;
    } else {
        // Redirigir a la página de clientes después de error
        header("Location: ../VerClientes.php?mensaje=error");
    }
} else {
    // Redirigir a la página de clientes incompletos
    header("Location: ../VerClientes.php?mensaje=incompleto");
}

$conn->close();
?>
