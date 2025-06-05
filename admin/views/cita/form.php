<div class="container mt-4">
    <h1><?php echo (isset($info['id_cita'])) ? 'Modificar' : 'Nueva'; ?> Cita</h1>

    <?php $web->alerta($alerta ?? []); ?>

    <form method="POST" action="cita.php?accion=<?php echo (isset($info['id_cita'])) ? 'modificar&id='.$info['id_cita'] : 'crear'; ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="fecha" name="datos[fecha]" value="<?php echo htmlspecialchars($info['fecha'] ?? date('Y-m-d')); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="hora" class="form-label">Hora <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="hora" name="datos[hora]" value="<?php echo htmlspecialchars($info['hora'] ?? ''); ?>" required>
            </div>

            <div class="col-md-6">
                <label for="id_paciente" class="form-label">Paciente <span class="text-danger">*</span></label>
                <select class="form-select" id="id_paciente" name="datos[id_paciente]" required <?php if ($disable_paciente_dropdown) echo 'disabled'; ?>>
                    <?php if (!$disable_paciente_dropdown): // Mostrar "Selecciona" solo si no está deshabilitado/preseleccionado ?>
                        <option value="">Selecciona un paciente...</option>
                    <?php endif; ?>
                    <?php foreach ($pacientes as $paciente_item): ?>
                        <?php
                        $selected_paciente = '';
                        if (isset($info['id_paciente']) && $info['id_paciente'] == $paciente_item['id_paciente']) {
                            $selected_paciente = 'selected';
                        }
                        // Si está deshabilitado y no es el paciente preseleccionado, no mostrarlo
                        if ($disable_paciente_dropdown && $selected_paciente === '' && isset($info['id_paciente'])) {
                            continue;
                        }
                        ?>
                        <option value="<?php echo $paciente_item['id_paciente']; ?>" <?php echo $selected_paciente; ?>>
                            <?php echo htmlspecialchars($paciente_item['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($disable_paciente_dropdown && isset($info['id_paciente'])): ?>
                    <input type="hidden" name="datos[id_paciente]" value="<?php echo htmlspecialchars($info['id_paciente']); ?>" />
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="id_medico" class="form-label">Médico <span class="text-danger">*</span></label>
                <select class="form-select" id="id_medico" name="datos[id_medico]" required <?php if ($disable_medico_dropdown) echo 'disabled'; ?>>
                     <?php if (!$disable_medico_dropdown): // Mostrar "Selecciona" solo si no está deshabilitado/preseleccionado ?>
                        <option value="">Selecciona un médico...</option>
                    <?php endif; ?>
                    <?php foreach ($medicos_list as $medico_item): // Usar $medicos_list aquí ?>
                        <?php
                        $selected_medico = '';
                        if (isset($info['id_medico']) && $info['id_medico'] == $medico_item['id_medico']) {
                            $selected_medico = 'selected';
                        }
                        // Si está deshabilitado y no es el médico preseleccionado, no mostrarlo
                         if ($disable_medico_dropdown && $selected_medico === '' && isset($info['id_medico'])) {
                            continue;
                        }
                        ?>
                        <option value="<?php echo $medico_item['id_medico']; ?>" <?php echo $selected_medico; ?>>
                            <?php echo htmlspecialchars($medico_item['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($disable_medico_dropdown && isset($info['id_medico'])): ?>
                    <input type="hidden" name="datos[id_medico]" value="<?php echo htmlspecialchars($info['id_medico']); ?>" />
                <?php endif; ?>
            </div>

            <div class="col-md-6">
                <label for="id_consultorio" class="form-label">Consultorio <span class="text-danger">*</span></label>
                <select class="form-select" id="id_consultorio" name="datos[id_consultorio]" required>
                    <option value="">Selecciona un consultorio...</option>
                    <?php foreach ($consultorios as $consultorio): ?>
                        <option value="<?php echo $consultorio['id_consultorio']; ?>" <?php echo (isset($info['id_consultorio']) && $info['id_consultorio'] == $consultorio['id_consultorio']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($consultorio['descripcion']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" class="form-control" id="precio" name="datos[precio]" value="<?php echo htmlspecialchars($info['precio'] ?? '0.00'); ?>" placeholder="Ej: 500.00">
            </div>

            <div class="col-12">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" id="descripcion" name="datos[descripcion]" rows="3"><?php echo htmlspecialchars($info['descripcion'] ?? ''); ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary" name="enviar"><i class="bi bi-check-circle-fill"></i> Guardar Cita</button>
            <a href="cita.php" class="btn btn-secondary"><i class="bi bi-x-circle-fill"></i> Cancelar</a>
        </div>
    </form>
</div>