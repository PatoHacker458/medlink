<?php
require_once(__DIR__ . '/models/especialidad.php');
require_once (__DIR__.'/views/header.php');

$web = new Especialidad();
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$alerta = [];

switch ($accion) {

    case 'crear':
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'];
            $resultado = $web->crear($datos);
            if ($resultado) {
                $alerta['mensaje'] = 'ESPECIALIDAD CREADA CORRECTAMENTE';
                $alerta['tipo'] = 'success';
                $especialidades = $web->leer();
                $web->alerta($alerta);
                include(__DIR__ . '/views/especialidad/index.php');
            } else {
                $alerta['mensaje'] = 'ERROR AL CREAR ESPECIALIDAD';
                $alerta['tipo'] = 'danger';
                $web->alerta($alerta);
                include(__DIR__ . '/views/especialidad/index.php');
            }
        } else {
            include(__DIR__ . '/views/especialidad/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web->leerUno($id);
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'];
            $resultado = $web->modificar($datos, $id);

            if ($resultado) {
                $alerta['mensaje'] = 'ESPECIALIDAD MODIFICADA CORRECTAMENTE';
                $alerta['tipo'] = 'success';
                $especialidades = $web->leer();
                $web->alerta($alerta);
                include(__DIR__ . '/views/especialidad/index.php');
            } else {
                $alerta['mensaje'] = 'ERROR AL MODIFICAR ESPECIALIDAD';
                $alerta['tipo'] = 'danger';
                $web->alerta($alerta);
                include(__DIR__ . '/views/especialidad/index.php');
            }
        } else {
            include(__DIR__ . '/views/especialidad/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web->eliminar($id);
        if ($resultado){
            $alerta['mensaje'] = 'ESPECIALIDAD ELIMINADA CORRECTAMENTE';
            $alerta['tipo'] = 'success';
        } else {
            $alerta ['mensaje'] = 'ERROR AL ELIMINAR ESPECIALIDAD, ESTÁ EN USO';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $especialidades = $web -> leer();
        require_once (__DIR__.'/views/especialidad/index.php');
        break;

    case 'leer':
    default:
        $especialidades = $web->leer();
        require_once(__DIR__ . '/views/especialidad/index.php');
        break;
}

require_once(__DIR__ . '/views/footer.php');
?>