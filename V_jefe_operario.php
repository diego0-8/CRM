<?php
// --- V_jefe_dashboard_rediseñado.php ---
// Este es el nuevo panel central para el Jefe de Campaña.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Jefe de Campaña - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Se recomienda tener un CSS específico para este nuevo dashboard -->
    <link rel="stylesheet" href="views/css/style_jefe_dashboard.css">
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
                    <a class="btn btn-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        
        <!-- SECCIÓN DE KPIs -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Total Clientes Asignados</h5>
                        <p class="display-4 fw-bold text-primary"><?php echo $kpis['total_asignados'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Clientes Contactados</h5>
                        <p class="display-4 fw-bold text-info"><?php echo $kpis['total_contactados'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title text-muted">Ventas Concretadas</h5>
                        <p class="display-4 fw-bold text-success"><?php echo $kpis['total_ventas'] ?? 0; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Columna Principal: Panel de Gestión -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <!-- Pestañas para organizar las funciones -->
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#nuevos">Nuevos por Asignar <span class="badge bg-danger"><?php echo count($clientes_nuevos ?? []); ?></span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#seguimiento">Seguimiento del Equipo</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Pestaña 1: Clientes nuevos para asignar -->
                            <div class="tab-pane fade show active" id="nuevos">
                                <h5>Clientes Nuevos</h5>
                                <p class="text-muted">Selecciona los clientes y asígnalos a un operario de tu equipo.</p>
                                <?php if (empty($clientes_nuevos)): ?>
                                    <div class="alert alert-success">¡Excelente trabajo! No hay nuevos clientes pendientes.</div>
                                <?php else: ?>
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;"><input type="checkbox" id="selectAll"></th>
                                                <th>Cliente</th>
                                                <th>Teléfono</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach ($clientes_nuevos as $cliente): ?>
                                            <tr>
                                                <td><input type="checkbox" class="cliente-checkbox" name="cliente_ids[]" value="<?php echo $cliente['id']; ?>"></td>
                                                <td><?php echo htmlspecialchars($cliente['nombres_completos']); ?></td>
                                                <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#asignarModal">Asignar Seleccionados</button>
                                <?php endif; ?>
                            </div>

                            <!-- Pestaña 2: Tabla de seguimiento de todo el equipo -->
                            <div class="tab-pane fade" id="seguimiento">
                                <h5>Gestión Activa del Equipo</h5>
                                <p class="text-muted">Supervisa en tiempo real el trabajo de tus operarios.</p>
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Operario Asignado</th>
                                            <th>Estado</th>
                                            <th class="text-center">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(empty($gestion_activa_equipo)): ?>
                                        <tr><td colspan="4" class="text-center text-muted">No hay gestiones activas en este momento.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($gestion_activa_equipo as $gestion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($gestion['nombres_completos']); ?></td>
                                            <td><?php echo htmlspecialchars($gestion['operario_nombre']); ?></td>
                                            <td>
                                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($gestion['estado']); ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-secondary" title="Reasignar Cliente"><i class="bi bi-arrow-repeat"></i></button>
                                                    <a href="index.php?c=Jefe&a=verOperario&id=<?php echo $gestion['operario_id']; ?>" class="btn btn-sm btn-outline-info" title="Ver Historial"><i class="bi bi-search"></i></a>
                                                </div>
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
            </div>

            <!-- Columna Derecha: Mi Equipo -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header"><h4 class="mb-0">Mi Equipo</h4></div>
                    <div class="list-group list-group-flush">
                        <?php if(empty($operarios_equipo)): ?>
                             <div class="list-group-item">Aún no tienes operarios a tu cargo.</div>
                        <?php else: ?>
                            <?php foreach ($operarios_equipo as $operario): ?>
                                <a href="index.php?c=Jefe&a=verOperario&id=<?php echo $operario['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($operario['nombre'] . ' ' . $operario['apellido']); ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asignación en Masa -->
    <div class="modal fade" id="asignarModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Asignar Clientes</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p>Selecciona el operario al que deseas asignar los <span id="cantidad-clientes"></span> clientes seleccionados.</p>
            <!-- ======================= CORRECCIÓN IMPORTANTE AQUÍ ======================= -->
            <form id="form-asignacion-masa" action="index.php?c=Jefe&a=asignarCliente" method="POST">
                <input type="hidden" name="cliente_ids_json" id="cliente_ids_json">
                <select name="operario_id" class="form-select" required>
                    <option value="" disabled selected>Seleccionar operario...</option>
                    <?php foreach ($operarios_equipo as $operario): ?>
                        <option value="<?php echo $operario['id']; ?>"><?php echo htmlspecialchars($operario['nombre'] . ' ' . $operario['apellido']); ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('form-asignacion-masa').submit();">Confirmar Asignación</button>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para la selección masiva de clientes
        document.getElementById('selectAll').addEventListener('change', function(e) {
            document.querySelectorAll('.cliente-checkbox').forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
        });

        // Script para actualizar el modal de asignación
        const asignarModal = document.getElementById('asignarModal');
        asignarModal.addEventListener('show.bs.modal', function () {
            const checkboxes = document.querySelectorAll('.cliente-checkbox:checked');
            const clienteIds = Array.from(checkboxes).map(cb => cb.value);
            document.getElementById('cantidad-clientes').textContent = clienteIds.length;
            document.getElementById('cliente_ids_json').value = JSON.stringify(clienteIds);
        });
    </script>
</body>
</html>
