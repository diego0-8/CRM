<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Operario - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Estilos para el Softphone y la página -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .softphone-modal .modal-content {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            background-color: #2c3e50;
            color: white;
        }
        .softphone-display {
            background-color: #ecf0f1;
            color: #2c3e50;
            border-radius: 8px;
            padding: 15px;
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            border: 2px solid #3498db;
            font-family: 'Courier New', Courier, monospace;
            min-height: 74px; /* Espacio para que no salte el layout */
        }
        .keypad-btn {
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            margin: 5px;
            border: none;
            background-color: #34495e;
            color: white;
            transition: background-color 0.2s;
        }
        .keypad-btn:hover {
            background-color: #4a627a;
        }
        .call-controls .btn {
            font-size: 1.2rem;
            border-radius: 10px;
            padding: 10px 20px;
            margin: 0 5px;
        }
        #call-status {
            font-weight: bold;
            padding: 5px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        .status-disconnected { background-color: #e74c3c; }
        .status-connecting { background-color: #f39c12; }
        .status-connected { background-color: #2ecc71; }
        
        /* Animación para error de validación */
        @keyframes shake {
          10%, 90% { transform: translate3d(-1px, 0, 0); }
          20%, 80% { transform: translate3d(2px, 0, 0); }
          30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
          40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        .shake {
          animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Panel de Operario</a>
            <ul class="navbar-nav ms-auto">
                <!-- ======================= BOTÓN NUEVO PARA MARCACIÓN MANUAL ======================= -->
                <li class="nav-item me-3">
                    <button type="button" class="btn btn-light" onclick="showPhone('')">
                        <i class="bi bi-keyboard-fill"></i> Marcación Manual
                    </button>
                </li>
                <li class="nav-item">
                    <span class="navbar-text text-white me-3">
                        Hola, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-light" href="index.php?c=Usuario&a=logout">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h3>Mis Clientes Asignados</h3>
        <p class="text-muted">Haz clic en "Llamar con Softphone" para iniciar la marcación.</p>

        <?php if (empty($clientes_asignados)): ?>
            <div class="alert alert-info">No tienes clientes asignados en este momento.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($clientes_asignados as $cliente): ?>
                    <?php
                        $telefono_limpio = preg_replace('/[^0-9]/', '', $cliente['telefono']);
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header">
                                <h5><?php echo htmlspecialchars($cliente['nombres_completos']); ?></h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($cliente['telefono']); ?></p>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" onclick="showPhone('<?php echo $telefono_limpio; ?>')">
                                        <i class="bi bi-telephone-outbound-fill"></i> Llamar con Softphone
                                    </button>
                                </div>
                                
                                <hr>
                                
                                <form action="index.php?c=Operario&a=actualizarCliente" method="POST">
                                    <!-- ... (formulario de gestión sin cambios) ... -->
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- MODAL DEL SOFTPHONE -->
    <div class="modal fade softphone-modal" id="softphoneModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Teléfono Web</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="call-status" class="status-disconnected">Desconectado</div>
                    <div id="softphone-display" class="softphone-display"></div>
                    
                    <div class="text-center mb-3">
                        <div class="btn-group keypad">
                            <button class="keypad-btn" onclick="pressKey('1')">1</button>
                            <button class="keypad-btn" onclick="pressKey('2')">2</button>
                            <button class="keypad-btn" onclick="pressKey('3')">3</button>
                        </div>
                        <div class="btn-group keypad">
                            <button class="keypad-btn" onclick="pressKey('4')">4</button>
                            <button class="keypad-btn" onclick="pressKey('5')">5</button>
                            <button class="keypad-btn" onclick="pressKey('6')">6</button>
                        </div>
                        <div class="btn-group keypad">
                            <button class="keypad-btn" onclick="pressKey('7')">7</button>
                            <button class="keypad-btn" onclick="pressKey('8')">8</button>
                            <button class="keypad-btn" onclick="pressKey('9')">9</button>
                        </div>
                        <div class="btn-group keypad">
                            <button class="keypad-btn" onclick="pressKey('*')">*</button>
                            <button class="keypad-btn" onclick="pressKey('0')">0</button>
                            <button class="keypad-btn" onclick="pressKey('#')">#</button>
                        </div>
                    </div>

                    <!-- ======================= BOTÓN NUEVO PARA BORRAR ======================= -->
                    <div class="text-center mb-3">
                        <button class="btn btn-secondary" onclick="backspace()">
                            <i class="bi bi-backspace-fill"></i> Borrar
                        </button>
                    </div>

                    <div class="d-flex justify-content-center call-controls">
                        <button id="call-button" class="btn btn-success" onclick="makeCall()">
                            <i class="bi bi-telephone-fill"></i>
                        </button>
                        <button id="hangup-button" class="btn btn-danger" onclick="hangupCall()" disabled>
                            <i class="bi bi-telephone-x-fill"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sipUser = "<?php echo $_SESSION['sip_user'] ?? ''; ?>";
        const sipPassword = "<?php echo $_SESSION['sip_secret'] ?? ''; ?>";
    </script>

    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="views/js/sip-0.20.0.js" ></script>
    <script src="views/js/softphone_sip_fixed.js" defer></script>

</body>
</html>
