<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Roles</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <div class="page-header"> <h1>Roles</h1>
            <a href="rol.php?accion=crear" class="btn btn-success">Nuevo Rol</a>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Rol</th>
                    <th scope="col" class="actions-column">Opciones</th> </tr>
            </thead>
            <tbody>
                <?php if (isset($rols) && is_array($rols)): ?>
                    <?php foreach ($rols as $rol): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($rol['id_rol']); ?></th>
                            <td><?php echo htmlspecialchars($rol['rol']); ?></td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Acciones para el rol <?php echo htmlspecialchars($rol['rol']); ?>">
                                    <a class="btn btn-primary" href="rol.php?accion=modificar&id=<?php echo htmlspecialchars($rol['id_rol']); ?>">Modificar</a>
                                    <a class="btn btn-danger" href="rol.php?accion=eliminar&id=<?php echo htmlspecialchars($rol['id_rol']); ?>"
                                       onclick="return confirm('¿Estás seguro de que deseas eliminar este rol?');">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">No hay roles para mostrar.</td> </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>