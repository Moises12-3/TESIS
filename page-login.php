<?php
session_start();
if (isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit();
}

// Obtener imágenes del fondo
$imagenes = glob("fondo/*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
?>
<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>🔐 Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="apple-touch-icon" href="images/favicon.png">
<link rel="shortcut icon" href="images/favicon.png">

<!-- Bootstrap & Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
body, html { height: 100%; margin: 0; }
.background-slideshow {
    position: fixed; top:0; left:0;
    width:100%; height:100%;
    z-index:-1; overflow:hidden;
}
.background-slideshow img {
    position:absolute;
    width:100%; height:100%;
    object-fit:cover;
    display:none;
}
.login-box {
    background:rgba(0,0,0,0.7);
    padding:40px;
    border-radius:12px;
    color:#fff;
    box-shadow:0 8px 25px rgba(0,0,0,0.5);
}
.form-control { border-radius:30px; padding-left:40px; }
.input-icon { position:absolute; left:15px; top:10px; color:#888; }
.btn-login { border-radius:30px; font-size:1.1rem; }
.password-toggle {
    position: absolute;
    right: 15px;
    top: 10px;
    cursor: pointer;
    color: #888;
}
</style>
</head>
<body>

<div class="background-slideshow">
    <?php foreach ($imagenes as $img) {
        echo "<img src='$img'>";
    } ?>
</div>

<div class="d-flex justify-content-center align-items-center" style="height:100%;">
    <div class="login-box col-md-4">
        <div class="text-center mb-4">
            <img src="images/favicon.png" alt="Logo" class="img-fluid mb-2" style="max-height:80px;">
            <h4>👋 ¡Bienvenido!</h4>
            <p>Inicia sesión para continuar</p>
        </div>

        <form id="loginForm">
            <div class="form-group position-relative">
                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                <input type="email" class="form-control" name="email" placeholder="📧 Correo electrónico" required>
            </div>
            <div class="form-group position-relative">
                <span class="input-icon"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="🔑 Contraseña" required>
                <span class="password-toggle"><i class="fas fa-eye" id="togglePassword"></i></span>
            </div>
            <button type="submit" class="btn btn-success btn-block btn-login">🚀 Entrar</button>

            <?php
            // Obtener la URL del sitio actual
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $siteURL = $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

            // Mensaje de WhatsApp con el link clickeable
            $whatsappMsg = urlencode("Hola, tengo problemas al iniciar sesion en este sitio: $siteURL");
            ?>
            <div class="mt-3 text-center">
                <small>
                    <a href="https://api.whatsapp.com/send?phone=50588090180&text=<?php echo $whatsappMsg; ?>" target="_blank" class="text-light">
                        ❓ ¿Olvidaste tu contraseña?
                    </a>
                </small>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center" id="modal-message"></div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(function(){
    // Slideshow con fade
    let images = $(".background-slideshow img");
    let current = 0;
    images.eq(current).fadeIn(1000);
    setInterval(() => {
        images.eq(current).fadeOut(1000);
        current = (current + 1) % images.length;
        images.eq(current).fadeIn(1000);
    }, 5000);

    // Toggle contraseña
    $("#togglePassword").on("click", function(){
        let input = $("#password");
        if(input.attr("type") === "password"){
            input.attr("type", "text");
            $(this).removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            $(this).removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    // Login AJAX
    $("#loginForm").on("submit", function(e){
        e.preventDefault();
        $.ajax({
            url: "Configuracion/login.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(res){
                if(res.status === "success"){
                    // Si el backend especifica una ruta, usarla; si no, fallback a index.php
                    if(res.redirect){
                        window.location.href = res.redirect;
                    } else {
                        window.location.href = "index.php";
                    }
                } else {
                    const colors = {danger:"text-danger", warning:"text-warning"};
                    $("#modal-message")
                        .removeClass()
                        .addClass("p-3 " + (colors[res.type] || ""))
                        .html(`<h5>${res.message}</h5>`);
                    $("#alertModal").modal("show");
                    setTimeout(()=>{ $("#alertModal").modal("hide"); }, 3000);
                }
            },
            error: function(){
                $("#modal-message")
                    .removeClass()
                    .addClass("p-3 text-danger")
                    .html("<h5>⚠️ Error al procesar la solicitud.</h5>");
                $("#alertModal").modal("show");
                setTimeout(()=>{ $("#alertModal").modal("hide"); }, 3000);
            }
        });
    });
});
</script>
</body>
</html>
