<?php
session_start();
require_once __DIR__ . '/../../models/transaccion.php';

$id_transaccion = $_GET['id_transaccion'] ?? null;
$metodo_pago_seleccionado = $_GET['metodo'] ?? null;

$errores = [];
if (empty($id_transaccion) || !filter_var($id_transaccion, FILTER_VALIDATE_INT)) {
    $errores[] = "ID de transacción no válido.";
}
if (empty($metodo_pago_seleccionado) || !in_array(strtoupper($metodo_pago_seleccionado), ['EFECTIVO', 'TARJETA'])) {
    $errores[] = "Método de pago no válido.";
}

if (!empty($errores)) {
    die("Error en los datos proporcionados: " . implode(", ", $errores));
}

$id_transaccion = (int)$id_transaccion;
$metodo_pago_seleccionado = strtoupper($metodo_pago_seleccionado);

$transaccion_model = new Transaccion();

$transaccion_existente = $transaccion_model->getTransaccionById($id_transaccion);
if (!$transaccion_existente) {
    error_log("actualizar_metodo_pago: Transacción ID {$id_transaccion} no encontrada.");
    die("Error: La transacción no existe.");
}
if (!empty($transaccion_existente['metodo_pago'])) {
    error_log("actualizar_metodo_pago: Transacción ID {$id_transaccion} ya procesada con método " . $transaccion_existente['metodo_pago']);
    header("Location: pago_realizado_ok.php?id_transaccion=" . $id_transaccion . "&status=already_set&method=" . urlencode($transaccion_existente['metodo_pago']));
    exit;
}

$actualizacion_exitosa = $transaccion_model->actualizarMetodoDePago($id_transaccion, $metodo_pago_seleccionado);

if ($actualizacion_exitosa) {
    header("Location: pago_realizado_ok.php?id_transaccion=" . $id_transaccion . "&status=success&method=" . urlencode($metodo_pago_seleccionado));
    exit;
} else {
    error_log("Error al actualizar el método de pago para la transacción ID: " . $id_transaccion);
    die("Error: No se pudo registrar el método de pago. Por favor, inténtelo más tarde.");
}
