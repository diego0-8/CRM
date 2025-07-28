<?php
// --- Archivo: views/V_admin_dashboard.php (MODIFICADO) ---
// Se añade el campo para subir imagen y el atributo enctype al formulario.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <?php if (isset($_GET['status'])): ?>
            <div class="alert alert-success">
                Operación realizada con éxito (<?php echo htmlspecialchars($_GET['status']); ?>).
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <a href="index.php?c=Admin&a=gestionarCampanas" class="card-link">
                    <div class="card shadow-sm"><div class="card-body card-stat">
                        <h5 class="card-title text-muted">Gestionar Campañas</h5>
                        <p class="display-4"><?php echo $conteo_campanas ?? 0; ?></p>
                        <small class="text-muted">Campañas Activas</small>
                    </div></div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="index.php?c=Admin&a=gestionarUsuarios" class="card-link">
                    <div class="card shadow-sm"><div class="card-body card-stat">
                        <h5 class="card-title text-muted">Gestionar Usuarios</h5>
                        <p class="display-4"><?php echo $conteo_usuarios ?? 0; ?></p>
                        <small class="text-muted">Usuarios Registrados</small>
                    </div></div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header"><h4>Resumen de Asignaciones</h4></div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead><tr><th>Campaña</th><th>Jefe Asignado</th></tr></thead>
                            <tbody>
                                <?php if (!empty($campanas_con_jefe)): ?>
                                    <?php foreach ($campanas_con_jefe as $asig): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($asig['nombre_campana']); ?></td>
                                            <td><strong><?php echo htmlspecialchars($asig['nombre']." ".$asig['apellido']); ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="2" class="text-center">No hay campañas con jefes asignados.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <h4>Equipos por Jefe de Campaña</h4>
                <div class="row">
                    <?php if (!empty($jefes)): ?>
                        <?php foreach ($jefes as $jefe): ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-header">Equipo de: <strong><?php echo htmlspecialchars($jefe['nombre'].' '.$jefe['apellido']); ?></strong></div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <?php if(isset($operarios_por_jefe[$jefe['id']])): foreach($operarios_por_jefe[$jefe['id']] as $op): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?php echo htmlspecialchars($op['nombre'].' '.$op['apellido']); ?>
                                                    <form action="index.php?c=Admin&a=liberarOperario" method="POST" onsubmit="return confirm('¿Está seguro que desea liberar a este operario?');">
                                                        <input type="hidden" name="operario_id" value="<?php echo $op['id']; ?>">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">Liberar</button>
                                                    </form>
                                                </li>
                                            <?php endforeach; else: echo '<li class="list-group-item text-muted">Sin operarios asignados.</li>'; endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No hay Jefes de Campaña registrados.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Acciones Rápidas</h4>
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Crear Usuario</h5>
                        <form action="index.php?c=Admin&a=crearUsuario" method="POST">
                            <input type="text" name="cedula" class="form-control mb-2" placeholder="Cédula" required>
                            <input type="text" name="nombre" class="form-control mb-2" placeholder="Nombre" required>
                            <input type="text" name="apellido" class="form-control mb-2" placeholder="Apellido" required>
                            <input type="email" name="correo" class="form-control mb-2" placeholder="Correo" required>
                            <input type="password" name="password" class="form-control mb-2" placeholder="Contraseña" required>
                            <select name="rol_id" class="form-select mb-2" required><option value="" disabled selected>Seleccionar Rol...</option><option value="1">Administrador</option><option value="2">Jefe de Campaña</option><option value="3">Operario</option></select>
                            <div class="d-grid"><button type="submit" class="btn btn-success">Crear Usuario</button></div>
                        </form>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Crear Nueva Campaña</h5>
                        <form action="index.php?c=Admin&a=crearCampana" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nombre_campana" class="form-label">Nombre de la Campaña</label>
                                <input type="text" class="form-control" id="nombre_campana" name="nombre_campana" required>
                            </div>
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="imagen_campana" class="form-label">Imagen de la Campaña</label>
                                <input class="form-control" type="file" id="imagen_campana" name="imagen_campana" accept="image/jpeg, image/png">
                                <small class="form-text text-muted">Opcional. Se usará en la página de servicios.</small>
                            </div>
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">Crear Campaña</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
