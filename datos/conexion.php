<?php
    class db{
        // Properties
        private $dbhost = HOST;
        private $dbport = PORT;
        private $dbuser = USER;
        private $dbpass = PASS;
        private $dbname = BASE;

        // Connect
        public function connect(){
            $mysql_connect_str = "mysql:host=$this->dbhost;dbname=$this->dbname";
            $dbConnection = new PDO($mysql_connect_str, $this->dbuser, $this->dbpass);
            $dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbConnection -> exec("set names utf8");
            return $dbConnection;
        }
    }
?>