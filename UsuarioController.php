<?php
// --- Archivo: controller/UsuarioController.php ---

// Requerimos todos los modelos que este controlador va a necesitar.
require_once 'models/M_Usuario.php';
require_once 'models/M_Campana.php';
require_once 'models/M_Cliente.php';

class UsuarioController {

    /**
     * Muestra la vista de login o redirige si ya hay sesión.
     */
    public function login() {
        // Carga la vista del formulario de login.
        require_once 'views/V_login.php';
    }

    /**
     * Valida los datos del formulario de login.
     */
    public function validarLogin() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $correo = $_POST['usuario'];
            $password = $_POST['password'];
            $usuario = Usuario::buscarPorCorreo($correo);

            if ($usuario && password_verify($password, $usuario['password_hash'])) {
                if ($usuario['estado'] !== 'activo') {
                    header("Location: index.php?c=Usuario&a=login&error=inactivo");
                    exit();
                }
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'] . " " . $usuario['apellido'];
                $_SESSION['usuario_rol'] = $usuario['nombre_rol'];

                switch ($usuario['nombre_rol']) {
                    case 'Administrador': header("Location: index.php?c=Admin&a=dashboard"); break;
                    case 'Jefe de Campaña': header("Location: index.php?c=Jefe&a=dashboard"); break;
                    case 'Operario': header("Location: index.php?c=Operario&a=dashboard"); break;
                    default: header("Location: index.php?c=Usuario&a=login&error=rol");
                }
                exit();
            } else {
                header("Location: index.php?c=Usuario&a=login&error=credenciales");
                exit();
            }
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        session_start();
        session_destroy();
        header("Location: index.php?c=Usuario&a=login");
        exit();
    }

    /**
     * Procesa la creación de un nuevo usuario (llamado desde un formulario de admin).
     */
    public function crear() {
        session_start();
        if ($_SESSION['usuario_rol'] !== 'Administrador') { die("Acceso denegado."); }
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            Usuario::crear($_POST);
            header("Location: index.php?c=Admin&a=gestionarUsuarios&exito=creado");
            exit();
        }
    }
    
    // --- Aquí irían los demás métodos que tenías en tus otros controladores ---
    // public function actualizar() { ... }
    // public function eliminar() { ... }
    // public function asignarJefe() { ... }
}
?>
