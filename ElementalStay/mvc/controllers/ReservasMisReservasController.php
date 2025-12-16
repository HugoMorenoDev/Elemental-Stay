<?php
// controllers/ReservasMisReservasController.php
class ReservasMisReservasController
{
    private $view;
    private $model;

    public function __construct()
    {
        $this->view = new View();
        require_once 'models/ReservasMisReservasModel.php';
        $this->model = new ReservasMisReservasModel();
    }

    public function listar()
    {
        // 1) Comprobación de sesión
        if (empty($_SESSION['usuario']['id_usuario'])) {
            $data = [
                'mensaje'  => 'Para poder consultar tus reservas, primero debes iniciar sesión. Serás redirigido en unos segundos...',
                'redirect' => '../inicio_session/inicio_session.php',
                'delay'    => 4
            ];
            $this->view->show('ErrorReserva/NoLogueado.php', $data);
            exit;
        }

        // 2) ID de usuario (logging)
        $idUsuario = (int) $_SESSION['usuario']['id_usuario'];
        error_log("DEBUG listar(): idUsuario en sesión = $idUsuario");

        // 3) Llamada al modelo
        $reservas = $this->model->obtenerPorUsuario($idUsuario);

        // 4) Contenido del array (logging)
        error_log("DEBUG listar(): contenido de \$reservas = " . print_r($reservas, true));

        // 5) Pasamos a la vista
        $data['reservas'] = $reservas;
        $this->view->show('ReservasUsuario/ReservasMisReservas.php', $data);
    }

    public function cancelarReserva()
    {
        if (isset($_POST['id_reserva'], $_POST['idreservashab'])) {
            $id_reserva     = $_POST['id_reserva'];
            $idreservashab  = $_POST['idreservashab'];

            error_log("Cancelando reserva con ID: $id_reserva y ID reserva hab: $idreservashab");

            $result = $this->model->eliminarReserva($id_reserva, $idreservashab);

            if ($result) {
                header("Location: index.php?controlador=ReservasMisReservas&accion=listar&success=Reserva cancelada exitosamente");
            } else {
                header("Location: index.php?controlador=ReservasMisReservas&accion=listar&error=Error al cancelar la reserva");
            }
            exit();
        }
    }

    public function verDetallesReserva()
    {
        // 1) Asegúrate de que el usuario esté logueado
        if (empty($_SESSION['usuario']['id_usuario'])) {
            $data = [
                'mensaje'  => 'Para ver detalles, primero debes iniciar sesión.',
                'redirect' => '../inicio_session/inicio_session.php',
                'delay'    => 4
            ];
            $this->view->show('ErrorReserva/NoLogueado.php', $data);
            exit;
        }

        // 2) Toma el ID de reserva de GET y conviértelo en entero
        $idReserva = isset($_GET['id_reserva']) ? (int) $_GET['id_reserva'] : 0;

        // 3) Llama al modelo para obtener todos los datos de esa reserva
        $detalle = $this->model->obtenerDetalleReserva($idReserva);

        // 4) Si no existe o no pertenece al usuario, muestra error
        if (empty($detalle) || $detalle['id_usuario'] !== (int) $_SESSION['usuario']['id_usuario']) {
            die('Reserva no encontrada o no tienes permiso para verla.');
        }

        // 5) Renderiza la vista pasándole $detalle
        $this->view->show('ReservasUsuario/DetalleReserva.php', ['detalle' => $detalle]);
    }

}
?>

