<?php
// --- Archivo: views/V_admin_editar_campana.php (NUEVO) ---
// Esta vista espera que AdminController le pase las variables:
// $campana (datos de la campaña a editar) y $jefes (lista de todos los jefes).
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Campaña - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Editando Campaña</h3>
                    </div>
                    <div class="card-body">
                        <form action="index.php?c=Admin&a=actualizarCampana" method="POST">
                            <input type="hidden" name="id" value="<?php echo $campana['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="nombre_campana" class="form-label">Nombre de la Campaña</label>
                                <input type="text" class="form-control" id="nombre_campana" name="nombre_campana" value="<?php echo htmlspecialchars($campana['nombre_campana']); ?>" disabled readonly>
                                <small class="form-text text-muted">El nombre de la campaña no se puede modificar.</small>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4"><?php echo htmlspecialchars($campana['descripcion']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="jefe_id" class="form-label">Jefe Asignado</label>
                                <select class="form-select" id="jefe_id" name="jefe_id" required>
                                    <option value="">-- Seleccione un Jefe --</option>
                                    <?php foreach ($jefes as $jefe): ?>
                                        <option value="<?php echo $jefe['id']; ?>" <?php if ($jefe['id'] == $campana['creada_por']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($jefe['nombre'] . ' ' . $jefe['apellido']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="index.php?c=Admin&a=gestionarCampanas" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
