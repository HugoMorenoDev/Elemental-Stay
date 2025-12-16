<?php
// Recoger variables desde $_GET o $hotelInfo
$idHotel           = isset($hotelInfo['id_hotel']) ? $hotelInfo['id_hotel'] : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$entrada           = isset($entrada) ? $entrada : (isset($_GET['entrada']) ? $_GET['entrada'] : '');
$salida            = isset($salida)  ? $salida  : (isset($_GET['salida'])  ? $_GET['salida']  : '');
$adultos           = isset($adultos) ? $adultos : (isset($_GET['adultos']) ? intval($_GET['adultos']) : 0);
$ninos             = isset($ninos)   ? $ninos   : (isset($_GET['ninos'])   ? intval($_GET['ninos'])   : 0);

// Variables habitación desde GET
$idHabitacion      = isset($_GET['id_habitacion'])     ? intval($_GET['id_habitacion'])     : 0;
$nombreHabitacion  = isset($_GET['nombre_habitacion']) ? $_GET['nombre_habitacion']        : '';
$precio            = isset($_GET['precio'])            ? $_GET['precio']                    : '';

// Variables hotel desde GET o $hotelInfo
$hotelName         = isset($hotelInfo['nombre'])          ? htmlspecialchars($hotelInfo['nombre'])           : (isset($_GET['hotelName'])       ? htmlspecialchars($_GET['hotelName'])       : 'N/A');
$hotelDesc         = isset($hotelInfo['descripcion'])     ? htmlspecialchars($hotelInfo['descripcion'])      : (isset($_GET['hotelDesc'])       ? htmlspecialchars($_GET['hotelDesc'])       : 'N/A');
$hotelCommunity    = isset($hotelInfo['comunidad_autonoma']) ? htmlspecialchars($hotelInfo['comunidad_autonoma']) : (isset($_GET['hotelCommunity']) ? htmlspecialchars($_GET['hotelCommunity']) : 'N/A');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Reservas - Información Completa</title>
  <link rel="stylesheet" href="../CSS/header/header.css"/>
  <link rel="stylesheet" href="../CSS/FormReservas/reservasForm.css"/>  <!-- CSS del formulario de reservas -->
  <link rel="stylesheet" href="../CSS/footer/footer.css"/>
  <link rel="shortcut icon" href="../img/logo.png"/>
</head>

<body>
    <!-- Navegación -->
    <!-- ------------------------ NAV PRINCIPAL + OPCIONES --------------------- -->
    <nav>
        <div class="logo">
            <img src="../img/logo.png" alt="Logo">
            <h1 class="logo-title">ELEMENTAL STAY</h1>
        </div>

        <!-- ------ MENU HAMBURGUESA ------ -->
        <input type="checkbox" id="menu-toggle" class="menu-toggle" />
        <label for="menu-toggle" class="menu-icon">
          <span></span>
          <span></span>
          <span></span>
        </label>

        <ul>
            <li><a href="../index.php" id="boton-superior"><b>INICIO</b></a></li>
            <li><a href="../mvc/index.php?controlador=ReservasMisReservas&accion=listar" class="boton-superior"><b>RESERVAS</b></a></li>      
            <li><a href="./index.php?controlador=Hoteles&accion=listarHotelesSinFiltros" id="boton-superior"><b>HOTELES</b></a></li>   
            <li><a href="../contactos/contactos.php" id="boton-superior"><b>CONTACTO</b></a></li>
            <?php if (isset($_SESSION['usuario'])): ?>
                <li class="profile">
                    <a href="javascript:void(0);" onclick="toggleProfileMenu(event)" id="profileButton"><b>PERFIL</b></a>
                    <div class="profile-menu" id="profileMenu">
                        <a href="../perfil/perfil.php">Ver Perfil</a>
                        <a href="../inicio_session/logout.php" class="logout">Cerrar Sesión</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a href="../inicio_session/inicio_session.php" id="boton-superior"><b>INICIAR SESIÓN</b></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- ------------------------ SCRIPT DEL DESPLEGABLE DEL PERFIL --------------------- -->
    <script>
        function toggleProfileMenu(event) {
            event.stopPropagation();
            var menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }
        window.onclick = function(event) {
            if (!event.target.matches('#profileButton') && !event.target.closest('.profile')) {
                var dropdowns = document.getElementsByClassName('profile-menu');
                for (var i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
    </script>

    <main>
        <div class="reservation-box">
            <h2>Información del Hotel</h2>
            <p><strong>Nombre:</strong> <?= $hotelName ?></p>
            <p><strong>Comunidad:</strong> <?= $hotelCommunity ?></p>
            <p><strong>Descripción:</strong> <?= $hotelDesc ?></p>
            <p><strong>Fechas de Reserva:</strong> <?= htmlspecialchars($entrada) ?> - <?= htmlspecialchars($salida) ?></p>
            <p><strong>Adultos:</strong> <?= htmlspecialchars($adultos) ?></p>
            <p><strong>Niños:</strong> <?= htmlspecialchars($ninos) ?></p>

            <!-- ------------------------ SERVICIOS --------------------- -->
            <?php if (!empty($servicios)): ?>
            <section class="hotel-services">
            <h3>Servicios del Hotel</h3>
            <div class="services-grid">
                <?php foreach ($servicios as $serv): ?>
                <div class="service-item">
                <span class="service-icon">🏨</span>
                <span class="service-name"><?= htmlspecialchars($serv['nombre']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            </section>
            <?php endif; ?>
            <!-- -------------------------------------------------------- -->

            <h2>Información Habitación</h2>
            <p><strong>Nombre de la habitación:</strong> <?= htmlspecialchars($nombreHabitacion) ?></p>
            <p><strong>Precio:</strong> <?= htmlspecialchars($precio) ?> €</p>
            <hr>

            <h3>Completa tu Reserva</h3>
            <form action="index.php?controlador=Reservaslistar&accion=guardarReserva" method="POST">
                <input type="hidden" name="id_hotel"           value="<?= htmlspecialchars($idHotel) ?>">
                <input type="hidden" name="id_habitacion"      value="<?= htmlspecialchars($idHabitacion) ?>">
                <input type="hidden" name="nombre_habitacion"  value="<?= htmlspecialchars($nombreHabitacion) ?>">
                <input type="hidden" name="precio"             value="<?= htmlspecialchars($precio) ?>">
                <input type="hidden" name="entrada"            value="<?= htmlspecialchars($entrada) ?>">
                <input type="hidden" name="salida"             value="<?= htmlspecialchars($salida) ?>">
                <input type="hidden" name="adultos"            value="<?= htmlspecialchars($adultos) ?>">
                <input type="hidden" name="ninos"              value="<?= htmlspecialchars($ninos) ?>">

                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required>

                <button type="submit">Enviar Reserva</button>
            </form>
        </div>
    </main>
    <!-- --------------------- ENLACE FOOTER -------------------  -->
    <?php include '../footer/footer.php'; ?>

    <script src="../JS/Header.js"></script>
</body>
</html>
