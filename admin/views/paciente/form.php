<h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Paciente:</h1>
<form method="POST" action="paciente.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id='.$_GET['id'] : 'crear' ?>">
        <label>Nombre</label><br/>     
        <input type="text" maxlength='50' name="datos[nombre]" value = "<?php echo (isset($_GET['id'])) ? $info ['nombre']: '' ?>" required/><br/>
        <label>Primer Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[primer_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['primer_apellido']: '' ?>" required/><br/>
        <label>Segundo Apellido:</label><br/>
        <input type="text" maxlength='50' name="datos[segundo_apellido]" value = "<?php echo (isset($_GET['id'])) ? $info ['segundo_apellido']: '' ?>"/><br/>
        <label>Nacimiento:</label><br/>
        <input type="date" name="datos[nacimiento]" value = "<?php echo (isset($_GET['id'])) ? $info ['nacimiento']: '' ?>" required/><br/>
        <label>Teléfono:</label><br/>
        <input type="text" maxlength='10' name="datos[telefono]" value = "<?php echo (isset($_GET['id'])) ? $info ['telefono']: '' ?>" required/><br/>
        <label>Correo</label><br/>
        <input type="text" maxlength="100" name="datos[correo]" value = "<?php echo (isset($_GET['id'])) ? $info ['correo']: '' ?>" required><br/>
        <label>Contraseña</label><br/>
        <input type="password" maxlength="32" name="datos[contrasena]"><br/>
        <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
        <a href="paciente.php" class="btn btn-danger">Cancelar</a>
</form>