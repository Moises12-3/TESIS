<?php
    require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

    // Verificar si se recibe el id del cliente
    if (isset($_GET['id'])) {
        // Obtener el id del cliente a eliminar
        $id_cliente = $_GET['id'];

        // Consulta SQL para eliminar el cliente
        $sql = "DELETE FROM clientes WHERE id = ?";

        // Preparar la consulta
        if ($stmt = $conn->prepare($sql)) {
            // Vincular el parámetro
            $stmt->bind_param('i', $id_cliente);
            
            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si la eliminación es exitosa, redirigir a la página de clientes con un mensaje
                header("Location: ../VerClientes.php?mensaje=eliminado");
                exit;
            } else {
                // Si ocurre un error, mostrar mensaje
                echo "Error al eliminar el cliente: " . $stmt->error;
            }
        } else {
            echo "Error al preparar la consulta: " . $conn->error;
        }
    } else {
        echo "No se ha recibido el id del cliente.";
    }

    // Cerrar la conexión
    $conn->close();
?>
