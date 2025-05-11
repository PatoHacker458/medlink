<?php
require_once(__DIR__ . '/../model.php');

class Especialidad extends Model
{
    function crear($datos)
    {
        if (isset($datos['especialidad'])) {
            if (strlen($datos['especialidad']) > 30) {
                return FALSE;
            }
        }
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO especialidad (especialidad) VALUES (:especialidad)";
            $insertar = $this->conn->prepare($sql);
            $insertar->bindParam(':especialidad', $datos['especialidad'], PDO::PARAM_STR);
            $resultado = $insertar->execute();
            $this->conn->commit();
            return $resultado;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    function modificar($datos, $id)
    {
        $datos['especialidad'] = trim($datos['especialidad']);
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE especialidad SET especialidad = :especialidad WHERE id_especialidad = :id_especialidad";
            $modificar = $this->conn->prepare($sql);
            $modificar->bindParam(':especialidad', $datos['especialidad'], PDO::PARAM_STR);
            $modificar->bindParam(':id_especialidad', $id, PDO::PARAM_INT);
            $resultado = $modificar->execute();
            $sql = "SELECT especialidad, count(*) as cantidad FROM especialidad WHERE especialidad = :especialidad group by especialidad";
            $data = $this->conn->prepare($sql);
            $data->bindParam(':especialidad', $datos['especialidad'], PDO::PARAM_STR);
            $info = $data->execute();
            $info = $data->fetch(PDO::FETCH_ASSOC);
            if (isset($info['especialidad'])) {
                if ($info['cantidad'] > 1) {
                    $this->conn->rollBack();
                    return FALSE;
                }
            };
            $this->conn->commit();
            return $resultado;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    function eliminar($id)
    {
        $this->conectar();
        $this->conn->beginTransaction();
        $cantidad = 0;

        try {
            $sql = "SELECT COUNT(*) as cantidad FROM medico WHERE id_especialidad = :id_especialidad";
            $datos = $this->conn->prepare($sql);
            $datos->bindParam(':id_especialidad', $id, PDO::PARAM_INT);
            $datos->execute();
            $resultado = $datos->fetch(PDO::FETCH_ASSOC);
            $resultado = $resultado['cantidad'];
            if ($resultado > 0) {
                $this->conn->rollBack();
                return FALSE;
            }
            $sql = "DELETE FROM especialidad WHERE id_especialidad = :id_especialidad";
            $eliminar = $this->conn->prepare($sql);
            $eliminar->bindParam(':id_especialidad', $id, PDO::PARAM_INT);
            $eliminar->execute();
            $cantidad = $eliminar->rowCount();
            $this->conn->commit();
            return $cantidad;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw new Exception("Error al eliminar la especialidad");
        }
    }

    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM especialidad");
        $datos->execute();
        $result = $datos->fetchAll();
        return $result;
    }

    function leerUno($id){
        $this->conectar();
        $datos = $this->conn->prepare("SELECT * FROM especialidad where id_especialidad = :id_especialidad");
        $datos->bindParam(':id_especialidad', $id, PDO::PARAM_INT);
        $datos->execute();
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }
}
?>