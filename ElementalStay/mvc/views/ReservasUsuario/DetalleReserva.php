<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/MisReservas/DetalleReserva.css">
  <link rel="stylesheet" href="../CSS/header/header.css">
  <link rel="stylesheet" href="../CSS/footer/footer.css">
  <link rel="shortcut icon" href="../img/logo.png"/>
  <title>Detalle Reserva – ElementalStay</title>
</head>
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
          <li><a href="../mvc/index.php?controlador=Hoteles&accion=listarHotelesSinFiltros" id="boton-superior"><b>HOTELES</b></a></li>
          <li><a href="../contactos/contactos.php" id="boton-superior"><b>CONTACTO</b></a></li>
  

          <!-- Aquí verificamos si la sesión está activa -->
          <?php if (isset($_SESSION['usuario'])): ?>
              <!-- Mostrar 'PERFIL' si la sesión está activa -->
              <li class="profile">
                  <a href="javascript:void(0);" onclick="toggleProfileMenu(event)" id="profileButton"><b>PERFIL</b></a>
                  <!-- Menú desplegable de opciones de perfil -->
                  <div class="profile-menu" id="profileMenu">
                      <a href="./perfil/perfil.php">Ver Perfil</a>
                      <a href="./inicio_session/logout.php" class="logout">Cerrar Sesión</a>
                  </div>
              </li>
          <?php else: ?>
              <!-- Si no hay sesión activa, mostrar el botón de "Iniciar sesión" -->
              <li><a href="../ElementalStay/inicio_session/inicio_session.php" id="boton-superior"><b>INICIAR SESIÓN</b></a></li>
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


  <!-- CONTENEDOR PRINCIPAL CON LOS DETALLES DE LA RESERVA -->
  <div class="wrapper">
    <!-- TITULO DEL CONTENEDOR -->
    <div class="header">
      <h1>Detalle de la Reserva – <?= htmlspecialchars($detalle['hotel']) ?></h1>
    </div>
    <!-- ------------------ CONTENIDO ---------------  -->
    <div class="content">
      <?php if (!empty($detalle['imagen_hotel'])): ?>
        <img src="<?= htmlspecialchars($detalle['imagen_hotel']) ?>" alt="Hotel <?= htmlspecialchars($detalle['hotel']) ?>">
      <?php endif; ?>
      <div class="info">
        <!-- CAMPOS DE INFORMACIÓN  -->
        <p><span>Hotel:</span> <?= htmlspecialchars($detalle['hotel']) ?></p>
        <p><span>Estrellas:</span> <?= str_repeat('⭐', intval($detalle['estrellas_hotel'])) ?></p>
        <p><span>Tipo de Hotel:</span> <?= htmlspecialchars($detalle['tipo_hotel']) ?></p>
        <p><span>Comunidad:</span> <?= htmlspecialchars($detalle['comunidad_autonoma_hotel']) ?></p>
        <p><span>Descripción Hotel:</span> <?= htmlspecialchars($detalle['descripcion_hotel']) ?></p>
        <p><span>Precio Noche:</span> €<?= number_format($detalle['precio'], 2) ?></p>
        <br>
        <p><span>Habitación:</span> <?= htmlspecialchars($detalle['nombre_hab_custom'] ?: $detalle['habitacion']) ?></p>
        <p><span>Descripción Hab:</span> <?= htmlspecialchars($detalle['descripcion_habitacion']) ?></p>
        <p><span>Capacidad Habitación:</span> <?= htmlspecialchars($detalle['capacidad_hab_custom'] ?: $detalle['capacidad_habitacion']) ?> personas</p>
        <p><span>Metros²:</span> <?= htmlspecialchars($detalle['metros_cuadrados']) ?> m²</p>
        <p><span>Wi-Fi:</span> <?= $detalle['wifi_habitacion'] ? 'Sí' : 'No' ?></p>
        <br>
        <p><span>Entrada:</span> <?= date('d/m/Y', strtotime($detalle['dia_entrada'])) ?></p>
        <p><span>Salida:</span> <?= date('d/m/Y', strtotime($detalle['dia_salida'])) ?></p>
      </div>
    </div>
    <!-- ACCIONES CON LA RESERVA  -->
    <div class="actions">
      <a href="index.php?controlador=ReservasMisReservas&accion=listar" class="btn btn-back">← Volver atrás</a>
      <form action="index.php?controlador=ReservasMisReservas&accion=cancelarReserva" method="POST" onsubmit="return confirm('¿Deseas cancelar esta reserva?');">
        <input type="hidden" name="id_reserva"    value="<?= htmlspecialchars($detalle['id_reserva']) ?>">
        <input type="hidden" name="idreservashab" value="<?= htmlspecialchars($detalle['idreservashab']) ?>">
        <button type="submit" class="btn btn-cancel">Cancelar Reserva</button>
      </form>
    </div>
  </div>

  <!-- --------------------- ENLACE FOOTER -------------------  -->
  <?php include '../footer/footer.php'; ?>

  <script src="../JS/Header.js"></script>
</body>
</html>
