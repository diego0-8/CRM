<?php
// --- Archivo: index.php (Corregido y Mejorado) ---

// --- CAMBIO CLAVE 1: Iniciar la sesión aquí ---
// Se inicia una sola vez para toda la aplicación.
// Esto evita errores y asegura que la sesión esté siempre disponible.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Se requiere la conexión a la base de datos, que es necesaria globalmente.
require_once 'models/M_Conexion.php';

// Controlador y acción por defecto (la página de inicio)
$controlador_nombre = 'Inicio'; 
$accion_nombre = 'index';      

if (!empty($_GET['c'])) {
    $controlador_nombre = $_GET['c'];
}

if (!empty($_GET['a'])) {
    $accion_nombre = $_GET['a'];
}

// Construimos la ruta al archivo del controlador
$archivo_controlador = 'controller/' . $controlador_nombre . 'Controller.php';
$clase_controlador = $controlador_nombre . 'Controller';

if (file_exists($archivo_controlador)) {
    
    require_once $archivo_controlador;
    $controlador = new $clase_controlador();

    if (method_exists($controlador, $accion_nombre)) {
        // Ejecutamos la acción del controlador
        $controlador->$accion_nombre();
    } else {
        die("Error: La acción '$accion_nombre' no existe en el controlador '$clase_controlador'.");
    }

} else {
    die("Error: El controlador en el archivo '$archivo_controlador' no se encontró.");
}
?>
