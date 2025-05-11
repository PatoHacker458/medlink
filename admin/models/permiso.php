<?php 
require_once (__DIR__.'/../model.php');

class Permiso extends Model {

    function crear($datos) {
        if (!isset($datos['permiso']) || empty(trim($datos['permiso']))) {
            return false;
        }
        
        if (strlen($datos['permiso']) > 30) {
            return false;
        }

        $this->conectar();
        $this->conn->beginTransaction();

        try {
            $sql = "INSERT INTO permiso (permiso) VALUES (:permiso)";
            $insertar = $this->conn->prepare($sql);
            $insertar->bindParam(':permiso', $datos['permiso'], PDO::PARAM_STR);
            $insertar->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    function modificar($datos, $id) {
        if (!isset($datos['permiso']) || empty(trim($datos['permiso']))) {
            return false;
        }

        $datos['permiso'] = trim($datos['permiso']);
        $this->conectar();
        $this->conn->beginTransaction();

        try {
            $sql = "UPDATE permiso SET permiso = :permiso WHERE id_permiso = :id_permiso";
            $actualizar = $this->conn->prepare($sql);
            $actualizar->bindParam(':permiso', $datos['permiso'], PDO::PARAM_STR);
            $actualizar->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $actualizar->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    function eliminar($id) {
        $this->conectar();
        $this->conn->beginTransaction();

        try {
            $sql = "SELECT COUNT(*) as cantidad FROM permiso_rol WHERE id_permiso = :id_permiso";
            $datos = $this->conn->prepare($sql);
            $datos->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $datos->execute();
            $resultado = $datos->fetch(PDO::FETCH_ASSOC);

            if ($resultado['cantidad'] > 0) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "DELETE FROM permiso WHERE id_permiso = :id_permiso";
            $eliminar = $this->conn->prepare($sql);
            $eliminar->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $eliminar->execute();

            $this->conn->commit();
            return $eliminar->rowCount();
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    function leer() {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT id_permiso,permiso, COUNT(permiso) AS cantidad 
                                        FROM permiso 
                                        GROUP BY id_permiso;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerUno($id) {
        $this->conectar();
        $datos = $this->conn->prepare('SELECT * FROM permiso WHERE id_permiso = :id_permiso');
        $datos->bindParam(':id_permiso', $id, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }
}
?>
