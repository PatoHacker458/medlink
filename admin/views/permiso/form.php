<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Permisos</title>
    <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Permiso:</h1>
        <form method="POST" action="permiso.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="permiso_nombre">Permiso:</label> <input type="text" id="permiso_nombre" maxlength='30' name="datos[permiso]" 
                           value="<?php echo (isset($_GET['id']) && isset($info['permiso'])) ? htmlspecialchars($info['permiso']) : ''; ?>" required />
                </div>
            </div>
            
            <div class="form-actions"> <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="permiso.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>