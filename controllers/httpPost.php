<?php
class httpPost
{
    public function __construct(){
        $this->method = 'post';
    }
    public function Login(){
        try {
            $api = new Rest($this->method);
            $json = $api->get_objectLogin();
            
            $json->pass = md5(sha1($json->pass));
            $sp  = 'CALL sp_login(?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->pass, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            if ($data) {/* 
                $key = explode(' ', $data->ingreso)[1];
                $gifmeEncrypt = new GifmeEncrypt($key);
                $data->apiKey = $gifmeEncrypt->gifme_encode($data->username);
                $data->foto = json_decode($data->foto); */
                unset($data->id);
                unset($data->ingreso);
            }
            return ($data) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
            : ['Code' => 301, 'Message' => 'Usuario o contraseña no son correctos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error en método login: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Usuario()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            $json->pass = md5(sha1($json->pass));
            
            $sp  = 'CALL sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
            $mode = 'ins';
            
            $json->id_usuario = (isset($json->id_usuario)) ? $json->id_usuario : null;
            $json->id_comuna = (isset($json->id_comuna)) ? $json->id_comuna : null;
            $json->id_region = (isset($json->id_region)) ? $json->id_region : null;
            $json->id_pais = (isset($json->id_pais)) ? $json->id_pais : null;
            $json->id_perfil = 'U';
            $json->id_estado = 1;
            $json->nombre = (isset($json->nombre)) ? $json->nombre : null;
            $json->apellido = (isset($json->apellido)) ? $json->apellido : null;
            $json->nacimiento = (isset($json->nacimiento)) ? $json->nacimiento : null;
            $json->telefono = (isset($json->telefono)) ? $json->telefono : null;
            $json->direccion = (isset($json->direccion)) ? $json->direccion : null;
            $json->correo = (isset($json->correo)) ? $json->correo : null;
            $json->foto = '["'.WEB.'/images/'.$json->username.'/default.png"]';
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $mode, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_comuna, PDO::PARAM_INT);
            $stmt->bindParam(4, $json->id_pais, PDO::PARAM_INT);
            $stmt->bindParam(5, $json->id_perfil, PDO::PARAM_STR);
            $stmt->bindParam(6, $json->id_estado, PDO::PARAM_INT);
            $stmt->bindParam(7, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(8, $json->nombre, PDO::PARAM_STR);
            $stmt->bindParam(9, $json->apellido, PDO::PARAM_STR);
            $stmt->bindParam(10, $json->nacimiento, PDO::PARAM_STR);
            $stmt->bindParam(11, $json->telefono, PDO::PARAM_STR);
            $stmt->bindParam(12, $json->direccion, PDO::PARAM_STR);
            $stmt->bindParam(13, $json->correo, PDO::PARAM_STR);
            $stmt->bindParam(14, $json->pass, PDO::PARAM_STR);
            $stmt->bindParam(15, $json->foto, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return (!isset($data->ERR)) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
            : ['Code' => $data->ERR, 'Message' => 'No se guardaron los datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error postUsuario: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Evento()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            
            $sp  = 'CALL sp_InsertaEvento(NULL, ?, ?, ?, ?, ?, ?)';
            
            $json->nombre      = (!empty($json->nombre)) ? $json->nombre : null;
            $json->id_estado   = 1;
            $json->descripcion = (!empty($json->descripcion)) ? base64_decode($json->descripcion) : null;
            $json->start       = (!empty($json->start)) ? $json->start : null;
            $json->end         = (!empty($json->end)) ? $json->end : null;
            $json->h_inicio    = (!empty($json->h_inicio)) ? $json->h_inicio : null;
            $json->h_fin       = (!empty($json->h_fin)) ? $json->h_fin : null;
            
            $DateInicio = new Fecha($json->start, $json->h_inicio.':0000');
            $DateFin    = new Fecha($json->end, $json->h_fin.':0000');
            $json->start = $DateInicio->MySql();
            $json->end    = $DateFin->MySql();
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->nombre, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_estado, PDO::PARAM_INT);
            $stmt->bindParam(4, $json->descripcion, PDO::PARAM_STR);
            $stmt->bindParam(5, $json->start, PDO::PARAM_STR);
            $stmt->bindParam(6, $json->end, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return (!isset($data->ERR)) ? ['Code' => 200, 'Message' => 'OK.']
            : ['Code' => $data->ERR, 'Message' => 'No se guardaron los datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error postEvento: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Deseo()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            
            $sp  = 'CALL sp_InsertaDeseo(NULL, ?, ?, ?, ?)';
            
            $json->id_estado   = 1;
            $json->nombre      = (!empty($json->nombre)) ? $json->nombre : null;
            $json->descripcion = (!empty($json->descripcion)) ? base64_decode($json->descripcion) : null;
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->id_estado, PDO::PARAM_INT);
            $stmt->bindParam(3, $json->nombre, PDO::PARAM_STR);
            $stmt->bindParam(4, $json->descripcion, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return (!isset($data->ERR)) ? ['Code' => 200, 'Message' => 'OK.']
            : ['Code' => $data->ERR, 'Message' => 'No se guardaron los datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error postDeseo: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Follow()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            
            $sp  = 'CALL sp_InsertaFollow(?, ?, ?)';
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->usernameFollow, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return $data->ROWCOUNT 
                ? ['Code' => 200, 'Message' => 'OK.']
                : ['Code' => -1, 'Message' => 'No se guardaron los datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error postDeseo: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
}
?>