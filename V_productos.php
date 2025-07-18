<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Productos - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="views/css/style.css"> <!-- Puedes usar tu estilo principal -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">OnixBPO</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php?c=Inicio&a=productos">Productos</a></li>
                    <a class="btn btn-primary ms-lg-3" href="index.php?c=Usuario&a=login">Iniciar Sesión</a>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="text-center mb-5">Nuestras Campañas y Servicios</h1>

        <div class="row">
            <?php if (empty($campanas_activas)): ?>
                <div class="col">
                    <p class="text-center text-muted">Actualmente no tenemos campañas disponibles. Vuelve pronto.</p>
                </div>
            <?php else: ?>
                <?php foreach ($campanas_activas as $campana): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?php echo htmlspecialchars($campana['nombre_campana']); ?></h5>
                                <p class="card-text text-muted flex-grow-1"><?php echo substr(htmlspecialchars($campana['descripcion']), 0, 100) . '...'; ?></p>
                                <button class="btn btn-outline-primary mt-auto" data-bs-toggle="modal" data-bs-target="#modal-campana-<?php echo $campana['id']; ?>">
                                    Más Información y Contacto
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal para cada campaña -->
                    <div class="modal fade" id="modal-campana-<?php echo $campana['id']; ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $campana['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-<?php echo $campana['id']; ?>"><?php echo htmlspecialchars($campana['nombre_campana']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><?php echo nl2br(htmlspecialchars($campana['descripcion'])); ?></p>
                                    <hr>
                                    <h6 class="mb-3">¿Interesado? Déjanos tus datos</h6>
                                    <form action="index.php?c=Cliente&a=registrar" method="POST">
                                        <!-- CAMPO CLAVE: Enviamos el ID de la campaña de forma oculta -->
                                        <input type="hidden" name="campana_id" value="<?php echo $campana['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="nombre-<?php echo $campana['id']; ?>" class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control" id="nombre-<?php echo $campana['id']; ?>" name="nombres_completos" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="telefono-<?php echo $campana['id']; ?>" class="form-label">Teléfono o WhatsApp</label>
                                            <input type="tel" class="form-control" id="telefono-<?php echo $campana['id']; ?>" name="telefono" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mensaje-<?php echo $campana['id']; ?>" class="form-label">Mensaje (opcional)</label>
                                            <textarea class="form-control" id="mensaje-<?php echo $campana['id']; ?>" name="mensaje" rows="2"></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <footer class="text-center py-4 bg-dark text-white">
        <p>&copy; 2025 OnixBPO. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
