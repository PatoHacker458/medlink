<h1>Médicos</h1>
<a href="medico.php?accion=crear" class="btn btn-success">Nuevo Médico</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Nombre</th>
            <th scope="col">Licencia</th>
            <th scope="col">Consultorio</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($medicos as $medico): ?>
            <tr>
                <th scope="row"><?php echo $medico['id_medico']; ?></th>
                <td><?php echo $medico['nombre'] ?></td>
                <td><?php echo $medico['especialidad'] ?></td>
                <td><?php echo $medico['licencia'] ?></td>
                <td><?php echo $medico['id_consultorio'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" href="medico.php?accion=modificar&id=<?php echo $medico['id_medico']; ?>">Modificar</a>
                        <a class="btn btn-danger" href="medico.php?accion=eliminar&id=<?php echo $medico['id_medico']; ?>">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>