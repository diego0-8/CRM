<?php
// --- Archivo: models/M_Usuario.php (Corregido y Completo) ---
// Contiene todas las funciones necesarias para los controladores.

class Usuario {
    
    /**
     * Busca un usuario por su correo electrónico.
     * @param string $correo El correo del usuario.
     * @return array|null Los datos del usuario si se encuentra, o null si no.
     */
    public static function buscarPorCorreo($correo) {
        $conexion = Conexion::conectar();
        $sql = "SELECT u.id, u.nombre, u.apellido, u.password_hash, u.estado, r.nombre_rol 
                FROM usuarios u
                JOIN roles r ON u.rol_id = r.id
                WHERE u.correo = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $usuario;
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     * @param array $datos Los datos del usuario del formulario.
     * @return bool True si se creó con éxito, false si no.
     */
    public static function crear($datos) {
        $conexion = Conexion::conectar();
        $sql = "INSERT INTO usuarios (cedula, nombre, apellido, correo, password_hash, rol_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt->bind_param("sssssi", $datos['cedula'], $datos['nombre'], $datos['apellido'], $datos['correo'], $password_hash, $datos['rol_id']);
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * Actualiza los datos de un usuario existente.
     * @param array $datos Los nuevos datos del usuario.
     * @return bool True si se actualizó con éxito, false si no.
     */
    public static function actualizar($datos) {
        $conexion = Conexion::conectar();
        $sql = "";
        if (!empty($datos['password'])) {
            $password_hash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, rol_id = ?, password_hash = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssisi", $datos['nombre'], $datos['apellido'], $datos['correo'], $datos['rol_id'], $password_hash, $datos['id']);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, rol_id = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssii", $datos['nombre'], $datos['apellido'], $datos['correo'], $datos['rol_id'], $datos['id']);
        }
        $exito = $stmt->execute();
        $stmt->close();
        $conexion->close();
        return $exito;
    }

    /**
     * Cambia el estado de un usuario (activo/inactivo).
     * @param int $id El ID del usuario.
     * @param string $nuevo_estado El nuevo estado ('activo' o 'inactivo').
     */
    public static function cambiarEstado($id, $nuevo_estado) {
        $conexion = Conexion::conectar();
        $sql = "UPDATE usuarios SET estado = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $nuevo_estado, $id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    /**
     * Elimina un usuario de la base de datos.
     * @param int $id El ID del usuario a eliminar.
     */
    public static function eliminar($id) {
        $conexion = Conexion::conectar();
        $conexion->begin_transaction();
        try {
            // Primero, liberar a los operarios que dependen de este jefe (si es jefe)
            $sql_liberar = "UPDATE usuarios SET jefe_id = NULL WHERE jefe_id = ?";
            $stmt_liberar = $conexion->prepare($sql_liberar);
            $stmt_liberar->bind_param("i", $id);
            $stmt_liberar->execute();
            $stmt_liberar->close();

            // Ahora, eliminar al usuario
            $sql_eliminar = "DELETE FROM usuarios WHERE id = ?";
            $stmt_eliminar = $conexion->prepare($sql_eliminar);
            $stmt_eliminar->bind_param("i", $id);
            $stmt_eliminar->execute();
            $stmt_eliminar->close();
            
            $conexion->commit();
        } catch (Exception $e) {
            $conexion->rollback();
            // Opcional: registrar el error $e->getMessage()
        } finally {
            $conexion->close();
        }
    }


    /**
     * --- FUNCIÓN ACTUALIZADA ---
     * Asigna un jefe Y una campaña a un operario.
     * @param int $operario_id El ID del operario.
     * @param int $jefe_id El ID del jefe.
     * @param int $campana_id El ID de la campaña.
     */
    public static function asignarJefe($operario_id, $jefe_id, $campana_id) {
        $conexion = Conexion::conectar();
        // Ahora la consulta actualiza tanto el jefe_id como el campana_id
        $sql = "UPDATE usuarios SET jefe_id = ?, campana_id = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iii", $jefe_id, $campana_id, $operario_id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    /**
     * Libera a un operario de su jefe.
     * @param int $operario_id El ID del operario.
     */
    public static function liberarOperario($operario_id) {
        $conexion = Conexion::conectar();
        $sql = "UPDATE usuarios SET jefe_id = NULL WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $operario_id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

    // --- FUNCIONES PARA OBTENER DATOS (Aquí está la que faltaba) ---

    /**
     * Cuenta el total de usuarios registrados.
     * @return int El número total de usuarios.
     */
    public static function contarUsuarios() {
        $conexion = Conexion::conectar();
        $resultado = $conexion->query("SELECT COUNT(id) as total FROM usuarios");
        $total = $resultado ? $resultado->fetch_assoc()['total'] : 0;
        $conexion->close();
        return $total;
    }

    /**
     * Obtiene todos los usuarios con el rol de "Jefe de Campaña".
     * @return array La lista de jefes.
     */
    public static function obtenerJefes() {
        $conexion = Conexion::conectar();
        $resultado = $conexion->query("SELECT id, nombre, apellido FROM usuarios WHERE rol_id = 2");
        $jefes = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $jefes;
    }
    
    /**
     * Obtiene todos los operarios que tienen un jefe asignado.
     * @param mysqli $conexion Una instancia de la conexión a la BD.
     * @return array La lista de operarios.
     */
    public static function obtenerOperariosAsignados($conexion) {
        $query = "SELECT id, nombre, apellido, jefe_id FROM usuarios WHERE rol_id = 3 AND jefe_id IS NOT NULL";
        $resultado = $conexion->query($query);
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Obtiene todos los operarios que están bajo el mando de un jefe específico.
     * @param int $jefe_id El ID del jefe.
     * @return array La lista de operarios.
     */
    public static function obtenerOperariosPorJefe($jefe_id) {
        $conexion = Conexion::conectar();
        $sql = "SELECT id, nombre, apellido FROM usuarios WHERE rol_id = 3 AND jefe_id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $jefe_id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $operarios = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $operarios;
    }

    public static function obtenerTodosConRol($busqueda = '', $rol_id_filtro = '') {
        $conexion = Conexion::conectar();
        $sql = "SELECT u.id, u.cedula, u.nombre, u.apellido, u.correo, u.estado, r.nombre_rol 
                FROM usuarios u JOIN roles r ON u.rol_id = r.id";
        
        $where_clauses = [];
        $params = [];
        $types = '';

        if (!empty($busqueda)) {
            $where_clauses[] = "(u.nombre LIKE ? OR u.apellido LIKE ? OR u.cedula LIKE ?)";
            $like_busqueda = "%" . $busqueda . "%";
            array_push($params, $like_busqueda, $like_busqueda, $like_busqueda);
            $types .= 'sss';
        }

        if (!empty($rol_id_filtro)) {
            $where_clauses[] = "u.rol_id = ?";
            $params[] = $rol_id_filtro;
            $types .= 'i';
        }

        if (!empty($where_clauses)) {
            $sql .= " WHERE " . implode(' AND ', $where_clauses);
        }
        $sql .= " ORDER BY u.nombre ASC";

        $stmt = $conexion->prepare($sql);
        if (!empty($types)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conexion->close();
        return $usuarios;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene un usuario específico por su ID.
     */
    public static function obtenerPorId($id) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("SELECT id, cedula, nombre, apellido, correo, rol_id FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();
        $conexion->close();
        return $usuario;
    }

    /**
     * --- NUEVA FUNCIÓN ---
     * Obtiene los operarios que no tienen un jefe asignado.
     */
    public static function obtenerOperariosLibres() {
        $conexion = Conexion::conectar();
        $resultado = $conexion->query("SELECT id, nombre, apellido FROM usuarios WHERE rol_id = 3 AND jefe_id IS NULL");
        $operarios = $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
        $conexion->close();
        return $operarios;
    }
} 
?>