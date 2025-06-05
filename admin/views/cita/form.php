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
                <select class="form-select" id="id_paciente" name="datos[id_paciente]" required>
                    <option value="">Selecciona un paciente...</option>
                    <?php foreach ($pacientes as $paciente): ?>
                        <option value="<?php echo $paciente['id_paciente']; ?>" <?php echo (isset($info['id_paciente']) && $info['id_paciente'] == $paciente['id_paciente']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($paciente['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label for="id_medico" class="form-label">Médico <span class="text-danger">*</span></label>
                <select class="form-select" id="id_medico" name="datos[id_medico]" required <?php if ($disable) echo 'disabled'; ?>>
                    
                    <?php if (!$disable): ?>
                        <option value="">Selecciona un médico...</option>
                    <?php endif; ?>

                    <?php foreach ($medicos as $medicos): ?>
                        <?php
                        $selected = '';
                        if ($disable) {
                            if ($medicos['id_medico'] == $logged_medico) {
                                $selected = 'selected';
                            } else {
                                continue; 
                            }
                        } 
                        elseif (isset($info['id_medico']) && $info['id_medico'] == $medicos['id_medico']) {
                            $selected = 'selected';
                        }
                        ?>
                        <option value="<?php echo $medicos['id_medico']; ?>" <?php echo $selected; ?>>
                            <?php echo htmlspecialchars($medicos['nombre_completo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php
                if ($disable && $logged_medico !== null): ?>
                    <input type="hidden" name="datos[id_medico]" value="<?php echo htmlspecialchars($logged_medico); ?>" />
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