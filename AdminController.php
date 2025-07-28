<?php
// --- Archivo: controller/AdminController.php (Actualizado y Completo) ---

require_once 'models/M_Usuario.php';
require_once 'models/M_Campana.php'; 
require_once 'models/M_Cliente.php';
// M_Conexion.php ya se carga en index.php

class AdminController {

    public function __construct() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Administrador') {
            header('Location: index.php?c=Usuario&a=login');
            exit();
        }
    }

    /**
     * Carga el panel principal del administrador.
     * Mueve la lógica de obtención de datos a los modelos.
     */
     public function dashboard() {
        $conteo_usuarios = Usuario::contarUsuarios();
        $jefes = Usuario::obtenerJefes();
        
        $conexion = Conexion::conectar();
        $resultado_conteo_campanas = $conexion->query("SELECT COUNT(id) as total FROM campanas WHERE fecha_fin IS NULL OR fecha_fin >= CURDATE()");
        $conteo_campanas = $resultado_conteo_campanas ? $resultado_conteo_campanas->fetch_assoc()['total'] : 0;

        $resultado_campanas_jefe = $conexion->query("SELECT c.nombre_campana, u.nombre, u.apellido FROM campanas c JOIN usuarios u ON c.creada_por = u.id WHERE u.rol_id = 2");
        $campanas_con_jefe = $resultado_campanas_jefe ? $resultado_campanas_jefe->fetch_all(MYSQLI_ASSOC) : [];
        
        $operarios_asignados = Usuario::obtenerOperariosAsignados($conexion);
        $operarios_por_jefe = [];
        foreach ($operarios_asignados as $operario) {
            if(isset($operario['jefe_id'])) {
                $operarios_por_jefe[$operario['jefe_id']][] = $operario;
            }
        }
        $conexion->close();
        
        require_once 'views/V_admin_dashboard.php';
    }

    /**
     * Carga la página para gestionar usuarios y prepara todos los datos necesarios.
     */
    public function gestionarUsuarios() {
        $busqueda = $_GET['busqueda'] ?? '';
        $rol_id_filtro = $_GET['rol_id'] ?? '';

        $usuarios_todos = Usuario::obtenerTodosConRol($busqueda, $rol_id_filtro);
        $roles = $this->obtenerRoles();
        $campanas_libres = Campana::obtenerLibres();
        $jefes = Usuario::obtenerJefes();
        $operarios_libres = Usuario::obtenerOperariosLibres();

        // --- LÓGICA AÑADIDA PARA EL FORMULARIO DINÁMICO ---
        $campanas_por_jefe_js = [];
        foreach ($jefes as $jefe) {
            // Por cada jefe, obtenemos sus campañas usando el modelo M_Campana
            $campanas_por_jefe_js[$jefe['id']] = Campana::obtenerPorJefe($jefe['id']);
        }
        // --- FIN DE LA LÓGICA AÑADIDA ---

        require_once 'views/V_admin_usuarios.php';
    }

    /**
     * Muestra la página para gestionar campañas.
     */
    public function gestionarCampanas() {
        $campanas = Campana::obtenerTodasConJefe();
        require_once 'views/V_admin_campanas.php';
    }

    /**
     * Muestra el formulario para editar un usuario.
     */
    public function editarUsuario() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?c=Admin&a=gestionarUsuarios');
            exit();
        }
        $usuario = Usuario::obtenerPorId($id);
        $roles = $this->obtenerRoles();
        require_once 'views/V_admin_editar_usuario.php';
    }

    /**
     * --- NUEVO MÉTODO ---
     * Procesa la actualización de una campaña.
     */
    public function actualizarCampana() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = $_POST;
            $directorio_destino = "uploads/campanas/";

            // Verificar si se subió una nueva imagen
            if (isset($_FILES['imagen_campana']) && $_FILES['imagen_campana']['error'] == UPLOAD_ERR_OK) {
                
                $archivo_temporal = $_FILES['imagen_campana']['tmp_name'];
                $nombre_original = basename($_FILES['imagen_campana']['name']);
                $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
                $nombre_nuevo_archivo = uniqid('campana_', true) . '.' . $extension;
                $ruta_nueva = $directorio_destino . $nombre_nuevo_archivo;

                if (move_uploaded_file($archivo_temporal, $ruta_nueva)) {
                    // Si la subida es exitosa, borramos la imagen anterior si existía
                    if (!empty($datos['imagen_actual'])) {
                        $ruta_antigua = $directorio_destino . $datos['imagen_actual'];
                        if (file_exists($ruta_antigua)) {
                            unlink($ruta_antigua);
                        }
                    }
                    // Asignamos el nombre del nuevo archivo para guardarlo en la BD
                    $datos['imagen_url'] = $nombre_nuevo_archivo;
                }
            } else {
                // --- ¡ESTA ES LA CORRECCIÓN CLAVE! ---
                // Si no se subió una nueva imagen, mantenemos la que ya estaba.
                $datos['imagen_url'] = $datos['imagen_actual'];
            }

            // Llamamos al modelo para actualizar la base de datos
            Campana::actualizar($datos);
            
            header('Location: index.php?c=Admin&a=gestionarCampanas&status=actualizada');
            exit();
        }
    }

    /**
     * --- NUEVO MÉTODO ---
     * Gestiona el cambio de estado y la eliminación de campañas.
     */
    public function gestionarEstadoCampana() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
           $accion = $_POST['accion'];
           $id = $_POST['id'];
           switch ($accion) {
               case 'deshabilitar': Campana::cambiarEstado($id, 'inactiva'); break;
               case 'habilitar': Campana::cambiarEstado($id, 'activa'); break;
               case 'eliminar': Campana::eliminar($id); break;
           }
           header("Location: index.php?c=Admin&a=gestionarCampanas&status=gestionada");
           exit();
       }
    }

    /**
     * --- MÉTODO AÑADIDO ---
     * Muestra el formulario para editar una campaña.
     */
    public function editarCampana() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: index.php?c=Admin&a=gestionarCampanas');
            exit();
        }
        $campana = Campana::obtenerPorId($id);
        $jefes = Usuario::obtenerJefes();
        require_once 'views/V_admin_editar_campana.php';
    }

    /**
     * Procesa la actualización de un usuario.
     */
    public function actualizarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            Usuario::actualizar($_POST);
            header('Location: index.php?c=Admin&a=gestionarUsuarios&status=actualizado');
            exit();
        }
    }

    public function crearUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            Usuario::crear($_POST);
            header("Location: index.php?c=Admin&a=dashboard&status=usuario_creado");
            exit();
        }
    }
    
    public function crearCampana() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            $datos = $_POST;
            $nombre_archivo_imagen = null;

            if (isset($_FILES['imagen_campana']) && $_FILES['imagen_campana']['error'] == UPLOAD_ERR_OK) {
                
                $archivo_temporal = $_FILES['imagen_campana']['tmp_name'];
                $nombre_original = basename($_FILES['imagen_campana']['name']);
                $extension = pathinfo($nombre_original, PATHINFO_EXTENSION);
                
                $directorio_destino = "uploads/campanas/";
                
                $nombre_archivo_imagen = uniqid('campana_', true) . '.' . $extension;
                
                $ruta_destino = $directorio_destino . $nombre_archivo_imagen;

                if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
                    $datos['imagen_url'] = $nombre_archivo_imagen;
                } else {
                    error_log("Error al mover el archivo subido a " . $ruta_destino);
                    $datos['imagen_url'] = null;
                }
            }

            if (Campana::crear($datos)) {
                header("Location: index.php?c=Admin&a=dashboard&status=campana_creada");
            } else {
                header("Location: index.php?c=Admin&a=dashboard&status=error_campana");
            }
            exit();
        }
    }

    /**
     * Gestiona el cambio de estado (habilitar/inhabilitar) y la eliminación de usuarios.
     */
    public function gestionarEstadoUsuario() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
           $accion = $_POST['accion'];
           $usuario_id = $_POST['usuario_id'];
           switch ($accion) {
               case 'inhabilitar': Usuario::cambiarEstado($usuario_id, 'inactivo'); break;
               case 'habilitar': Usuario::cambiarEstado($usuario_id, 'activo'); break;
               case 'eliminar': Usuario::eliminar($usuario_id); break;
           }
           header("Location: index.php?c=Admin&a=gestionarUsuarios&status=gestionado");
           exit();
       }
   }

   /**
    * Asigna un jefe a una campaña.
    */
   public function asignarJefeCampana() {
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
           Campana::asignarJefe($_POST['campana_id'], $_POST['jefe_id']);
           header("Location: index.php?c=Admin&a=gestionarUsuarios&status=asignado");
           exit();
       }
   }

   public function asignarOperarioJefe() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $operario_id = $_POST['operario_id'];
            $jefe_id = $_POST['jefe_id'];
            $campana_id = $_POST['campana_id']; // Recibimos el nuevo campo

            // Llamamos a la función del modelo actualizada con los 3 parámetros
            Usuario::asignarJefe($operario_id, $jefe_id, $campana_id);
            
            header("Location: index.php?c=Admin&a=gestionarUsuarios&status=asignado");
            exit();
        }
    }

   /**
    * Libera a un operario de su jefe.
    */
   public function liberarOperario() {
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
           Usuario::liberarOperario($_POST['operario_id']);
           header("Location: index.php?c=Admin&a=dashboard&status=liberado");
           exit();
       }
   }

   /**
    * Método privado para obtener los roles.
    */
   private function obtenerRoles() {
       $conexion = Conexion::conectar();
       $resultado = $conexion->query("SELECT id, nombre_rol FROM roles ORDER BY nombre_rol");
       $roles = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
       $conexion->close();
       return $roles;
   }
}
?>

