<?php
// models/ReservasMisReservasModel.php
require_once __DIR__ . '/../libs/SMysqli.php';

class ReservasMisReservasModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SMysqli::singleton()->getConnection();
    }

    /**
     * Devuelve todas las reservas del usuario dado.
     *
     * @param int $idUsuario
     * @return array
     */
    public function obtenerPorUsuario(int $idUsuario): array
    {
        $sql = "
            SELECT
              r.id_reserva,
              rh.idreservashab,
              h.nombre       AS hotel,
              h.img          AS imagen_hotel,
              ha.nombre      AS habitacion,
              ha.descripcion AS descripcion_habitacion,
              r.dia_entrada,
              r.dia_salida,
              r.precio
            FROM reservas r
            LEFT JOIN reservashab rh ON r.id_reserva = rh.id_reserva
            LEFT JOIN hoteles       h  ON r.id_hotel    = h.id_hotel
            LEFT JOIN habitaciones  ha ON rh.idhabitacion = ha.id_habitacion
            WHERE r.id_usuario = ?
            ORDER BY r.dia_salida ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();

        // Logging para saber cuántas filas trae la consulta
        $result = $stmt->get_result();
        error_log("DEBUG obtenerPorUsuario($idUsuario): filas obtenidas = " . $result->num_rows);

        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $rows;
    }

    /**
     * Elimina una reserva de la base de datos.
     *
     * @param int $id_reserva
     * @param int $idreservashab
     * @return bool
     */
    public function eliminarReserva($id_reserva, $idreservashab)
    {
        $this->db->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->db->begin_transaction();

        try {
            $sql1 = "DELETE FROM reservashab WHERE idreservashab = ?";
            $stmt1 = $this->db->prepare($sql1);
            $stmt1->bind_param("i", $idreservashab);
            $stmt1->execute();

            $sql2 = "DELETE FROM reservas WHERE id_reserva = ?";
            $stmt2 = $this->db->prepare($sql2);
            $stmt2->bind_param("i", $id_reserva);
            $stmt2->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            error_log('Error en eliminarReserva: ' . $e->getMessage());
            return false;
        }
    }

/**
 * Devuelve todos los datos de una reserva, incluyendo detalles del hotel, habitación, parking y mascota.
 *
 * @param int $idReserva
 * @return array|null
 */
public function obtenerDetalleReserva(int $idReserva): ?array
{
    $sql = "
    SELECT
      r.id_reserva,
      r.id_usuario,
      rh.idreservashab,

      /* Datos del hotel */
      h.nombre             AS hotel,
      h.tipo_hotel         AS tipo_hotel,
      h.comunidad_autonoma AS comunidad_autonoma_hotel,
      h.descripcion        AS descripcion_hotel,
      h.precio_noche       AS precio_noche_hotel,
      h.estrellas          AS estrellas_hotel,
      h.img                AS imagen_hotel,

      /* Datos de la habitación estándar */
      ha.nombre            AS habitacion,
      ha.descripcion       AS descripcion_habitacion,
      ha.capacidad         AS capacidad_habitacion,
      ha.m2                AS metros_cuadrados,
      ha.wifi              AS wifi_habitacion,

      /* Si usas datos específicos guardados en reservashab */
      rh.nombrehab         AS nombre_hab_custom,
      rh.capacidad         AS capacidad_hab_custom,
      rh.mascota           AS admite_mascota,

      /* Datos de la propia reserva */
      r.precio,
      r.dia_entrada,
      r.dia_salida
    FROM reservas       r
    LEFT JOIN reservashab rh ON r.id_reserva     = rh.id_reserva
    LEFT JOIN hoteles       h  ON r.id_hotel      = h.id_hotel
    LEFT JOIN habitaciones  ha ON r.id_habitacion = ha.id_habitacion
    WHERE r.id_reserva = ?
    LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $idReserva);
    $stmt->execute();
    $detalle = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    return $detalle ?: null;
}




}

?>
