<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pacientes</title> <link rel="stylesheet" href="..\..\..\css\styles-views.css"> </head>

<body>
    <div class="container">
        <h1><?php echo (isset($_GET['id'])) ? 'Modificar' : 'Nuevo' ?> Paciente:</h1>
        <form method="POST" action="paciente.php?accion=<?php echo (isset($_GET['id'])) ? 'modificar&id=' . $_GET['id'] : 'crear' ?>">

            <div class="form-row">
                <div class="form-col">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" maxlength='50' name="datos[nombre]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['nombre']) : '' ?>" required />
                </div>
                <div class="form-col">
                    <label for="primer_apellido">Primer Apellido:</label>
                    <input type="text" id="primer_apellido" maxlength='50' name="datos[primer_apellido]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['primer_apellido']) : '' ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="segundo_apellido">Segundo Apellido:</label>
                    <input type="text" id="segundo_apellido" maxlength='50' name="datos[segundo_apellido]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['segundo_apellido']) : '' ?>" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="nacimiento">Nacimiento:</label>
                    <input type="date" id="nacimiento" name="datos[nacimiento]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['nacimiento']) : '' ?>" required />
                </div>
                <div class="form-col">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" maxlength='10' name="datos[telefono]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['telefono']) : '' ?>" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="correo">Correo</label>
                    <input type="text" id="correo" maxlength="100" name="datos[correo]" value="<?php echo (isset($_GET['id'])) ? htmlspecialchars($info['correo']) : '' ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" maxlength="32" name="datos[contrasena]">
                </div>
            </div>

            <div class="form-actions">
                <input type="submit" value="Guardar" class="btn btn-success" name="enviar">
                <a href="paciente.php" class="btn btn-danger">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>