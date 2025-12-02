<?php
    class Conexion{
        public $ip;
        public $nombre;
        public $password;
        public $bd;

        public function __construct($ip,$nombre,$password,$bd){
            $this->ip = $ip;
            $this->nombre = $nombre;
            $this->password = $password;
            $this->bd = $bd;
        }

        public function __toString(){
            return 'Ip: '
            . $this->ip .'<br>Nombre: '
            . $this->nombre .'<br>Contraseña: '
            . $this->password .'<br>Base de datos: '
            . $this->bd .'<br>';

        }
        
        public function getIp(){
            return $this->ip;
        }
        public function setIp($ip){
            $this->ip = $ip;
        }

        public function getNombre(){
            return $this->nombre;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }

        public function getPassword(){
            return $this->password;
        }
        public function setPassword($password){
            $this->password = $password;
        }

        public function getBd(){
            return $this->bd;
        }
        public function setBd($bd){
            $this->bd = $bd;
        }

        public function conection(){
            $conexion = new mysqli($this->ip,$this->nombre,$this->password,$this->bd);
            if($conexion->connect_errno != null){
                echo 'Error conectando a la base de datos: ';
                echo '$conexion->connect_error';
                exit();
            }else{
			return $conexion;
		    }
        }

        public function conectionPDO(){
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            try {
                $conexion = new PDO('mysql:host='.$this->ip.';dbname='.$this->bd, $this->nombre,$this->password,$opc);
                return $conexion;
            } catch (PDOException $e) {
                echo 'Falló la conexión: ' . $e->getMessage();
            }
        }
    }
?>