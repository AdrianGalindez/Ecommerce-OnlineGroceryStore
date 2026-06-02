<?php

class Database {

    private $host;
    private $port;
    private $db;
    private $user;
    private $pass;

    public function __construct(){

        // No dependas de loadEnv aquí (Render-safe)
        $this->host = getenv('DB_HOST') ?: null;
        $this->port = getenv('DB_PORT') ?: '3306';
        $this->db   = getenv('DB_NAME') ?: null;
        $this->user = getenv('DB_USER') ?: null;
        $this->pass = getenv('DB_PASS') ?: null;

        // 🔥 VALIDACIÓN CRÍTICA
        if (!$this->host || !$this->db || !$this->user) {
            throw new Exception("❌ Variables de base de datos no configuradas en Render");
        }
    }

    public function connect(){

        try {

            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8mb4";

            $conn = new PDO($dsn, $this->user, $this->pass);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;

        } catch(PDOException $e){

            $env = getenv('APP_ENV') ?: 'production';

            if ($env === 'development') {
                die('❌ Error de conexión: ' . $e->getMessage());
            }

            die('❌ Error de conexión a la base de datos');
        }
    }
}
