<?php
class View
{
    public function show($name, $vars = array())
    {
        // Obtenemos la configuración
        $config = Config::singleton();
 
        // Armamos la ruta a la plantilla (sin la barra inicial)
        $path = $config->get('viewsFolder') . $name;
 
        // Si la plantilla no existe, se muestra un error
        if (!file_exists($path)) {
            trigger_error('Template `' . $path . '` does not exist.', E_USER_NOTICE);
            return false;
        }
 
        // Extraemos las variables para que sean accesibles en la vista
        if (is_array($vars)) {
            foreach ($vars as $key => $value) {
                $$key = $value;
            }
        }
 
        // Se incluye la plantilla
        include($path);
    }
}
?>
