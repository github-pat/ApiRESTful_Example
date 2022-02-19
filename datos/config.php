<?php
	ini_set('default_charset', 'UTF-8');
	iconv_set_encoding("input_encoding", "UTF-8");
	iconv_set_encoding("internal_encoding", "UTF-8");
	iconv_set_encoding("output_encoding", "UTF-8");

	#Confuracion cache
	header("Pragma: no-cache");
	header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check = 0, pre-check = 0", false);

	/*
		SOFTWARE: Example APIRest
		VERSION: 1.0
		FECHA CREACIÓN : 03-08-2021
		FECHA ACTUALIZACIÓN : 03-08-2021
	*/

	#Configuración Servidor de imágenes
		const RAIZ = 'C:/AppServ/www/ServerImages/'; //Servidor de imágenes ruta local
		const WEB  = 'http://localhost/ServerImages/'; // Servidor de imágenes ruta web


	#Configuración Base de datos
		const HOST = 'localhost';
		const BASE = 'example';
		const PORT = 3306;
		const USER = 'root';
		const PASS = 'rootroot';

	# Configuración de envío de correos
		const MAIL_Host = "------------";
		const MAIL_Username = "----------------";
		const MAIL_Password = "--------------";
		const MAIL_Mailer = 'smtp';
		const MAIL_Port = 465;
		const MAIL_SMTPSecure = 'tls';
		const MAIL_SMTPAuth = true;
		const MAIL_isHTML = true;
		const MAIL_From = "------------";
		const MAIL_From_Name = '------------';


	const KEY_ENCRYPT ="--------------";

	#Confuracion Depuracion PHP
		# Habilitado    :-1
		# Deshabilitado : 0

	error_reporting(-1);
	date_default_timezone_set("America/Santiago");
	
?>
