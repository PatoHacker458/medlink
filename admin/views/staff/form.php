<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Miembro de Staff:</h1>
<form method="POST" action="staff.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
        <label>Nombre</label><br/>     
        <input type="text" maxlength='50' name="datos[nombre]" value = "<?php echo (isset($_GET['id'])) ? $info ['nombre']: '' ?>" required/><br/>
        <label>Primer Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[primer_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['primer_apellido']: '' ?>" required/><br/>
        <label>Segundo Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[segundo_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['segundo_apellido']: '' ?>"/><br/>
        <label>Correo</label><br/>
        <input type="text" maxlength="50" name="datos[correo]" value = "<?php echo (isset($_GET['id'])) ? $info ['correo']: '' ?>" required><br/>
        <label>Contrasena</label><br/>
        <input type="password" maxlength="50" name="datos[contrasena]"><br/><br/>
        <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
        <a href="staff.php" class="btn btn-danger">Cancelar</a>
</form>