<?php
// --- Archivo: controller/JefeController.php (Actualizado y Funcional) ---

// Requerimos los modelos necesarios para que el controlador funcione.
// Las rutas son directas desde el index.php
require_once 'models/M_Cliente.php';
require_once 'models/M_Usuario.php'; 

class JefeController {

    public function __construct() {
        // La sesión ya fue iniciada por index.php, solo la validamos.
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Jefe de Campaña') {
            // Si no es un Jefe, lo redirigimos a la página de login.
            header('Location: index.php?c=Usuario&a=login');
            exit();
        }
    }

    /**
     * Carga el panel principal (dashboard) del Jefe de Campaña.
     * Prepara todos los datos que la vista necesita mostrar.
     */
    public function dashboard() {
        // Obtenemos el ID del jefe que ha iniciado sesión.
        $jefe_id = $_SESSION['usuario_id'];

        // 1. Obtenemos la lista de operarios que pertenecen al equipo de este jefe.
        $operarios_equipo = Usuario::obtenerOperariosPorJefe($jefe_id);

        // 2. Obtenemos los clientes potenciales que están en estado 'Nuevo'
        //    y pertenecen a la campaña que gestiona este jefe.
        $clientes_nuevos = Cliente::obtenerClientesNuevosPorJefe($jefe_id);
        
        // 3. Obtenemos un resumen de los clientes que ya han sido asignados
        //    a los operarios de su equipo.
        $clientes_asignados = Cliente::obtenerClientesAsignadosPorJefe($jefe_id);


        // 4. Cargamos la vista del dashboard del jefe.
        //    La vista ahora tendrá acceso a las variables:
        //    $operarios_equipo, $clientes_nuevos, $clientes_asignados
        require_once 'views/V_jefe_dashboard.php';
    }

    /**
     * Procesa la asignación de un cliente a un operario.
     * Este método es llamado por el formulario en el dashboard del jefe.
     */
    public function asignarCliente() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtenemos el ID del jefe de la sesión para registrar quién hizo la asignación.
            $jefe_id = $_SESSION['usuario_id'];
            
            // Llamamos al método del modelo para realizar la asignación.
            Cliente::asignarOperario($_POST['cliente_id'], $_POST['operario_id'], $jefe_id);
            
            // Redirigimos de vuelta al dashboard con un mensaje de éxito.
            header("Location: index.php?c=Jefe&a=dashboard&status=asignado");
            exit();
        }
    }
}
?>
