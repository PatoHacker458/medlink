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
            <form method="post" action="login.php?accion=login" class="form">
                <div class="textbox">
                    <input 
                        required type="email" 
                        name="datos[correo]"
                        class="form-control form-control-lg"/>
                    <label>Email</label>
                </div>
                <div class="textbox">
                    <input 
                        required type="password"
                        name="datos[contrasena]"
                        class="form-control form-control-lg"/>
                    <label>Contraseña</label>
                </div>
                <button name="enviar" type="submit">Login</button>
            </form>
            <a href="login.php?accion=olvidar">Olvide mi contraseña</a>
            <p class="footer">
                No tienes una cuenta? <a>Registrar</a>
            </p>
        </div>

<?php
require_once (__DIR__.'/../views/footer_login.php');
?>