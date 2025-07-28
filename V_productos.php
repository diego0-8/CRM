<?php
// --- Archivo: views/V_productos.php (REDiseñado) ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestros Servicios - OnixBPO</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="views/css/style_inicio.css">
    <link rel="stylesheet" href="views/css/style_productos.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-bar-chart-line-fill text-primary"></i> OnixBPO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link active" href="index.php?c=Inicio&a=productos">Nuestros Servicios</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="index.php?c=Usuario&a=login">Iniciar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section-productos d-flex align-items-center justify-content-center text-center">
        <div class="container">
            <h1 class="display-4 fw-bold text-white">Nuestras Campañas y Servicios</h1>
            <p class="lead my-4 text-white-50">Descubre cómo nuestras soluciones personalizadas pueden potenciar tu negocio.</p>
        </div>
    </header>

    <section class="services-carousel-section py-5">
        <div class="container">
            <div id="serviciosCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php if (!empty($campanas_activas)): ?>
                        <?php foreach ($campanas_activas as $index => $campana): ?>
                            <button type="button" data-bs-target="#serviciosCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index == 0 ? 'active' : ''; ?>" aria-current="<?php echo $index == 0 ? 'true' : 'false'; ?>" aria-label="Slide <?php echo $index + 1; ?>"></button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="carousel-inner">
                    <?php if (empty($campanas_activas)): ?>
                        <div class="carousel-item active">
                            <div class="d-block w-100 text-center p-5">
                                <h3>No hay servicios disponibles</h3>
                                <p class="text-muted">Por favor, vuelve más tarde.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($campanas_activas as $index => $campana): ?>
                            <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="carousel-image-container">
                                            <?php
                                                $imageUrl = !empty($campana['imagen_url']) 
                                                            ? 'uploads/campanas/' . htmlspecialchars($campana['imagen_url']) 
                                                            : 'https://placehold.co/600x400/EAEAEA/808080?text=Servicio';
                                            ?>
                                            <img src="<?php echo $imageUrl; ?>" class="carousel-image" alt="Imagen del servicio <?php echo htmlspecialchars($campana['nombre_campana']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="carousel-caption-custom">
                                            <h3 class="fw-bold"><?php echo htmlspecialchars($campana['nombre_campana']); ?></h3>
                                            <p class="my-3 text-muted"><?php echo htmlspecialchars($campana['descripcion']); ?></p>
                                            <p><strong>En este servicio encontrarás:</strong> Un breve enunciado sobre los beneficios, como por ejemplo: optimización de recursos, mejora en la comunicación y aumento de la satisfacción del cliente.</p>
                                            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#modal-campana-<?php echo $campana['id']; ?>">
                                                Me Interesa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#serviciosCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#serviciosCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <section class="company-image-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold">Tecnología y Talento Humano</h2>
                    <p class="text-muted">Combinamos las mejores herramientas tecnológicas con un equipo de profesionales altamente capacitados para garantizar el éxito de tus campañas. Nuestra infraestructura robusta y escalable se adapta a las necesidades de tu negocio, asegurando un servicio de alta disponibilidad y rendimiento.</p>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?q=80&w=1974&auto=format&fit=crop" alt="Imagen del equipo de OnixBPO" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-section py-5">
        <div class="container">
             <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold"><i class="bi bi-bar-chart-line-fill text-primary"></i> OnixBPO</h5>
                    <p class="text-muted mt-3">Soluciones integrales para la gestión de la relación con sus clientes.</p>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="fw-bold">Menú Rápido</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="footer-link">Inicio</a></li>
                        <li><a href="index.php?c=Inicio&a=productos" class="footer-link">Servicios</a></li>
                        <li><a href="index.php?c=Usuario&a=login" class="footer-link">Login de Agentes</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="fw-bold">Políticas</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="footer-link">Políticas y Aviso Legal</a></li>
                        <li><a href="#" class="footer-link">Términos de Servicio</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted">
                <p>&copy; <?php echo date('Y'); ?> OnixBPO. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <?php if (!empty($campanas_activas)): ?>
        <?php foreach ($campanas_activas as $campana): ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>