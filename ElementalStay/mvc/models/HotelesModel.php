<?php
class HotelesModel
{
    protected $db;

    public function __construct()
    {
        $this->db = SMysqli::singleton()->getConnection();
    }

    public function listadoHotelesTotal() : array
    {
        $sql = "
            SELECT h.*, MIN(t.precio) AS min_precio
            FROM hoteles h
            INNER JOIN habitaciones ha ON h.id_hotel = ha.id_hotel
            INNER JOIN tarifas t ON ha.id_hotel = t.id_hotel AND ha.id_categoria = t.id_categoria
            GROUP BY h.id_hotel
        ";

        $result = $this->db->query($sql);
        if (!$result) {
            throw new Exception("Error en la consulta listadoHotelesTotal: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function buscarHoteles(
        string $destino = null,
        int $totalPersonas = null,
        string $entrada = null,
        string $salida = null,
        float $precioMin = null,
        float $precioMax = null
    ) : array {
        // Base de la consulta
        $sql = "
            SELECT
                h.*, MIN(t.precio) AS min_precio
            FROM
                hoteles h
            INNER JOIN
                habitaciones ha ON h.id_hotel = ha.id_hotel
            INNER JOIN
                tarifas t ON ha.id_hotel = t.id_hotel
                           AND ha.id_categoria = t.id_categoria
            WHERE 1=1
        ";

        $params = [];
        $types = "";

        // Filtro de comunidad_autonoma
        if (!empty($destino)) {
            $sql .= " AND h.comunidad_autonoma = ?";
            $types .= "s";
            $params[] = $destino;
        }

        // Filtro de capacidad
        if (!empty($totalPersonas)) {
            $sql .= " AND ha.capacidad >= ?";
            $types .= "i";
            $params[] = $totalPersonas;
        }

        // Disponibilidad de fechas: no overlap con reservas existentes
        if (!empty($entrada) && !empty($salida)) {
            $sql .= " AND NOT EXISTS (
                        SELECT 1
                        FROM reservas r
                        WHERE r.id_hotel = h.id_hotel
                          AND r.id_habitacion = ha.id_habitacion
                          AND r.dia_entrada < ? AND r.dia_salida > ?
                    )";
            $types .= "ss";
            $params[] = $salida;
            $params[] = $entrada;
        }

        // Agrupar antes de filtrar precio
        $sql .= " GROUP BY h.id_hotel";

        // Rango de precio usando HAVING sobre el mínimo de tarifas
        if (!is_null($precioMin) && !is_null($precioMax)) {
            $sql .= " HAVING min_precio BETWEEN ? AND ?";
            $types .= "dd";
            $params[] = $precioMin;
            $params[] = $precioMax;
        } elseif (!is_null($precioMin)) {
            $sql .= " HAVING min_precio >= ?";
            $types .= "d";
            $params[] = $precioMin;
        } elseif (!is_null($precioMax)) {
            $sql .= " HAVING min_precio <= ?";
            $types .= "d";
            $params[] = $precioMax;
        }

        // Preparar, bind y ejecutar
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $this->db->error);
        }

        if ($types !== "") {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception("Error en la consulta: " . $this->db->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
