<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Campañas - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="views/css/style_admin_campanas.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php?c=Admin&a=dashboard">Panel de Administrador</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Gestión de Campañas</h3>
            <a href="index.php?c=Admin&a=dashboard" class="btn btn-secondary">Volver al Panel</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre de la Campaña</th>
                                <!-- INICIO: Columna añadida -->
                                <th>Descripción</th>
                                <!-- FIN: Columna añadida -->
                                <th>Jefe Asignado</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($campanas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay campañas registradas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($campanas as $campana): ?>
                                    <?php
                                        $estado = $campana['estado'] ?? 'inactiva';
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($campana['nombre_campana']); ?></td>
                                        <!-- INICIO: Celda añadida para mostrar la descripción -->
                                        <td>
                                            <small class="text-muted"><?php echo htmlspecialchars($campana['descripcion']); ?></small>
                                        </td>
                                        <!-- FIN: Celda añadida -->
                                        <td>
                                            <?php if (!empty($campana['creador_nombre'])): ?>
                                                <?php echo htmlspecialchars($campana['creador_nombre'] . ' ' . $campana['creador_apellido']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">Sin asignar</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $estado == 'activa' ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo ucfirst($estado); ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="index.php?c=Admin&a=editarCampana&id=<?php echo $campana['id']; ?>" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil-square"></i></a>
                                            
                                            <form action="index.php?c=Admin&a=gestionarEstadoCampana" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $campana['id']; ?>">
                                                <?php if ($estado == 'activa'): ?>
                                                    <input type="hidden" name="accion" value="deshabilitar">
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Deshabilitar" onclick="return confirm('¿Estás seguro de deshabilitar esta campaña?');"><i class="bi bi-slash-circle"></i></button>
                                                <?php else: ?>
                                                    <input type="hidden" name="accion" value="habilitar">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Habilitar" onclick="return confirm('¿Estás seguro de habilitar esta campaña?');"><i class="bi bi-check-circle"></i></button>
                                                <?php endif; ?>
                                            </form>

                                            <form action="index.php?c=Admin&a=gestionarEstadoCampana" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $campana['id']; ?>">
                                                <input type="hidden" name="accion" value="eliminar">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar" onclick="return confirm('¡ADVERTENCIA! Eliminar una campaña es permanente. ¿Continuar?');"><i class="bi bi-trash3"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>