<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Permiso:</h1>
<form method="POST" action="permiso.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
  <input type="text" maxlength='30' name="datos[permiso]" value = "<?php echo (isset($_GET['id'])) ? $info ['permiso']: '' ?>" required/>
  <br/>
  <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
  <a href="permiso.php" class="btn btn-danger">Cancelar</a>
</form>