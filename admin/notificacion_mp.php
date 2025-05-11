<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Transaccion.php'; // Ruta a tu modelo Transaccion

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Client\MerchantOrder\MerchantOrderClient; // Si también manejas merchant_orders
use MercadoPago\Exceptions\MPApiException;

// Configurar el Access Token
if (defined('MP_ACCESS_TOKEN') && MP_ACCESS_TOKEN) {
    MercadoPagoConfig::setAccessToken(MP_ACCESS_TOKEN);
} else {
    error_log("IPN Error: MP_ACCESS_TOKEN no está configurado.");
    http_response_code(500); // Internal Server Error
    exit;
}

$transaccionModel = new Transaccion();

// Leer el cuerpo de la notificación POST
$raw_post_data = file_get_contents('php://input');
$notification_data = json_decode($raw_post_data, true);

// Verificar que source_news=ipn esté en la URL (como medida de seguridad básica)
if (!isset($_GET['source_news']) || $_GET['source_news'] !== 'ipn') {
    error_log("IPN Ignorado: Falta source_news=ipn en la URL.");
    http_response_code(400); // Bad Request
    exit;
}


if ($notification_data && isset($notification_data['type'], $notification_data['data']['id'])) {
    $type = $notification_data['type'];
    $resource_id = $notification_data['data']['id']; // ID del recurso (ej. payment_id)

    error_log("IPN Recibida: Tipo: {$type}, ID Recurso: {$resource_id}");

    try {
        if ($type === 'payment') {
            $paymentClient = new PaymentClient();
            $paymentInfo = $paymentClient->get($resource_id);

            if ($paymentInfo && $paymentInfo->id) {
                // Buscar la transacción en tu BD.
                // El paymentInfo->external_reference debería ser tu referencia (ej. CITA_ID_X_TIMESTAMP)
                // O si guardaste el paymentInfo->order->id (que es el merchant_order_id) en tu tabla de transacción.
                // O buscar por preference_id si lo tienes (aunque payment no siempre tiene preference_id directo, sí order->preference_id)

                $transaccion = $transaccionModel->buscarPorIdMpPayment($paymentInfo->id);

                if (!$transaccion && isset($paymentInfo->order->id)) {
                    // Si no se encontró por payment_id, intentar buscar por merchant_order_id (si lo guardas)
                    // o podrías tener que buscar la preferencia asociada al merchant_order y luego tu transacción
                    // Esto depende de cómo hayas estructurado tu BD y qué IDs guardes.
                    // Por simplicidad, asumimos que buscarPorIdMpPayment es suficiente o que tienes otro método.
                    error_log("IPN: Transacción no encontrada por payment_id {$paymentInfo->id}. Verificando external_reference o merchant_order si aplica.");
                }


                if ($transaccion) {
                    $id_transaccion_local = $transaccion['id_transaccion'];
                    $datos_respuesta_mp_json = json_encode($paymentInfo);

                    $actualizado = $transaccionModel->actualizarEstadoTransaccionMP(
                        $id_transaccion_local,
                        $paymentInfo->id,
                        $paymentInfo->status,
                        $datos_respuesta_mp_json
                    );

                    if ($actualizado) {
                        error_log("IPN: Transacción local ID {$id_transaccion_local} actualizada para payment ID {$paymentInfo->id}, estado: {$paymentInfo->status}.");
                        // Lógica adicional si el pago es aprobado, etc.
                        // if ($paymentInfo->status === 'approved') {
                            // $this->citaModel->actualizarEstado($transaccion['id_cita'], 'PAGADA');
                            // Enviar email de confirmación, etc.
                        // }
                    } else {
                        error_log("IPN Error: No se pudo actualizar la transacción local ID {$id_transaccion_local} para payment ID {$paymentInfo->id}.");
                    }
                } else {
                    error_log("IPN Error: Transacción local no encontrada para payment ID {$paymentInfo->id}. External Reference: " . ($paymentInfo->external_reference ?? 'N/A'));
                    // Considera crear un registro de transacción si no existe y es un pago válido que te corresponde.
                    // Esto puede pasar si la creación inicial falló pero el pago se completó.
                }
            } else {
                error_log("IPN Error: No se pudo obtener información del pago ID {$resource_id} desde MP.");
            }
        } elseif ($type === 'merchant_order') {
            // Si estás usando Merchant Orders y quieres manejar sus notificaciones
            // $merchantOrderClient = new MerchantOrderClient();
            // $merchantOrderInfo = $merchantOrderClient->get($resource_id);
            // ... lógica para actualizar basado en la orden mercantil ...
            error_log("IPN: Notificación de Merchant Order ID {$resource_id} recibida. Lógica de manejo no implementada en este ejemplo.");
        } else {
            error_log("IPN Ignorado: Tipo de notificación '{$type}' no manejado.");
        }

        http_response_code(200); // OK - Notificación procesada (o al menos recibida e intentada)

    } catch (MPApiException $e) {
        error_log("IPN MPApiException: " . $e->getApiResponse()->getStatusCode() . " - " . json_encode($e->getApiResponse()->getContent()));
        http_response_code(500); // Error del servidor al procesar
    } catch (\Exception $e) {
        error_log("IPN Exception: " . $e->getMessage());
        http_response_code(500); // Error del servidor al procesar
    }
} else {
    error_log("IPN Error: Notificación inválida o datos faltantes. Payload: " . $raw_post_data);
    http_response_code(400); // Bad Request
}
exit; // Terminar el script después de enviar la respuesta HTTP
?>