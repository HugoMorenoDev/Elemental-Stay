<?php

// Habilitamos la visualización de errores para desarrollo (quitar en producción)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluimos el FrontController
require 'libs/FrontController.php';

// Iniciamos el FrontController con su método estático main.
FrontController::main();
?>
