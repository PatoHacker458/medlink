<?php
   
   require_once __DIR__ . '/funciones_mp.php';
   require_once __DIR__ . '/../models/transaccion.php';
   
   // Obtener los datos de la notificación
   $payment_id = $_GET['payment_id'] ?? null;
   $status = $_GET['status'] ?? null;
   $preference_id = $_GET['preference_id'] ?? null;
   
   if (!$payment_id || !$status || !$preference_id) {
       error_log("Notificación de Mercado Pago incompleta.");
       http_response_code(400);
       exit;
   }
   
   $transaccion = new Transaccion();
   $transaccion_data = $transaccion->buscarPorIdMpPreference($preference_id);
   
   if ($transaccion_data) {
       // Actualizar el estado de la transacción en la base de datos
       $transaccion->actualizarEstadoTransaccionMP($transaccion_data['id_transaccion'], $payment_id, $status);
   
       echo "OK"; // Responder a Mercado Pago para confirmar la recepción
   } else {
       error_log("Transacción no encontrada para la preferencia: " . $preference_id);
       http_response_code(404);
   }
   
   ?>