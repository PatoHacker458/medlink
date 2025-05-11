<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Consulorio:</h1>
<form method="POST" action="consultorio.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
  <label>Piso: </label>
  <input type="number" name="datos[piso]" value = "<?php echo (isset($_GET['id'])) ? $info ['piso']: '' ?>" required/>
  <label>Habitación: </label>
  <input type="number" name="datos[habitacion]" value = "<?php echo (isset($_GET['id'])) ? $info ['habitacion']: '' ?>" required/>
  <br/>
  <br/>
  <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
  <a href="consultorio.php" class="btn btn-danger">Cancelar</a>
</form>