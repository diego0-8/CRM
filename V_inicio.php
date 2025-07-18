<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnixBPO - Soluciones Digitales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="views/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">OnixBPO</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?c=Inicio&a=productos">Productos</a></li>
                    <a class="btn btn-primary ms-lg-3" href="index.php?c=Usuario&a=login">Iniciar Sesión</a>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sección Principal (Hero) -->
    <header class="hero-section text-center text-white">
        <div class="container">
            <h1 class="display-4 fw-bold">Impulsamos tu Negocio al Siguiente Nivel</h1>
            <p class="lead my-4">Conectamos tus clientes con tus asesores en tiempo real. No pierdas ni una sola venta.</p>
            <!-- El botón principal ahora lleva a la página de productos -->
            <a href="index.php?c=Inicio&a=productos" class="btn btn-light btn-lg">Ver Nuestros Servicios</a>
        </div>
    </header>

    <!-- Sección de Servicios (Simplificada) -->
    <section id="servicios" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Soluciones a tu Medida</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <h4>Atención al Cliente</h4>
                    <p>Equipos especializados para gestionar las necesidades de tus clientes.</p>
                </div>
                <div class="col-md-4">
                    <h4>Generación de Leads</h4>
                    <p>Campañas efectivas para atraer nuevos prospectos a tu negocio.</p>
                </div>
                <div class="col-md-4">
                    <h4>Soporte Técnico</h4>
                    <p>Asistencia experta para resolver cualquier incidencia técnica.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de Página -->
    <footer class="text-center py-4 bg-dark text-white">
        <p>&copy; 2025 OnixBPO. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
