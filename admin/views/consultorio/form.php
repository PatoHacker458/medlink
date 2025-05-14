<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Consultorios</title> <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">
        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Consultorio:</h1> <form method="POST" action="consultorio.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">

            <div class="form-row">
                <div class="form-col">
                    <label for="piso">Piso:</label>
                    <input type="number" id="piso" name="datos[piso]" value="<?php echo (isset($_GET['id']) && isset($info['piso'])) ? htmlspecialchars($info['piso']) : '' ?>" required />
                    <label for="habitacion">Habitación:</label>
                    <input type="number" id="habitacion" name="datos[habitacion]" value="<?php echo (isset($_GET['id']) && isset($info['habitacion'])) ? htmlspecialchars($info['habitacion']) : '' ?>" required />
                </div>
            </div>

            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="consultorio.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>