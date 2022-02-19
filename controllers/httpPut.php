<?php
class httpPut
{
    public function __construct(){
        $this->method = 'put';
    }
    public function Usuario()
    {
        try {
            $api = new Rest($this->method);
            $sp  = 'CALL sp_InsertaUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)';

            $json = $api->inicializar();
            $json->comuna     = (!empty($json->comuna)) ? $json->comuna : null;
            $json->pais       = 56;
            $json->id_perfil  = 'U';
            $json->estado     = (!empty($json->estado)) ? $json->estado : 1;
            $json->nombre     = (!empty($json->nombre)) ? $json->nombre : null;
            $json->apellido   = (!empty($json->apellido)) ? $json->apellido : null;

            $json->telefono   = (!empty($json->telefono)) ? $json->telefono : null;
            $json->direccion  = (!empty($json->direccion)) ? $json->direccion : null;
            $json->correo     = (!empty($json->correo)) ? $json->correo : null;
            $json->pass       = (!empty($json->pass)) ? md5(sha1($json->pass)) : null;

            if (!empty($json->date)){
                $Date = new Fecha($json->date, '00:00:00:0000');
                $json->date = $Date->MySql();
            }
            else{
                $json->date = null;
            }
            if (!empty($json->old) && $json->old != md5(sha1($json->old_pass))) {
                echo json_encode(['Code'=> 301, 'Message'=>  'Contraseña antigüa es incorrecta.']);
                die();
            }

            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->username, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->comuna, PDO::PARAM_INT);
            $stmt->bindParam(3, $json->pais, PDO::PARAM_INT);
            $stmt->bindParam(4, $json->id_perfil, PDO::PARAM_STR);
            $stmt->bindParam(5, $json->estado, PDO::PARAM_INT);
            $stmt->bindParam(6, $json->nombre, PDO::PARAM_STR);
            $stmt->bindParam(7, $json->apellido, PDO::PARAM_STR);
            $stmt->bindParam(8, $json->date, PDO::PARAM_STR);
            $stmt->bindParam(9, $json->telefono, PDO::PARAM_INT);
            $stmt->bindParam(10, $json->direccion, PDO::PARAM_STR);
            $stmt->bindParam(11, $json->correo, PDO::PARAM_STR);
            $stmt->bindParam(12, $json->pass, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            if ($data) {
                $key = explode(' ', $data->ingreso)[1];
                /* $gifmeEncrypt = new GifmeEncrypt($key);
                $data->apiKey = $gifmeEncrypt->gifme_encode($data->username); */
                $data->foto = json_decode($data->foto);
                unset($data->id);
                unset($data->ingreso);
            }
            $db = null;

            return (empty($data->ERROR)) ? ['Code' => 200, 'Data' => $data]
                           : ['Code' => 600, 'Message' => $data->ERROR];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error putUsuario: '.$e->getMessage().' in line: '.$e->getLine());
        }
    }
    public function Imagen()
    {
        try {
            $api = new Rest($this->method);
            $json = $api->inicializar();

            $Image = new Imagen('images/'.$json->username.'/perfil', $json->file->name);
            $Image->DeleteAll();
            $json->foto = $Image->Crear($json->file->base64, 300, 300);

            if ($json->foto->Code != 200) {
                return $json->foto;
            }

            $json->foto = '["'.$json->foto->Data.'"]';
            $sp  = 'CALL sp_imagen(?, ?)';
            $db = new db();
            $db = $db->connect();
            $stmt = $db->prepare($sp);
            $stmt->bindParam(1, $json->foto, PDO::PARAM_STR);
            $stmt->bindParam(2, $json->username, PDO::PARAM_STR);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_OBJ);
            $db = null;
            if(!$data->ROWCOUNT) {
                return ['Code' => 600, 'Message' => 'No se pudo actualizar la imagen.'];
            }
            return ['Code' => 200, 'Message' => 'Se ha actualizado su foto de perfil.', 'src' => $Image->getRutaWeb()];
        }
        catch (PDOException $e) {
            throw new ExcepcionApi($e->getCode(), 'Error putImagen: '.trim($e->getMessage()).' in line: '.$e->getLine());
        }   
    }
}
?>