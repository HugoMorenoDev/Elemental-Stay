<?php 
    error_reporting(E_ALL & ~E_NOTICE);
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <title>Reserva Confirmada</title>
  <link rel="stylesheet" href="../CSS/header/header.css">
  <link rel="stylesheet" href="../CSS/FormReservas/ReservaCompletada.css">
  <link rel="stylesheet" href="../CSS/footer/footer.css">
  <link rel="shortcut icon" href="../img/logo.png"/>
</head>

<body class="reservation-page"> 
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
      <h2>¡Tu reserva ha sido realizada!</h2>
      <p><strong>Habitación:</strong> <?= htmlspecialchars($reserva['habitacion']) ?></p>
      <p><strong>Capacidad:</strong> <?= htmlspecialchars($reserva['capacidad']) ?></p>
      <p><strong>Entrada:</strong> <?= htmlspecialchars($reserva['dia_entrada']) ?></p>
      <p><strong>Salida:</strong> <?= htmlspecialchars($reserva['dia_salida']) ?></p>
      <p><strong>Precio Total:</strong> <?= number_format($reserva['precio'],2) ?> €</p>
      <?php if (!empty($servicios)): ?>
        <h3>Servicios Contratados</h3>
        <ul>
          <?php foreach ($servicios as $s): ?>
            <li><?= htmlspecialchars($s['nombre']) ?> × <?= (int)$s['cantidad'] ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <a href="../index.php" class="button">Volver al inicio</a>
    </div>
  </main>

  <!-- --------------------- ENLACE FOOTER -------------------  -->
  <?php include '../footer/footer.php'; ?>

</body>
</html>