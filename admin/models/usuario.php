<?php 
require_once (__DIR__.'/../model.php');

class Usuario extends Model {

    function crear($datos) {
        if (!isset($datos['correo']) || empty(trim($datos['correo']))) {
            return false;
        }
        
        if (strlen($datos['correo']) > 30) {
            return false;
        }

        $this->conectar();
        $this->conn->beginTransaction();

        try {
            $sql = "INSERT INTO usuario (correo,contrasena) VALUES (:correo,md5(:contrasena));";
            $insertar = $this->conn->prepare($sql);
            $insertar->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $insertar->bindParam(':contrasena', $datos['contrasena'], PDO::PARAM_STR);
            $insertar->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    function modificar($datos, $id) {
        if (!isset($datos['correo']) || empty(trim($datos['correo']))) {
            return false;
        }

        $datos['correo'] = trim($datos['correo']);
        $this->conectar();
        $this->conn->beginTransaction();

        try {
            $sql = "UPDATE usuario SET correo = :correo, contrasena=md5(:contrasena)
                      WHERE id_usuario = :id_usuario";
            $actualizar = $this->conn->prepare($sql);
            $actualizar->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $actualizar->bindParam(':contrasena', $datos['contrasena'], PDO::PARAM_STR);
            $actualizar->bindParam(':id_usuario', $id, PDO::PARAM_INT);
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
            $sql = "SELECT COUNT(*) as cantidad FROM medico WHERE id_usuario = :id_usuario";
            $datos = $this->conn->prepare($sql);
            $datos->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $datos->execute();
            $resultado = $datos->fetch(PDO::FETCH_ASSOC);

            if ($resultado['cantidad'] > 0) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $eliminar = $this->conn->prepare($sql);
            $eliminar->bindParam(':id_usuario', $id, PDO::PARAM_INT);
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
        $datos = $this->conn->prepare("SELECT id_usuario,correo, COUNT(correo) as cantidad
                                        FROM usuario GROUP BY id_usuario;");
        $datos->execute();
        return $datos->fetchAll(PDO::FETCH_ASSOC);
    }

    function leerUno($id) {
        $this->conectar();
        $datos = $this->conn->prepare('SELECT * FROM usuario WHERE id_usuario = :id_usuario');
        $datos->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $datos->execute();
        return $datos->fetch(PDO::FETCH_ASSOC);
    }
}
?>