<?php

require_once (__DIR__.'/../model.php');

class Transaccion extends Model {

    public function crear($id_cita, $monto) {
        $this->conectar();
        if (!$this->conn) {
            error_log("crearTransaccionInternaPendiente: Conexión a BD no establecida.");
            return false;
        }
        
        $sql = "INSERT INTO transaccion (id_cita, monto, metodo_pago, estado) 
                VALUES (:id_cita, :monto, NULL, 'PENDIENTE')";

        try {
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
            $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                error_log("Error en crearTransaccionInternaPendiente al ejecutar: " . implode(" | ", $stmt->errorInfo()));
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException en crearTransaccionInternaPendiente: " . $e->getMessage());
            return false;
        }
    }

    public function getTransaccionById($id_transaccion) {
        $this->conectar();
        if (!$this->conn) {
            error_log("getTransaccionById: Conexión a BD no establecida.");
            return false;
        }

        $sql = "SELECT id_transaccion, id_cita, monto, moneda, metodo_pago, estado, fecha_creacion, fecha_actualizacion 
                FROM transaccion 
                WHERE id_transaccion = :id_transaccion";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDOException en getTransaccionById (ID: {$id_transaccion}): " . $e->getMessage());
            return false;
        }
    }

    public function getTransaccionByCita($id_cita)
    {
        $this->conectar();
        if (!$this->conn) {
            error_log("getTransaccionByCitaId: Conexión a BD no establecida.");
            return false;
        }

        $sql = "SELECT id_transaccion, id_cita, monto, moneda, metodo_pago, estado, fecha_creacion, fecha_actualizacion 
            FROM transaccion 
            WHERE id_cita = :id_cita 
            ORDER BY id_transaccion DESC LIMIT 1";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
            $stmt->execute();
            $transaccion = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($transaccion) {
                return $transaccion;
            } else {
                error_log("No se encontró transacción para id_cita: " . $id_cita);
                return false;
            }
        } catch (PDOException $e) {
            error_log("PDOException en getTransaccionByCitaId (id_cita: {$id_cita}): " . $e->getMessage());
            return false;
        }
    }
    
    public function actualizarMetodoDePago($id_transaccion, $metodo_pago_seleccionado) {
        $this->conectar();
        if (!$this->conn) {
            error_log("actualizarMetodoDePago: Conexión a BD no establecida.");
            return false;
        }

        $sql = "UPDATE transaccion 
                SET metodo_pago = :metodo_pago, estado = 'PAGADO' 
                WHERE id_transaccion = :id_transaccion";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':metodo_pago', $metodo_pago_seleccionado, PDO::PARAM_STR);
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDOException en actualizarMetodoDePago (ID: {$id_transaccion}): " . $e->getMessage());
            return false;
        }
    }
}
?>