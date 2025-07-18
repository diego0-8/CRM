<?php
// --- Archivo: models/M_Campana.php ---
class Campana {
    
    /**
     * Crea una nueva campaña en la base de datos.
     * @param array $datos Datos del formulario de la campaña.
     * @return bool True si se crea con éxito, false en caso contrario.
     */
    public static function crear($datos) {
        $conexion = Conexion::conectar();
        $sql = "INSERT INTO campanas (nombre_campana, descripcion, fecha_inicio, creada_por) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        // Asume que el ID del creador (admin) está en la sesión.
        $creada_por = $_SESSION['usuario_id']; 
        $stmt->bind_param("sssi", $datos['nombre_campana'], $datos['descripcion'], $datos['fecha_inicio'], $creada_por);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * Asigna un Jefe de Campaña a una campaña existente.
     * @param int $campana_id ID de la campaña.
     * @param int $jefe_id ID del usuario que será el jefe.
     * @return bool True si la asignación es exitosa.
     */
    public static function asignarJefe($campana_id, $jefe_id) {
        $conexion = Conexion::conectar();
        // Se usa 'creada_por' para la asignación, según la estructura definida.
        $sql = "UPDATE campanas SET creada_por = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ii", $jefe_id, $campana_id);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * --- FUNCIÓN CORREGIDA ---
     * Obtiene todas las campañas con el nombre del jefe asignado (si lo tiene).
     * @return array Lista de todas las campañas.
     */
    public static function obtenerTodasConJefe() {
        $conexion = Conexion::conectar();
        $sql = "SELECT c.id, c.nombre_campana, c.descripcion, c.fecha_inicio, c.fecha_fin, c.estado, u.nombre as creador_nombre, u.apellido as creador_apellido 
                FROM campanas c 
                LEFT JOIN usuarios u ON c.creada_por = u.id 
                ORDER BY c.fecha_inicio DESC";
        $resultado = $conexion->query($sql);
        $campanas = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $campanas;
    }



    /**
     * Obtiene todas las campañas.
     * @return array Lista de todas las campañas.
     */
    public static function obtenerTodas() {
        $conexion = Conexion::conectar();
        $sql = "SELECT c.id, c.nombre_campana, c.descripcion, c.fecha_inicio, u.nombre as creador_nombre 
                FROM campanas c 
                LEFT JOIN usuarios u ON c.creada_por = u.id 
                ORDER BY c.fecha_inicio DESC";
        $resultado = $conexion->query($sql);
        $campanas = $resultado->fetch_all(MYSQLI_ASSOC);
        $conexion->close();
        return $campanas;
    }

    /**
     * Obtiene solo las campañas que no tienen un jefe asignado.
     * @return array Lista de campañas libres.
     */
    public static function obtenerLibres() {
        $conexion = Conexion::conectar();
        $sql = "SELECT id, nombre_campana FROM campanas WHERE creada_por IS NULL OR creada_por NOT IN (SELECT id FROM usuarios WHERE rol_id = 2)";
        $resultado = $conexion->query($sql);
        $campanas = $resultado->fetch_all(MYSQLI_ASSOC);
        $conexion->close();
        return $campanas;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene los datos de una campaña específica por su ID.
     */
    public static function obtenerPorId($id) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("SELECT id, nombre_campana, descripcion, creada_por FROM campanas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $campana = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $campana;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Actualiza la descripción y/o el jefe de una campaña.
     */
    public static function actualizar($datos) {
        $conexion = Conexion::conectar();
        $sql = "UPDATE campanas SET descripcion = ?, creada_por = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sii", $datos['descripcion'], $datos['jefe_id'], $datos['id']);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Cambia el estado de una campaña (activa/inactiva).
     */
    public static function cambiarEstado($id, $estado) {
        $conexion = Conexion::conectar();
        $sql = "UPDATE campanas SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $estado, $id);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Elimina una campaña de la base de datos.
     */
    public static function eliminar($id) {
        $conexion = Conexion::conectar();
        // Cuidado: Antes de eliminar, se deberían manejar los clientes potenciales de esta campaña.
        // Por ahora, la eliminamos directamente.
        $sql = "DELETE FROM campanas WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene todas las campañas que están asignadas a un jefe específico.
     * @param int $jefe_id El ID del jefe.
     * @return array La lista de campañas de ese jefe.
     */
    public static function obtenerPorJefe($jefe_id) {
        $conexion = Conexion::conectar();
        $sql = "SELECT id, nombre_campana FROM campanas WHERE creada_por = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $jefe_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $campanas = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $campanas;
    }

    public static function obtenerActivasPublicas() {
        $conexion = Conexion::conectar();
        // Seleccionamos solo las campañas activas que ya tienen un jefe (creada_por no es nulo)
        $sql = "SELECT c.id, c.nombre_campana, c.descripcion 
                FROM campanas c
                WHERE c.estado = 'activa' AND c.creada_por IS NOT NULL";
        $resultado = $conexion->query($sql);
        $campanas = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $campanas;
    }
}
?>