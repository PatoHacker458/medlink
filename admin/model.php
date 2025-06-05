<?php
require_once(__DIR__ . '/config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Model
{
    var $conn;
    var $tipos;
    var $max_tamp;

    function get_max_tamp()
    {
        $this->max_tamp = 5000 * 5000;
        return $this->max_tamp;
    }

    function get_tipos()
    {
        $this->tipos = array('image/jpeg', 'image/png', 'image/gif');
        return $this->tipos;
    }
    function conectar()
    {
        $this->conn = new PDO(SGBD . ':host=' . HOST . ';dbname=' . DB, USER, PASSWORD);
    }

    function alerta($alerta)
    {
        include __DIR__ . '/views/alerta.php';
    }

    function cargar_img()
    {
        if (isset($_FILES)) {
            $imagenes = $_FILES;
            foreach ($imagenes as $imagen) {
                if ($imagen['error'] == 0) {
                    if ($imagen['size'] <= $this->get_max_tamp() + 1) {
                        if (in_array($imagen['type'], $this->get_tipos())) {
                            $extension = explode('.', $imagen['name']);
                            $extension = $extension[sizeof($extension) - 1];
                            $nombre = md5($imagen['name'] . random_int(1, 10000000)) . $extension;
                            if (!file_exists(UPLOADDIR . $nombre)) {
                                if(move_uploaded_file($imagen['tmp_name'], UPLOADDIR.$nombre)){
                                    return $nombre;
                                }else{
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    function validar_correo($correo){
        $expReg = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/';
        return preg_match($expReg, $correo);
    }

    function obtenerEspecialidades() {
        $this->conectar();
        $datos = $this->conn->prepare("SELECT id_especialidad, especialidad FROM especialidad");
        $datos->execute();
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    
    function obtenerConsultorios(){
        $this->conectar();
        $datos = $this->conn->prepare("SELECT id_consultorio, piso, habitacion FROM consultorio");
        $datos->execute();
        $resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }

    function obtenerPermisos() {
        $permisoModel = new Permiso();
        return $permisoModel->leer();
    }

    function login($correo, $contrasena){
        $_SESSION['validado'] = false;
        $_SESSION['roles'] = [];
        $_SESSION['permisos'] = [];
        // Eliminar cualquier id_medico pendiente de agendar si el login falla o es un nuevo intento
        /*if(isset($_SESSION['id_medico_para_agendar'])) {
            unset($_SESSION['id_medico_para_agendar']);
        }*/
        if(isset($_SESSION['agendar_cita_id_medico'])) {
            unset($_SESSION['agendar_cita_id_medico']);
        }
        if(isset($_SESSION['agendar_cita_id_paciente'])) {
            unset($_SESSION['agendar_cita_id_paciente']);
        }


        $contrasena = md5($contrasena);
        if ($this -> validar_correo($correo)) {
            $this -> conectar();
            $sql = "SELECT * FROM usuario WHERE correo = :correo AND contrasena = :contrasena";
            $stmt = $this -> conn -> prepare($sql);
            $stmt -> bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt -> bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
            $stmt -> execute();
            $res = $stmt -> fetch(PDO::FETCH_ASSOC);
            if ($res) {
                $_SESSION['validado'] = true;
                $_SESSION['correo'] = $correo;
                $_SESSION['id_usuario'] = $res['id_usuario'];

                $sql_roles = "select r.rol from usuario u 
                              join usuario_rol ur on u.id_usuario = ur.id_usuario
                              join rol r on ur.id_rol = r.id_rol
                              where u.id_usuario = :id_usuario";
                $stmt_roles = $this -> conn -> prepare($sql_roles);
                $stmt_roles -> bindParam(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
                $stmt_roles -> execute();
                $res_roles = $stmt_roles -> fetchAll(PDO::FETCH_ASSOC);
                $roles = [];
                foreach ($res_roles as $rol_item){
                    $roles [] = $rol_item['rol'];
                }
                $_SESSION['roles'] = $roles;

                $sql_permisos = "select p.permiso from usuario u 
                                 join usuario_rol ur on u.id_usuario = ur.id_usuario
                                 join rol r on ur.id_rol = r.id_rol 
                                 join permiso_rol pr on r.id_rol = pr.id_rol
                                 join permiso p on pr.id_permiso = p.id_permiso
                                 where u.id_usuario = :id_usuario";
                $stmt_permisos = $this -> conn -> prepare($sql_permisos);
                $stmt_permisos -> bindParam(':id_usuario', $_SESSION['id_usuario'], PDO::PARAM_INT);
                $stmt_permisos -> execute();
                $res_permisos = $stmt_permisos -> fetchAll(PDO::FETCH_ASSOC);
                $permisos = [];
                foreach ($res_permisos as $permiso_item){
                    $permisos [] = $permiso_item['permiso'];
                }
                $_SESSION['permisos'] = $permisos;
                
                return true;
            }
        }
        return false;
    }

    function logout(){
        unset($_SESSION['validado']);
        unset($_SESSION['correo']);
        unset($_SESSION['roles']);
        unset($_SESSION['permisos']);
        session_destroy();
        return true;
    }

    function checar($rol){
        if(isset($_SESSION['validado'])){
            $roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : [];
            if(in_array($rol, $roles)){
                return true;
            }
        }
        include_once (__DIR__.'/views/header_login.php');
        $alerta = [];
        $alerta ['mensaje'] = "NO TIENES EL ROL ADECUADO <a href='login.php'>CAMBIAR CUENTA </a>";
        $alerta ['tipo'] = 'danger';
        $this -> alerta($alerta);
        include_once (__DIR__.'/views/cadenero.php');
        die();
    }

    function checar_permiso($permiso){
        if(isset($_SESSION['validado'])){
            $permisos = isset($_SESSION['permisos'])? $_SESSION['permisos'] : [];
            if(in_array($permiso, $permisos)){
                return true;
            }
        }
        return false;
    }

    function enviar_correo($destinatario,$asunto,$mensaje)
    {
        require __DIR__.'/../vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = MAILSMTP;
        $mail->Port = MAILPORT;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->Username = MAILUSER;
        $mail->Password = MAILPASSWORD;
        $mail->setFrom(MAILUSER, 'MEDLINK');
        $mail->addAddress($destinatario, 'Destinatario génerico');
        $mail->Subject = $asunto;
        $mail -> msgHTML($mensaje);
        return $mail->send();
    }

    function validar_token($correo, $token){
        $this -> conectar();
        try{
            $sql = "SELECT correo FROM usuario WHERE correo=:correo AND token=:token";
            $datos = $this -> conn -> prepare($sql);
            $datos->bindParam(':correo', $correo, PDO::PARAM_STR);
            $datos->bindParam(':token', $token, PDO::PARAM_STR);
            $datos -> execute();
            $info = $datos -> fetch(PDO::FETCH_ASSOC);
            if(isset($info['correo'])){
                return True;
            }
            return False;
        }catch(PDOException $e){
            return False;
        }
    }

    function cambiar_contrasena($correo){
        if($this -> validar_correo($correo)){
            $this -> conectar();
            $this -> conn -> beginTransaction();
            try{
                $sql = "SELECT correo, contrasena FROM usuario WHERE correo=:correo";
                $datos = $this -> conn -> prepare($sql);
                $datos->bindParam(':correo', $correo, PDO::PARAM_STR);
                $datos -> execute();
                $info = $datos -> fetch(PDO::FETCH_ASSOC);
                if(isset($info['correo'])){
                    $blowfish = 'PIXEL'.rand(1,100000);
                    $token = md5($blowfish.$info['correo']).md5($info['contrasena']);
                    $sql = 'UPDATE usuario SET token=:token WHERE correo=:correo';
                    $datos = $this -> conn -> prepare($sql);
                    $datos->bindParam(':token', $token, PDO::PARAM_STR);
                    $datos->bindParam(':correo', $correo, PDO::PARAM_STR);
                    $datos -> execute();
                    $this -> conn -> commit();
                    return $token;
                }
                $this -> conn -> rollBack();
                return False;
            }catch(PDOException $e){
                $this -> conn -> rollBack();
                return False;
            }
        }
    }

    function restablecer($correo, $contrasena, $token){
        if($this -> validar_token($correo, $token)){
            $contrasena = md5($contrasena);
            try{
                $this -> conectar();
                $sql = 'UPDATE usuario SET contrasena=:contrasena, token=NULL WHERE correo=:correo';
                $datos = $this -> conn -> prepare($sql);
                $datos->bindParam(':contrasena', $contrasena, PDO::PARAM_STR);
                $datos->bindParam(':correo', $correo, PDO::PARAM_STR);
                $datos -> execute();
                $cantidad = $datos -> rowCount();
                return $cantidad;
                return False;
            }catch(PDOException $e){
                return False;
            }
        }
    }

    function StaffCount() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM staff");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    function ConsultorioCount() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM consultorio");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function PacienteCount() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM paciente");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    function MedicoCount() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT COUNT(*) as total FROM medico");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) ($result['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    function disponibilidad($fecha, $hora, $id_medico, $id_paciente, $id_consultorio, $id_cita_actual = null) {
        $sql = "SELECT COUNT(*) FROM cita WHERE fecha = :fecha AND hora = :hora AND id_medico = :id_medico";
        if ($id_cita_actual) {
            $sql .= " AND id_cita != :id_cita_actual";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
        $stmt->bindParam(':id_medico', $id_medico, PDO::PARAM_INT);
        if ($id_cita_actual) {
            $stmt->bindParam(':id_cita_actual', $id_cita_actual, PDO::PARAM_INT);
        }
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            error_log("Conflicto: El médico ya tiene una cita en este horario.");
            return false;
        }

        $sql = "SELECT COUNT(*) FROM cita WHERE fecha = :fecha AND hora = :hora AND id_paciente = :id_paciente";
        if ($id_cita_actual) {
            $sql .= " AND id_cita != :id_cita_actual";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
        $stmt->bindParam(':id_paciente', $id_paciente, PDO::PARAM_INT);
        if ($id_cita_actual) {
            $stmt->bindParam(':id_cita_actual', $id_cita_actual, PDO::PARAM_INT);
        }
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            error_log("Conflicto: El paciente ya tiene una cita en este horario.");
            return false;
        }

        $sql = "SELECT COUNT(*) FROM cita WHERE fecha = :fecha AND hora = :hora AND id_consultorio = :id_consultorio";
        if ($id_cita_actual) {
            $sql .= " AND id_cita != :id_cita_actual";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindParam(':hora', $hora, PDO::PARAM_STR);
        $stmt->bindParam(':id_consultorio', $id_consultorio, PDO::PARAM_INT);
        if ($id_cita_actual) {
            $stmt->bindParam(':id_cita_actual', $id_cita_actual, PDO::PARAM_INT);
        }
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            error_log("Conflicto: El consultorio ya está ocupado en este horario.");
            return false;
        }

        return true;
    }

    function leerPacientes() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT id_paciente, CONCAT(nombre, ' ', primer_apellido) as nombre_completo FROM paciente ORDER BY nombre_completo");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obteniendo lista de pacientes: " . $e->getMessage());
            return [];
        }
    }

    function leerMedicos() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT id_medico, CONCAT(nombre, ' ', primer_apellido) as nombre_completo FROM medico ORDER BY nombre_completo");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error obteniendo lista de médicos: " . $e->getMessage());
            return [];
        }
    }

    function leerConsultorios() {
        $this->conectar();
        try {
            $stmt = $this->conn->query("SELECT id_consultorio, CONCAT('Piso ', piso, ' - Hab. ', habitacion) as descripcion FROM consultorio ORDER BY piso, habitacion");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
             error_log("Error obteniendo lista de consultorios: " . $e->getMessage());
            return [];
        }
    }

    function leerCitaCompleta($id) {
        $this->conectar();
        try {
            $sql = "SELECT c.*,
                           CONCAT(p.nombre, ' ', p.primer_apellido) as paciente_nombre_completo,
                           p.telefono as paciente_telefono,
                           up.correo as paciente_correo,
                           CONCAT(m.nombre, ' ', m.primer_apellido) as medico_nombre_completo,
                           m.licencia as medico_licencia,
                           e.especialidad as medico_especialidad,
                           co.piso as consultorio_piso,
                           co.habitacion as consultorio_habitacion
                    FROM cita c
                    JOIN paciente p ON c.id_paciente = p.id_paciente
                    LEFT JOIN usuario up ON p.id_usuario = up.id_usuario
                    JOIN medico m ON c.id_medico = m.id_medico
                    LEFT JOIN especialidad e ON m.id_especialidad = e.id_especialidad
                    JOIN consultorio co ON c.id_consultorio = co.id_consultorio
                    WHERE c.id_cita = :id_cita";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_cita', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al leer datos completos de cita ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
?>