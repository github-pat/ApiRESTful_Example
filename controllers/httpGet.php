<?php
class httpGet
{
    public function __construct(){
        $this->method = 'get';
    }
    public function Inicio()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_inicio(?)';

            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            foreach ($data as $key => $value) {
                $data[$key] = json_decode($value->json);
            }
            return ($data) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
                           : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
        throw new ExcepcionApi($e->getCode(), 'Error getInicio: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Evento()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_TraerEventos(?, ?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->id_evento, PDO::PARAM_INT);
            $stmt->bindParam(2, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return ($data)
                ? ['Code' => 200, 'Message'=> 'Ok','Data' => $data]
                : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getEvento: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Producto_Evento()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            $sp  = 'CALL sp_Trae_Producto_Evento(?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->id_producto_evento, PDO::PARAM_INT);
            $stmt->bindParam(2, $json->id_evento, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            return ($data)
                ? ['Code' => 200, 'Message'=> 'Ok','Data' => $data]
                : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getEvento: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Producto_Deseo()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            $sp  = 'CALL sp_Trae_Producto_Evento(?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->id_producto_evento, PDO::PARAM_INT);
            $stmt->bindParam(2, $json->id_evento, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;

            return ($data)
                ? ['Code' => 200, 'Message'=> 'Ok','Data' => $data]
                : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getEvento: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Deseo()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();
            
            $sp  = 'CALL sp_TraerDeseos(?, ?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->id_deseo, PDO::PARAM_INT);
            $stmt->bindParam(2, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return ($data)
                ? ['Code' => 200, 'Message'=> 'Ok','Data' => $data]
                : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getDeseo: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Raffle()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_TraerRifa(?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->id_estado, PDO::PARAM_INT);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            if (!$data) {
                return ['Code' => 404, 'Message' => 'No se encontraron datos.'];
            }
            $i = 0;
            foreach ($data as $value) {
                $data[$i]->premios = json_decode($value->premios);
                $data[$i]->rifa_usuario = json_decode($value->rifa_usuario);
                unset($data[$i]->id_usuario);
                $i++;
            }
            return ['Code' => 200, 'Data' => $data];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getRaffle: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }  
    }
    public function Usuario()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_usuario(?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)';
            $mode = 'sel';
            $json->id_usuario = (isset($json->id_usuario)) ? $json->id_usuario : null;
            $json->id_comuna = (isset($json->id_comuna)) ? $json->id_comuna : null;
            $json->id_region = (isset($json->id_region)) ? $json->id_region : null;
            $json->id_pais = (isset($json->id_pais)) ? $json->id_pais : null;
            $json->id_perfil = (isset($json->id_perfil)) ? $json->id_perfil : null;
            $json->id_estado = (isset($json->id_estado)) ? $json->id_estado : null;
            $json->username = (isset($json->username)) ? $json->username : null;
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $mode, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->id_usuario, PDO::PARAM_STR);
            $stmt->bindParam(3, $json->id_comuna, PDO::PARAM_INT);
            $stmt->bindParam(4, $json->id_region, PDO::PARAM_INT);
            $stmt->bindParam(5, $json->id_pais, PDO::PARAM_INT);
            $stmt->bindParam(6, $json->id_perfil, PDO::PARAM_INT);
            $stmt->bindParam(7, $json->id_estado, PDO::PARAM_INT);
            $stmt->bindParam(8, $json->username, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return ($data) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => Utils::jsonInside($data)]
                           : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getUsuario: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Username()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_usuario(?, NULL, NULL, NULL, NULL, NULL, NULL, ?, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)';
            $mode = 'nom';

            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $mode, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->id_username, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            return ($data) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
                           : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getUsername: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
    public function Localidad()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_Localidad()';
            $mode = 'reg';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->execute();
            $Localidad = $stmt->fetch(PDO::FETCH_OBJ);
            $data = ['Regiones' => json_decode($Localidad->regiones), 'Comunas' => json_decode($Localidad->comunas)];
            $db = null;
            return ($Localidad) 
                        ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
                        : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getLocalidad: '.$e->getMessage().' in line: '.$e->getLine());
        }
    }
    public function Buscar()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $sp  = 'CALL sp_trae_busqueda(?, ?)';
            
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->id_buscar, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            return ($data) ? ['Code' => 200, 'Message' => 'OK.', 'Data' => $data]
                           : ['Code' => 404, 'Message' => 'No se encontraron datos.'];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error getBuscar: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }
    }
}
?>