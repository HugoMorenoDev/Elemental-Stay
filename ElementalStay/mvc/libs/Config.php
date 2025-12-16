<?php
class Config
{
    private $vars;
    private static $instance;

    private function __construct()
    {
        $this->vars = array();
    }

    // Almacena variables de configuración
    public function set($name, $value)
    {
        if (!isset($this->vars[$name])) {
            $this->vars[$name] = $value;
        }
    }

    // Recupera el valor de una variable
    public function get($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        return null;
    }

    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

?>
