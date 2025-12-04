<?php
require '../Conexion/conex.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Recibir datos del formulario
    $usuario = trim($_POST["usuario"]);
    $nombre = trim($_POST["nombre"]);
    $cedula = trim($_POST["cedula"]);
    $telefono = trim($_POST["telefono"]);
    $direccion = trim($_POST["direccion"]);
    $rol = trim($_POST["rol"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_BCRYPT);

    // Validaciones básicas
    if (empty($usuario) || empty($nombre) || empty($cedula) || empty($telefono) || empty($direccion) || empty($password) || empty($rol)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Insertar usuario sin descuento
    $sql = "INSERT INTO usuarios (usuario, nombre, cedula, telefono, direccion, rol, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $usuario, $nombre, $cedula, $telefono, $direccion, $rol, $password);

    if (!$stmt->execute()) {
        die("Error al guardar usuario: " . $stmt->error);
    }

    // ID usuario
    $usuario_id = $stmt->insert_id;
    $stmt->close();

    // ---------------------------------------------------
    // ASIGNAR PERMISOS SEGÚN ROL
    // ---------------------------------------------------
    if ($rol == 'ADMINISTRADOR') {
        $query = "SELECT id FROM paginas_projectos";
    } elseif ($rol == 'VENTAS') {
        $paginas_ventas = [
            'Ventas.php',
            'ventas_select.php',
            'VerClientes.php',
            'VerDevolucion.php',
            'VerFechaVencimiento.php',
            'VerProductos.php',
            'VerReportes.php',
            'VerUsuario.php',
            'ver_detalle_factura.php',
            'ver_facturas.php',
            'url.php'
        ];
        $in = "'" . implode("','", $paginas_ventas) . "'";
        $query = "SELECT id FROM paginas_projectos WHERE pagina IN ($in)";
    } else {
        $query = "";
    }

    if ($query) {
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $sqlPerm = $conn->prepare("INSERT INTO permisos_usuario (id_usuario, id_permiso) VALUES (?, ?)");
            $sqlPerm->bind_param("ii", $usuario_id, $row['id']);
            $sqlPerm->execute();
            $sqlPerm->close();
        }
    }

    $conn->close();

    // Redirigir
    header("Location: ../VerUsuario.php");
    exit();
}
?>
