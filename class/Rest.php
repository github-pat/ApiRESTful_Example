<?php
	class Rest
	{
		private $object;
		private $controller;
		private $username;
		public function __construct($method)
		{
			$this->method = $method;
			$this->controller = explode('/', $_GET['PATH_INFO'])[0];
			$this->object = json_decode(file_get_contents("php://input"));
			$this->username = null;
		}
		public function inicializar(){
			$error = false;
			if(!empty($_SERVER['PHP_AUTH_PW'])){
				$db = new db();
				$db = $db->connect();
				$stmt = $db->query("SELECT DATE_FORMAT(ingreso, '%H:%i:%s') gifmeKey FROM usuario WHERE username = '".$_SERVER['PHP_AUTH_USER']."'");
				$data = $stmt->fetch(PDO::FETCH_OBJ);
				if ($data) {
					$username = '';
					/* $gifmeEncrypt = new GifmeEncrypt($data->gifmeKey);
					$username = $gifmeEncrypt->gifme_decode(trim($_SERVER['PHP_AUTH_PW'])); */
					if (trim($username) == trim($_SERVER['PHP_AUTH_USER'])) {
						$this->username = $_SERVER['PHP_AUTH_USER'];
						self::get_object();
						$this->object->username = $_SERVER['PHP_AUTH_USER'];
					}
					else{
						$error = true;
					}
				}
				else{
					$error = true;
				}
			}
			else{
				$error = true;
			}

			if ($error) {
				echo json_encode(["Code"=> 305, 'Message'=> 'Los datos proporcionados para usar esta API no son correctos. Se ha prohibido el acceso.']);
				die();
			}
			return $this->object;
		}
		public function get_objectLogin(){
			self::get_object();
			return $this->object;
		}
		private function get_object(){
			if ($this->method == 'get' || $this->method == 'delete' || $this->method == 'put') {
				$uri = explode('/', $_GET['PATH_INFO']);
				$arr = false;
				foreach ($uri as $i => $value) {
					$sust = ($i > 0) ? 'id_'.$uri[($i - 1)] : '';
					if ($i > 1){
						if ($i % 2 == 0) {
							$arr[$sust] = (!$value || strtolower($value) != 'null' && $value != '*') ? $value : null;
						}
					}
				}
				if ($this->method == 'put') {
					$object = (array) $this->object;
					if ($arr) {
						foreach ($arr as $key => $value) {
							$object[$key] = $value;
						}
					}
					else{
						$object[$sust] = null;
					}
					$this->object = (object) $object;
				}
				else{
					$this->object = ($arr) ? (object) $arr : (object) [$sust => null];
				}
			}
		}
	}