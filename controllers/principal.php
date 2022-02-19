<?php
#Include CLass
    include 'class/controller_fecha.php';
    #include 'class/controller_Encrypt.php';
    include 'class/controller_imagen.php';
    include "class/Utils.php";

#Include GET POST PUT DELETE
    include 'httpGet.php';
    include 'httpPost.php';
    include 'httpPut.php';
    include 'httpDelete.php';

class principal
{
    #region contantes
        const ESTADO_NO_AUTORIZADO = ['Code' => 301, 'Message'=> 'Los datos proporcionados para usar esta API no son correctos. Se ha denegado el acceso.'];
        const ESTADO_NO_PERMITIDO = ['Code' => 405, 'Message'=> 'El metodo que intenta acceder no existe o no esta disponible.'];
        const ESTADO_NOT_FOUND = ['Code' => 404, 'Message'=> 'Sin resultados.'];
        const ESTADO_CONFLICTO = ['Code' => 409, 'Message'=> 'La solicitud no pudo ser procesada debido a un conflicto con el estado actual del recurso.'];
        const ESTADO_ERROR_BD = ['Code' => 600, 'Message'=> 'Ha ocurrido un error en la Base de Datos.'];
    #endregion contantes

    #region selector
    public static function post($peticion){
        $metodo = (isset($peticion[0])) ? $peticion[0] : null;
        switch ($metodo) {
            case 'login':
                $httpPost = new httpPost();
                return $httpPost->Login();
            break;
            case 'usuario':
                $httpPost = new httpPost();
                return $httpPost->Usuario();
            break;
            case 'evento':
                $httpPost = new httpPost();
                return $httpPost->Evento();
            break;
            case 'deseo':
                $httpPost = new httpPost();
                return $httpPost->Deseo();
            break;
            case 'follow':
                $httpPost = new httpPost();
                return $httpPost->Follow();
            break;
            default:
                throw new ExcepcionApi(405, self::ESTADO_NO_PERMITIDO['Message'], 405);
            break;
        }
    }
    public static function get($peticion){
        $metodo = (isset($peticion[0])) ? $peticion[0] : null;
        $httpGet = new httpGet();
        switch ($metodo) {
            case 'inicio':
                return $httpGet->Inicio();
            break;
            case 'evento':
                return $httpGet->Evento();
            break;
            case 'deseo':
                return $httpGet->Deseo();
            break;
            case 'raffle':
                return $httpGet->Raffle();
            break;
            case 'username':
                return $httpGet->Username();
            break;
            case 'localidad':
                return $httpGet->Localidad();
            break;
            case 'usuario':
                return $httpGet->Usuario();
            break;
            case 'producto_evento':
                return $httpGet->Producto_Evento();
            break;
            case 'buscar':
                return $httpGet->Buscar();
            break;
            default:
                throw new ExcepcionApi(405, self::ESTADO_NO_PERMITIDO['Message'], 405);
            break;
        }
    }
    public static function put($peticion){
        $metodo = (isset($peticion[0])) ? $peticion[0] : null;
        switch ($metodo) {
            case 'usuario':
                $httpPut = new httpPut();
                return $httpPut->Usuario();
            break;
            case 'imagen':
                $httpPut = new httpPut();
                return $httpPut->Imagen();
            default:
                throw new ExcepcionApi(405, self::ESTADO_NO_PERMITIDO['Message'], 405);
            break;
        }
    }
    public static function delete($peticion){
    }
}
?>
