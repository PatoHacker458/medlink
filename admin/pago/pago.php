<?php
echo "<pre>Contenido de \$_POST:\n";
print_r($_POST);
echo "</pre>";
require_once __DIR__ . '/../models/transaccion.php';

$id_cita = $_POST['id_cita'] ?? null;
$precio = $_POST['precio'] ?? null;

if (
    empty($id_cita) || !is_numeric($id_cita) || $id_cita <= 0 ||
    empty($precio) || !is_numeric($precio) || $precio <= 0
) {
    die("Error: Datos de la cita inválidos o incompletos.");
}

$id_cita = (int)$id_cita;
$precio = (float)$precio;

$transaccion_model = new Transaccion();

$id_transaccion_creada = $transaccion_model->crear($id_cita, $precio);

if ($id_transaccion_creada) {
    header("Location: seleccionar_metodo_pago.php?id_transaccion=" . $id_transaccion_creada);
    exit;
} else {
    error_log("Error al crear la transacción interna pendiente para la cita ID: " . $id_cita);
    die("Error: No se pudo iniciar el proceso de pago. Por favor, inténtelo más tarde.");
}
