<h1>Usuarios</h1>
<a class="btn btn-success" href="usuario.php?accion=crear">Nuevo Usuario</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Correo</th>
      <th scope="col">Opciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($usuarios as $usuario): ?>

      <tr>
        <th scope="row"><?php echo $usuario['id_usuario']; ?></th>
        <td><?php echo $usuario['correo']; ?></td>
        <td>
          <div class="btn-group" role="group" aria-label="Basic example">
            <a class="btn btn-primary"
              href="usuario.php?accion=modificar&id=<?php echo $usuario['id_usuario']; ?>">Modificar</a>
            <a class="btn btn-danger" href="usuario.php?accion=eliminar&id=<?php echo $usuario['id_usuario']; ?>"
              onclick="return confirm('¿Estás seguro de que deseas eliminar esta usuario?');">Eliminar</a>
          </div>
        </td>

      </tr>
    <?php endforeach; ?>
  </tbody>
</table>