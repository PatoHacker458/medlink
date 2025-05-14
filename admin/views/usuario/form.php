<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title> <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Usuario:</h1>
        <form method="POST" action="usuario.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="usuario_correo">Correo:</label>
                    <input type="text" id="usuario_correo" maxlength='100' name="datos[correo]" value="<?php echo (isset($_GET['id']) && isset($info['correo'])) ? htmlspecialchars($info['correo']) : ''; ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="usuario_contrasena">Contraseña:</label>
                    <input type="password" id="usuario_contrasena" maxlength='32' name="datos[contrasena]" 
                           <?php if (!isset($_GET['id'])): /* Solo es 'required' al crear un nuevo usuario */ ?>
                               required 
                           <?php else: /* Placeholder para modificar, indicando que es opcional */ ?>
                               placeholder="Dejar en blanco para no cambiar"
                           <?php endif; ?> />
                </div>
            </div>
            
            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="usuario.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>