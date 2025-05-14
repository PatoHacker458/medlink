<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Staff</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Miembro de Staff:</h1>
        <form method="POST" action="staff.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="staff_nombre">Nombre</label>
                    <input type="text" id="staff_nombre" maxlength='50' name="datos[nombre]" value="<?php echo (isset($_GET['id']) && isset($info['nombre'])) ? htmlspecialchars($info['nombre']) : ''; ?>" required />
                </div>
                <div class="form-col">
                    <label for="staff_primer_apellido">Primer Apellido:</label>
                    <input type="text" id="staff_primer_apellido" maxlength='50' name="datos[primer_apellido]" value="<?php echo (isset($_GET['id']) && isset($info['primer_apellido'])) ? htmlspecialchars($info['primer_apellido']) : ''; ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="staff_segundo_apellido">Segundo Apellido:</label>
                    <input type="text" id="staff_segundo_apellido" maxlength='50' name="datos[segundo_apellido]" value="<?php echo (isset($_GET['id']) && isset($info['segundo_apellido'])) ? htmlspecialchars($info['segundo_apellido']) : ''; ?>" />
                </div>
                <div class="form-col">
                    <label for="staff_correo">Correo</label>
                    <input type="text" id="staff_correo" maxlength="50" name="datos[correo]" value="<?php echo (isset($_GET['id']) && isset($info['correo'])) ? htmlspecialchars($info['correo']) : ''; ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="staff_contrasena">Contraseña</label> <input type="password" id="staff_contrasena" maxlength="50" name="datos[contrasena]" placeholder="Dejar en blanco para no cambiar" />
                </div>
            </div>
            
            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="staff.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>