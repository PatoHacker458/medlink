<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Médico:</h1>
<?php if(isset($_GET['id'])):?>
<div class="text-center">
    <img src="../uploads/<?php echo $info['fotografia'];?>" class="rounded" alt="Imagen medico" width="100" height="100">
</div>
<?php endif;?>
<form enctype="multipart/form-data" method="POST" action="medico.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
        <label>Nombre</label><br/>     
        <input type="text" maxlength='50' name="datos[nombre]" value = "<?php echo (isset($_GET['id'])) ? $info ['nombre']: '' ?>" required/><br/>
        <label>Primer Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[primer_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['primer_apellido']: '' ?>" required/><br/>
        <label>Segundo Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[segundo_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['segundo_apellido']: '' ?>"/><br/>
        <label>Licencia:</label><br/>
        <input type="text" maxlength='18' name="datos[licencia]" value = "<?php echo (isset($_GET['id'])) ? $info ['licencia']: '' ?>" required/><br/>
        <label>Especialidad:</label><br/>
        <select name="datos[id_especialidad]" required>
            <option value="">Seleccione una especialidad</option>
            <?php foreach ($especialidades as $especialidad): ?>
                <option value="<?php echo $especialidad['id_especialidad'] ?>"
                    <?php echo (isset($_GET['id']) && $especialidad['id_especialidad'] == $info['id_especialidad']) ? 'selected' : ''; ?>>
                    <?php echo $especialidad['especialidad'] ?>
                </option>
            <?php endforeach; ?>
        </select><br/>
        <label>Consultorio (Piso - Habitación):</label><br/>
        <select name="datos[id_consultorio]" required>
            <option value="">Seleccione un consultorio</option>
            <?php foreach ($consultorios as $consultorio): ?>
                <option value="<?php echo $consultorio['id_consultorio'] ?>"
                    <?php echo (isset($_GET['id']) && $consultorio['id_consultorio'] == $info['id_consultorio']) ? 'selected' : ''; ?>>
                    <?php echo $consultorio['piso'] . ' - ' . $consultorio['habitacion'] ?>
                </option>
            <?php endforeach; ?>
        </select><br/>
        <label>Correo</label><br/>
        <input type="text" maxlength="100" name="datos[correo]" value = "<?php echo (isset($_GET['id'])) ? $info ['correo']: '' ?>" required><br/>
        <label>Contraseña</label><br/>
        <input type="password" maxlength="32" name="datos[contrasena]"><br/>
        <label>Imagen:</label><br/>
        <input type="file" name="fotografia"/><br/>
        <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
        <a href="medico.php" class="btn btn-danger">Cancelar</a>
</form>