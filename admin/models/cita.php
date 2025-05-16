<?php
require_once (__DIR__.'/../model.php');

class Cita extends Model {

    public function leer() {
    $this->conectar();
    try {
        $sql = "SELECT c.*,
                CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre_completo,
                CONCAT(m.nombre, ' ', m.primer_apellido) as medico_nombre_completo,
                t.estado
                FROM cita c
                JOIN paciente p ON c.id_paciente = p.id_paciente
                JOIN medico m ON c.id_medico = m.id_medico
                JOIN consultorio co ON c.id_consultorio = co.id_consultorio
                INNER JOIN transaccion t ON c.id_cita = t.id_cita
                ORDER BY c.fecha DESC, c.hora DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al leer citas: " . $e->getMessage());
        return [];
    }
}

    public function leerUno($id) {
        $this->conectar();
        try {
            $sql = "SELECT * FROM cita WHERE id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cita', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al leer cita ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function crear($datos) {
        if (!isset($datos['fecha'], $datos['hora'], $datos['id_paciente'], $datos['id_medico'], $datos['id_consultorio'])) {
            error_log("Error al crear cita: Faltan datos obligatorios.");
            return false;
        }
        $fecha = trim($datos['fecha']);
        $hora = trim($datos['hora']);
        $id_paciente = filter_var($datos['id_paciente'], FILTER_VALIDATE_INT);
        $id_medico = filter_var($datos['id_medico'], FILTER_VALIDATE_INT);
        $id_consultorio = filter_var($datos['id_consultorio'], FILTER_VALIDATE_INT);
        if (!$id_paciente || !$id_medico || !$id_consultorio) {
             error_log("Error al crear cita: IDs de paciente, médico o consultorio no válidos.");
            return false;
        }
        $this->conectar();
        if (!$this->disponibilidad($fecha, $hora, $id_medico, $id_paciente, $id_consultorio)) {
            return false;
        }

        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO cita (fecha, hora, descripcion, precio, id_paciente, id_medico, id_consultorio)
                    VALUES (:fecha, :hora, :descripcion, :precio, :id_paciente, :id_medico, :id_consultorio)";
            $stmt = $this->conn->prepare($sql);
            $descripcion = isset($datos['descripcion']) ? trim($datos['descripcion']) : null;
            $precio = isset($datos['precio']) && is_numeric($datos['precio']) ? $datos['precio'] : null;
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
            $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
            $stmt->bindParam(':id_consultorio', $id_consultorio, PDO::PARAM_INT);
            $resultado = $stmt->execute();
            if ($resultado) {
                $this->conn->commit();
                return true;
            } 
            else {
                $this->conn->rollBack();
                error_log("Error al ejecutar la inserción de la cita.");
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Excepción PDO al crear cita: " . $e->getMessage());
            return false;
        }
    }

    public function modificar($datos, $id) {
        if (!isset($datos['fecha'], $datos['hora'], $datos['id_paciente'], $datos['id_medico'], $datos['id_consultorio'], $id) || !filter_var($id, FILTER_VALIDATE_INT)) {
             error_log("Error al modificar cita ID {$id}: Faltan datos obligatorios o ID no válido.");
            return false;
        }
        $fecha = trim($datos['fecha']);
        $hora = trim($datos['hora']);
        $id_paciente = filter_var($datos['id_paciente'], FILTER_VALIDATE_INT);
        $id_medico = filter_var($datos['id_medico'], FILTER_VALIDATE_INT);
        $id_consultorio = filter_var($datos['id_consultorio'], FILTER_VALIDATE_INT);
        $id_cita_actual = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id_paciente || !$id_medico || !$id_consultorio || !$id_cita_actual) {
             error_log("Error al modificar cita: IDs de paciente, médico, consultorio o cita actual no válidos.");
            return false;
        }
        $this->conectar();
        if (!$this->disponibilidad($fecha, $hora, $id_medico, $id_paciente, $id_consultorio, $id_cita_actual)) {
            return false;
        }
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE cita SET
                        fecha = :fecha,
                        hora = :hora,
                        descripcion = :descripcion,
                        precio = :precio,
                        id_paciente = :id_paciente,
                        id_medico = :id_medico,
                        id_consultorio = :id_consultorio
                    WHERE id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $descripcion = isset($datos['descripcion']) ? trim($datos['descripcion']) : null;
            $precio = isset($datos['precio']) && is_numeric($datos['precio']) ? $datos['precio'] : null;
            $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
            $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_STR);
            $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
            $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
            $stmt->bindParam(':id_consultorio', $id_consultorio, PDO::PARAM_INT);
            $stmt->bindParam(':id_cita', $id_cita_actual, PDO::PARAM_INT);
            $resultado = $stmt->execute();
            if ($resultado) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                 error_log("Error al ejecutar la actualización de la cita ID {$id_cita_actual}.");
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Excepción PDO al modificar cita ID {$id_cita_actual}: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($id) {
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "DELETE FROM cita WHERE id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cita', $id, PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->rowCount();
            $this->conn->commit();
            return $count;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            if ($e->getCode() == '23000') {
                 error_log("Error al eliminar cita ID {$id}: No se puede eliminar, probablemente porque tiene transacciones u otras dependencias asociadas.");
                 return false;
            }
            error_log("Error al eliminar cita ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
?>