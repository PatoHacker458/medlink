<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Usuario:</h1>
<form method="POST" action="usuario.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
  <label>Correo:</label><br/>
  <input type="text" maxlength='100' name="datos[correo]" value = "<?php echo (isset($_GET['id'])) ? $info ['correo']: '' ?>" required/><br/>
  <label>Contraseña:</label><br/>
  <input type="text" maxlength='32' name="datos[contrasena]" required/><br/><br/>
  <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
  <a href="usuario.php" class="btn btn-danger">Cancelar</a>
</form>