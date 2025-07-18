<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - OnixBPO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Editando a <?php echo htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido']); ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="index.php?c=Admin&a=actualizarUsuario" method="POST">
                            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($usuario['correo']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="rol_id" class="form-label">Rol</label>
                                <select class="form-select" id="rol_id" name="rol_id" required>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?php echo $rol['id']; ?>" <?php if ($rol['id'] == $usuario['rol_id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($rol['nombre_rol']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <hr>
                            <div class="mb-3">
                                <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <small class="form-text text-muted">Deja este campo en blanco para no cambiar la contraseña.</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php?c=Admin&a=gestionarUsuarios" class="btn btn-secondary">Cancelar</a>
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
