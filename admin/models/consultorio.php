<?php
require_once(__DIR__ . '/../model.php');

class Consultorio extends Model
{

    function crear($datos)
    {

        if (
            !isset($datos['piso'], $datos['habitacion']) ||
            !is_numeric($datos['piso']) || !is_numeric($datos['habitacion']) ||
            $datos['piso'] < 0 || $datos['habitacion'] < 0
        ) {
            return false;
        }

        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO consultorio (piso, habitacion) VALUES (:piso, :habitacion)";
            $insertar = $this->conn->prepare($sql);
            $insertar->bindParam(':piso', $datos['piso'], PDO::PARAM_INT);
            $insertar->bindParam(':habitacion', $datos['habitacion'], PDO::PARAM_INT);
            $resultado = $insertar->execute();
            $this->conn->commit();
            return $resultado;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }


    function modificar($datos, $id)
    {
        if (
            !isset($datos['piso'], $datos['habitacion'], $id) ||
            !is_numeric($datos['piso']) || !is_numeric($datos['habitacion']) ||
            !is_numeric($id) ||
            $datos['piso'] < 0 || $datos['habitacion'] < 0
        ) {
            return false;
        }

        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE consultorio SET piso = :piso, habitacion = :habitacion WHERE id_consultorio = :id_consultorio";
            $modificar = $this->conn->prepare($sql);
            $modificar->bindParam(':piso', $datos['piso'], PDO::PARAM_INT);
            $modificar->bindParam(':habitacion', $datos['habitacion'], PDO::PARAM_INT);
            $modificar->bindParam(':id_consultorio', $id, PDO::PARAM_INT);
            $resultado = $modificar->execute();
            $this->conn->commit();
            return $resultado;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    function eliminar($id)
{
    $this->conectar();
    $this->conn->beginTransaction();
    try {
        $sql_check = "SELECT COUNT(*) FROM medico WHERE id_consultorio = :id_consultorio";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bindParam(':id_consultorio', $id, PDO::PARAM_INT);
        $stmt_check->execute();
        $count = $stmt_check->fetchColumn();
        if ($count > 0) {
            $this->conn->rollBack();
            return false;
        } else {
            $sql_delete = "DELETE FROM consultorio WHERE id_consultorio = :id_consultorio";
            $stmt_delete = $this->conn->prepare($sql_delete);
            $stmt_delete->bindParam(':id_consultorio', $id, PDO::PARAM_INT);
            $resultado = $stmt_delete->execute();
            $this->conn->commit();
            return $resultado;
        }
    } catch (PDOException $e) {
        $this->conn->rollBack();
        return false;
    }
}

    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM consultorio");
        $datos->execute();
        $result = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function leerUno($id) {
        $this->conectar();
        $datos = $this->conn->prepare('SELECT * FROM consultorio WHERE id_consultorio = :id_consultorio');
        $datos->bindParam(':id_consultorio', $id, PDO::PARAM_INT);
        $datos->execute();
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }

}

?>