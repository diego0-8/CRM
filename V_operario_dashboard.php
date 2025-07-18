<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Operario - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Añadimos Bootstrap Icons para los botones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="views/css/style_admin_dashboard.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Panel de Operario</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="navbar-text text-white me-3">
                        Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Mis Clientes Asignados</h3>
        <p class="text-muted">Estos son los clientes que debes contactar. Registra cada interacción.</p>
        <hr>

        <?php if (empty($clientes_asignados)): ?>
            <div class="alert alert-info">No tienes clientes asignados en este momento.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clientes_asignados as $cliente): ?>
                    <?php
                        // Preparamos el número de teléfono para los enlaces
                        $telefono_limpio = preg_replace('/[^0-9]/', '', $cliente['telefono']);
                        // IMPORTANTE: Para WhatsApp, el número debería incluir el código de país (ej: 57 para Colombia)
                        // Si no lo tienes, el enlace podría no funcionar correctamente.
                        // Ejemplo: si el número es 3001234567, debería ser 573001234567
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($cliente['nombres_completos']); ?></h5>
                                <span class="badge <?php echo $cliente['estado'] == 'Asignado' ? 'bg-warning text-dark' : 'bg-info text-dark'; ?>">
                                    <?php echo htmlspecialchars($cliente['estado']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['telefono']); ?></p>
                                
                                <!-- INICIO: Botones de Acción de Llamada y WhatsApp -->
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-3">
                                    <a href="tel:<?php echo $telefono_limpio; ?>" class="btn btn-info text-white">
                                        <i class="bi bi-telephone-fill"></i> Llamar
                                    </a>
                                    <a href="https://wa.me/<?php echo $telefono_limpio; ?>" target="_blank" class="btn btn-success">
                                        <i class="bi bi-whatsapp"></i> Enviar WhatsApp
                                    </a>
                                </div>
                                <!-- FIN: Botones de Acción -->

                                <p><strong>Requerimiento inicial:</strong> <?php echo htmlspecialchars($cliente['requerimiento']); ?></p>
                                
                                <hr>
                                
                                <form action="index.php?c=Operario&a=actualizarCliente" method="POST">
                                    <input type="hidden" name="cliente_id" value="<?php echo $cliente['id']; ?>">
                                    
                                    <div class="mb-3">
                                        <label for="resultado-<?php echo $cliente['id']; ?>" class="form-label"><strong>Registrar Interacción / Notas</strong></label>
                                        <textarea class="form-control" id="resultado-<?php echo $cliente['id']; ?>" name="resultado" rows="3" required><?php echo htmlspecialchars($cliente['resultado'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="estado-<?php echo $cliente['id']; ?>" class="form-label"><strong>Actualizar Estado</strong></label>
                                        <select class="form-select" name="estado" id="estado-<?php echo $cliente['id']; ?>" required>
                                            <option value="Contactado" <?php if($cliente['estado'] == 'Contactado') echo 'selected'; ?>>Contactado</option>
                                            <option value="Vendido">Venta Concretada</option>
                                            <option value="Descartado">Descartado</option>
                                        </select>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">Guardar Gestión</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>