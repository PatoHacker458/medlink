<h1>Consultorios</h1>
<a href="consultorio.php?accion=crear"class="btn btn-success">Nuevo Consultorio</a>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Piso</th>
      <th scope="col">Habitación</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($consultorios as $consultorio): ?>
      <tr>
        <th scope="row"><?php echo $consultorio['id_consultorio']; ?></th>
        <td><?php echo $consultorio['piso'] ?></td>
        <td><?php echo $consultorio['habitacion'] ?></td>
        <td>
          <div class="btn-group" role="group" aria-label="Basic example">
            <a href="consultorio.php?accion=modificar&id=<?php echo $consultorio["id_consultorio"]; ?>"class="btn btn-primary">Modificar</a>
            <a class="btn btn-danger" href= "consultorio.php?accion=eliminar&id=<?php echo $consultorio["id_consultorio"]; ?>"
            onclick="return confirm('Borrar Consultorio?')">Eliminar</a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>