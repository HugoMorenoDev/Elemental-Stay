<?php
require_once 'models/ReservaslistarModel.php';

class ReservaslistarController {
    private $view;
    private $model;

    public function __construct() {
        $this->view  = new View();
        $this->model = new ReservaslistarModel();
    }

    /** Procesa el POST, valida y crea la reserva. */
    public function guardarReserva() {
        // 1) Recogemos TODO lo que enviamos en el form
        $idHotel          = intval($_POST['id_hotel'] ?? 0);
        $idHabitacion     = intval($_POST['id_habitacion'] ?? 0);
        $nombreHabitacion = $_POST['nombre_habitacion'] ?? '';
        $precio           = floatval($_POST['precio'] ?? 0);
        $entrada          = $_POST['entrada'] ?? '';
        $salida           = $_POST['salida'] ?? '';
        $adultos          = intval($_POST['adultos'] ?? 0);
        $ninos            = intval($_POST['ninos'] ?? 0);
        $correo           = $_POST['correo'] ?? '';
        $contrasena       = $_POST['contrasena'] ?? '';

        // 2) Validar usuario
        $idUsuario = $this->model->validarUsuario($correo, $contrasena);
        if (!$idUsuario) {
            // 3) Redirigir a la misma forma, reenviando TODO para no perder datos
            $params = http_build_query([
                'id'                 => $idHotel,
                'id_habitacion'      => $idHabitacion,
                'nombre_habitacion'  => $nombreHabitacion,
                'precio'             => $precio,
                'entrada'            => $entrada,
                'salida'             => $salida,
                'adultos'            => $adultos,
                'ninos'              => $ninos,
                'error'              => 'Credenciales'
            ]);
            header("Location: index.php?controlador=ReservasForm&accion=listarReservasDisponibles&{$params}");
            exit;
        }

        // 4) Crear reserva
        $idReserva = $this->model->crearReserva(
            $idHotel,
            $idHabitacion,
            $idUsuario,
            $precio,
            $entrada,
            $salida
        );
        if (!$idReserva) {
            throw new Exception("Error al crear la reserva");
        }

        // 5) Redirigir a la confirmación
        header("Location: index.php?controlador=Reservaslistar&accion=mostrarReserva&id={$idReserva}");
        exit;
    }

    /** Muestra la confirmación con datos. */
    public function mostrarReserva() {
        $idReserva = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $datos     = $this->model->obtenerDetallesReserva($idReserva);
        $this->view->show("Reservas/Reservaslistar.php", [
            'reserva'   => $datos['reserva'],
            'servicios' => $datos['servicios']
        ]);
    }
}
