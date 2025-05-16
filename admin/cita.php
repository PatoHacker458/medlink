<?php
require_once (__DIR__.'/models/cita.php');
require_once (__DIR__.'/models/paciente.php');
require_once (__DIR__.'/models/medico.php');
require_once (__DIR__.'/models/consultorio.php');

$web = new Cita();

ob_start();
require_once (__DIR__.'/views/header.php');

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
            $resultado = $web->crear($datos);
            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita creada correctamente.'];
                ob_clean();
                header('Location: cita.php');
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al crear la cita. Verifica los datos.'];
                include(__DIR__.'/views/cita/form.php');
            }
        } else {
            $info = [];
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
            $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Cita no encontrada.'];
            ob_clean();
            header('Location: cita.php');
            exit;
        }

        if (isset($_POST['enviar'])) {
            $datos = $_POST['datos'] ?? [];
            $resultado = $web->modificar($datos, $id);
            if ($resultado) {
                $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => 'Cita modificada correctamente.'];
                ob_clean();
                header('Location: cita.php');
                exit;
            } else {
                $alerta = ['tipo' => 'danger', 'mensaje' => 'Error al modificar la cita. Verifica los datos.'];
                $info = array_merge($info, $datos);
                include(__DIR__.'/views/cita/form.php');
            }
        } else {
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