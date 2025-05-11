<h1>Especialidades</h1>
<a href="especialidad.php?accion=crear" class="btn btn-success">Nueva Especialidad</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Especialidad</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($especialidades as $especialidad): ?>
            <tr>
                <th scope="row"><?php echo htmlspecialchars($especialidad['id_especialidad']); ?></th>
                <td><?php echo htmlspecialchars($especialidad['especialidad']); ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" href="especialidad.php?accion=modificar&id=<?php echo $especialidad["id_especialidad"]; ?>">Modificar</a>
                        <a class="btn btn-danger" href="especialidad.php?accion=eliminar&id=<?php echo $especialidad["id_especialidad"]; ?>" 
                        onclick="return confirm('Borrar Especialidad?')">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>