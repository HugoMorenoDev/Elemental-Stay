<?php
class SMysqli {
    private static $instance = null;
    private $conn;

    // Constructor privado para evitar instanciaciones externas
    private function __construct() {
        // Se incluye el archivo de conexión mysqli (la ruta es relativa a esta carpeta)
        require_once __DIR__ . '/../../config/conexion.php';
        // Se supone que en conexion.php se crea la variable $conn
        $this->conn = $conn;
    }

    // Retorna la única instancia de la conexión
    public static function singleton() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Devuelve el objeto de conexión mysqli
    public function getConnection() {
        return $this->conn;
    }
}
?>
