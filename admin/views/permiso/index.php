<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Permisos</title>
  <link rel="stylesheet" href="..\..\..\css\styles-views.css">
</head>

<body>
  <div class="container">

    <h1>Permisos</h1>
    <a class="btn btn-success" href="permiso.php?accion=crear">Nuevo Permiso</a>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Permiso</th>
          <th scope="col">Opciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($permisos as $permiso): ?>

          <tr>
            <th scope="row"><?php echo $permiso['id_permiso']; ?></th>
            <td><?php echo $permiso['permiso']; ?></td>
            <td>
              <div class="btn-group" role="group" aria-label="Basic example">
                <a class="btn btn-primary" href="permiso.php?accion=modificar&id=<?php echo $permiso['id_permiso']; ?>">Modificar</a>
                <a class="btn btn-danger" href="permiso.php?accion=eliminar&id=<?php echo $permiso['id_permiso']; ?>"
                  onclick="return confirm('¿Estás seguro de que deseas eliminar esta permiso?');">Eliminar</a>

              </div>
            </td>

          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>

</html>