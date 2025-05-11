<?php
require_once (__DIR__.'/../model.php');

class Transaccion extends Model {

    public function crearTransaccionInicialMP($id_cita, $monto, $id_mp_preference, $estado_inicial = 'PENDING_PAYMENT', $moneda = 'MXN') {
        if (!filter_var($id_cita, FILTER_VALIDATE_INT) || !is_numeric($monto) || empty($id_mp_preference)) {
            error_log("Error en crearTransaccionInicialMP: Datos de entrada no válidos. CitaID: {$id_cita}, Monto: {$monto}, PrefID: {$id_mp_preference}");
            return false;
        }

        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO transaccion (id_cita, id_mp_preference, monto, moneda, estado_mp, metodo_pago)
                    VALUES (:id_cita, :id_mp_preference, :monto, :moneda, :estado_mp, :metodo_pago)";
            $stmt = $this->conn->prepare($sql);

            $monto_str = number_format((float)$monto, 2, '.', '');
            $metodo = 'MercadoPago';

            $stmt->bindParam(':id_cita', $id_cita, PDO::PARAM_INT);
            $stmt->bindParam(':id_mp_preference', $id_mp_preference, PDO::PARAM_STR);
            $stmt->bindParam(':monto', $monto_str, PDO::PARAM_STR);
            $stmt->bindParam(':moneda', $moneda, PDO::PARAM_STR);
            $stmt->bindParam(':estado_mp', $estado_inicial, PDO::PARAM_STR);
            $stmt->bindParam(':metodo_pago', $metodo, PDO::PARAM_STR);

            $stmt->execute();
            $lastInsertId = $this->conn->lastInsertId();
            $this->conn->commit();
            return $lastInsertId;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("PDOException en crearTransaccionInicialMP: " . $e->getMessage());
            return false;
        }
    }

    public function actualizarEstadoTransaccionMP($id_transaccion_local, $id_mp_payment, $estado_mp, $datos_respuesta_mp = null) {
        if (!filter_var($id_transaccion_local, FILTER_VALIDATE_INT) || empty($estado_mp)) {
            error_log("Error en actualizarEstadoTransaccionMP: ID de transacción local o estado_mp no válidos.");
            return false;
        }
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE transaccion SET
                        id_mp_payment = :id_mp_payment,
                        estado_mp = :estado_mp
                    WHERE id_transaccion = :id_transaccion_local";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id_mp_payment', $id_mp_payment, PDO::PARAM_STR);
            $stmt->bindParam(':estado_mp', $estado_mp, PDO::PARAM_STR);
            $stmt->bindParam(':id_transaccion_local', $id_transaccion_local, PDO::PARAM_INT);
            $stmt->execute();
            $rowCount = $stmt->rowCount();
            $this->conn->commit();
            return $rowCount > 0;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("PDOException en actualizarEstadoTransaccionMP para ID {$id_transaccion_local}: " . $e->getMessage());
            return false;
        }
    }

    public function leerUno($id_transaccion) {
        if (!filter_var($id_transaccion, FILTER_VALIDATE_INT)) {
            return false;
        }
        $this->conectar();
        try {
            $sql = "SELECT t.*,
                           c.fecha as cita_fecha,
                           c.hora as cita_hora,
                           CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre
                    FROM transaccion t
                    JOIN cita c ON t.id_cita = c.id_cita
                    JOIN paciente p ON c.id_paciente = p.id_paciente
                    WHERE t.id_transaccion = :id_transaccion";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDOException en leerUno (Transaccion) para ID {$id_transaccion}: " . $e->getMessage());
            return false;
        }
    }

    public function leerTodas() {
        $this->conectar();
        try {
            $sql = "SELECT t.*,
                           c.fecha as cita_fecha,
                           c.hora as cita_hora,
                           CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre
                    FROM transaccion t
                    JOIN cita c ON t.id_cita = c.id_cita
                    JOIN paciente p ON c.id_paciente = p.id_paciente
                    ORDER BY t.fecha_creacion DESC";
            $stmt = $this->conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDOException en leerTodas (Transaccion): " . $e->getMessage());
            return [];
        }
    }

    public function buscarPorIdMpPreference($id_mp_preference) {
        if (empty($id_mp_preference)) {
            return false;
        }
        $this->conectar();
        try {
            $sql = "SELECT * FROM transaccion WHERE id_mp_preference = :id_mp_preference";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_mp_preference', $id_mp_preference, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDOException en buscarPorIdMpPreference para MP Preference ID {$id_mp_preference}: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorIdMpPayment($id_mp_payment) {
        if (empty($id_mp_payment)) {
            return false;
        }
        $this->conectar();
        try {
            $sql = "SELECT * FROM transaccion WHERE id_mp_payment = :id_mp_payment";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_mp_payment', $id_mp_payment, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDOException en buscarPorIdMpPayment para MP Payment ID {$id_mp_payment}: " . $e->getMessage());
            return false;
        }
    }
}
?>