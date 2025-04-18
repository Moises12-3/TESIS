<?php
session_start(); // Inicia la sesión

// Incluir la conexión
require_once "Conexion/conex.php";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Preparar consulta segura
    $stmt = $conn->prepare("SELECT id, email, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $db_email, $db_password);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $db_password)) {
            $_SESSION["usuario"] = $db_email;
            $_SESSION["id"] = $id;
            header("Location: index.php"); // Redirigir si es correcto
            exit();
        } else {
            header("Location: page-login.php?mensaje=Incorrecto");
            //$error = "Usuario o contraseña incorrectos";
        }
    } else {
        header("Location: page-login.php?mensaje=UserNotSearch");
        //$error = "Usuario no encontrado";
    }

    $stmt->close();
}
?>

<!-- Mostrar errores -->
<?php if (!empty($error)): ?>
    <div style="color: red; font-weight: bold;"><?php echo $error; ?></div>
<?php endif; ?>
