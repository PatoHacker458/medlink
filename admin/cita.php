<?php
// admin/cita.php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/models/cita.php');
require_once(__DIR__ . '/models/paciente.php');
require_once(__DIR__ . '/models/medico.php');
require_once(__DIR__ . '/models/consultorio.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

use Carbon\Carbon;

if (!defined('CARBON_INITIALIZED')) {
    date_default_timezone_set('America/Mexico_City');
    Carbon::setLocale('es');
    define('CARBON_INITIALIZED', true);
}

$web = new Cita();
$medico_model_cita = new Medico();

ob_start();
require_once(__DIR__ . '/views/header.php');

$is_admin = (isset($_SESSION['roles']) && in_array('Administrador', $_SESSION['roles']));
$is_paciente_role = (isset($_SESSION['roles']) && in_array('Paciente', $_SESSION['roles']));
$is_medico_role_only = (isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !$is_admin);
$logged_medico_id = null;

$disable_paciente_dropdown = false;
$disable_medico_dropdown = false;

if ($is_medico_role_only) {
    $logged_medico_id = $medico_model_cita->obtenerIdMedicoLogueado();
    if ($logged_medico_id !== null) {
        $disable_medico_dropdown = true;
    }
}

$accion = $_GET['accion'] ?? 'leer';
$id = $_GET['id'] ?? null;
$alerta = $_SESSION['alerta'] ?? [];
if (!empty($alerta)) {
    unset($_SESSION['alerta']);
}

$pacientes = $web->leerPacientes();
$medicos_list = $web->leerMedicos();
$consultorios = $web->leerConsultorios();
$info = [];

if ($accion === 'crear' && $is_paciente_role && isset($_SESSION['agendar_cita_id_medico']) && isset($_SESSION['agendar_cita_id_paciente'])) {
    $info['id_medico'] = $_SESSION['agendar_cita_id_medico'];
    $info['id_paciente'] = $_SESSION['agendar_cita_id_paciente'];
    $disable_medico_dropdown = true;
    $disable_paciente_dropdown = true;
    unset($_SESSION['agendar_cita_id_medico']);
    unset($_SESSION['agendar_cita_id_paciente']);
} elseif ($accion === 'crear' && $is_medico_role_only && $logged_medico_id !== null) {
    $info['id_medico'] = $logged_medico_id;
}


switch ($accion) {
    case 'crear':
        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'] ?? [];
            if ($disable_paciente_dropdown && isset($info['id_paciente']) && !isset($datos['id_paciente'])) {
                $datos['id_paciente'] = $info['id_paciente'];
            }
            if ($disable_medico_dropdown && isset($info['id_medico']) && !isset($datos['id_medico'])) {
                $datos['id_medico'] = $info['id_medico'];
            }
            if ($is_medico_role_only && $logged_medico_id !== null) {
                $datos['id_medico'] = $logged_medico_id;
            }

            $resultado = $web->crear($datos);

            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => '¡Tu cita ha sido agendada correctamente!']; // Mensaje amigable para el paciente
                
                if (ob_get_level() > 0) {
                    ob_end_clean();
                }

                $es_solo_paciente = false;
                if (isset($_SESSION['roles']) && is_array($_SESSION['roles'])) {
                    $es_solo_paciente = in_array('Paciente', $_SESSION['roles']) &&
                                        !in_array('Administrador', $_SESSION['roles']) &&
                                        !in_array('Medico', $_SESSION['roles']);
                }

                if ($es_solo_paciente) {
                    header('Location: ../medicos.php');
                } else {
                    header('Location: cita.php'); 
                }
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al crear la cita. Verifica los datos o la disponibilidad.'];
                $info = array_merge($info, $datos);
                include(__DIR__ . '/views/cita/form.php');
            }
        } else {
            include(__DIR__ . '/views/cita/form.php');
        }
        break;

    case 'modificar':
        if ($id === null) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de cita no proporcionado.'];
            if (ob_get_level() > 0) ob_clean();
            header('Location: cita.php');
            exit;
        }
        $info = $web->leerUno($id);
        if (!$info) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Cita no encontrada.'];
            if (ob_get_level() > 0) ob_clean();
            header('Location: cita.php');
            exit;
        }

        if ($is_medico_role_only && $logged_medico_id !== null) {
            if ($info['id_medico'] != $logged_medico_id) {
                $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No tienes permiso para modificar esta cita.'];
                if (ob_get_level() > 0) ob_clean();
                header('Location: cita.php');
                exit;
            }
            $disable_medico_dropdown = true;
            $info['id_medico'] = $logged_medico_id;
        }


        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'] ?? [];
            if ($disable_medico_dropdown && isset($info['id_medico'])) {
                $datos['id_medico'] = $info['id_medico'];
            }

            $resultado = $web->modificar($datos, $id);
            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita modificada correctamente.'];
                if (ob_get_level() > 0) ob_clean();
                header('Location: cita.php');
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al modificar la cita. Verifica los datos o la disponibilidad.'];
                $info = array_merge($info, $datos);
                include(__DIR__ . '/views/cita/form.php');
            }
        } else {
            include(__DIR__ . '/views/cita/form.php');
        }
        break;

    case 'eliminar':
        if ($id === null) {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'ID de cita no proporcionado.'];
            if (ob_get_level() > 0) ob_clean();
            header('Location: cita.php');
            exit;
        }

        if ($is_medico_role_only && $logged_medico_id !== null) {
            $cita_a_eliminar = $web->leerUno($id);
            if (!$cita_a_eliminar || $cita_a_eliminar['id_medico'] != $logged_medico_id) {
                $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No tienes permiso para eliminar esta cita.'];
                if (ob_get_level() > 0) ob_clean();
                header('Location: cita.php');
                exit;
            }
        }

        $resultado = $web->eliminar($id);
        if ($resultado) {
            $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita eliminada correctamente.'];
        } else {
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Error al eliminar la cita. Puede tener transacciones asociadas o ya no existe.'];
        }
        if (ob_get_level() > 0) ob_clean();
        header('Location: cita.php');
        exit;

    case 'leer':
    default:
        if ($is_medico_role_only && $logged_medico_id !== null) {
            $citas = $web->leerPorMedico($logged_medico_id);
        } else {
            $citas = $web->leer();
        }
        $web->alerta($alerta);
        if (file_exists(__DIR__ . '/views/cita/index.php')) {
            include(__DIR__ . '/views/cita/index.php');
        } else {
            echo "Vista no encontrada.";
        }
        break;
}

require_once(__DIR__ . '/views/footer.php');
ob_end_flush();
