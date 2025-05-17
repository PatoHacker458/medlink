<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <div class="page-header">
            <h1>Gestión de Citas</h1>
            <a href="cita.php?accion=crear" class="btn btn-success"><i class="bi bi-plus-circle"></i> Nueva Cita</a>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Día</th> <th scope="col">Hace/En</th> <th scope="col">Paciente</th>
                        <th scope="col">Médico</th>
                        <th scope="col">Consultorio</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($citas)): ?>
                        <tr>
                            <td colspan="11" style="text-align: center;">No hay citas registradas.</td> </tr>
                    <?php else: ?>
                        <?php foreach ($citas as $cita): ?>
                            <?php
                                $fechaHoraCita = Carbon\Carbon::parse($cita['fecha'] . ' ' . $cita['hora'], 'America/Mexico_City');
                            ?>
                            <tr>
                                <th scope="row"><?php echo htmlspecialchars($cita['id_cita']); ?></th>
                                
                                <td><?php echo htmlspecialchars($fechaHoraCita->isoFormat('D [de] MMMM [de] YYYY')); ?></td>
                                <td><?php echo htmlspecialchars($fechaHoraCita->isoFormat('h:mm A')); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($fechaHoraCita->isoFormat('dddd'))); ?></td>
                                <td><?php echo htmlspecialchars($fechaHoraCita->diffForHumans()); ?></td>

                                <td><?php echo htmlspecialchars($cita['paciente_nombre_completo'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($cita['medico_nombre_completo'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($cita['id_consultorio']); ?></td>
                                <td><?php echo htmlspecialchars(isset($cita['precio']) ? '$' . number_format($cita['precio'], 2) : '-'); ?></td>
                                <td><?php echo htmlspecialchars($cita['estado']); ?></td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Acciones para cita <?php echo htmlspecialchars($cita['id_cita']); ?>">
                                        <a href="cita.php?accion=modificar&id=<?php echo htmlspecialchars($cita['id_cita']); ?>" class="btn btn-primary" title="Modificar">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        <a href="cita.php?accion=eliminar&id=<?php echo htmlspecialchars($cita['id_cita']); ?>" class="btn btn-danger" title="Eliminar"
                                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita? Esta acción no se puede deshacer.');">
                                            <i class="bi bi-trash-fill"></i>
                                        </a>
                                        <a href="reporte.php?accion=cita_detalle&id_cita=<?php echo htmlspecialchars($cita['id_cita']); ?>" class="btn btn-info" title="Ver PDF" target="_blank">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </a>
                                        <form action="pago/pago.php" method="POST" style="margin:0; padding:0; display:inline;" target="_self">
                                            <input type="hidden" name="id_cita" value="<?php echo htmlspecialchars($cita['id_cita']); ?>">
                                            <input type="hidden" name="precio" value="<?php echo htmlspecialchars($cita['precio'] ?? '0.00'); ?>">
                                            <button type="submit" class="btn btn-warning" title="Pagar Cita"><i class="bi bi-credit-card-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>