<?php
// --- Archivo: controller/UsuarioController.php (Corregido) ---

require_once 'models/M_Usuario.php';
require_once 'models/M_Campana.php';
require_once 'models/M_Cliente.php';

class UsuarioController {

    public function login() {
        require_once 'views/V_login.php';
    }

    public function validarLogin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $correo = $_POST['usuario'];
            $password = $_POST['password'];
            $usuario = Usuario::buscarPorCorreo($correo);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                if ($usuario['estado'] !== 'activo') {
                    // Preparamos un mensaje de error para la vista de login
                    $_SESSION['error_login'] = 'Este usuario se encuentra inactivo.';
                    header("Location: index.php?c=Usuario&a=login");
                    exit();
                }
                
                // La sesión ya fue iniciada por index.php, solo asignamos las variables.
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'] . " " . $usuario['apellido'];
                $_SESSION['usuario_rol'] = $usuario['nombre_rol'];
                $_SESSION['sip_user'] = $usuario['sip_user'] ?? null;
                $_SESSION['sip_secret'] = $usuario['sip_secret'] ?? null;

                switch ($usuario['nombre_rol']) {
                    case 'Administrador': header("Location: index.php?c=Admin&a=dashboard"); break;
                    case 'Jefe de Campaña': header("Location: index.php?c=Jefe&a=dashboard"); break;
                    case 'Operario': header("Location: index.php?c=Operario&a=dashboard"); break;
                    default: 
                        $_SESSION['error_login'] = 'Rol de usuario no reconocido.';
                        header("Location: index.php?c=Usuario&a=login");
                }
                exit();
            } else {
                $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
                header("Location: index.php?c=Usuario&a=login");
                exit();
            }
        }
    }

    public function logout() {
        // La sesión ya está iniciada, solo la destruimos.
        session_destroy();
        header("Location: index.php?c=Usuario&a=login");
        exit();
    }

    public function crear() {
        // La sesión ya está iniciada.
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') { 
            die("Acceso denegado."); 
        }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            Usuario::crear($_POST);
            header("Location: index.php?c=Admin&a=gestionarUsuarios&exito=creado");
            exit();
        }
    }
}
?>