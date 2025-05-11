<?php
require_once (__DIR__.'/models/permiso.php');
require_once (__DIR__.'/views/header.php');

$web = new Permiso();
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$alerta = [];


switch ($accion){

    case 'crear':
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> crear($datos);
            if($resultado){
                $alerta ['mensaje'] = 'PERMISO CREADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $permisos = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/permiso/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE CREAR EL PERMISO';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/permiso/form.php');
            }
        }else{
            include(__DIR__.'/views/permiso/form.php');
        }
        break;

    case 'modificar':
        $info = null;
        $info = $web -> leerUno($id);
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $resultado = $web -> modificar($datos,$id);

            if($resultado){
                $alerta ['mensaje'] = 'PERMISO MODIFICADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success';
                $permisos = $web -> leer();
                $web -> alerta($alerta);
                include(__DIR__.'/views/permiso/index.php');
            }else{
                $alerta ['mensaje'] = 'NO SE PUEDE MODIFICAR EL PERMISO';
                $alerta ['tipo'] = 'danger';
                $web -> alerta($alerta);
                include (__DIR__.'/views/permiso/form.php');
            }
        }else{
            include(__DIR__.'/views/permiso/form.php');
        }
        break;

    case 'eliminar':
        $resultado = $web -> eliminar($id);
        if($resultado){
            $alerta ['mensaje'] = 'PERMISO ELIMINADO CORRECTAMENTE';
            $alerta ['tipo'] = 'success';
        }else{
            $alerta ['mensaje'] = 'NO SE PUEDE ELIMINAR EL PERMISO, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger';
        }
        $web -> alerta($alerta);
        $permisos = $web -> leer();
        require_once (__DIR__.'/views/permiso/index.php');
        break;
    case 'leer':
        default:
        $permisos = $web -> leer();
         
        require_once (__DIR__.'/views/permiso/index.php');
}


require_once (__DIR__.'/views/footer.php');

?>