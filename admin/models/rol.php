<?php
require_once (__DIR__.'/../model.php');

class Rol extends Model
{
    function crear($datos, $r_permisos){
        if(isset($_POST["enviar"])){
            if(isset($datos['rol'])){
                if(strlen($datos['rol'])>50){
                    return false;
                }
            }
            $this -> conectar();
            $this -> conn -> beginTransaction();
            try{
                $sql = "INSERT INTO rol (rol) VALUES (:rol)";
                $insertar = $this -> conn -> prepare($sql);
                $insertar -> bindParam(':rol', $datos['rol'], PDO::PARAM_STR);  
                $resultado = $insertar -> execute();
                if ($resultado) {
                    $lastInsertId = $this->conn->lastInsertId();
                    if(isset($r_permisos)){
                        foreach($r_permisos as $permiso){
                            $sql = "INSERT INTO permiso_rol (id_rol, id_permiso) VALUES (:id_rol, :id_permiso)";
                            $insertar = $this -> conn -> prepare($sql);
                            $insertar -> bindParam(':id_rol', $lastInsertId, PDO::PARAM_INT);
                            $insertar -> bindParam(':id_permiso', $permiso, PDO::PARAM_INT);
                            $resultado = $insertar -> execute();
                        }
                    }
                } 
                $sql = "SELECT rol, count(*) as cantidad FROM rol WHERE rol=:rol GROUP BY rol";
                $data = $this -> conn -> prepare($sql);
                $data -> bindParam(':rol', $datos['rol'], PDO::PARAM_STR);
                $info = $data -> execute();
                $info = $data -> fetch(PDO::FETCH_ASSOC);            
                if(isset($info['rol'])){   
                    if($info['cantidad']>1){
                        $this->conn->rollback();//Ya existe un rol con ese nombre
                        return false;
                    }
                }
                $this -> conn -> commit();
                return $resultado;
            }
            catch(PDOException $e) {
                $this->conn->rollback();
                throw new Exception($e -> getMessage());            
            }
        }
        
        return false;
    }

    function modificar($datos, $id, $r_permisos){
        $datos['rol']=trim($datos['rol']);
        $this -> conectar();
        $this -> conn -> beginTransaction();
        try{            
            $sql = "UPDATE rol SET rol=:rol WHERE id_rol=:id_rol";
            $actualizar = $this -> conn -> prepare($sql);
            $actualizar -> bindParam(':rol', $datos['rol'], PDO::PARAM_STR);
            $actualizar -> bindParam(':id_rol', $id, PDO::PARAM_INT);        
            $resultado = $actualizar -> execute();
            if(isset($r_permisos)){
                $sql = "DELETE FROM permiso_rol WHERE id_rol=:id_rol";
                $eliminar = $this -> conn -> prepare($sql);
                $eliminar -> bindParam(':id_rol', $id, PDO::PARAM_INT);
                $eliminar -> execute();
                foreach($r_permisos as $permiso){
                    $sql = "INSERT INTO permiso_rol (id_rol, id_permiso) VALUES (:id_rol, :id_permiso)";
                    $insertar = $this -> conn -> prepare($sql);
                    $insertar -> bindParam(':id_rol', $id, PDO::PARAM_INT);
                    $insertar -> bindParam(':id_permiso', $permiso, PDO::PARAM_INT);
                    $resultado = $insertar -> execute();
                }
            }
            $sql = "SELECT rol, count(*) as cantidad FROM rol WHERE rol=:rol GROUP BY rol";
            $data = $this -> conn -> prepare($sql);
            $data -> bindParam(':rol', $datos['rol'], PDO::PARAM_STR);
            $info = $data -> execute();
            $info = $data -> fetch(PDO::FETCH_ASSOC);            
            if(isset($info['rol'])){   
                if($info['cantidad']>1){
                    $this->conn->rollback();//Ya existe un rol con ese nombre
                    return false;
                }
            }
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
        if(!is_numeric($id)){
            return false;
        }
        $this -> conectar();
        $this -> conn -> beginTransaction();
        $cantidad = 0;
        try {
            $sql = "DELETE FROM rol WHERE id_rol=:id_rol";
            $eliminar = $this -> conn -> prepare($sql);
            $eliminar -> bindParam(':id_rol', $id, PDO::PARAM_INT);
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
        $sql = "SELECT p.* FROM rol p ORDER BY rol";
        $datos = $this -> conn -> prepare($sql);
        $datos->execute();        
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function leerUno($id){
        $this -> conectar();
        $datos = $this -> conn -> prepare("SELECT r.id_rol, r.rol, GROUP_CONCAT(pr.id_permiso) AS permisos
                                            FROM rol r
                                            LEFT JOIN permiso_rol pr ON r.id_rol = pr.id_rol
                                            WHERE r.id_rol = :id_rol
                                            GROUP BY r.id_rol, r.rol;");
        $datos->bindParam(':id_rol', $id, PDO::PARAM_INT);
        $datos->execute();        
        $resultado = $datos->fetch(PDO::FETCH_ASSOC);   
        return $resultado;
    }
}