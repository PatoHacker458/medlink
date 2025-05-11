<?php
require_once(__DIR__ . '/models/consultorio.php');
$web = new Consultorio();
$web -> checar('Papu');
require_once (__DIR__.'/views/header.php');
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$alerta = [];

switch ($accion) {

    case 'crear':
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'];
            $resultado = $web->crear($datos);
            if ($resultado) {
                $alerta['mensaje'] = 'CONSULTORIO CREADO CORRECTAMENTE';
                $alerta['tipo'] = 'success';
                $consultorios = $web->leer();
                $web->alerta($alerta);
                include(__DIR__ . '/views/consultorio/index.php');
            } else {
                $alerta['mensaje'] = 'ERROR AL CREAR consultorio';
                $alerta['tipo'] = 'danger';
                $web->alerta($alerta);
                include(__DIR__ . '/views/consultorio/index.php');
            }
        } else {
            include(__DIR__ . '/views/consultorio/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web->leerUno($id);
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'];
            $resultado = $web->modificar($datos, $id);

            if ($resultado) {
                $alerta['mensaje'] = 'CONSULTORIO MODIFICADO CORRECTAMENTE';
                $alerta['tipo'] = 'success';
                $consultorios = $web->leer();
                $web->alerta($alerta);
                include(__DIR__ . '/views/consultorio/index.php');
            } else {
                $alerta['mensaje'] = 'ERROR AL MODIFICAR CONSULTORIO';
                $alerta['tipo'] = 'danger';
                $web->alerta($alerta);
                include(__DIR__ . '/views/consultorio/index.php');
            }
        } else {
            include(__DIR__ . '/views/consultorio/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web->eliminar($id);
        if ($resultado){
            $alerta['mensaje'] = 'CONSULTORIO ELIMINADO CORRECTAMENTE';
            $alerta['tipo'] = 'success';
        } else {
            $alerta ['mensaje'] = 'ERROR AL ELIMINAR CONSULTORIO, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $consultorios = $web -> leer();
        require_once (__DIR__.'/views/consultorio/index.php');
        break;

    case 'leer':
    default:
        $consultorios = $web->leer();
        require_once(__DIR__ . '/views/consultorio/index.php');
        break;
}

require_once(__DIR__ . '/views/footer.php');
?>