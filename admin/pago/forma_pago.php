<?php

session_start(); 
require_once __DIR__ . '/../../models/transaccion.php'; 

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

// Variables para la vista
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
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        .payment-details p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .payment-details strong {
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="radio"] {
            margin-right: 5px;
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1em;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Confirmar Pago</h1>

        <div class="payment-details">
            <p>Estás por registrar un pago para la transacción #<?php echo htmlspecialchars($id_transaccion); ?>.</p>
            <p><strong>Monto a Pagar:</strong> <?php echo htmlspecialchars(number_format($monto_a_pagar, 2)); ?> <?php echo htmlspecialchars($moneda); ?></p>
        </div>

        <form action="confirmar_pago_interno.php" method="POST">
            <input type="hidden" name="id_transaccion" value="<?php echo htmlspecialchars($id_transaccion); ?>">

            <div class="form-group">
                <label>Selecciona un método de pago:</label>
                <div>
                    <input type="radio" id="metodo_efectivo" name="metodo_pago" value="EFECTIVO" required>
                    <label for="metodo_efectivo">Efectivo</label>
                </div>
                <div>
                    <input type="radio" id="metodo_tarjeta" name="metodo_pago" value="TARJETA">
                    <label for="metodo_tarjeta">Tarjeta de Crédito/Débito</label>
                </div>
            </div>

            <button type="submit" class="btn-submit">Confirmar Registro de Pago</button>
        </form>
    </div>
</body>

</html>