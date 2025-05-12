<div class="container mt-4">
    <h1>Gestión de Citas</h1>
    <a href="cita.php?accion=crear" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Nueva Cita</a>

    <?php $web->alerta($alerta); ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Paciente</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Consultorio</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($citas)): ?>
                    <tr>
                        <td colspan="9" class="text-center">No hay citas registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <th scope="row"><?php echo htmlspecialchars($cita['id_cita']); ?></th>
                            <td><?php echo htmlspecialchars($cita['fecha']); ?></td>
                            <td><?php echo htmlspecialchars(substr($cita['hora'], 0, 5)); ?></td>
                            <td><?php echo htmlspecialchars($cita['paciente_nombre_completo'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cita['medico_nombre_completo'] ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                echo htmlspecialchars($cita['id_consultorio']);
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($cita['descripcion'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars(isset($cita['precio']) ? '$' . number_format($cita['precio'], 2) : '-'); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="cita.php?accion=modificar&id=<?php echo $cita['id_cita']; ?>" class="btn btn-primary btn-sm" title="Modificar">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="cita.php?accion=eliminar&id=<?php echo $cita['id_cita']; ?>" class="btn btn-danger btn-sm" title="Eliminar"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita? Esta acción no se puede deshacer.');">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                    <a href="reporte.php?accion=cita_detalle&id_cita=<?php echo htmlspecialchars($cita['id_cita']); ?>" class="btn btn-info btn-sm" title="Ver PDF" target="_blank">
                                        <i class="bi bi-file-earmark-pdf-fill"></i>
                                    </a>
                                    <a href="<?php echo SITE_URL; ?>admin/pago.php?id_cita=<?php echo htmlspecialchars($cita['id_cita']); ?>" class="btn btn-success btn-sm" target="_blank">Pagar MP</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>