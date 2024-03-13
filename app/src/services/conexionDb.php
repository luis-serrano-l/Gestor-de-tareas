<?php
use PDO as PDO;
class ConexionDB {
    private $dbhost;
    private $usuario;
    private $contrasena;
    private $database;
    public $conexion;
    
    public function __construct() {
        $this->dbhost = getenv("MYSQL_HOST");
        $this->usuario  = getenv("MYSQL_USER");
        $this->contrasena = getenv("MYSQL_PASSWORD");
        $this->database = getenv("MYSQL_DATABASE");
        try{
            $this->conexion = new PDO("mysql:host=$this->dbhost;dbname=$this->database", $this->usuario, $this->contrasena);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
    
    public function cerrarConexion() {
        $this->conexion = null;
    }

    public function getConexion() {
        return $this->conexion;
    }
}