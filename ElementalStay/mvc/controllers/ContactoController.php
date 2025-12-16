<?php
// mvc/controllers/ContactoController.php

require_once __DIR__ . '/../models/ContactoModel.php';

class ContactoController {
    private $view;
    private $contactoModel;

    public function __construct()
    {
        // Instanciamos el motor de plantillas y el modelo de Contacto
        $this->view         = new View();
        $this->contactoModel = new ContactoModel();
    }

    /**
     * Muestra el formulario de contacto.
     */
    public function formulario()
    {
        $this->view->show('Contacto/contactoForm.php');
    }

    /**
     * Procesa el envío del formulario e inserta el contacto.
     */
    public function insertar()
    {
        // Solo procesar POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ./index.php?controlador=Contacto&accion=formulario');
            exit;
        }

        // Recoger datos del formulario
        $nombre  = trim($_POST['name']    ?? '');
        $correo  = trim($_POST['email']   ?? '');
        $mensaje = trim($_POST['message'] ?? '');

        // Llamar al modelo para insertar en BD
        $ok = $this->contactoModel->insertarContacto($nombre, $correo, $mensaje);

        if ($ok) {
            // Redirigir a confirmación
            header('Location: ./index.php?controlador=Contacto&accion=confirmacion');
            exit;
        }

        // En caso de error, mostrar mensaje
        echo 'Error al enviar el mensaje';
    }

    /**
     * Muestra la página de confirmación de envío.
     */
    public function confirmacion()
    {
        $this->view->show('Contacto/ConfirmaContacto.php');
    }
}
