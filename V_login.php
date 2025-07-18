<?php
// Iniciar sesión para poder acceder a las variables de sesión (para mostrar errores).
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Estilo personalizado para el login -->
    <link rel="stylesheet" href="views/css/style_login.css">
    
</head>
<body>

    <div class="login-container">
        <div class="card p-4">
            <div class="card-body">
                <div class="text-center mb-4">
                    <a class="navbar-brand fw-bold text-primary fs-4" href="index.php">OnixBPO</a>
                    <h5 class="text-muted mt-2">Acceso para Asesores</h5>
                </div>
                
                <?php
                    // Verificar si existe un mensaje de error en la sesión.
                    if (isset($_SESSION['error_login'])) {
                        // Mostrar el mensaje de error en una alerta de Bootstrap.
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_SESSION['error_login']) . '</div>';
                        
                        // Eliminar el mensaje de la sesión para que no se muestre de nuevo.
                        unset($_SESSION['error_login']);
                    }
                ?>

                <!-- Formulario de Login -->
                <form action="index.php?c=Usuario&a=validarLogin" method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario o Email</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Ingresar</button>
                    </div>
                    <div class="text-center">
                        <a href="#" class="text-decoration-none">¿Olvidaste tu contraseña?</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!--  Botón para volver al inicio -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-decoration-none text-muted">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16" style="vertical-align: -0.125em;">
                  <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8zm15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5z"/>
                </svg>
                Volver a la página principal
            </a>
        </div>

        <footer class="text-center py-4 text-muted">
            <p>&copy; 2025 OnixBPO. Todos los derechos reservados.</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
