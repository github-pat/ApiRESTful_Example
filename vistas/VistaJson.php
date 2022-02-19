<?php

class VistaJson extends VistaApi
{
    public function __construct($estado = 400)
    {
        $this->estado = $estado;
    }

    public function imprimir($cuerpo)
    {
        header('Content-Type: application/json');
        echo json_encode($cuerpo, JSON_PRETTY_PRINT);
        exit;
    }
}
