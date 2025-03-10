<?php
require '../Conexion/conex.php'; // Incluir la conexión a la base de datos

// Verificar si se han enviado datos por el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $nombre_cliente = $_POST['nombre_cliente'];
    $telefono_cliente = $_POST['telefono_cliente'];
    $direccion_cliente = $_POST['direccion_cliente'];

    // Preparar la consulta SQL para insertar el nuevo cliente en la tabla clientes
    $sql = "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $nombre_cliente, $telefono_cliente, $direccion_cliente);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir al usuario a la página de clientes (o donde desees mostrar el mensaje)
        header("Location: ../VerClientes.php"); // Cambia esta URL a la página que desees
        exit();
    } else {
        echo "Error al guardar el cliente. Intenta de nuevo.";
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>
