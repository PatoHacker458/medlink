<h1>Miembros de Staff</h1>
<a href="staff.php?accion=crear" class="btn btn-success">Nuevo Miembro de Staff</a>
<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Staff</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($staffs as $staff): ?>
            <tr>
                <th scope="row"><?php echo $staff['id_staff']; ?></th>
                <td><?php echo $staff['nombre'] ?></td>
                <td>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a class="btn btn-primary" href="staff.php?accion=modificar&id=<?php echo $staff['id_staff']; ?>">Modificar</a>
                        <a class="btn btn-danger" href="staff.php?accion=eliminar&id=<?php echo $staff['id_staff']; ?>">Eliminar</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>