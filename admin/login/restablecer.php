<?php
require_once (__DIR__.'/../views/header_login.php');
?>

    <body>
        <img class="blob" src="/../images/login/blob.svg" />
        <div class="orbit"></div>
        <div class="login">
            <img src="/../images/login/logo.png" />
            <h2> Bienvenido a Medlink</h2>
            <h3> Manten tus datos seguros</h3>
            <form method="post" action="login.php?accion=nueva" class="form">
                <div class="textbox">
                    <input required type="password" name="datos[contrasena]" class="form-control form-control-lg"/>
                    <label>Nueva Contraseña</label>
                    <input type="hidden" name="datos[correo]" value="<?php echo htmlspecialchars($datos['correo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                    <input type="hidden" name="datos[token]" value="<?php echo htmlspecialchars($datos['token'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
                </div>
                <button name="enviar" type="submit">Restablecer</button>
            </form>
            <a href="login/olvidar.php">Olvide mi contraseña</a>
            <p class="footer">
                No tienes una cuenta? <a>Registrar</a>
            </p>
        </div>

<?php
require_once (__DIR__.'/../views/footer_login.php');
?>