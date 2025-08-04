<?php
// --- Archivo: models/M_Cliente.php (Actualizado) ---
class Cliente {
    
    /**
     * Registra un nuevo cliente potencial desde el formulario público.
     * @param array $datos Datos del cliente.
     * @return bool True si se registra con éxito.
     */
    public static function registrar($datos) {
        $conexion = Conexion::conectar();
        // --- CORRECCIÓN CRÍTICA ---
        // El campana_id no debe ser fijo. Aquí asumimos que se pasa en los datos.
        // Si no, se necesita otra lógica para determinar la campaña.
        $sql = "INSERT INTO clientes_potenciales (nombres_completos, correo, telefono, requerimiento, campana_id, estado) VALUES (?, ?, ?, ?, ?, 'Nuevo')";
        $stmt = $conexion->prepare($sql);
        
        $campana_id = isset($datos['campana_id']) ? $datos['campana_id'] : 1; // Usar campana_id del form, o default 1
        $correo = isset($datos['correo']) ? $datos['correo'] : 'sin_correo@prospecto.com';
        
        $stmt->bind_param("ssssi", $datos['nombres_completos'], $correo, $datos['telefono'], $datos['mensaje'], $campana_id);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    public static function actualizarGestion($datos) {
        $conexion = Conexion::conectar();
        $conexion->begin_transaction();
        try {
            // 1. Actualizar estado del cliente
            $sql_cliente = "UPDATE clientes_potenciales SET estado = ? WHERE id = ?";
            $stmt_cliente = $conexion->prepare($sql_cliente);
            $stmt_cliente->bind_param("si", $datos['estado'], $datos['cliente_id']);
            $stmt_cliente->execute();
            $stmt_cliente->close();

            // 2. Actualizar la interacción
            $venta_concretada = ($datos['estado'] === 'Vendido') ? 1 : 0;
            $sql_interaccion = "UPDATE interacciones SET resultado = ?, fecha_contacto = NOW(), venta_concretada = ? WHERE cliente_id = ? AND operario_id = ?";
            $stmt_interaccion = $conexion->prepare($sql_interaccion);
            $stmt_interaccion->bind_param("siii", $datos['resultado'], $venta_concretada, $datos['cliente_id'], $datos['operario_id']);
            $stmt_interaccion->execute();
            $stmt_interaccion->close();

            $conexion->commit();
            return true;
        } catch (Exception $e) {
            $conexion->rollback();
            // Opcional: registrar el error $e->getMessage() en un log.
            return false;
        } finally {
            $conexion->close();
        }
    }

    public static function obtenerGestionEquipo($jefe_id, $operario_id = null) {
        $conexion = Conexion::conectar();
        
        $sql = "SELECT 
                    cp.id as cliente_id, 
                    cp.nombres_completos, 
                    cp.telefono,
                    cp.estado,
                    u.id as operario_id,
                    u.nombre as operario_nombre,
                    u.apellido as operario_apellido,
                    i.resultado,
                    i.fecha_contacto,
                    i.id as interaccion_id
                    -- En el futuro, aquí podrías añadir una columna para la URL de la grabación
                    -- i.url_grabacion 
                FROM interacciones i
                JOIN clientes_potenciales cp ON i.cliente_id = cp.id
                JOIN usuarios u ON i.operario_id = u.id
                WHERE i.jefe_id = ?";

        $params = [$jefe_id];
        $types = "i";

        if ($operario_id !== null) {
            $sql .= " AND i.operario_id = ?";
            $params[] = $operario_id;
            $types .= "i";
        }

        $sql .= " ORDER BY i.fecha_contacto DESC, i.fecha_asignacion DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $clientes;
    }

    public static function asignarOperario($cliente_id, $operario_id, $jefe_id) {
        $conexion = Conexion::conectar();
        $conexion->begin_transaction();
        try {
            // 1. Actualizar el estado del cliente a 'Asignado'
            $sql_update = "UPDATE clientes_potenciales SET estado = 'Asignado' WHERE id = ?";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->bind_param("i", $cliente_id);
            $stmt_update->execute();
            $stmt_update->close();

            // 2. Crear el registro de la interacción
            $sql_insert = "INSERT INTO interacciones (cliente_id, operario_id, jefe_id, fecha_asignacion) VALUES (?, ?, ?, NOW())";
            $stmt_insert = $conexion->prepare($sql_insert);
            $stmt_insert->bind_param("iii", $cliente_id, $operario_id, $jefe_id);
            $stmt_insert->execute();
            $stmt_insert->close();

            $conexion->commit();
            return true;
        } catch (Exception $e) {
            $conexion->rollback();
            // Opcional: registrar el error en un log para depuración
            // error_log('Error al asignar cliente: ' . $e->getMessage());
            return false;
        } finally {
            $conexion->close();
        }
    }
    
    public static function obtenerClientesPorOperario($operario_id) {
        $conexion = Conexion::conectar();
        $sql = "SELECT cp.id, cp.nombres_completos, cp.telefono, cp.requerimiento, cp.estado, i.resultado
                FROM clientes_potenciales cp
                JOIN interacciones i ON cp.id = i.cliente_id
                WHERE i.operario_id = ? AND cp.estado IN ('Asignado', 'Contactado')";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $operario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $clientes;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene los clientes en estado 'Nuevo' de las campañas asociadas a un jefe.
     * @param int $jefe_id El ID del jefe de campaña.
     * @return array La lista de clientes nuevos.
     */
    public static function obtenerClientesNuevosPorJefe($jefe_id) {
        $conexion = Conexion::conectar();
        // Esta consulta busca clientes 'Nuevos' en campañas donde el jefe es el 'creada_por'.
        $sql = "SELECT cp.id, cp.nombres_completos, cp.telefono, cp.fecha_registro
                FROM clientes_potenciales cp
                JOIN campanas c ON cp.campana_id = c.id
                WHERE c.creada_por = ? AND cp.estado = 'Nuevo'";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $jefe_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $clientes;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene los clientes que ya fueron asignados a un operario del equipo de un jefe.
     * @param int $jefe_id El ID del jefe de campaña.
     * @return array La lista de clientes asignados.
     */
    public static function obtenerClientesAsignadosPorJefe($jefe_id) {
        $conexion = Conexion::conectar();
        // Esta consulta busca interacciones creadas por el jefe y une los datos del cliente y operario.
        $sql = "SELECT 
                    cp.nombres_completos, 
                    cp.estado,
                    u.nombre as operario_nombre,
                    u.apellido as operario_apellido,
                    i.fecha_asignacion,
                    i.resultado
                FROM interacciones i
                JOIN clientes_potenciales cp ON i.cliente_id = cp.id
                JOIN usuarios u ON i.operario_id = u.id
                WHERE i.jefe_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $jefe_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $clientes = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $clientes;
    }
}
?>
