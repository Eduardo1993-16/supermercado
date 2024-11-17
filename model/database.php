<?php
    class Database{
        private $database = 'Supermercado';
        private $host = 'localhost';
        private $user = 'root';
        private $password = '';
        private $conexion;

        function getConexion():PDO{
            try {
                $this->conexion = new PDO("mysql:host={$this->host};dbname={$this->database}",$this->user,$this->password);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                http_response_code(200);
                echo"Conexión exitosa";
            } catch (\Throwable $th) {
                http_response_code(500);
                echo $th->getMessage();
                echo"No se pudo conectar a la base de datos";
                
            }
            return $this->conexion;
        }
}
// $database = new Database();
// $conexion = $database->getConexion();