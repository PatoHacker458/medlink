<h1><?php echo(isset($_GET['id']))? 'Modificar':'Nuevo';?> Rol</h1>
<form action="rol.php?accion=<?php echo(isset($_GET['id']))? 'modificar&id='.$_GET['id']:'crear';?>" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Nombre del rol</label>
        <input type="text" maxlength="50" name="datos[rol]" class="form-control" id="exampleInputEmail1" value="<?php echo (isset($_GET['id']))? $info['rol'] : ''; ?>" required>
        <label>Permisos</label><br/>
        <?php foreach ($permisos as $permiso): ?>
            <input type="checkbox" name="r_permisos[]" value="<?php echo $permiso['id_permiso'];?>" <?php echo (isset($_GET['id']) && in_array($permiso['id_permiso'], explode(',', $info['permisos'] ?? '')))?'checked':'';?>><?php echo $permiso['permiso'];?>
        <?php endforeach;?>
    </div>
    <input type="submit" value="Guardar" name="enviar" class ="btn btn-success">
    <a href="rol.php" class="btn btn-danger">Cancelar</a>
</form>