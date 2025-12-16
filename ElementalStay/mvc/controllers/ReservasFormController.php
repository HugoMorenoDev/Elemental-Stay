<?php
// controllers/ReservasFormController.php
require_once 'models/ReservasFormModel.php';

class ReservasFormController {
    private $view;
    private $reservasFormModel;
    
    public function __construct() {
        $this->view = new View();
        $this->reservasFormModel = new ReservasFormModel();
    }
    
    /**
     * Muestra la información del hotel, la habitación disponible y un formulario para reservar.
     */
    public function listarReservasDisponibles() {
        // 1. Recoger parámetros de la URL
        $idHotel = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $entrada = isset($_GET['entrada']) ? $_GET['entrada'] : '';
        $salida  = isset($_GET['salida'])  ? $_GET['salida']  : '';
        $adultos = isset($_GET['adultos']) ? intval($_GET['adultos']) : 0;
        $ninos   = isset($_GET['ninos'])   ? intval($_GET['ninos'])   : 0;
        
        // 2. Calcular total de personas
        $totalPersonas = $adultos + $ninos;
        
        // 3. Obtener datos hotel y habitación
        $datos = $this->reservasFormModel->obtenerDatosReserva($idHotel, $entrada, $salida, $totalPersonas);

        // 3.1. Obtener servicios del hotel
        $servicios = $this->reservasFormModel->obtenerServiciosHotel($datos['hotelInfo']['nombre']);

        // 4. Preparar datos para la vista
        $data = [
            'hotelInfo'      => $datos['hotelInfo']      ?? [],
            'habitacionInfo' => $datos['habitacionInfo'] ?? [],
            'servicios'      => $servicios,
            'entrada'        => $entrada,
            'salida'         => $salida,
            'adultos'        => $adultos,
            'ninos'          => $ninos
        ];

        // 5. Renderizar la vista
        $this->view->show("Reservas/ReservasFormlistar.php", $data);
    }
    
    /**
     * (Opcional) Método para procesar la reserva enviada desde el formulario.
     */
    public function guardarReserva() {
        // Aquí iría la lógica para procesar y guardar la reserva.
    }
}
?>
