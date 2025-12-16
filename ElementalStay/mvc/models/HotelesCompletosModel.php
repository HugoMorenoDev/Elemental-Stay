<?php


class HotelesCompletosModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SMysqli::singleton()->getConnection();
    }

    /**
     * Retorna todas las habitaciones de un hotel que estén disponibles
     * para las fechas y capacidad solicitadas.
     */
    public function obtenerHabitacionesDisponibles($idHotel, $entrada, $salida, $totalPersonas)
    {
        $sql = "SELECT ha.id_habitacion,
                       ha.nombre,
                       ha.id_categoria,
                       ha.capacidad,
                       c.nombre_categoria,
                       ha.m2,
                       ha.wifi,
                       ha.descripcion,
                       GROUP_CONCAT(im.imagen SEPARATOR ',') AS imagenes,
                       MIN(t.precio) AS precio,
                       MIN(t.id_temporada) AS id_temporada
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
                GROUP BY ha.id_habitacion, ha.nombre, ha.m2, ha.wifi, ha.id_categoria, ha.capacidad, c.nombre_categoria, ha.descripcion"; 
                
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $this->db->error);
        }
        
        $stmt->bind_param("iiss", $idHotel, $totalPersonas, $entrada, $salida);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error ejecutando la consulta: " . $this->db->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Retorna información básica de un hotel (nombre, descripción, etc.)
     */
    public function obtenerInfoHotel($idHotel)
    {
        $sql = "SELECT id_hotel, nombre, img AS imagen, comunidad_autonoma, descripcion 
                FROM hoteles 
                WHERE id_hotel = ?";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $this->db->error);
        }
        $stmt->bind_param("i", $idHotel);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error ejecutando la consulta: " . $this->db->error);
        }
    
        return $result->fetch_assoc(); // Retornamos solo un registro
    }
    /**
 * Retorna los servicios de un hotel dado su nombre (o id_hotel).
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
