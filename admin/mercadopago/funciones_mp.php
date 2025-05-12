<?php
   
   require_once __DIR__ . '/../config.php'; // Incluir la configuración
   
   use MercadoPago\Preference;
   use MercadoPago\Item;
   
   MercadoPago\SDK::setAccessToken(MP_ACCESS_TOKEN);
   
   /**
    * Crea una preferencia de pago en Mercado Pago.
    *
    * @param int    $id_cita      El ID de la cita en tu sistema.
    * @param float  $precio       El precio de la cita.
    * @param string $descripcion  Descripción del pago.
    * @return string|false El ID de la preferencia o false en caso de error.
    */
   function crearPreferenciaMercadoPago(int $id_cita, float $precio, string $descripcion) {
       $preference = new Preference();
   
       $item = new Item();
       $item->title = $descripcion;
       $item->quantity = 1;
       $item->unit_price = $precio;
   
       $preference->items = array($item);
       $preference->external_reference = strval($id_cita); // Convertir a string
       $preference->save();
   
       if ($preference->id) {
           return $preference->id;
       } else {
           error_log("Error al crear la preferencia en Mercado Pago: " . json_encode($preference->error));
           return false;
       }
   }
   
   ?>