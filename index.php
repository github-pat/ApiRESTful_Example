<?php
require 'datos/config.php';
require 'datos/conexion.php';

require 'class/ExcepcionApi.php';
require 'class/Rest.php';

require 'controllers/principal.php';

require 'vistas/VistaApi.php';
require 'vistas/VistaJson.php';

#Constantes de estado
const ESTADO_URL_INCORRECTA = 404;
const ESTADO_EXISTENCIA_RECURSO = 404;
const ESTADO_METODO_NO_PERMITIDO = 405;

if (isset($_SERVER['HTTP_ORIGIN'])) {  
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");  
    header('Access-Control-Allow-Credentials: true');  
    header('Access-Control-Max-Age: 86400');   
}  
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { 
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    }
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
}


$vista = new VistaJson();

set_exception_handler(function ($exception) use ($vista) {
    $cuerpo = array(
        "Code" => 500,
        "Message" => $exception->getMessage().' in line '.$exception->getLine()
    );
    if ($exception->getCode()) {
        $vista->estado = $exception->getCode();
    } else {
        $vista->estado = 500;
    }
    $vista->imprimir($cuerpo);
});

#Extraer segmento de la url
if (isset($_GET['PATH_INFO'])){
    $peticion = explode('/', $_GET['PATH_INFO']);
}else{
    throw new ExcepcionApi(ESTADO_URL_INCORRECTA, utf8_encode("No se reconoce la petición"));
}

#Obtener recurso
$recurso = array_shift($peticion);
$recursos_existentes = ['principal'];

#Comprobar si existe el recurso
if (!in_array($recurso, $recursos_existentes)) {
    throw new ExcepcionApi(ESTADO_EXISTENCIA_RECURSO,  "No se reconoce el recurso al que intentas acceder");
}

#Obtenemos el metodo http para consumir ell servicio web
$metodo = strtolower($_SERVER['REQUEST_METHOD']);
#Filtrar método http
switch ($metodo) {
    case 'get':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    case 'post':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    case 'put':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    case 'delete':
        if (method_exists($recurso, $metodo)) {
            $respuesta = call_user_func(array($recurso, $metodo), $peticion);
            $vista->imprimir($respuesta);
            break;
        }
    default:
        // Método no aceptado
        $vista->estado = 405;
        $cuerpo = [
            "Code" => ESTADO_METODO_NO_PERMITIDO,
            "Message" => "Metodo no permitido."
        ];
        $vista->imprimir($cuerpo);
}
?>