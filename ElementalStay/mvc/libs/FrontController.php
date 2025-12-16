<?php
class FrontController
{
    public static function main()
    {
        session_start();
        // Incluimos las clases básicas
        require 'libs/Config.php';      // Clase de configuración
        require 'libs/SMysqli.php';      // Clase de conexión mysqli
        require 'libs/View.php';        // Motor de plantillas
 
        // Incluimos el archivo de configuración que define rutas y otras variables
        require 'config.php';
 
        // Determinamos el controlador a usar (por defecto IndexController)
        $controllerName = !empty($_GET['controlador']) ? $_GET['controlador'] . 'Controller' : "IndexController";
        // Determinamos la acción a ejecutar (por defecto index)
        $actionName = !empty($_GET['accion']) ? $_GET['accion'] : "index";
 
        // Formamos la ruta del controlador usando la configuración
        $controllerPath = $config->get('controllersFolder') . $controllerName . '.php';
 
        // Verificamos que el controlador exista
        if (!is_file($controllerPath)) {
            die('El controlador no existe - 404 not found');
        }
 
        // Incluimos el controlador
        require $controllerPath;
 
        // Creamos la instancia del controlador y verificamos que el método exista
        $controller = new $controllerName();
        if (!method_exists($controller, $actionName)) {
            die('La acción no existe - 404 not found');
        }
 
        // Ejecutamos la acción
        $controller->$actionName();
    }
}
?>
