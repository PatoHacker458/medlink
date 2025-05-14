<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Especialidades</title> <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">

        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nueva'; ?> Especialidad</h1>
        <form action="especialidad.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear'; ?>"
            method="POST">
            
            <div class="form-row">
                <div class="form-col">
                    <label for="especialidad_nombre">Especialidad</label> <input type="text" id="especialidad_nombre" maxlength="50" name="datos[especialidad]"
                        value="<?php echo (isset($_GET['id']) && isset($info['especialidad'])) ? htmlspecialchars($info['especialidad']) : ''; ?>" required>
                </div>
            </div>
            
            <div class="form-actions">
                <input type="submit" value="Guardar" name="enviar" class="btn btn-success">
                <a href="especialidad.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>

</html>