<?php

session_start();

$id_transaccion = $_GET['id_transaccion'] ?? null;
$status = $_GET['status'] ?? 'unknown';
$metodo = $_GET['method'] ?? '';

$mensaje_titulo = "Estado del Pago";
$mensaje_cuerpo = "Ocurrió un error desconocido.";
$clase_mensaje = "error";

if (!empty($id_transaccion)) {
    if ($status === 'success') {
        $mensaje_titulo = "¡Pago Registrado Exitosamente!";
        $mensaje_cuerpo = "El método de pago '" . htmlspecialchars(urldecode($metodo)) . "' ha sido registrado para la transacción #" . htmlspecialchars($id_transaccion) . ".";
        $clase_mensaje = "success";
    } elseif ($status === 'already_set') {
        $mensaje_titulo = "Información";
        $mensaje_cuerpo = "El método de pago para la transacción #" . htmlspecialchars($id_transaccion) . " ya había sido establecido a '" . htmlspecialchars(urldecode($metodo)) . "'.";
        $clase_mensaje = "info";
    } else {
        $mensaje_titulo = "Error en el Proceso";
        $mensaje_cuerpo = "Hubo un problema al procesar el pago para la transacción #" . htmlspecialchars($id_transaccion) . ". Por favor, contacta a soporte si el problema persiste.";
    }
} else {
    $mensaje_titulo = "Error";
    $mensaje_cuerpo = "No se proporcionó información de la transacción.";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($mensaje_titulo); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            width: 90%;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #fff;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

        p {
            font-size: 1.1em;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        .success {
            color: #28a745;
        }

        .info {
            color: #17a2b8;
        }

        .error {
            color: #dc3545;
        }

        a.button-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        a.button-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($mensaje_titulo); ?></h1>
        <p class="<?php echo $clase_mensaje; ?>">
            <?php echo $mensaje_cuerpo; ?>
        </p>
        <p>
            <a href="/admin/cita.php" class="button-link">Volver a Citas</a>
        </p>
    </div>
</body>

</html>