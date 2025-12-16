<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Elemental Stay</title>
    <link rel="stylesheet" href="../CSS/footer/footer.css">
    <link rel="stylesheet" href="../CSS/contactos/contactos.css"> 
    <link rel="stylesheet" href="../CSS/header/header.css">
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
            <li><a href="../mvc/index.php?controlador=Hoteles&accion=listarHotelesSinFiltros" id="boton-superior"><b>HOTELES</b></a></li>
            <li><a href="../contactos/contactos.php" id="boton-superior"><b>CONTACTO</b></a></li>
            
            <!-- Verificación de sesión -->
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

    <script>
        function toggleProfileMenu(event) {
            event.stopPropagation();
            var menu = document.getElementById('profileMenu');
            menu.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('#profileButton') && !event.target.matches('.profile-menu') && !event.target.closest('.profile')) {
                var dropdowns = document.getElementsByClassName('profile-menu');
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };
    </script>


    <!-- Contenedor principal -->
    <main class="contact-container">
        <h1>Contáctanos</h1>
        <p>¿Tienes algún comentario, duda o problema? Completa el formulario a continuación y nos pondremos en contacto contigo lo antes posible.</p>

        <!-- Formulario de contacto -->
        <form class="contact-form" action="../mvc/index.php?controlador=Contacto&accion=insertar" method="post">
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" placeholder="Escribe tu nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="correo@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="message">Mensaje</label>
                <textarea id="message" name="message" placeholder="Escribe tu comentario o problema aquí..." rows="5" required></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar</button>
        </form>
    </main>

      <!-- --------------------- ENLACE FOOTER -------------------  -->
      <?php include '../footer/footer.php'; ?>

    <script src="../JS/Header.js"></script>
</body>
</html>
