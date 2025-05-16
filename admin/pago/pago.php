<?php
ob_start();
require_once __DIR__ . '/../models/transaccion.php';
require_once __DIR__ . '/../models/cita.php';

$id_cita = $_POST['id_cita'] ?? null;

if (empty($id_cita) || !is_numeric($id_cita) || $id_cita <= 0) {
    die("Error: ID de cita inválido o incompleto.");
}
$id_cita = (int)$id_cita;

$transaccion_model = new Transaccion();
$transaccion_existente = $transaccion_model->getTransaccionByCita($id_cita);

if ($transaccion_existente) {
    ob_clean();
    header("Location: forma_pago.php?id_transaccion=" . $transaccion_existente['id_transaccion']);
    exit;
} else {
    $cita_model = new Cita();
    $datos_cita = $cita_model->leerUno($id_cita);

    if ($datos_cita && isset($datos_cita['precio'])) {
        error_log("Fallback: No se encontró transacción para la cita ID {$id_cita} vía getTransaccionByCitaId. Intentando crearla ahora.");
        $id_transaccion_creada_manual = $transaccion_model->crear($id_cita, $datos_cita['precio']);

        if ($id_transaccion_creada_manual) {
            header("Location: forma_pago.php?id_transaccion=" . $id_transaccion_creada_manual);
            exit;
        } else {
            error_log("Error CRÍTICO: No se encontró transacción para la cita ID {$id_cita} y tampoco se pudo crear manualmente.");
            exit;
        }
    } else {
        error_log("Error: No se encontró transacción para la cita ID {$id_cita} y no se pudo obtener datos de la cita o su precio.");
        exit;
    }
}
?>