<?php
// --- Archivo: views/V_login.php (REDiseñado) ---
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - OnixBPO</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tu nueva hoja de estilos para el login -->
    <link rel="stylesheet" href="views/css/style_login.css">
</head>
<body>

    <div class="login-wrapper">
        <div class="login-box">
            <!-- Columna Izquierda con la Imagen de Fondo -->
            <div class="login-image-panel">
                <div class="login-image-content">
                    <h2 class="fw-bold">OnixBPO</h2>
                    <p class="text-white-50">Soluciones que conectan.</p>
                    <a href="index.php" class="btn btn-outline-light mt-3">
                        <i class="bi bi-arrow-left"></i> Volver al Inicio
                    </a>
                </div>
            </div>

            <!-- Columna Derecha con el Formulario -->
            <div class="login-form-panel">
                <div class="form-content">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold">Bienvenido de Nuevo</h3>
                        <p class="text-muted">Ingresa a tu cuenta para continuar</p>
                    </div>

                    <?php
                        if (isset($_SESSION['error_login'])) {
                            echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_login']) . '</div>';
                            unset($_SESSION['error_login']);
                        }
                    ?>

                    <form action="index.php?c=Usuario&a=validarLogin" method="POST">
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="usuario" name="usuario" required placeholder="tu@correo.com">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required placeholder="********">
                        </div>
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>