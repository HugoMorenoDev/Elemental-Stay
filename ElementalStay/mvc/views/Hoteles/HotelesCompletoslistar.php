<?php


// Recoger variables: primero se comprueba si existen en el array del controlador (por ejemplo, $hotelInfo, $entrada, etc.), 
// y en caso contrario se recogen desde $_GET.

// Datos generales
$idHotel  = isset($hotelInfo['id_hotel'])
             ? $hotelInfo['id_hotel']
             : (isset($_GET['id']) ? intval($_GET['id']) : 0);

$entrada  = isset($entrada)
             ? $entrada
             : (isset($_GET['entrada']) ? $_GET['entrada'] : '');

$salida   = isset($salida)
             ? $salida
             : (isset($_GET['salida']) ? $_GET['salida'] : '');

$adultos  = isset($adultos)
             ? $adultos
             : (isset($_GET['adultos']) ? intval($_GET['adultos']) : 0);

$ninos    = isset($ninos)
             ? $ninos
             : (isset($_GET['ninos']) ? intval($_GET['ninos']) : 0);

// Para compatibilidad con el código existente, definimos también $filtroEntrada y $filtroSalida
$filtroEntrada = isset($filtroEntrada) ? $filtroEntrada : $entrada;
$filtroSalida  = isset($filtroSalida)  ? $filtroSalida  : $salida;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Elemental Stay - Hoteles Información completa</title>
  <link rel="stylesheet" href="../CSS/header/header.css"/>
  <link rel="stylesheet" href="../CSS/hoteles/hoteles_completos.css"/>
  <link rel="stylesheet" href="../CSS/footer/footer.css"/>
  <link rel="shortcut icon" href="../img/logo.png"/>
</head>
<body>
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
        <!-- Mostrar información básica del hotel -->
        <section class="hotel-info">
            <?php if (!empty($hotelInfo['imagen'])): ?>
            <div class="hotel-info__image">
                <img src="<?= $config->get('imagePath') . $hotelInfo['imagen'] ?>" alt="Imagen de <?= htmlspecialchars($hotelInfo['nombre']) ?>">
            </div>
            <?php endif; ?>
            <h2><?= htmlspecialchars($hotelInfo['nombre']) ?></h2>
            <div class="hotel-info__community">
                <?= htmlspecialchars($hotelInfo['comunidad_autonoma']) ?>
            </div>
            <p><?= htmlspecialchars($hotelInfo['descripcion']) ?></p>
            <p>Reserva para: <strong><?= $entrada ?></strong> - <strong><?= $salida ?></strong></p>
            <p>Adultos: <strong><?= $adultos ?></strong>, Niños: <strong><?= $ninos ?></strong></p>
            <!-- ------------------------ SERVICIOS --------------------- -->
            <?php if (!empty($servicios)): ?>
            <section class="hotel-services">
            <h3>Servicios del Hotel</h3>
            <div class="services-grid">
                <?php foreach ($servicios as $serv): ?>
                <div class="service-item">
                    <!-- Aquí podrías usar un icono genérico o uno específico según $serv['nombre'] -->
                    <span class="service-icon">🏨</span>
                    <span class="service-name"><?= htmlspecialchars($serv['nombre']) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            </section>
            <?php endif; ?>

        </section>

        <?php 
            $rutaBase = "http://localhost/ElementalStayMayo/ElementalStay/mvc/img_habitaciones/"; // Ruta absoluta
        ?>

        <!-- Mostrar habitaciones disponibles -->
        <section class="habitaciones-disponibles">
            <h3>Habitaciones Disponibles</h3>
            <?php if (!empty($habitaciones)): ?>
                <?php foreach ($habitaciones as $index => $hab): ?>
                    <article class="habitacion-card">
                        <div class="habitacion-card__container">
                            
                            <!-- Carrusel de imágenes -->
                            <div class="carousel" id="carousel-<?= $index ?>">
                                <button class="prev" onclick="changeImage(-1, <?= $index ?>)">&#10094;</button>
                                <div class="carousel-images">
                                    <?php 
                                    if (!empty($hab['imagenes'])):
                                        $imagenes = array_unique(explode(',', $hab['imagenes']));
                                        foreach ($imagenes as $i => $imagen): 
                                            $rutaImagen = $rutaBase . trim($imagen);
                                    ?>
                                            <img src="<?= $rutaImagen ?>" class="<?= $i === 0 ? 'active' : '' ?>" alt="Imagen de <?= htmlspecialchars($hab['nombre']) ?>">
                                    <?php 
                                        endforeach; 
                                    endif; 
                                    ?>
                                </div>
                                <button class="next" onclick="changeImage(1, <?= $index ?>)">&#10095;</button>
                            </div>

                            <!-- Bloque de contenido a la derecha -->
                            <div class="habitacion-card__content">
                                <h4><?= htmlspecialchars($hab['nombre']) ?></h4>
                                <p>Categoría: <?= htmlspecialchars($hab['nombre_categoria']) ?></p>
                                <p>Capacidad: <?= $hab['capacidad'] ?></p>
                                <p>Precio: <?= number_format($hab['precio'], 2) ?> €</p>
                                <div class="habitacion-card__details">
                                    <span><?= $hab['m2'] ?> m²</span>
                                    <span class="<?= $hab['wifi'] ? 'wifi' : 'no-wifi' ?>">
                                        <?= $hab['wifi'] ? 'Con WiFi' : 'Sin WiFi' ?>
                                    </span>
                                </div>
                                <p><strong>Descripción:</strong> <?= htmlspecialchars($hab['descripcion']) ?></p>
                                <a class="hotel-card__btn" href="index.php?controlador=ReservasForm&accion=listarReservasDisponibles&id=<?= $idHotel ?>&id_habitacion=<?= $hab['id_habitacion'] ?>&entrada=<?= urlencode($entrada) ?>&salida=<?= urlencode($salida) ?>&adultos=<?= $adultos ?>&ninos=<?= $ninos ?>&nombre_habitacion=<?= urlencode($hab['nombre']) ?>&precio=<?= urlencode(number_format($hab['precio'], 2)) ?>">
                                    Reservar
                                </a>
                            </div>

                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay habitaciones disponibles para estas fechas.</p>
            <?php endif; ?>
        </section>

        <!-- Script del carrusel -->
        <script>
            function changeImage(direction, index) {
                const carousel = document.getElementById(`carousel-${index}`);
                const images = carousel.querySelectorAll(".carousel-images img");
                let activeIndex = Array.from(images).findIndex(img => img.classList.contains("active"));

                images[activeIndex].classList.remove("active");

                let nextIndex = activeIndex + direction;
                if (nextIndex >= images.length) nextIndex = 0;
                if (nextIndex < 0) nextIndex = images.length - 1;

                images[nextIndex].classList.add("active");
            }
        </script>
    </main>

    <!-- --------------------- ENLACE FOOTER -------------------  -->
    <?php include '../footer/footer.php'; ?>

    <!-- ------------------------ SCRIPTS --------------------- -->
    <script src="../JS/Header.js"></script>
    <script src="../JS/hoteles.js"></script>
</body>
</html>