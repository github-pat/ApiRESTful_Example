<?php
class Imagen
{
	function __construct($ruta, $nombre)
	{	
		$this->clase = 'Imagen';
		$this->ruta = $ruta;
		$this->nombre = $nombre;
	}
	public function Crear($base64, $width, $height)
	{
		try {
			$ruta = RAIZ.'/'.$this->ruta.'/';
			if (!file_exists($ruta)) {
				mkdir($ruta, 0777);
			}
			
			$name 	= substr($this->nombre, 0, strrpos($this->nombre, "."));
			$binary =  base64_decode($base64);
			$gestor = fopen($ruta.$this->nombre, "w");
			fwrite($gestor, $binary);
			fclose($gestor);

			$image = new Imagick($ruta.$this->nombre);
			$image->adaptiveResizeImage($width, $height);
			unlink($ruta.$this->nombre);

			$image->writeImage($ruta.$name.'.jpg');
			$this->nombre = $name.'.jpg';
			return (object)['Code'=> 200, 'Data'=> WEB.'/'.$this->ruta.'/'.$this->nombre];
		}
		catch (Exception $e) {
			return (object)['Code'=> $e->getCode(), 'Message'=> $this->clase.'->Crear() '.$e->getMessage().' in line '.$e->getLine()];
		}
	}
	public function getRutaWeb()
	{
		return WEB.'/'.$this->ruta.'/'.$this->nombre;
	}
	public function Borrar_Adjunto($ruta){
		$ruta = explode(WEB, $ruta)[1];
		unlink(RAIZ.$ruta);
	}
	public function DeleteAll(){
		$ruta = RAIZ.'/'.$this->ruta.'/';
		if (!file_exists($ruta)) {
			mkdir($ruta, 0777);
		}
		array_map('unlink', glob($ruta.'/*'));
	}
}
?>