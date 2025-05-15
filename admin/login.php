<?php

require_once (__DIR__.'/model.php');

$app = new Model();
$accion = 'login';
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$alerta = [];

switch ($accion) {

    case 'logout':
        if ($app->logout()){
            $alerta ['mensaje'] = 'SESION CERRADA CORRECTAMENTE';
            $alerta ['tipo'] = 'success';
            $app -> alerta($alerta);
            include_once (__DIR__.'/login/login.php');
        }
    break;

    case 'olvidar':
        include_once (__DIR__.'/login/olvidar.php');
    break;
        
    case 'cambiar':
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $token = $app -> cambiar_contrasena($datos['correo']);
            if($token){
                $alerta ['mensaje'] = 'Se envio un correo si su cuenta existe';
                $alerta ['tipo'] = 'success'; 
                $mensaje = 'Estimado usuario, has solicitado un cambio de contraseña.<br>' .
                'Por favor presione el siguiente enlace para restablecerla: <br>' .
                '<a href="http://localhost:8080/admin/login.php?accion=restablecer&correo=' . $datos['correo'] . '&token=' . $token . '">Restablecer contraseña</a>';
                $app -> enviar_correo($datos['correo'], 'Reestablecimiento de credenciales MEDLINK', $mensaje);
            }else{
                $alerta ['mensaje'] = 'No se pudo enviar el correo de recuperacion';
                $alerta ['tipo'] = 'danger'; 
            }
            $app -> alerta($alerta);
            include_once (__DIR__.'/login/login.php');
        }
    break;

    case 'restablecer':
        $datos = $_GET;
        if (isset($datos['correo']) && isset($datos['token'])) {
            if ($app->validar_correo($datos['correo']) && strlen($datos['token']) == 64) {
                if ($app->validar_token($datos['correo'], $datos['token'])) {
                    include_once (__DIR__.'/login/restablecer.php');
                } else {
                    $app->alerta(['mensaje' => 'El token proporcionado es inválido o ha expirado.', 'tipo' => 'danger']);
                    include_once (__DIR__.'/login/login.php');
                }
            } else {
                $app->alerta(['mensaje' => 'El correo o token no tienen el formato esperado.', 'tipo' => 'danger']);
                include_once (__DIR__.'/login/login.php');
            }
        } else {
            $app->alerta(['mensaje' => 'Faltan parámetros necesarios para restablecer la contraseña.', 'tipo' => 'danger']);
            include_once (__DIR__.'/login/login.php');
        }
    break;

    case 'nueva':
        if (isset($_POST['datos'])) {
            $datos = $_POST['datos'];
            if (isset($datos['correo']) && isset($datos['token']) && isset($datos['contrasena'])) {
                if ($app->validar_correo($datos['correo']) && strlen($datos['token']) == 64) {
                    if ($app->validar_token($datos['correo'], $datos['token'])) {
                        if (!empty(trim($datos['contrasena']))) {
                                if ($app->restablecer($datos['correo'], $datos['contrasena'], $datos['token'])) {
                                if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }
                                $_SESSION['alerta_mensaje'] = 'Se actualizó su contraseña correctamente';
                                $_SESSION['alerta_tipo'] = 'success';
                                header('Location: login.php');
                                exit();
                            } else {
                                $alerta['mensaje'] = 'Error al intentar restablecer la contraseña.';
                                $alerta['tipo'] = 'danger';
                            }
                        } else {
                                $alerta['mensaje'] = 'La nueva contraseña no puede estar vacía.';
                                $alerta['tipo'] = 'danger';
                        }
                    } else {
                            $alerta['mensaje'] = 'El token proporcionado es inválido o ha expirado.';
                            $alerta['tipo'] = 'danger';
                    }
                } else {
                        $alerta['mensaje'] = 'El correo o token no tienen el formato esperado.';
                        $alerta['tipo'] = 'danger';
                }
            } else {
                $alerta['mensaje'] = 'Faltan datos necesarios para actualizar la contraseña.';
                $alerta['tipo'] = 'danger';
            }
        } else {
                $alerta['mensaje'] = 'No se recibieron datos del formulario.';
                $alerta['tipo'] = 'danger';
        }
        if (!empty($alerta)) {
            $app->alerta($alerta);
        }
        include_once (__DIR__.'/login/login.php');
    break;

    case 'login':
    default:
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'];
            if ($app->login($datos['correo'], $datos['contrasena'])){
                header('Location: index.php');
            }
        }
        include_once (__DIR__.'/login/login.php');
    break;
}