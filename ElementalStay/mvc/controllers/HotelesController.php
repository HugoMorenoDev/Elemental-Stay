<?php
class HotelesController
{
    private $view;
    
    public function __construct()
    {
        // Instanciamos el motor de plantillas
        $this->view = new View();
    }

    public function listarHotelesSinFiltros()
    {
        require_once 'models/HotelesModel.php';
        $hoteles = new HotelesModel();
        $listado = $hoteles->listadoHotelesTotal();
    
        // Indicamos a la vista que estamos en modo sin filtros
        $data['listado']    = $listado;
        $data['sinFiltros'] = true;
    
        $this->view->show("Hoteles/Hoteleslistar.php", $data);
    }
    
    public function listarHoteles()
    {
        require_once 'models/HotelesModel.php';
        $hoteles = new HotelesModel();
        
        // Recoger parámetros del formulario
        $destino = isset($_GET['destino']) ? trim($_GET['destino']) : null;
        $adultos = isset($_GET['adultos']) ? intval($_GET['adultos']) : 0;
        $ninos = isset($_GET['ninos']) ? intval($_GET['ninos']) : 0;
        $entrada = isset($_GET['entrada']) && !empty($_GET['entrada']) ? $_GET['entrada'] : null;
        $salida = isset($_GET['salida']) && !empty($_GET['salida']) ? $_GET['salida'] : null;
        $precioMin = isset($_GET['precio_min']) && $_GET['precio_min'] !== '' ? floatval($_GET['precio_min']) : null;
        $precioMax = isset($_GET['precio_max']) && $_GET['precio_max'] !== '' ? floatval($_GET['precio_max']) : null;
            
        // Calcular el total de personas
        $totalPersonas = $adultos + $ninos > 0 ? $adultos + $ninos : null;
        
        // Llamar al modelo con los filtros ingresados
        $listado = $hoteles->buscarHoteles($destino, $totalPersonas, $entrada, $salida, $precioMin, $precioMax);
        
        // Enviar los datos a la vista
        $data['listado'] = $listado;
        $data['sinFiltros'] = false;
        $this->view->show("Hoteles/Hoteleslistar.php", $data);
    }
}
?>
