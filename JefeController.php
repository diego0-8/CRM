<?php
// --- Archivo: controller/JefeController.php (Versión Final Corregida) ---

require_once 'models/M_Cliente.php';
require_once 'models/M_Usuario.php'; 

class JefeController {

    public function __construct() {
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'Jefe de Campaña') {
            header('Location: index.php?c=Usuario&a=login');
            exit();
        }
    }

    /**
     * --- MÉTODO CORREGIDO ---
     * Carga el panel principal y prepara los datos EXACTAMENTE como la vista los necesita.
     */
    public function dashboard() {
        $jefe_id = $_SESSION['usuario_id'];

        $operarios_equipo = Usuario::obtenerOperariosPorJefe($jefe_id);
        $clientes_nuevos = Cliente::obtenerClientesNuevosPorJefe($jefe_id);
        
        // La vista espera la variable '$gestion_activa_equipo'
        $gestion_activa_equipo = Cliente::obtenerGestionEquipo($jefe_id);

        // Calculamos los KPIs.
        $total_clientes_asignados = count($gestion_activa_equipo);
        $total_contactados = 0;
        $total_ventas = 0;
        foreach ($gestion_activa_equipo as $gestion) {
            if ($gestion['estado'] !== 'Asignado' && $gestion['estado'] !== 'Nuevo') {
                $total_contactados++;
            }
            if ($gestion['estado'] === 'Vendido') {
                $total_ventas++;
            }
        }
        
        // La vista espera un array llamado '$kpis'
        $kpis = [
            'total_asignados' => $total_clientes_asignados,
            'total_contactados' => $total_contactados,
            'total_ventas' => $total_ventas
        ];

        require_once 'views/V_jefe_dashboard.php';
    }

    public function verOperario() {
        $jefe_id = $_SESSION['usuario_id'];
        $operario_id = $_GET['id'] ?? null;

        if (!$operario_id) {
            header('Location: index.php?c=Jefe&a=dashboard');
            exit();
        }

        $operario_info = Usuario::obtenerPorId($operario_id);
        
        // CORRECCIÓN: La vista espera la variable '$gestion_historica'
        $gestion_historica = Cliente::obtenerGestionEquipo($jefe_id, $operario_id);

        require_once 'views/V_jefe_operario.php';
    }

    /**
     * Procesa la asignación de uno o varios clientes a un operario.
     */
    public function asignarCliente() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $jefe_id = $_SESSION['usuario_id'];
            $operario_id = $_POST['operario_id'];
            $cliente_ids_json = $_POST['cliente_ids_json'];
            $cliente_ids = json_decode($cliente_ids_json);

            if (is_array($cliente_ids) && !empty($cliente_ids) && !empty($operario_id)) {
                foreach ($cliente_ids as $cliente_id) {
                    // Llamamos a la función del modelo que ahora sí guardará los datos
                    Cliente::asignarOperario($cliente_id, $operario_id, $jefe_id);
                }
            }
            
            header("Location: index.php?c=Jefe&a=dashboard&status=asignado");
            exit();
        }
    }
}
?>
