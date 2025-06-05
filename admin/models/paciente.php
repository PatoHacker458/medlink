<?php

require_once(__DIR__ . '/../model.php');
$web = new Model();

class Paciente extends Model
{
    function crear($datos)
    {
        if (isset($datos['paciente'])) {
            if (strlen($datos['paciente']) > 50) {
                return false;
            }
            if ($this->validar_correo($datos['correo'])) {
                return false;
            }
        }
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql = "SELECT correo, count(*) as cantidad FROM usuario WHERE correo=:correo";
            $data = $this->conn->prepare($sql);
            $data->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $info = $data->execute();
            if (isset($info['correo'])) {
                if ($info['cantidad'] > 1) {
                    $this->conn->rollback(); //Ya existe un usuario con ese correo
                    return false;
                }
            }

            if (empty(trim($datos['contrasena']))) {
                $passwordAleatorio = bin2hex(random_bytes(6));
                $datos['contrasena'] = $passwordAleatorio;
            }

            $sql = "INSERT INTO usuario (correo, contrasena) VALUES (:correo, :contrasena)";
            $contrasena = md5($datos['contrasena']);
            $insertar = $this->conn->prepare($sql);
            $insertar->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $insertar->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $usuario = $insertar->execute();
            if ($insertar->rowCount() > 0) {
                $sql = "SELECT id_usuario FROM usuario WHERE correo=:correo";
                $data = $this->conn->prepare($sql);
                $data->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
                $data->execute();
                $info = $data->fetch(PDO::FETCH_ASSOC);
                $datos['id_usuario'] = $info['id_usuario'];

                $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : '';
                $sql = "INSERT INTO paciente (nombre, primer_apellido, segundo_apellido, id_usuario, nacimiento, telefono) 
                         VALUES (:nombre, :primer_apellido, :segundo_apellido, :id_usuario, :nacimiento, :telefono)";
                $insertar = $this->conn->prepare($sql);
                $insertar->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
                $insertar->bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
                $insertar->bindParam(':segundo_apellido', $datos['segundo_apellido'], PDO::PARAM_STR);
                $insertar->bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                $insertar->bindParam('nacimiento', $datos['nacimiento'], PDO::PARAM_STR);
                $insertar->bindParam('telefono', $datos['telefono'], PDO::PARAM_STR);
                $resultado = $insertar->execute();

                $sql = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)";
                $roles = array(7); #Rol paciente
                foreach ($roles as $rol) {
                    $insertar = $this->conn->prepare($sql);
                    $insertar->bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                    $insertar->bindParam(':id_rol', $rol, PDO::PARAM_INT);
                    $insertar->execute();
                }
                $resultado = $this->conn->commit();
            } else {
                $this->conn->rollback();
                return false;
            }
            return $resultado;
        } catch (PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e->getMessage());
        }
        return false;
    }

    function modificar($datos, $id, $id_usuario)
    {
        $datos['nombre'] = trim($datos['nombre']); //Quitamos espacios para evitar graciositos
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            if ($datos['correo'] != '') {
                $sql = "UPDATE usuario SET correo=:correo WHERE id_usuario=:id_usuario;";
                $actualizar = $this->conn->prepare($sql);
                $actualizar->bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
                $actualizar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado = $actualizar->execute();
                if (!$resultado) {
                    $this->conn->rollback();
                    return false;
                }
            }
            if ($datos['contrasena'] != '') {
                $sql = "UPDATE usuario SET contrasena=:contrasena WHERE id_usuario=:id_usuario;";
                $contrasena = md5($datos['contrasena']);
                $actualizar = $this->conn->prepare($sql);
                $actualizar->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
                $actualizar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado = $actualizar->execute();
                if (!$resultado) {
                    $this->conn->rollback();
                    return false;
                }
            }

            $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : '';
            $sql = "UPDATE paciente SET nombre=:nombre, primer_apellido=:primer_apellido, segundo_apellido=:segundo_apellido,
                                        nacimiento=:nacimiento, telefono=:telefono  
                                        WHERE id_paciente=:id_paciente;";
            $actualizar = $this->conn->prepare($sql);
            $actualizar->bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $actualizar->bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
            $actualizar->bindParam(':segundo_apellido', $datos['segundo_apellido'], PDO::PARAM_STR);
            $actualizar->bindParam('nacimiento', $datos['nacimiento'], PDO::PARAM_STR);
            $actualizar->bindParam('telefono', $datos['telefono'], PDO::PARAM_STR);
            $actualizar->bindParam(':id_paciente', $id, PDO::PARAM_INT);
            $resultado = $actualizar->execute();
            $this->conn->commit();
            return $resultado;
        } catch (PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e->getMessage());
        }
        return false;
    }

    function eliminar($id)
    {
        $this->conectar();
        $this->conn->beginTransaction();
        try {
            $sql_get_user_id = "SELECT id_usuario FROM paciente WHERE id_paciente = :id_paciente";
            $stmt_get_user_id = $this->conn->prepare($sql_get_user_id);
            $stmt_get_user_id->bindParam(':id_paciente', $id, PDO::PARAM_INT);
            $stmt_get_user_id->execute();
            $user_id = $stmt_get_user_id->fetchColumn();

            $sql_delete_paciente = "DELETE FROM paciente WHERE id_paciente=:id_paciente";
            $stmt_delete_paciente = $this->conn->prepare($sql_delete_paciente);
            $stmt_delete_paciente->bindParam(':id_paciente', $id, PDO::PARAM_INT);
            $stmt_delete_paciente->execute();
            $paciente_rows_affected = $stmt_delete_paciente->rowCount();

            if ($user_id) {
                $sql_delete_usuario = "DELETE FROM usuario WHERE id_usuario=:id_usuario";
                $stmt_delete_usuario = $this->conn->prepare($sql_delete_usuario);
                $stmt_delete_usuario->bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
                $stmt_delete_usuario->execute();
                $usuario_rows_affected = $stmt_delete_usuario->rowCount();
            } else {
                $usuario_rows_affected = 0;
            }
            $this->conn->commit();
            return $paciente_rows_affected;
        } catch (PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e->getMessage());
        }
    }

    function leer()
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT p.*, u.correo AS correo, CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre_completo
                                            FROM paciente p
                                            INNER JOIN usuario u ON p.id_usuario = u.id_usuario;");
        $datos->execute();
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function leerUno($id)
    {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT p.*, u.correo as correo
                                            FROM paciente p LEFT JOIN usuario u on p.id_usuario=u.id_usuario
                                            WHERE p.id_paciente = :id_paciente");
        $datos->bindParam(':id_paciente', $id, PDO::PARAM_INT);
        $datos->execute();
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }
}
