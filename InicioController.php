<?php
// --- Archivo: controller/InicioController.php (Actualizado) ---

class InicioController {

    /**
     * Muestra la página de inicio principal (ahora sin formulario).
     */
    public function index() {
        require_once 'views/V_inicio.php';
    }

    /**
     * --- NUEVO MÉTODO ---
     * Muestra la página de productos/campañas.
     */
    public function productos() {
        // Requerimos el modelo para poder usarlo
        require_once 'models/M_Campana.php';
        
        // Obtenemos solo las campañas activas y públicas usando la nueva función del modelo
        $campanas_activas = Campana::obtenerActivasPublicas();

        // Cargamos la nueva vista de productos y le pasamos los datos
        require_once 'views/V_productos.php';
    }
}
?>