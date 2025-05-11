<?php
require_once (__DIR__.'/models/paciente.php');
require_once (__DIR__.'/views/header.php');

$web = new Paciente();
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
                $alerta ['mensaje'] = 'PACIENTE CREADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $pacientes = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/paciente/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE CREAR EL PACIENTE';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/paciente/form.php');
            }
        }else{
            include(__DIR__.'/views/paciente/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web -> leerUno($id);
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> modificar($datos,$id, $info['id_usuario']);

            if($resultado){
                $alerta ['mensaje'] = 'PACIENTE MODIFICADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $pacientes = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/paciente/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE MODIFICAR EL PACIENTE';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/paciente/form.php');
            }
        }else{
            include(__DIR__.'/views/paciente/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web -> eliminar($id);
        if($resultado){
            $alerta ['mensaje'] = 'PACIENTE ELIMINADO CORRECTAMENTE';
            $alerta ['tipo'] = 'success';
        }else{
            $alerta ['mensaje'] = 'NO SE PUEDE ELIMINAR EL PACIENTE, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $pacientes = $web -> leer();
        require_once (__DIR__.'/views/paciente/index.php');
        break;
    case 'leer':
        default:
        $pacientes = $web -> leer();
         
        require_once (__DIR__.'/views/paciente/index.php');
}

require_once (__DIR__.'/views/footer.php');
?>