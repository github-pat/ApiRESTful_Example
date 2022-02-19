<?php

class ExcepcionApi extends Exception
{
    public $estado;

    public function __construct($estado, $mensaje, $codigo = 200)
    {
        $this->estado = $estado;
        $this->message = $mensaje;
        $this->code = $codigo;
    }

}
?>