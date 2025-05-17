<?php
require_once(__DIR__ . '/../model.php');

class Cita extends Model
{

    public function leer()
    {
        $this->conectar();

        $sqlBase = "SELECT c.*,
                        CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre_completo,
                        CONCAT(m.nombre, ' ', m.primer_apellido) as medico_nombre_completo,
                        t.estado
                        FROM cita c
                        JOIN paciente p ON c.id_paciente = p.id_paciente
                        JOIN medico m ON c.id_medico = m.id_medico
                        JOIN consultorio co ON c.id_consultorio = co.id_consultorio
                        INNER JOIN transaccion t ON c.id_cita = t.id_cita";

        $params = [];
        $whereClause = "";

        if (isset($_SESSION['validado']) && $_SESSION['validado'] === true && isset($_SESSION['roles'])) {
            if (in_array('Administrador', $_SESSION['roles'])) {
            } elseif (in_array('Medico', $_SESSION['roles']) && isset($_SESSION['correo'])) {
                try {
                    $sql_user = "SELECT id_usuario FROM usuario WHERE correo = :correo";
                    $stmt_user = $this->conn->prepare($sql_user);
                    $stmt_user->bindParam(':correo', $_SESSION['correo']);
                    $stmt_user->execute();
                    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

                    if ($user_data && isset($user_data['id_usuario'])) {
                        $sql_medico = "SELECT id_medico FROM medico WHERE id_usuario = :id_usuario";
                        $stmt_medico = $this->conn->prepare($sql_medico);
                        $stmt_medico->bindParam(':id_usuario', $user_data['id_usuario'], PDO::PARAM_INT);
                        $stmt_medico->execute();
                        $medico_data = $stmt_medico->fetch(PDO::FETCH_ASSOC);

                        if ($medico_data && isset($medico_data['id_medico'])) {
                            $whereClause = " WHERE c.id_medico = :id_medico";
                            $params[':id_medico'] = $medico_data['id_medico'];
                        } else {
                            return [];
                        }
                    } else {
                        return [];
                    }
                } catch (PDOException $e) {
                    return [];
                }
            } else {
                return [];
            }
        } else {
            return [];
        }

        $sql = $sqlBase . $whereClause . " ORDER BY c.fecha DESC, c.hora DESC";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function leerUno($id)
    {
        $this->conectar();
        try {
            $sql = "SELECT * FROM cita WHERE id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cita', $id, PDO::PARAM_INT);
            $stmt->execute();
            $cita = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cita && isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !in_array('Administrador', $_SESSION['roles'])) {
                $current_medico_id = null;
                if (isset($_SESSION['correo'])) {
                    $sql_user = "SELECT id_usuario FROM usuario WHERE correo = :correo";
                    $stmt_user = $this->conn->prepare($sql_user);
                    $stmt_user->bindParam(':correo', $_SESSION['correo']);
                    $stmt_user->execute();
                    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
                    if ($user_data && isset($user_data['id_usuario'])) {
                        $sql_medico_check = "SELECT id_medico FROM medico WHERE id_usuario = :id_usuario";
                        $stmt_medico_check = $this->conn->prepare($sql_medico_check);
                        $stmt_medico_check->bindParam(':id_usuario', $user_data['id_usuario'], PDO::PARAM_INT);
                        $stmt_medico_check->execute();
                        $medico_data_check = $stmt_medico_check->fetch(PDO::FETCH_ASSOC);
                        if ($medico_data_check) {
                            $current_medico_id = $medico_data_check['id_medico'];
                        }
                    }
                }
                if ($cita['id_medico'] != $current_medico_id) {
                    return false;
                }
            }
            return $cita;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function crear($datos)
    {
        if (!isset($datos['fecha'], $datos['hora'], $datos['id_paciente'], $datos['id_medico'], $datos['id_consultorio'])) {
            return false;
        }
        $fecha = trim($datos['fecha']);
        $hora = trim($datos['hora']);
        $id_paciente = filter_var($datos['id_paciente'], FILTER_VALIDATE_INT);
        $id_medico = filter_var($datos['id_medico'], FILTER_VALIDATE_INT);
        $id_consultorio = filter_var($datos['id_consultorio'], FILTER_VALIDATE_INT);
        if (!$id_paciente || !$id_medico || !$id_consultorio) {
            return false;
        }

        if (isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !in_array('Administrador', $_SESSION['roles'])) {
            $current_medico_id = null;
            if (isset($_SESSION['correo'])) {
                $this->conectar();
                $sql_user = "SELECT id_usuario FROM usuario WHERE correo = :correo";
                $stmt_user = $this->conn->prepare($sql_user);
                $stmt_user->bindParam(':correo', $_SESSION['correo']);
                $stmt_user->execute();
                $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
                if ($user_data && isset($user_data['id_usuario'])) {
                    $sql_medico_check = "SELECT id_medico FROM medico WHERE id_usuario = :id_usuario";
                    $stmt_medico_check = $this->conn->prepare($sql_medico_check);
                    $stmt_medico_check->bindParam(':id_usuario', $user_data['id_usuario'], PDO::PARAM_INT);
                    $stmt_medico_check->execute();
                    $medico_data_check = $stmt_medico_check->fetch(PDO::FETCH_ASSOC);
                    if ($medico_data_check) {
                        $current_medico_id = $medico_data_check['id_medico'];
                    }
                }
            }
            if ($id_medico != $current_medico_id) {
                return false;
            }
        }


        $this->conectar();

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
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function modificar($datos, $id)
    {
        if (!isset($datos['fecha'], $datos['hora'], $datos['id_paciente'], $datos['id_medico'], $datos['id_consultorio'], $id) || !filter_var($id, FILTER_VALIDATE_INT)) {
            return false;
        }

        $this->conectar();
        $cita_actual = $this->leerUno($id);
        if (!$cita_actual) {
            return false;
        }

        if (isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !in_array('Administrador', $_SESSION['roles'])) {
            $current_medico_id = null;
            if (isset($_SESSION['correo'])) {
                $sql_user = "SELECT id_usuario FROM usuario WHERE correo = :correo";
                $stmt_user = $this->conn->prepare($sql_user);
                $stmt_user->bindParam(':correo', $_SESSION['correo']);
                $stmt_user->execute();
                $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
                if ($user_data && isset($user_data['id_usuario'])) {
                    $sql_medico_check = "SELECT id_medico FROM medico WHERE id_usuario = :id_usuario";
                    $stmt_medico_check = $this->conn->prepare($sql_medico_check);
                    $stmt_medico_check->bindParam(':id_usuario', $user_data['id_usuario'], PDO::PARAM_INT);
                    $stmt_medico_check->execute();
                    $medico_data_check = $stmt_medico_check->fetch(PDO::FETCH_ASSOC);
                    if ($medico_data_check) {
                        $current_medico_id = $medico_data_check['id_medico'];
                    }
                }
            }
            if ($datos['id_medico'] != $current_medico_id || $cita_actual['id_medico'] != $current_medico_id) {
                return false;
            }
        }


        $fecha = trim($datos['fecha']);
        $hora = trim($datos['hora']);
        $id_paciente = filter_var($datos['id_paciente'], FILTER_VALIDATE_INT);
        $id_medico_nuevo = filter_var($datos['id_medico'], FILTER_VALIDATE_INT);
        $id_consultorio = filter_var($datos['id_consultorio'], FILTER_VALIDATE_INT);
        $id_cita_actual_param = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id_paciente || !$id_medico_nuevo || !$id_consultorio || !$id_cita_actual_param) {
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
            $stmt->bindParam(':id_medico', $id_medico_nuevo, PDO::PARAM_INT);
            $stmt->bindParam(':id_consultorio', $id_consultorio, PDO::PARAM_INT);
            $stmt->bindParam(':id_cita', $id_cita_actual_param, PDO::PARAM_INT);
            $resultado = $stmt->execute();
            if ($resultado) {
                $this->conn->commit();
                return true;
            } else {
                $this->conn->rollBack();
                return false;
            }
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function eliminar($id)
    {
        $this->conectar();

        if (isset($_SESSION['roles']) && in_array('Medico', $_SESSION['roles']) && !in_array('Administrador', $_SESSION['roles'])) {
            $cita_a_eliminar = $this->leerUno($id);
            if (!$cita_a_eliminar) {
                return false;
            }
        }

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
                return false;
            }
            return false;
        }
    }
}
