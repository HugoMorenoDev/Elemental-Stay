

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Elemental Stay - Hoteles seleccionados</title>
    <link rel="stylesheet" href="../CSS/header/header.css"/>
    <link rel="stylesheet" href="../CSS/hoteles/hoteles.css"/>
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
    
    <div class="wrapper">
    <!-- --------------------- BARRA DE BÚSQUEDA --------------------- -->

    
    <!-- --------------------- CONTENEDOR PRINCIPAL --------------------- -->
    <?php
    // Recuperar los filtros aplicados (puedes adaptarlo según tu lógica de aplicación)
    $filtroComunidad = isset($_GET['comunidad']) ? htmlspecialchars($_GET['comunidad']) : '';
    $filtroEntrada   = isset($_GET['entrada'])   ? htmlspecialchars($_GET['entrada'])   : '';
    $filtroSalida    = isset($_GET['salida'])    ? htmlspecialchars($_GET['salida'])    : '';
    $filtroAdultos   = isset($_GET['adultos'])   ? htmlspecialchars($_GET['adultos'])   : '';
    $filtroNinos     = isset($_GET['ninos'])     ? htmlspecialchars($_GET['ninos'])     : '';
    ?>
    <main>
        <div class="container">
            <!-- Sección de resultados -->
            <section class="results" id="hotel-list">
                <?php if (!empty($listado) && is_array($listado)): ?>
                    <?php foreach ($listado as $hotel): 
                        $imagen      = $config->get('imagePath') . $hotel['img'];
                        $nombre      = htmlspecialchars($hotel['nombre']);
                        $comunidad   = htmlspecialchars($hotel['comunidad_autonoma']);
                        $precio      = number_format($hotel['min_precio'], 2);
                        $idHotel     = htmlspecialchars($hotel['id_hotel']);
                        $descripcion = htmlspecialchars($hotel['descripcion']);
                        $estrellas   = (int) $hotel['estrellas'];
                    ?>
                        <article class="hotel-card" data-hotel="<?= $nombre ?>">
                            <!-- Imagen del hotel -->
                            <div class="hotel-card__image">
                                <?php if (file_exists($imagen)): ?>
                                    <img src="<?= $imagen ?>" alt="Imagen de <?= $nombre ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="ruta_a_una_imagen_por_defecto.jpg" alt="Imagen no disponible" loading="lazy">
                                <?php endif; ?>
                            </div>

                            <!-- Contenido / Información del hotel -->
                            <div class="hotel-card__content">
                                <div class="hotel-card__header">
                                    <h3 class="hotel-card__name"><?= $nombre ?></h3>
                                    <p class="hotel-card__stars">
                                        <?php for ($i = 0; $i < $estrellas; $i++): ?>
                                            <span>⭐</span>
                                        <?php endfor; ?>
                                    </p>
                                </div>

                                <!-- Descripción extendida -->
                                <p class="hotel-card__description"><?= $descripcion ?></p>

                                <!-- Detalles adicionales -->
                                <ul class="hotel-card__details">
                                    <li><strong>Comunidad:</strong> <?= $comunidad ?></li>
                                    <li><strong>Precio:</strong> Desde <?= $precio ?>€ <span class="incluye">Incluye impuestos y cargos</span></li>
                                </ul>

                                <!-- Botón dinámico según origen -->
                                <div class="hotel-card__footer">
                                    <?php if (!empty($sinFiltros)): ?>
                                        <!-- Si venimos de listarHotelesSinFiltros -->
                                        <a href="/ElementalStayMayo/ElementalStay/index.php" class="hotel-card__btn">Ir a Reservar</a>
                                    <?php else: ?>
                                        <!-- Si venimos de la búsqueda filtrada -->
                                        <a href="index.php?controlador=HotelesCompletos&accion=listarHabitacionesDisponibles&
                                                id=<?= $idHotel ?>
                                                &entrada=<?= urlencode($filtroEntrada) ?>
                                                &salida=<?= urlencode($filtroSalida) ?>
                                                &adultos=<?= $filtroAdultos ?>
                                                &ninos=<?= $filtroNinos ?>"
                                           class="hotel-card__btn">
                                            Ver más
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-results">No se encontraron hoteles disponibles.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>


   <!-- --------------------- ENLACE FOOTER -------------------  -->
   <?php include '../footer/footer.php'; ?>

    <!-- ------------------------ SCRIPTS --------------------- -->
    <script src="../JS/Header.js"></script>
    <script src="../JS/hoteles.js"></script>
</body>
</html>