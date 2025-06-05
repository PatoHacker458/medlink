<?php

require_once (__DIR__.'/../model.php');
$web = new Model();

class Medico extends Model
{
    function crear($datos){
        if(isset($datos['medico'])){
            if(strlen($datos['medico'])>50){
                return false;
            }
            if($this -> validar_correo($datos['correo'])){
                return false;
            }
        }
        $this -> conectar();
        $this -> conn -> beginTransaction();
        try{
            $sql = "SELECT correo, count(*) as cantidad FROM usuario WHERE correo=:correo";
            $data = $this -> conn -> prepare($sql);
            $data -> bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $info = $data -> execute();
            if(isset($info['correo'])){   
                if($info['cantidad']>1){
                    $this->conn->rollback();//Ya existe un usuario con ese correo
                    return false;
                }
            }
            $sql = "INSERT INTO usuario (correo, contrasena) VALUES (:correo, :contrasena)";
            $contrasena = md5($datos['contrasena']);
            $insertar = $this -> conn -> prepare($sql);
            $insertar -> bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
            $insertar -> bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $usuario = $insertar -> execute();
            if($insertar -> rowCount() > 0){
                $sql = "SELECT id_usuario FROM usuario WHERE correo=:correo";
                $data = $this -> conn -> prepare($sql);
                $data -> bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
                $data -> execute();
                $info = $data -> fetch(PDO::FETCH_ASSOC);
                $datos['id_usuario'] = $info['id_usuario'];

                $imagen = $this -> cargar_img();
                $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : '';
                $sql = "INSERT INTO medico (nombre, primer_apellido, segundo_apellido, id_usuario, licencia, telefono, horario, id_consultorio, id_especialidad) 
                         VALUES (:nombre, :primer_apellido, :segundo_apellido, :id_usuario, :licencia, :telefono, :horario, :id_consultorio, :id_especialidad)";
                if ($imagen) {
                    $sql = "INSERT INTO medico (nombre, primer_apellido, segundo_apellido, id_usuario, licencia, telefono, horario, id_consultorio, id_especialidad, fotografia) 
                         VALUES (:nombre, :primer_apellido, :segundo_apellido, :id_usuario, :licencia, :telefono, :horario, :id_consultorio, :id_especialidad, :fotografia)";
                }
                $insertar = $this -> conn -> prepare($sql);
                $insertar -> bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
                $insertar -> bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
                $insertar -> bindParam(':segundo_apellido', $datos['segundo_apellido'] , PDO::PARAM_STR);
                $insertar -> bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                $insertar -> bindParam(':licencia', $datos['licencia'], PDO::PARAM_STR);
                $insertar -> bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
                $insertar -> bindParam(':horario', $datos['horario'], PDO::PARAM_STR);
                $insertar -> bindParam(':id_consultorio', $datos['id_consultorio'], PDO::PARAM_INT);
                $insertar -> bindParam('id_especialidad', $datos['id_especialidad'], PDO::PARAM_INT);
                if ($imagen) {
                    $insertar->bindParam(':fotografia', $imagen, PDO::PARAM_STR);
                }
                $resultado = $insertar -> execute();

                $sql = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)";
                $roles = array(8);
                foreach ($roles as $rol) {
                    $insertar = $this -> conn -> prepare($sql);
                    $insertar -> bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                    $insertar -> bindParam(':id_rol', $rol, PDO::PARAM_INT);
                    $insertar -> execute();
                }
                $resultado = $this -> conn -> commit();
            }else{
                $this->conn->rollback();
                return false;
            }
            return $resultado;
        }
        catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
            
        }
        return false;
    }

    public function modificar($datos, $id, $id_usuario){
        $datos['nombre']=trim($datos['nombre']);//Quitamos espacios para evitar graciositos
        $this -> conectar();
        $this -> conn -> beginTransaction();
        try{
            if($datos['correo']!=''){
                $sql = "UPDATE usuario SET correo=:correo WHERE id_usuario=:id_usuario;";
                $actualizar = $this -> conn -> prepare($sql);
                $actualizar -> bindParam(':correo', $datos['correo'], PDO::PARAM_STR);
                $actualizar -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado = $actualizar -> execute();
                if(!$resultado){
                    $this->conn->rollback();
                    return false;
                }
            }
            if($datos['contrasena']!=''){
                $sql = "UPDATE usuario SET contrasena=:contrasena WHERE id_usuario=:id_usuario;";
                $contrasena = md5($datos['contrasena']);
                $actualizar = $this -> conn -> prepare($sql);
                $actualizar -> bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
                $actualizar -> bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $resultado = $actualizar -> execute();
                if(!$resultado){
                    $this->conn->rollback();
                    return false;
                }
            }
    
            $imagen = $this -> cargar_img('fotografia');
            $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : '';
            if ($imagen) {
                $sql = "UPDATE medico SET nombre=:nombre, primer_apellido=:primer_apellido, segundo_apellido=:segundo_apellido,
                                licencia=:licencia, telefono=:telefono, horario=:horario, id_especialidad=:id_especialidad, id_consultorio=:id_consultorio, fotografia=:fotografia
                                WHERE id_medico=:id_medico;";
            } else {
                $sql = "UPDATE medico SET nombre=:nombre, primer_apellido=:primer_apellido, segundo_apellido=:segundo_apellido,
                                licencia=:licencia, telefono=:telefono, horario=:horario, id_especialidad=:id_especialidad, id_consultorio=:id_consultorio
                                WHERE id_medico=:id_medico;";
            }
            $actualizar = $this -> conn -> prepare($sql);
            $actualizar -> bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $actualizar -> bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
            $actualizar -> bindParam(':segundo_apellido',$datos['segundo_apellido'], PDO::PARAM_STR);
            $actualizar -> bindParam('licencia',$datos['licencia'], PDO::PARAM_STR);
            $actualizar -> bindParam(':telefono', $datos['telefono'], PDO::PARAM_STR);
            $actualizar -> bindParam(':horario', $datos['horario'], PDO::PARAM_STR);
            $actualizar -> bindParam(':id_especialidad', $datos['id_especialidad'], PDO::PARAM_INT);
            $actualizar -> bindParam('id_consultorio', $datos['id_consultorio'], PDO::PARAM_INT);
            $actualizar -> bindParam(':id_medico', $id, PDO::PARAM_INT);
            if ($imagen) {
                $actualizar->bindParam(':fotografia', $imagen, PDO::PARAM_STR);
            }
            $resultado = $actualizar -> execute();
            $this -> conn -> commit();
            return $resultado;
        }
        catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
        }
        return false;
    }

    function eliminar($id){
        $this -> conectar();
        $this -> conn -> beginTransaction();
        try {
            $sql_get_user_id = "SELECT id_usuario FROM medico WHERE id_medico = :id_medico";
            $stmt_get_user_id = $this -> conn -> prepare($sql_get_user_id);
            $stmt_get_user_id -> bindParam(':id_medico', $id, PDO::PARAM_INT);
            $stmt_get_user_id -> execute();
            $user_id = $stmt_get_user_id -> fetchColumn();
    
            $sql_delete_medico = "DELETE FROM medico WHERE id_medico=:id_medico";
            $stmt_delete_medico = $this -> conn -> prepare($sql_delete_medico);
            $stmt_delete_medico -> bindParam(':id_medico', $id, PDO::PARAM_INT);
            $stmt_delete_medico -> execute();
            $medico_rows_affected = $stmt_delete_medico -> rowCount();
    
            if ($user_id) {
                $sql_delete_usuario = "DELETE FROM usuario WHERE id_usuario=:id_usuario";
                $stmt_delete_usuario = $this -> conn -> prepare($sql_delete_usuario);
                $stmt_delete_usuario -> bindParam(':id_usuario', $user_id, PDO::PARAM_INT);
                $stmt_delete_usuario -> execute();
                $usuario_rows_affected = $stmt_delete_usuario -> rowCount();
            } else {
                $usuario_rows_affected = 0;
            }
            $this -> conn -> commit();
            return $medico_rows_affected;
        } catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
        }
    }

    function leer(){
        $this -> conectar();
        $datos = $this -> conn -> prepare("SELECT m.*, e.especialidad AS especialidad, CONCAT(m.nombre, ' ', m.primer_apellido) as medico_nombre_completo, u.correo
                                        FROM medico m
                                        INNER JOIN especialidad e ON m.id_especialidad = e.id_especialidad
                                        INNER JOIN usuario u ON m.id_usuario = u.id_usuario;");
        $datos->execute();
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function leerUno($id){
        $this -> conectar();
        $datos = $this -> conn -> prepare("SELECT *, u.correo as correo
                                        FROM medico m LEFT JOIN usuario u on m.id_usuario=u.id_usuario
                                        WHERE m.id_medico = :id_medico");
        $datos->bindParam(':id_medico', $id, PDO::PARAM_INT);
        $datos->execute();        
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);        
        return $resultado;
    }

    public function obtenerIdMedicoLogueado() {
        if (isset($_SESSION['validado'], $_SESSION['roles'], $_SESSION['correo']) &&
            $_SESSION['validado'] === true &&
            in_array('Medico', $_SESSION['roles']) &&
            !in_array('Administrador', $_SESSION['roles'])) {

            $this->conectar();
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
                        return (int)$medico_data['id_medico'];
                    }
                }
            } catch (PDOException $e) {
                return null;
            }
        }
        return null;
    }
    

}