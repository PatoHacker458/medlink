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

        <h1>Pacientes</h1>
        <a href="paciente.php?accion=crear" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nuevo Paciente</a>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pacientes as $paciente): ?>
                    <tr>
                        <th scope="row"><?php echo $paciente['id_paciente']; ?></th>
                        <td><?php echo $paciente['paciente_nombre_completo'] ?></td>
                        <td><?php echo $paciente['telefono'] ?></td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a class="btn btn-primary" href="paciente.php?accion=modificar&id=<?php echo $paciente['id_paciente']; ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a class="btn btn-danger" href="paciente.php?accion=eliminar&id=<?php echo $paciente['id_paciente']; ?>">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>