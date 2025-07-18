<?php
// --- Archivo: controller/OperarioController.php (Corregido) ---

// --- CAMBIO 1: Ruta corregida ---
// La ruta ahora es directa desde el index.php
require_once 'models/M_Cliente.php';

class OperarioController {

    public function __construct() {
        // La sesión ya está iniciada por index.php
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Operario') {
            // Redirigir al login si no tiene permisos
            header('Location: index.php?c=Usuario&a=login');
            exit();
        }
    }

    /**
     * --- CAMBIO 2: Lógica añadida al Dashboard ---
     * Ahora el controlador obtiene los datos y se los pasa a la vista.
     */
    public function dashboard() {
        $operario_id = $_SESSION['usuario_id'];
        
        // Llamamos al modelo para obtener solo los clientes de este operario
        $clientes_asignados = Cliente::obtenerClientesPorOperario($operario_id);

        // Cargamos la vista, que ahora tendrá acceso a la variable $clientes_asignados
        require_once 'views/V_operario_dashboard.php';
    }

    public function actualizarCliente() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $datos = [
                'cliente_id' => $_POST['cliente_id'],
                'estado' => $_POST['estado'],
                'resultado' => $_POST['resultado'],
                'operario_id' => $_SESSION['usuario_id'] // Usamos el ID de la sesión
            ];
            
            // Suponiendo que tienes una función 'actualizarGestion' en tu modelo M_Cliente
            Cliente::actualizarGestion($datos);
            
            header("Location: index.php?c=Operario&a=dashboard&status=actualizado");
            exit();
        }
    }
}
?>