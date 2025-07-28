<?php
// --- Archivo: views/V_operario_dashboard.php (REDiseñado y Funcional) ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Operario - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        <!-- PANEL DE CONTROL DEL AGENTE -->
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0">Panel de Control del Agente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Controles de Estado -->
                    <div class="col-md-6">
                        <h6>Mi Estado</h6>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-success" onclick="marcarComoListo()"><i class="bi bi-check-circle-fill"></i> Listo (Ready)</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="entrarEnACW()"><i class="bi bi-pencil-fill"></i> Post-Llamada (ACW)</button>
                        </div>
                        <h6 class="mt-3">Visibilidad del Agente</h6>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="hacerAgenteVisible(true)"><i class="bi bi-eye-fill"></i> Visible</button>
                            <button type="button" class="btn btn-outline-danger" onclick="hacerAgenteVisible(false)"><i class="bi bi-eye-slash-fill"></i> Invisible</button>
                        </div>
                    </div>
                    <!-- Llamada Auxiliar y Transferencia -->
                    <div class="col-md-6">
                        <h6>Llamada Auxiliar / Transferencia</h6>
                        <div class="input-group">
                            <input type="tel" id="numero_auxiliar" class="form-control" placeholder="Ingresar número...">
                            <button class="btn btn-outline-info" type="button" onclick="realizarLlamadaAuxiliar(document.getElementById('numero_auxiliar').value)">Llamada Auxiliar</button>
                            <button class="btn btn-outline-warning" type="button" onclick="transferirLlamada(document.getElementById('numero_auxiliar').value)">Transferir</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <h3>Mis Clientes Asignados</h3>
        <p class="text-muted">Estos son los clientes que debes contactar. Registra cada interacción.</p>

        <?php if (empty($clientes_asignados)): ?>
            <div class="alert alert-info">No tienes clientes asignados en este momento.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clientes_asignados as $cliente): ?>
                    <?php
                        $telefono_limpio = preg_replace('/[^0-9]/', '', $cliente['telefono']);
                        $id_operario_actual = $_SESSION['usuario_id'] ?? '0';
                        $nombre_completo_cliente = htmlspecialchars($cliente['nombres_completos']);
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo $nombre_completo_cliente; ?></h5>
                                <span class="badge <?php echo $cliente['estado'] == 'Asignado' ? 'bg-warning text-dark' : 'bg-info text-dark'; ?>">
                                    <?php echo htmlspecialchars($cliente['estado']); ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['telefono']); ?></p>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" onclick="iniciarLlamadaConDatos('<?php echo $id_operario_actual; ?>', '<?php echo $telefono_limpio; ?>', '<?php echo $nombre_completo_cliente; ?>', this)">
                                        <i class="bi bi-telephone-outbound-fill"></i> Iniciar Llamada a Cliente
                                    </button>
                                </div>

                                <!-- Controles durante la llamada -->
                                <div class="mt-3 p-3 border rounded">
                                    <h6 class="text-muted">Controles en Llamada</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-danger" onclick="colgarLlamada()"><i class="bi bi-telephone-x-fill"></i> Colgar</button>
                                            <button class="btn btn-sm btn-secondary" onclick="ponerEnEspera()"><i class="bi bi-pause-fill"></i> Espera</button>
                                            <button class="btn btn-sm btn-warning" onclick="silenciarLlamada()"><i class="bi bi-mic-mute-fill"></i> Silenciar</button>
                                        </div>
                                        <div class="input-group" style="max-width: 150px;">
                                            <input type="text" id="dtmf_<?php echo $cliente['id']; ?>" class="form-control form-control-sm" placeholder="DTMF">
                                            <button class="btn btn-sm btn-outline-dark" onclick="enviarTonoDTMF(document.getElementById('dtmf_<?php echo $cliente['id']; ?>').value)">Enviar</button>
                                        </div>
                                    </div>
                                </div>
                                
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

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="views/js/wolkvox_integration.js"></script>
</body>
</html>
