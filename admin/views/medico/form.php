<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Médicos</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Médico:</h1>

        <?php if (isset($_GET['id']) && !empty($info['fotografia'])): ?>
            <div class="form-image-preview">
                <img src="../uploads/<?php echo htmlspecialchars($info['fotografia']); ?>" class="rounded" alt="Imagen médico" width="100" height="100">
            </div>
        <?php endif; ?>

        <form enctype="multipart/form-data" method="POST" action="medico.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" maxlength='50' name="datos[nombre]" value="<?php echo (isset($_GET['id']) && isset($info['nombre'])) ? htmlspecialchars($info['nombre']) : ''; ?>" required />
                </div>
                <div class="form-col">
                    <label for="primer_apellido">Primer Apellido:</label>
                    <input type="text" id="primer_apellido" maxlength='50' name="datos[primer_apellido]" value="<?php echo (isset($_GET['id']) && isset($info['primer_apellido'])) ? htmlspecialchars($info['primer_apellido']) : ''; ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="segundo_apellido">Segundo Apellido:</label>
                    <input type="text" id="segundo_apellido" maxlength='50' name="datos[segundo_apellido]" value="<?php echo (isset($_GET['id']) && isset($info['segundo_apellido'])) ? htmlspecialchars($info['segundo_apellido']) : ''; ?>" />
                </div>
                <div class="form-col">
                    <label for="licencia">Licencia:</label>
                    <input type="text" id="licencia" maxlength='18' name="datos[licencia]" value="<?php echo (isset($_GET['id']) && isset($info['licencia'])) ? htmlspecialchars($info['licencia']) : ''; ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" maxlength='50' name="datos[telefono]" value="<?php echo (isset($_GET['id']) && isset($info['telefono'])) ? htmlspecialchars($info['telefono']) : ''; ?>" />
                </div>
                <div class="form-col">
                    <label for="horario">Horario:</label>
                    <input type="text" id="horario" maxlength='50' name="datos[horario]" value="<?php echo (isset($_GET['id']) && isset($info['horario'])) ? htmlspecialchars($info['horario']) : ''; ?>" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="id_especialidad">Especialidad:</label>
                    <select id="id_especialidad" name="datos[id_especialidad]" required>
                        <option value="">Seleccione una especialidad</option>
                        <?php if (isset($especialidades) && is_array($especialidades)): ?>
                            <?php foreach ($especialidades as $especialidad): ?>
                                <option value="<?php echo htmlspecialchars($especialidad['id_especialidad']); ?>"
                                    <?php echo (isset($_GET['id']) && isset($info['id_especialidad']) && $especialidad['id_especialidad'] == $info['id_especialidad']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($especialidad['especialidad']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-col">
                    <label for="id_consultorio">Consultorio (Piso - Habitación):</label>
                    <select id="id_consultorio" name="datos[id_consultorio]" required>
                        <option value="">Seleccione un consultorio</option>
                        <?php if (isset($consultorios) && is_array($consultorios)): ?>
                            <?php foreach ($consultorios as $consultorio): ?>
                                <option value="<?php echo htmlspecialchars($consultorio['id_consultorio']); ?>"
                                    <?php echo (isset($_GET['id']) && isset($info['id_consultorio']) && $consultorio['id_consultorio'] == $info['id_consultorio']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($consultorio['piso'] . ' - ' . $consultorio['habitacion']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="correo">Correo</label>
                    <input type="text" id="correo" maxlength="100" name="datos[correo]" value="<?php echo (isset($_GET['id']) && isset($info['correo'])) ? htmlspecialchars($info['correo']) : ''; ?>" required />
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-col">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" maxlength="32" name="datos[contrasena]" placeholder="Dejar en blanco para no cambiar" />
                </div>
                <div class="form-col">
                    <label for="fotografia">Imagen:</label>
                    <input type="file" id="fotografia" name="fotografia" />
                </div>
            </div>

            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="medico.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>