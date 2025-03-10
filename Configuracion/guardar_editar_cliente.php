<?php
    require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

    // Verificar si se recibieron los datos del formulario
    if (isset($_POST['id'], $_POST['nombre_cliente'], $_POST['telefono_cliente'], $_POST['direccion_cliente'])) {
        $id_cliente = $_POST['id'];
        $nombre_cliente = $_POST['nombre_cliente'];
        $telefono_cliente = $_POST['telefono_cliente'];
        $direccion_cliente = $_POST['direccion_cliente'];

        // Consulta para actualizar los datos del cliente
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $nombre_cliente, $telefono_cliente, $direccion_cliente, $id_cliente);

        if ($stmt->execute()) {
            echo "Cliente actualizado con éxito.";
            // Redirigir a la página de clientes después de actualizar
            header("Location: ../VerClientes.php");
            exit;
        } else {
            echo "Error al actualizar el cliente.";
        }
    } else {
        echo "Datos incompletos.";
    }

    $conn->close();
?>
