<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Usuario:</h1>
<form method="POST" action="user.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
  <label>Nombre de usuario: </label>
  <input type="text" maxlength="20" name="datos[username]" class="form-control" value="<?php echo (isset($_GET['id']))? $info['username'] : ''; ?>" required>
  <label>Contraseña: </label>
  <input type="text" maxlength="32" name="datos[password]" class="form-control" value="<?php echo (isset($_GET['id']))? $info['password'] : ''; ?>" required>
  <label>Nombre Completo: </label>
  <input type="text" maxlength="50" name="datos[name]" class="form-control" value="<?php echo (isset($_GET['id']))? $info['name'] : ''; ?>" required>
  <label>Teléfono: </label>
  <input type="number" name="datos[phone]" class="form-control" value="<?php echo (isset($_GET['id']))? $info['phone'] : ''; ?>" required>
  <label>Correo: </label>
  <input type="text" maxlength="100" name="datos[email]" class="form-control" value="<?php echo (isset($_GET['id']))? $info['email'] : ''; ?>" required>
  <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
  <a href="user.php" class="btn btn-danger">Cancelar</a>
</form>