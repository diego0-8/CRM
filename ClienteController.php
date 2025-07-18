<?php
// --- Archivo: controller/ClienteController.php (Corregido) ---

// --- CAMBIO CLAVE: Ruta corregida ---
require_once 'models/M_Cliente.php';

class ClienteController {
    public function registrar() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (Cliente::registrar($_POST)) {
                // --- CAMBIO CLAVE: Redirección corregida ---
                header("Location: index.php?status=success#contacto");
            } else {
                header("Location: index.php?status=error#contacto");
            }
            exit();
        }
    }
}
?>
