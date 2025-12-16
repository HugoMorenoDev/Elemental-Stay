<?php
// mvc/views/ReservasUsuario/ReservasMisReservas.php
?>
<!DOCTYPE html>
<html lang="es">
<meta charset="UTF-8">
<title>Mis Reservas – ElementalStay</title>
<link rel="stylesheet" href="../CSS/MisReservas/MisReservas.css">
<link rel="stylesheet" href="../CSS/footer/footer.css">
<link rel="stylesheet" href="../CSS/header/header.css">
<link rel="shortcut icon" href="../img/logo.png"/>
<body>
    <!------------------------ MENÚ DE LINKS ----------------------->
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
            <li><a href="../mvc/index.php?controlador=Hoteles&accion=listarHotelesSinFiltros" id="boton-superior"><b>HOTELES</b></a></li>
            <li><a href="../contactos/contactos.php" id="boton-superior"><b>CONTACTO</b></a></li>
    

            <!-- Aquí verificamos si la sesión está activa -->
            <?php if (isset($_SESSION['usuario'])): ?>
                <!-- Mostrar 'PERFIL' si la sesión está activa -->
                <li class="profile">
                    <a href="javascript:void(0);" onclick="toggleProfileMenu(event)" id="profileButton"><b>PERFIL</b></a>
                    <!-- Menú desplegable de opciones de perfil -->
                    <div class="profile-menu" id="profileMenu">
                        <a href="../perfil/perfil.php">Ver Perfil</a>
                        <a href="../inicio_session/logout.php" class="logout">Cerrar Sesión</a>
                    </div>
                </li>
            <?php else: ?>
                <!-- Si no hay sesión activa, mostrar el botón de "Iniciar sesión" -->
                <li><a href="../inicio_session/inicio_session.php" id="boton-superior"><b>INICIAR SESIÓN</b></a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- SCRIPT DEL DESPLEGABLE DEL PERIL -->
    <script>
        // Función para alternar la visibilidad del menú de perfil
        function toggleProfileMenu(event) {
            event.stopPropagation();  // Evita que el clic se propague a otras partes de la página
            var menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');  // Alterna la clase 'show' para mostrar/ocultar el menú
        }

        // Cerrar el menú si se hace clic fuera de él
        window.onclick = function(event) {
            // Verificamos si el clic NO fue sobre el enlace 'PERFIL' ni el menú desplegable
            if (!event.target.matches('#profileButton') && !event.target.matches('.profile-menu') && !event.target.closest('.profile')) {
                var dropdowns = document.getElementsByClassName('profile-menu');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');  // Oculta el menú
                    }
                }
            }
        }
    </script>


    <!-- CONTENEDOR PRINCIPAL PARA MOSTRAR INFORMACIÓN DE LAS RESERVAS DEL USUARIO -->
    <div class="wrapper">
        <h1>Mis Reservas</h1>

        <div style="text-align: left; margin-bottom: 20px;">
            <a href="/ElementalStayMayo/ElementalStay/index.php" class="btn btn-details">
                ← Volver atrás
            </a>
        </div>

        <!-- Verifica si no hay reservas -->
        <?php if (empty($reservas)): ?>
            <div class="reservas-empty">No tienes reservas registradas.</div>
        <?php else: ?>
            <!-- Si hay reservas, se muestran aquí -->
            <div class="reservas-container">
                <?php foreach ($reservas as $res): ?>
                    <div class="reserva-card">
                        <!-- Imagen del hotel si está disponible -->
                        <?php if (!empty($res['imagen_hotel'])): ?>
                            <img src="<?= htmlspecialchars($res['imagen_hotel'] ?? '') ?>" alt="Hotel <?= htmlspecialchars($res['hotel'] ?? '') ?>">
                        <?php endif; ?>
                        <div class="reserva-details">
                            <h2><?= htmlspecialchars($res['hotel'] ?? 'Información no disponible') ?></h2>
                            <div class="reserva-info">
                                <p><span>Habitación:</span> <?= htmlspecialchars($res['habitacion'] ?? 'No especificada') ?></p>
                                <p class="descripcion"><span>Descripción:</span> <?= htmlspecialchars($res['descripcion_habitacion'] ?? 'No disponible') ?></p>
                                <p><span>Precio:</span> €<?= number_format($res['precio'], 2) ?></p>
                            </div>
                            <div class="reserva-footer">
                                <div class="fecha">
                                    Entrada: <?= date('d/m/Y', strtotime($res['dia_entrada'])) ?> |
                                    Salida: <?= date('d/m/Y', strtotime($res['dia_salida'])) ?>
                                </div>
                                <div class="btn-group">
                                    <!-- Enlace para ver más detalles -->
                                    <a href="index.php?controlador=ReservasMisReservas&accion=verDetallesReserva&id_reserva=<?= $res['id_reserva'] ?>"
                                        class="btn btn-details">
                                        Ver Más Detalles
                                    </a>

                                    <!-- Formulario para cancelar la reserva -->
                                    <form action="index.php?controlador=ReservasMisReservas&accion=cancelarReserva"
                                        method="POST"
                                        style="display:inline;"
                                        onsubmit="return confirm('¿Estás seguro de que quieres cancelar esta reserva?');">
                                        <input type="hidden" name="id_reserva"    value="<?= $res['id_reserva'] ?>">
                                        <input type="hidden" name="idreservashab" value="<?= $res['idreservashab'] ?>">
                                        <button type="submit" class="btn btn-cancel">Cancelar Reserva</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- --------------------- ENLACE FOOTER -------------------  -->
    <?php include '../footer/footer.php'; ?>

    <script src="../JS/Header.js"></script>
</body>
</html>
