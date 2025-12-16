<?php
// mvc/models/ContactoModel.php

class ContactoModel {
    private $db;

    public function __construct()
    {
        // Usamos tu singleton SMysqli para obtener la conexión
        $this->db = SMysqli::singleton()->getConnection();
    }

    /**
     * Inserta un mensaje en la tabla `contacto`.
     * @return bool
     */
    public function insertarContacto(string $nombre, string $correo, string $mensaje): bool {
        $sql  = "INSERT INTO contacto (nombre, correo, mensaje) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param('sss', $nombre, $correo, $mensaje);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
