<?php
   
   require_once __DIR__ . '/funciones_mp.php';
   require_once __DIR__ . '/../models/transaccion.php';
   
   // Recoger datos de la cita (asumo que vienen por POST)
   $id_cita = $_POST['id_cita'] ?? 0;
   $precio = $_POST['precio'] ?? 0.0;
   $descripcion = $_POST['descripcion'] ?? 'Pago de Cita Médica';
   
   if ($id_cita <= 0 || $precio <= 0) {
       echo json_encode(['error' => 'Datos de cita inválidos.']);
       http_response_code(400);
       exit;
   }
   
   // Crear la preferencia en Mercado Pago
   $id_mp_preference = crearPreferenciaMercadoPago($id_cita, $precio, $descripcion);
   
   if ($id_mp_preference) {
       // Guardar la transacción inicial en la base de datos
       $transaccion = new Transaccion();
       $transaccion_id = $transaccion->crearTransaccionInicialMP($id_cita, $precio, $id_mp_preference);
   
       if ($transaccion_id) {
           echo json_encode(['preference_id' => $id_mp_preference]);
       } else {
           echo json_encode(['error' => 'Error al guardar la transacción.']);
           http_response_code(500);
       }
   } else {
       echo json_encode(['error' => 'Error al crear la preferencia.']);
       http_response_code(500);
   }
   
   ?>