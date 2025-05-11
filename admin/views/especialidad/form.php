<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nueva'; ?> Especialidad</h1>
<form action="especialidad.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear'; ?>"
    method="POST">
    <div class="form-group">
        <label>Especialidad</label>
        <input type="text" maxlength="50" name="datos[especialidad]" class="form-control"
            value="<?php echo (isset($_GET['id'])) ? $info['especialidad'] : ''; ?>" required>
    </div>
    <br>
    <input type="submit" value="Guardar" name="enviar" class="btn btn-success">
    <a href="especialidad.php" class="btn btn-danger">Cancelar</a>
</form>