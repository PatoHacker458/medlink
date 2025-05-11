<h1>Usuarios</h1>
<a href="user.php?accion=crear"class="btn btn-success">Nuevo Usuario</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Username</th>
      <th scope="col">Nombre</th>
      <th scope="col">Teléfono</th>
      <th scope="col">Correo</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <th scope="row"><?php echo $user['id_user']; ?></th>
      <td><?php echo $user['username'] ?></td>
      <td><?php echo $user['name'] ?></td>
      <td><?php echo $user['phone'] ?></td>
      <td><?php echo $user['email'] ?></td>
      <td>
        <div class="btn-group" role="group" aria-label="Basic example">
          <a class="btn btn-primary" href="user.php?accion=modificar&id=<?php echo $user["id_user"]; ?>">Modificar</a>
          <a class="btn btn-danger" href= "user.php?accion=eliminar&id=<?php echo $user["id_user"]; ?>">Eliminar</a>
        </div>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>