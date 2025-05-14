<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo'; ?> Rol</h1>
        <form action="rol.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear'; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="rol_nombre">Nombre del rol</label> 
                <input type="text" maxlength="50" name="datos[rol]" class="form-control" id="rol_nombre" value="<?php echo (isset($_GET['id']) && isset($info['rol'])) ? htmlspecialchars($info['rol']) : ''; ?>" required>
                
                <label class="permissions-group-label">Permisos</label> 
                <div class="permissions-list-container"> 
                    <?php if (isset($permisos) && is_array($permisos)): ?>
                        <?php foreach ($permisos as $permiso): 
                            $permiso_id_esc = htmlspecialchars($permiso['id_permiso']);
                            $permiso_nombre_esc = htmlspecialchars($permiso['permiso']);
                            $checkbox_id = 'permiso_' . $permiso_id_esc;

                            $isChecked = false;
                            if (isset($_GET['id']) && isset($info['permisos'])) {
                                $assigned_permissions_str = is_string($info['permisos']) ? $info['permisos'] : '';
                                if (!empty($assigned_permissions_str)) {
                                    $assigned_permissions_array = explode(',', $assigned_permissions_str);
                                    if (in_array((string)$permiso['id_permiso'], $assigned_permissions_array)) {
                                        $isChecked = true;
                                    }
                                }
                            }
                        ?>
                            <div class="permission-item">
                                <input type="checkbox" 
                                       name="r_permisos[]" 
                                       id="<?php echo $checkbox_id; ?>" 
                                       value="<?php echo $permiso_id_esc; ?>" 
                                       <?php echo $isChecked ? 'checked' : ''; ?>>
                                <label class="checkbox-text-label" for="<?php echo $checkbox_id; ?>"><?php echo $permiso_nombre_esc; ?></label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No hay permisos disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-actions"> 
                <input type="submit" value="Guardar" name="enviar" class="btn btn-success">
                <a href="rol.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>