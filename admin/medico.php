<?php
require_once (__DIR__.'/models/medico.php');
require_once (__DIR__.'/views/header.php');

$web = new Medico();
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$alerta = [];

switch ($accion){

    case 'crear':
        $especialidades = $web->obtenerEspecialidades();
        $consultorios = $web->obtenerConsultorios();
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> crear($datos);
            if($resultado){
                $alerta ['mensaje'] = 'MEDICO CREADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $medicos = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/medico/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE CREAR EL MEDICO';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/medico/form.php');
            }
        }else{
            include(__DIR__.'/views/medico/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web -> leerUno($id);
        $especialidades = $web->obtenerEspecialidades();
        $consultorios = $web->obtenerConsultorios();
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> modificar($datos,$id, $info['id_usuario']);

            if($resultado){
                $alerta ['mensaje'] = 'MEDICO MODIFICADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $medicos = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/medico/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE MODIFICAR EL MEDICO';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/medico/form.php');
            }
        }else{
            include(__DIR__.'/views/medico/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web -> eliminar($id);
        if($resultado){
            $alerta ['mensaje'] = 'MEDICO ELIMINADO CORRECTAMENTE';
            $alerta ['tipo'] = 'success';
        }else{
            $alerta ['mensaje'] = 'NO SE PUEDE ELIMINAR EL MEDICO, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $medicos = $web -> leer();
        require_once (__DIR__.'/views/medico/index.php');
        break;
    case 'leer':
        default:
        $medicos = $web -> leer();
         
        require_once (__DIR__.'/views/medico/index.php');
}

require_once (__DIR__.'/views/footer.php');
?>