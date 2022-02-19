<?php
class Fecha
{
	function __construct($fecha, $hora = '23:59:59.240')
	{
		# Formato fecha para MySql yyyy-mm-dd
		# Formato fecha para Sql yyyymmdd
		# Opcional  yyyy-mm-dd, 00:00:00


		$fecha = str_replace('/', '-', $fecha);
		$f = explode('-', $fecha); 
		$this->fecha = $f[2].'-'.$f[1].'-'.$f[0];
		$this->hora	 = $hora;
	}
	public function MySql()
	{
		return $this->fecha.' '.$this->hora;
	}
}