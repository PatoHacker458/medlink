<?php
session_start(); 
require_once __DIR__ . '/../models/transaccion.php'; 

$id_transaccion = $_GET['id_transaccion'] ?? null;

if (empty($id_transaccion) || !filter_var($id_transaccion, FILTER_VALIDATE_INT)) {
    die("Error: Identificador de transacción no válido.");
}
$id_transaccion = (int)$id_transaccion;

$transaccion_model = new Transaccion();
$transaccion_data = $transaccion_model->getTransaccionById($id_transaccion);

if (!$transaccion_data) {
    die("Error: Transacción no encontrada.");
}

if (!empty($transaccion_data['metodo_pago'])) {
    die("Este pago ya ha sido procesado. Método de pago seleccionado: " . htmlspecialchars($transaccion_data['metodo_pago']));
}

$monto_a_pagar = $transaccion_data['monto'];
$moneda = $transaccion_data['moneda'] ?? 'MXN';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Método de Pago</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        .btn { display: inline-block; padding: 10px 20px; margin: 10px; font-size: 1.2em; text-decoration: none; border-radius: 5px; cursor: pointer; }
        .btn-cash { background-color: #28a745; color: white; }
        .btn-card { background-color: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seleccionar Método de Pago</h1>
        <div class="payment-details">
            <p><strong>Monto a Pagar:</strong> <?php echo htmlspecialchars(number_format($monto_a_pagar, 2)); ?> <?php echo htmlspecialchars($moneda); ?></p>
        </div>

        <p>Por favor, seleccione cómo desea pagar:</p>
        
        <a href="actualizar_pago.php?id_transaccion=<?php echo htmlspecialchars($id_transaccion); ?>&metodo=EFECTIVO" class="btn btn-cash">Pago en Efectivo</a>
        <a href="actualizar_pago.php?id_transaccion=<?php echo htmlspecialchars($id_transaccion); ?>&metodo=TARJETA" class="btn btn-card">Pago con Tarjeta</a>
    </div>
</body>
</html>