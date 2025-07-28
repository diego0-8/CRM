<?php
// --- Archivo: views/V_inicio.php (REDiseñado) ---
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnixBPO - Soluciones Profesionales para Contact Center</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tu nueva hoja de estilos para la página de inicio -->
    <link rel="stylesheet" href="views/css/style_inicio.css">
</head>
<body>

    <!-- BARRA DE NAVEGACIÓN -->
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
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?c=Inicio&a=productos">Nuestros Servicios</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="index.php?c=Usuario&a=login">Iniciar Sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- SECCIÓN HERO (PRINCIPAL) -->
    <header class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="container">
            <h1 class="display-4 fw-bold text-white">SERVICIOS PROFESIONALES DE CALL CENTER</h1>
            <p class="lead my-4 text-white-50">Optimizamos la comunicación con tus clientes a través de tecnología y personal calificado.</p>
        </div>
    </header>

    <!-- SECCIÓN DE CARACTERÍSTICAS -->
    <section class="features-section py-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-item p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="bi bi-person-check-fill"></i>
                        </div>
                        <h4 class="fw-bold">Atención Personalizada</h4>
                        <p class="text-muted">Nos adaptamos a las necesidades de cada cliente para ofrecer soluciones a medida y un trato cercano.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-item p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                        </div>
                        <h4 class="fw-bold">Experiencia y Solidez</h4>
                        <p class="text-muted">Contamos con años de experiencia en el sector, garantizando procesos eficientes y resultados probados.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-item p-4">
                        <div class="icon-circle mb-3 mx-auto">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="fw-bold">Excelencia Garantizada</h4>
                        <p class="text-muted">Nuestro compromiso es con la calidad, aplicando las mejores prácticas para maximizar tu rentabilidad.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE MISIÓN Y ESTADÍSTICAS -->
    <section class="mission-section py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="fw-bold">NUESTRA MISIÓN</h2>
                    <p class="text-muted">Ser el socio estratégico que impulse el crecimiento de nuestros clientes a través de soluciones de contact center innovadoras, eficientes y humanas. Nos comprometemos a transformar cada interacción en una oportunidad de negocio y fidelización.</p>
                </div>
                <div class="col-lg-6">
                    <div class="row text-center">
                        <div class="col-4">
                            <h3 class="display-5 fw-bold text-primary">143+</h3>
                            <p class="text-muted">Clientes Satisfechos</p>
                        </div>
                        <div class="col-4">
                            <h3 class="display-5 fw-bold text-primary">25+</h3>
                            <p class="text-muted">Campañas Exitosas</p>
                        </div>
                        <div class="col-4">
                            <h3 class="display-5 fw-bold text-primary">12</h3>
                            <p class="text-muted">Años de Experiencia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN CALL TO ACTION -->
    <section class="cta-section text-center text-white py-5">
        <div class="container">
            <h2 class="fw-bold">Contadores, Administradores y Abogados conforman nuestro equipo de profesionales.</h2>
            <p class="my-4">Vea todo lo que podemos hacer por usted.</p>
            <a href="index.php?c=Inicio&a=productos" class="btn btn-light btn-lg">Conozca Nuestros Servicios</a>
        </div>
    </section>

    <!-- FOOTER -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>