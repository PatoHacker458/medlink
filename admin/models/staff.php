<?php
require_once (__DIR__.'/../model.php');

class Staff extends Model
{
    function crear($datos){
        if(isset($datos[''])){
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

                $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : null;
                $sql = "INSERT INTO staff (nombre, primer_apellido, segundo_apellido, id_usuario) 
                        VALUES (:nombre, :primer_apellido, :segundo_apellido, :id_usuario)";
                $insertar = $this -> conn -> prepare($sql);
                $insertar -> bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
                $insertar -> bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
                $insertar -> bindParam(':segundo_apellido', $datos['segundo_apellido'] , PDO::PARAM_STR);
                $insertar -> bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                $resultado = $insertar -> execute();

                $sql = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)";
                $roles = array(4); #Rol de staff
                foreach ($roles as $rol) {
                    $insertar = $this -> conn -> prepare($sql);
                    $insertar -> bindParam(':id_usuario', $datos['id_usuario'], PDO::PARAM_INT);
                    $insertar -> bindParam(':id_rol', $rol, PDO::PARAM_INT);
                    $insertar -> execute();
                }
                $resultado = $this -> conn -> commit();
                return $resultado;
            }else{
                $this->conn->rollback();
                return false;
            }
        }
        catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
            
        }
        return false;
    }

    function modificar($datos, $id, $id_usuario){
        $datos['nombre']=trim($datos['nombre']);//Quitamos espacios para evitar graciositos
        $this -> conectar();
        $this -> conn -> beginTransaction();
        try{
            if(isset($datos['correo'])){
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

            if(isset($datos['contrasena']) && !empty($datos['contrasena'])){
                if(isset($datos['contrasena'])){
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
            }
            
            $datos['segundo_apellido'] = isset($datos['segundo_apellido']) ? $datos['segundo_apellido'] : null;
            $sql = "UPDATE staff 
                    SET nombre=:nombre, primer_apellido=:primer_apellido, segundo_apellido=:segundo_apellido 
                    WHERE id_staff=:id_staff;";
            $actualizar = $this -> conn -> prepare($sql);
            $actualizar -> bindParam(':nombre', $datos['nombre'], PDO::PARAM_STR);
            $actualizar -> bindParam(':primer_apellido', $datos['primer_apellido'], PDO::PARAM_STR);
            $actualizar -> bindParam(':segundo_apellido',$datos['segundo_apellido'], PDO::PARAM_STR);
            $actualizar -> bindParam(':id_staff', $id, PDO::PARAM_INT);

            $resultado = $actualizar -> execute();
            $this -> conn -> commit();
            return $resultado;
        }
        catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
        }
    }

    function eliminar($id){
        $this -> conectar();
        $this -> conn -> beginTransaction();
        $cantidad = 0;
        try {
            $sql = "DELETE FROM staff WHERE id_staff=:id_staff";
            $eliminar = $this -> conn -> prepare($sql);
            $eliminar -> bindParam(':id_staff', $id, PDO::PARAM_INT);
            $eliminar -> execute();
            $cantidad = $eliminar -> rowCount();
            $this -> conn -> commit();
            return $cantidad;
        }
        catch(PDOException $e) {
            $this->conn->rollback();
            throw new Exception($e -> getMessage());
        }
    }

    function leer(){
        $this -> conectar();
        $datos = $this -> conn -> prepare("SELECT * from staff");
        $datos->execute();        
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function leerUno($id){
        $this -> conectar();
        $datos = $this -> conn -> prepare("SELECT *, u.correo as correo
                                        FROM staff m LEFT JOIN usuario u on m.id_usuario=u.id_usuario
                                        WHERE m.id_staff = :id_staff");
        $datos->bindParam(':id_staff', $id, PDO::PARAM_INT);
        $datos->execute();        
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);        
        return $resultado;
    }

}