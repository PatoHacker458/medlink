<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Médicos</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css">
</head>

<body>

    <div class="container">
        <h1>Médicos</h1>
        <a href="medico.php?accion=crear" class="btn btn-success">Nuevo Médico</a>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Especialidad</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Licencia</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Horario</th>
                    <th scope="col">Consultorio</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($medicos as $medico): ?>
                    <tr>
                        <th scope="row"><?php echo htmlspecialchars($medico['id_medico']); ?></th>
                        <td><?php echo htmlspecialchars($medico['especialidad']); ?></td>
                        <td><?php echo htmlspecialchars($medico['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($medico['licencia']); ?></td>
                        <td><?php echo htmlspecialchars($medico['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($medico['horario']); ?></td>
                        <td><?php echo htmlspecialchars($medico['id_consultorio']); ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Opciones">
                                <a class="btn btn-primary" href="medico.php?accion=modificar&id=<?php echo htmlspecialchars($medico['id_medico']); ?>">Modificar</a>
                                <a class="btn btn-danger" href="medico.php?accion=eliminar&id=<?php echo htmlspecialchars($medico['id_medico']); ?>">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>