<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="views/css/style_admin_dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?c=Admin&a=dashboard">Panel de Administrador</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="btn btn-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Gestión de Usuarios y Asignaciones</h3>
            <a href="index.php?c=Admin&a=dashboard" class="btn btn-secondary">Volver al Panel</a>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header"><h5 class="mb-0">Panel de Asignaciones</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Asignar Campaña a Jefe</h6>
                        <form action="index.php?c=Admin&a=asignarJefeCampana" method="POST">
                            <div class="mb-3">
                                <label for="campana_id" class="form-label">Campaña sin Asignar</label>
                                <select name="campana_id" id="campana_id" class="form-select" required>
                                    <option value="" disabled selected>Seleccione una campaña...</option>
                                    <?php foreach ($campanas_libres as $campana): ?>
                                        <option value="<?php echo $campana['id']; ?>"><?php echo htmlspecialchars($campana['nombre_campana']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jefe_id_campana" class="form-label">Jefe de Campaña</label>
                                <select name="jefe_id" id="jefe_id_campana" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un jefe...</option>
                                    <?php foreach ($jefes as $jefe): ?>
                                        <option value="<?php echo $jefe['id']; ?>"><?php echo htmlspecialchars($jefe['nombre'] . ' ' . $jefe['apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Asignar Campaña</button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6>Asignar Operario a Jefe y Campaña</h6>
                        <form action="index.php?c=Admin&a=asignarOperarioJefe" method="POST">
                            <div class="mb-3">
                                <label for="operario_id" class="form-label">Operario Disponible</label>
                                <select name="operario_id" id="operario_id" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un operario...</option>
                                    <?php foreach ($operarios_libres as $operario): ?>
                                        <option value="<?php echo $operario['id']; ?>"><?php echo htmlspecialchars($operario['nombre'] . ' ' . $operario['apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="jefe_id_operario" class="form-label">Asignar al Jefe</label>
                                <select name="jefe_id" id="jefe_id_operario" class="form-select" required>
                                    <option value="" disabled selected>Seleccione un jefe...</option>
                                    <?php foreach ($jefes as $jefe): ?>
                                        <option value="<?php echo $jefe['id']; ?>"><?php echo htmlspecialchars($jefe['nombre'] . ' ' . $jefe['apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- INICIO: Nuevo campo de Campaña -->
                            <div class="mb-3">
                                <label for="campana_id_operario" class="form-label">Asignar a la Campaña</label>
                                <select name="campana_id" id="campana_id_operario" class="form-select" required disabled>
                                    <option value="" disabled selected>Primero seleccione un jefe...</option>
                                </select>
                            </div>
                            <!-- FIN: Nuevo campo de Campaña -->
                            <button type="submit" class="btn btn-primary">Asignar Operario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header"><h5 class="mb-0">Lista General de Usuarios</h5></div>
            <div class="card-body">
                <div class="p-3 bg-light rounded mb-4">
                    <form action="index.php" method="GET">
                        <input type="hidden" name="c" value="Admin">
                        <input type="hidden" name="a" value="gestionarUsuarios">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label for="busqueda" class="form-label">Buscar por Nombre, Apellido o Cédula</label>
                                <input type="text" class="form-control" id="busqueda" name="busqueda" value="<?php echo htmlspecialchars($busqueda ?? ''); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="rol_id" class="form-label">Filtrar por Rol</label>
                                <select class="form-select" id="rol_id" name="rol_id">
                                    <option value="">Todos los Roles</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?php echo $rol['id']; ?>" <?php if (($rol_id_filtro ?? '') == $rol['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex">
                                <button type="submit" class="btn btn-primary flex-grow-1 me-2">Buscar</button>
                                <a href="index.php?c=Admin&a=gestionarUsuarios" class="btn btn-secondary">Limpiar</a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre Completo</th><th>Correo</th><th>Rol</th><th>Estado</th><th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios_todos as $usuario): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['correo']); ?></td>
                                    <td>
                                        <?php 
                                            $rol_nombre = htmlspecialchars($usuario['nombre_rol']);
                                            $badge_class = 'bg-secondary';
                                            if ($rol_nombre == 'Administrador') $badge_class = 'bg-primary';
                                            elseif ($rol_nombre == 'Jefe de Campaña') $badge_class = 'bg-info text-dark';
                                            elseif ($rol_nombre == 'Operario') $badge_class = 'bg-success';
                                            echo "<span class='badge $badge_class'>$rol_nombre</span>";
                                        ?>
                                    </td>
                                    <td><span class="badge <?php echo $usuario['estado'] == 'activo' ? 'bg-success' : 'bg-danger'; ?>"><?php echo ucfirst($usuario['estado']); ?></span></td>
                                    <td class="text-center">
                                        <a href="index.php?c=Admin&a=editarUsuario&id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                        <form action="index.php?c=Admin&a=gestionarEstadoUsuario" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de cambiar el estado de este usuario?');">
                                            <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                            <?php if ($usuario['estado'] == 'activo'): ?>
                                                <input type="hidden" name="accion" value="inhabilitar">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" title="Inhabilitar"><i class="bi bi-slash-circle"></i></button>
                                            <?php else: ?>
                                                <input type="hidden" name="accion" value="habilitar">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Habilitar"><i class="bi bi-check-circle"></i></button>
                                            <?php endif; ?>
                                        </form>
                                        <form action="index.php?c=Admin&a=gestionarEstadoUsuario" method="POST" class="d-inline" onsubmit="return confirm('¡ADVERTENCIA! Eliminar un usuario es permanente. ¿Continuar?');">
                                            <input type="hidden" name="usuario_id" value="<?php echo $usuario['id']; ?>">
                                            <input type="hidden" name="accion" value="eliminar">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- INICIO: JavaScript para el formulario dinámico -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Convertimos los datos de PHP (campañas por jefe) a un objeto JavaScript
            const campanasPorJefe = <?php echo json_encode($campanas_por_jefe_js ?? []); ?>;

            const jefeSelect = document.getElementById('jefe_id_operario');
            const campanaSelect = document.getElementById('campana_id_operario');

            jefeSelect.addEventListener('change', function () {
                const jefeId = this.value;
                
                // Limpiar opciones anteriores
                campanaSelect.innerHTML = '<option value="" disabled selected>Cargando campañas...</option>';

                if (jefeId && campanasPorJefe[jefeId]) {
                    // Habilitar el select de campañas
                    campanaSelect.disabled = false;
                    
                    // Limpiar y añadir la opción por defecto
                    campanaSelect.innerHTML = '<option value="" disabled selected>Seleccione una campaña...</option>';

                    // Añadir las campañas del jefe seleccionado
                    campanasPorJefe[jefeId].forEach(function (campana) {
                        const option = document.createElement('option');
                        option.value = campana.id;
                        option.textContent = campana.nombre_campana;
                        campanaSelect.appendChild(option);
                    });
                } else {
                    // Si no se selecciona un jefe o no tiene campañas, deshabilitar
                    campanaSelect.innerHTML = '<option value="" disabled selected>Primero seleccione un jefe...</option>';
                    campanaSelect.disabled = true;
                }
            });
        });
    </script>
    <!-- FIN: JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
