<?php
require_once 'libs/SMysqli.php';

class ReservaslistarModel {
    protected $db;

    public function __construct() {
        $this->db = SMysqli::singleton()->getConnection();
        $this->ensureProcedureExists();
    }

    /**
     * Crea el procedimiento si no existe, usando multi_query.
     */
    protected function ensureProcedureExists() {
        $sql = "
        DROP PROCEDURE IF EXISTS sp_insertar_reserva;
        CREATE PROCEDURE sp_insertar_reserva(
        IN p_idHotel INT,
        IN p_idHabitacion INT,
        IN p_idUsuario INT,
        IN p_precio DECIMAL(10,2),
        IN p_entrada DATETIME,
        IN p_salida DATETIME
        )
        BEGIN
        DECLARE newReserva INT;

        -- Inserta la reserva en la tabla 'reservas'
        INSERT INTO reservas
            (id_hotel, id_habitacion, id_usuario, precio, dia_entrada, dia_salida)
        VALUES
            (p_idHotel, p_idHabitacion, p_idUsuario, p_precio, p_entrada, p_salida);

        -- Obtiene el ID de la nueva reserva
        SET newReserva = LAST_INSERT_ID();

        -- Inserta la relación entre la reserva y la habitación en 'reservashab'
        INSERT INTO reservashab
            (id_reserva, idhotel, idhabitacion, nombrehab, capacidad, mascota)
        SELECT
            newReserva,  -- Aquí agregamos el 'id_reserva'
            r.id_hotel,
            r.id_habitacion,
            LEFT(h.nombre, 30),  -- Limita el nombre de la habitación a 30 caracteres
            h.capacidad,
            0  -- Asume que el valor de mascota es 0 por defecto
        FROM reservas r
        JOIN habitaciones h ON h.id_habitacion = r.id_habitacion
        WHERE r.id_reserva = newReserva;

        -- Devuelve el ID de la nueva reserva
        SELECT newReserva AS id_reserva;
        END

        ";
        // Ejecuta ambos: DROP + CREATE
        $this->db->multi_query($sql);
        // Limpia resultados
        do {
            if ($result = $this->db->store_result()) {
                $result->free();
            }
        } while ($this->db->more_results() && $this->db->next_result());
    }

    /**
     * Valida correo/contraseña. Devuelve id_usuario o false.
     */
    public function validarUsuario(string $correo, string $contrasena) {
        $sql = "SELECT id_usuario, contraseña FROM usuarios WHERE correo = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$res) return false;
        return password_verify($contrasena, $res['contraseña'])
             ? (int)$res['id_usuario']
             : false;
    }

    /**
     * Llama al procedimiento para crear la reserva. Devuelve id_reserva.
     */
    public function crearReserva(
        int $idHotel,
        int $idHabitacion,
        int $idUsuario,
        float $precio,
        string $entrada,
        string $salida
    ) {
        $sql = "CALL sp_insertar_reserva(?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iiidss",
            $idHotel,
            $idHabitacion,
            $idUsuario,
            $precio,
            $entrada,
            $salida
        );
        $stmt->execute();

        // Leer el SELECT newReserva AS id_reserva
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        // Limpia cualquier resultado pendiente
        while ($this->db->more_results()) {
            $this->db->next_result();
            if ($res = $this->db->store_result()) {
                $res->free();
            }
        }

        return $row['id_reserva'] ?? null;
    }

    /**
     * Obtiene detalles de reserva y servicios.
     */
    public function obtenerDetallesReserva(int $idReserva) {
        // Reserva + habitación
        $sql1 = "SELECT r.id_reserva, r.dia_entrada, r.dia_salida, r.precio,
                         h.nombre AS habitacion, h.capacidad
                  FROM reservas r
                  JOIN habitaciones h ON h.id_habitacion = r.id_habitacion
                  WHERE r.id_reserva = ?";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param("i", $idReserva);
        $stmt1->execute();
        $reserva = $stmt1->get_result()->fetch_assoc();
        $reserva['dia_entrada'] = date('Y-m-d', strtotime($reserva['dia_entrada']));
        $reserva['dia_salida']  = date('Y-m-d', strtotime($reserva['dia_salida']));

        $stmt1->close();

        // Servicios reservados
        $sql2 = "SELECT s.nombre, rs.cantidad
                 FROM reservasservicios rs
                 JOIN servicios s ON s.id_servicio = rs.idServicio
                 WHERE rs.idReserva = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("i", $idReserva);
        $stmt2->execute();
        $servicios = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt2->close();

        return [
            'reserva'   => $reserva,
            'servicios' => $servicios
        ];
    }
}
?>
