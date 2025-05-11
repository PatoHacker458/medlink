<?php
require_once (__DIR__.'/../views/header_login.php');
?>

    <body>
        <img class="blob" src="/../images/login/blob.svg" />
        <div class="orbit"></div>
        <div class="login">
            <img src="/../images/login/logo.png" />
            <h2> Recupera tu Contraseña</h2><br>
            <form method="post" action="login.php?accion=cambiar" class="form">
                <div class="textbox">
                    <input 
                        required type="email" 
                        name="datos[correo]"
                        class="form-control form-control-lg"/>
                    <label>Email</label>
                </div>
                <button name="enviar" type="submit">Recuperar</button>
            </form>
        </div>

<?php
require_once (__DIR__.'/../views/footer_login.php');
?>