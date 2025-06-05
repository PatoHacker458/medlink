<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once (__DIR__.'/models/cita.php');
require_once (__DIR__.'/models/paciente.php');
require_once (__DIR__.'/models/medico.php');
require_once (__DIR__.'/models/consultorio.php');

use Carbon\Carbon;
if (!defined('CARBON_INITIALIZED')) {
    date_default_timezone_set('America/Mexico_City');
    Carbon::setLocale('es');
    define('CARBON_INITIALIZED', true);
}

$web = new Cita();
$medico_model = new Medico();

ob_start();
require_once (__DIR__.'/views/header.php');

$is_admin = (isset($_SESSION['roles']) && in_array('Administrador', $_SESSION['roles']));
$is_medico_role_only = (isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !$is_admin);
$logged_medico = null;
$disable = false;

if ($is_medico_role_only) {
    $logged_medico = $medico_model->obtenerIdMedicoLogueado();
    if ($logged_medico !== null) {
        $disable = true;
    }
}

$accion = $_GET['accion'] ?? 'leer';
$id = $_GET['id'] ?? null;
$alerta = $_SESSION['alerta'] ?? [];
if (!empty($alerta)) {
    unset($_SESSION['alerta']);
}
$pacientes = $web->leerPacientes();
$medicos = $web->leerMedicos();
$consultorios = $web->leerConsultorios();

switch ($accion) {
    case 'crear':
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'] ?? [];
            if ($disable && $logged_medico !== null) {
                $datos['id_medico'] = $logged_medico;
            }

            $resultado = $web->crear($datos);
            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita creada correctamente.'];
                ob_clean();
                header('Location: cita.php');
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al crear la cita. Verifica los datos o la disponibilidad.'];
                $info = $datos;
                if ($disable && $logged_medico !== null) {
                    $info['id_medico'] = $logged_medico;
                }
                include(__DIR__.'/views/cita/form.php');
            }
        } else {
            $info = [];
            if ($disable && $logged_medico !== null) {
                $info['id_medico'] = $logged_medico;
            }
            include(__DIR__.'/views/cita/form.php');
        }
        break;

     case 'modificar':
        if ($id === null) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de cita no proporcionado.'];
            ob_clean();
            header('Location: cita.php');
            exit;
        }
        $info = $web->leerUno($id);
        if (!$info) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Cita no encontrada o no tienes permiso para verla.'];
            ob_clean();
            header('Location: cita.php');
            exit;
        }
        
        if ($is_medico_role_only && $logged_medico !== null && $info['id_medico'] != $logged_medico) {
             $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No tienes permiso para modificar esta cita.'];
             ob_clean();
             header('Location: cita.php');
             exit;
        }

        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'] ?? [];
            if ($disable && $logged_medico !== null) {
                $datos['id_medico'] = $logged_medico;
            }

            $resultado = $web->modificar($datos, $id);
            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita modificada correctamente.'];
                ob_clean();
                header('Location: cita.php');
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al modificar la cita. Verifica los datos o la disponibilidad.'];
                $info = array_merge($info, $datos);
                 if ($disable && $logged_medico !== null) {
                    $info['id_medico'] = $logged_medico;
                }
                include(__DIR__.'/views/cita/form.php');
            }
        } else {
            if ($disable && $logged_medico !== null) {
                $info['id_medico'] = $logged_medico;
            }
            include(__DIR__.'/views/cita/form.php');
        }
        break;

    case 'eliminar':
        if ($id === null) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de cita no proporcionado.'];
            ob_clean();
            header('Location: cita.php');
            exit;
        }
        $resultado = $web->eliminar($id);
        if ($resultado) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita eliminada correctamente.'];
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al eliminar la cita. Puede tener transacciones asociadas.'];
        }
        ob_clean();
        header('Location: cita.php');
        exit;

    case 'leer':
    default:
        $citas = $web->leer();
        $web->alerta($alerta);
        if (file_exists(__DIR__.'/views/cita/index.php')) {
            include(__DIR__.'/views/cita/index.php');
        } else {
        }
        break;
}

require_once (__DIR__.'/views/footer.php');
?>