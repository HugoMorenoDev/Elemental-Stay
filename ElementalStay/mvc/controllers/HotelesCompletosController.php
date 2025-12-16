<?php
require_once 'models/HotelesCompletosModel.php';

class HotelesCompletosController {
    private $view;
    private $hotelesCompletosModel;

    public function __construct()
    {
        // Instanciamos el motor de plantillas y el modelo de HotelesCompletos
        $this->view = new View();
        $this->hotelesCompletosModel = new HotelesCompletosModel();
    }

    public function listarHabitacionesDisponibles()
    {
        // 1. Recoger los parámetros que vienen por GET
        $idHotel   = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $entrada   = isset($_GET['entrada']) ? $_GET['entrada'] : '';
        $salida    = isset($_GET['salida'])  ? $_GET['salida']  : '';
        $adultos   = isset($_GET['adultos']) ? intval($_GET['adultos']) : 0;
        $ninos     = isset($_GET['ninos'])   ? intval($_GET['ninos'])   : 0;

        // 2. Calcular total de personas
        $totalPersonas = $adultos + $ninos;

        // 3. Pedir al modelo las habitaciones disponibles para este hotel
        $habitaciones = $this->hotelesCompletosModel
                            ->obtenerHabitacionesDisponibles($idHotel, $entrada, $salida, $totalPersonas);

        // 4. Obtener también información del hotel
        $hotelInfo = $this->hotelesCompletosModel->obtenerInfoHotel($idHotel);

        // 4.1. Obtener servicios del hotel
        $servicios = $this->hotelesCompletosModel->obtenerServiciosHotel($hotelInfo['nombre']);

        // 5. Preparar datos para la vista
        $data = [
            'habitaciones' => $habitaciones,
            'hotelInfo'    => $hotelInfo,
            'servicios'    => $servicios,
            'entrada'      => $entrada,
            'salida'       => $salida,
            'adultos'      => $adultos,
            'ninos'        => $ninos
        ];


        // 6. Renderizar la vista
        $this->view->show("Hoteles/HotelesCompletoslistar.php", $data);
    }
}

?>
