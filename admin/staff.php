<?php
require_once (__DIR__.'/models/staff.php');
require_once (__DIR__.'/views/header.php');

$web = new Staff();
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$alerta = [];

switch ($accion){

    case 'crear':
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> crear($datos);
            if($resultado){
                $alerta ['mensaje'] = 'MIEMBRO DE STAFF CREADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $staffs = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/staff/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE CREAR EL MIEMBRO DE STAFF';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/staff/form.php');
            }
        }else{
            include(__DIR__.'/views/staff/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web -> leerUno($id);
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> modificar($datos,$id, $info['id_usuario']);

            if($resultado){
                $alerta ['mensaje'] = 'MIEMBRO DE STAFF MODIFICADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $staffs = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/staff/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE MODIFICAR EL MIEMBRO DE STAFF';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/staff/form.php');
            }
        }else{
            include(__DIR__.'/views/staff/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web -> eliminar($id);
        if($resultado){
            $alerta ['mensaje'] = 'MIEMBRO DE STAFF ELIMINADO CORRECTAMENTE';
            $alerta ['tipo'] = 'success';
        }else{
            $alerta ['mensaje'] = 'NO SE PUEDE ELIMINAR EL MIEMBRO DE STAFF, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $staffs = $web -> leer();
        require_once (__DIR__.'/views/staff/index.php');
        break;
    case 'leer':
        default:
        $staffs = $web -> leer();
         
        require_once (__DIR__.'/views/staff/index.php');
}

require_once (__DIR__.'/views/footer.php');
?>