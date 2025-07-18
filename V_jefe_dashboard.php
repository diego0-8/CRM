<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Jefe de Campaña - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Ruta al CSS corregida para funcionar desde index.php -->
    <link rel="stylesheet" href="views/css/style_admin_dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Panel Jefe de Campaña</a>
            <ul class="navbar-nav ms-auto">
                 <li class="nav-item">
                    <span class="navbar-text text-white me-3">
                        Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <!-- Ruta al logout corregida -->
                    <a class="btn btn-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Mensaje de estado opcional -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'asignado'): ?>
            <div class="alert alert-success">Cliente asignado correctamente.</div>
        <?php endif; ?>

        <div class="row">
            <!-- Columna Principal: Clientes por Asignar -->
            <div class="col-md-8">
                <h3>Clientes Potenciales por Asignar</h3>
                <hr>
                <?php if (empty($clientes_nuevos)): ?>
                    <div class="alert alert-success" role="alert">
                      ¡Excelente trabajo! No hay nuevos clientes pendientes de asignación.
                    </div>
                <?php else: ?>
                    <?php foreach ($clientes_nuevos as $cliente): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header fw-bold">
                                <?php echo htmlspecialchars($cliente['nombres_completos']); ?>
                            </div>
                            <div class="card-body">
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['telefono']); ?></p>
                                <p><strong>Registrado el:</strong> <?php echo date("d/m/Y H:i", strtotime($cliente['fecha_registro'])); ?></p>
                                
                                <hr>
                                <!-- Formulario de asignación con ruta corregida -->
                                <form action="index.php?c=Jefe&a=asignarCliente" method="POST" class="row g-2 align-items-center">
                                    <input type="hidden" name="cliente_id" value="<?php echo $cliente['id']; ?>">
                                    <div class="col-sm-8">
                                        <label for="operario-<?php echo $cliente['id']; ?>" class="visually-hidden">Operario</label>
                                        <select name="operario_id" id="operario-<?php echo $cliente['id']; ?>" class="form-select" required>
                                            <option value="" disabled selected>Asignar a...</option>
                                            <?php foreach ($operarios_equipo as $operario): ?>
                                                <option value="<?php echo $operario['id']; ?>">
                                                    <?php echo htmlspecialchars($operario['nombre'] . ' ' . $operario['apellido']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary w-100">Asignar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Columna Derecha: Información de Equipo -->
            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Mi Equipo</h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($operarios_equipo)): ?>
                             <p class="text-muted">Aún no tienes operarios a tu cargo.</p>
                        <?php else: ?>
                            <ul class="list-group list-group-flush">
                                <?php foreach ($operarios_equipo as $operario): ?>
                                    <li class="list-group-item"><?php echo htmlspecialchars($operario['nombre'] . ' ' . $operario['apellido']); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>