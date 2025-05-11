<h1>Roles</h1>
<a href="rol.php?accion=crear" class="btn btn-success">Nuevo Rol</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Rol</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rols as $rol): ?>
      <tr>
        <th scope="row"><?php echo $rol['id_rol']; ?></th>
        <td><?php echo $rol['rol'] ?></td>
        <td>
          <div class="btn-group" role="group" aria-label="Basic example">
            <a class="btn btn-primary" href="rol.php?accion=modificar&id=<?php echo $rol["id_rol"]; ?>">Modificar</a>
            <a class="btn btn-danger" href="rol.php?accion=eliminar&id=<?php echo $rol['id_rol']; ?>"
              onclick="return confirm('¿Estás seguro de que deseas eliminar este rol?');">Eliminar</a>
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>