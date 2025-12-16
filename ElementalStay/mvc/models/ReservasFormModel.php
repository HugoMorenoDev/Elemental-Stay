<?php
require_once 'libs/SMysqli.php';

class ReservasFormModel {
    protected $db;

    public function __construct() {
        $this->db = SMysqli::singleton()->getConnection();
    }
    
    public function obtenerDatosReserva($idHotel, $entrada, $salida, $totalPersonas) {
        // Información del hotel
        $sqlHotel = "SELECT id_hotel, nombre, img AS imagen, comunidad_autonoma, descripcion 
                     FROM hoteles 
                     WHERE id_hotel = ?";
        $stmtHotel = $this->db->prepare($sqlHotel);
        if (!$stmtHotel) {
            throw new Exception("Error preparando consulta de hotel: " . $this->db->error);
        }
        $stmtHotel->bind_param("i", $idHotel);
        $stmtHotel->execute();
        $hotelInfo = $stmtHotel->get_result()->fetch_assoc();

        $sqlHabitacion = "SELECT ha.id_habitacion,
                                  ha.nombre,
                                  ha.id_categoria,
                                  ha.capacidad,
                                  c.nombre_categoria,
                                  ha.m2,
                                  ha.wifi,
                                  ha.descripcion,
                                  GROUP_CONCAT(im.imagen SEPARATOR ',') AS imagenes,
                                  MIN(t.precio) AS precio
                           FROM habitaciones ha
                           INNER JOIN categorias c 
                               ON ha.id_categoria = c.id_categoria
                           INNER JOIN tarifas t
                               ON t.id_hotel = ha.id_hotel 
                               AND t.id_categoria = ha.id_categoria
                           LEFT JOIN imagenes_habitaciones im
                               ON im.id_habitacion = ha.id_habitacion
                           WHERE ha.id_hotel = ? 
                             AND ha.capacidad >= ?
                             AND NOT EXISTS (
                                 SELECT 1 
                                 FROM reservas r
                                 WHERE r.id_habitacion = ha.id_habitacion
                                   AND ? < r.dia_salida
                                   AND ? > r.dia_entrada
                             )
                           GROUP BY ha.id_habitacion, ha.nombre, ha.m2, ha.wifi, ha.id_categoria, ha.capacidad, c.nombre_categoria, ha.descripcion
                           LIMIT 1";
        $stmtHabitacion = $this->db->prepare($sqlHabitacion);
        if (!$stmtHabitacion) {
            throw new Exception("Error preparando consulta de habitación: " . $this->db->error);
        }
        $stmtHabitacion->bind_param("iiss", $idHotel, $totalPersonas, $entrada, $salida);
        $stmtHabitacion->execute();
        $habitacionInfo = $stmtHabitacion->get_result()->fetch_assoc();
        
        return [
            'hotelInfo'      => $hotelInfo,
            'habitacionInfo' => $habitacionInfo
        ];
    }
    /**
 * Retorna los servicios de un hotel dado su nombre.
 */
public function obtenerServiciosHotel($nombreHotel)
{
    $sql = "SELECT id_servicio, nombre
            FROM servicios
            WHERE nombre_hotel = ?";
    $stmt = $this->db->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparando la consulta de servicios: " . $this->db->error);
    }
    $stmt->bind_param("s", $nombreHotel);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result) {
        throw new Exception("Error ejecutando la consulta de servicios: " . $this->db->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

}
?>
