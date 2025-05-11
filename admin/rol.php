<?php
require_once (__DIR__.'/models/rol.php');
require_once (__DIR__.'/models/permiso.php');
$web = new Rol();
$web2 = new Permiso();
$accion = isset($_GET['accion']) ? $_GET['accion'] : null;
$id = isset($_GET['id']) ? $_GET['id']:null;
$alerta = [];
require_once (__DIR__.'/views/header.php');

switch ($accion){
    case 'crear':
        $permisos = $web2 -> leer();
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $r_permisos = isset($_POST['r_permisos']) ? $_POST['r_permisos'] : null;
            $resultado = $web -> crear($datos, $r_permisos);
            if($resultado){
                $alerta ['mensaje'] = 'ROL CREADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success'; 
                $rols = $web -> leer();
                $web -> alerta($alerta);
                require_once (__DIR__ . '/views/rol/index.php');
                
            } else {
                $alerta ['mensaje'] = 'NO SE PUDO CREAR EL ROL';
                $alerta ['tipo'] = 'danger'; 
                $web -> alerta($alerta);
                include_once (__DIR__.'/views/rol/form.php');
            }
        } else{
            include_once (__DIR__.'/views/rol/form.php');
        }
        
        break;
    case 'modificar':
        $permisos = $web2 -> leer();
        $info=null;
        $info = $web -> leerUno($id);
        if(isset($_POST['enviar'])){
            $datos = $_POST['datos'];
            $r_permisos = isset($_POST['r_permisos']) ? $_POST['r_permisos'] : null;
            $resultado = $web -> modificar($datos, $id, $r_permisos);            
            if($resultado){
                $alerta ['mensaje'] = 'ROL MODIFICADO CORRECTAMENTE';
                $alerta ['tipo'] = 'success'; 
                $rols = $web -> leer();
                $web -> alerta($alerta);
                require_once (__DIR__ . '/views/rol/index.php');
                
            } else {
                $alerta ['mensaje'] = 'NO SE PUDO MODIFICAR EL ROL';
                $alerta ['tipo'] = 'danger'; 
                $web -> alerta($alerta);
                include_once (__DIR__.'/views/rol/form.php');
            }
        } else{
            include_once (__DIR__.'/views/rol/form.php');
        }
        break;
    case 'eliminar':        
        $resultado = $web -> eliminar($id);
        if($resultado){
            $alerta ['mensaje'] = 'ROL ELIMINADO CORRECTAMENTE';
            $alerta ['tipo'] = 'success'; 
            
        } else {
            $alerta ['mensaje'] = 'NO SE PUDO ELIMINAR EL ROL, HAY ELEMENTOS QUE DEPENDEN DE EL';
            $alerta ['tipo'] = 'danger'; 
        }
        $web -> alerta($alerta);
        $rols = $web -> leer();
        require_once (__DIR__ . '/views/rol/index.php');
        break;
    case 'leer':
        default:
        $rols = $web->leer();
        require_once (__DIR__ . '/views/rol/index.php');
    }
require_once (__DIR__.'/views/footer.php');