<!-- ----------------------------- CODIGO PHP ----------------------------- -->
<?php
session_start();

// Si por algún motivo $_SESSION['usuario'] es string, lo convertimos en array
if (isset($_SESSION['usuario']) && !is_array($_SESSION['usuario'])) {
    $_SESSION['usuario'] = [
        'email'      => $_SESSION['usuario'],
        // Si quieres, puedes rellenar id_usuario con null u 0
        'id_usuario' => null
    ];
}

ini_set('display_errors', 1);
error_reporting(E_ALL); // Muestra todos los errores, advertencias y notificaciones
?>

<?php
// Establecer los datos de conexión
$host = 'localhost'; 
$usuario = 'root';   
$contraseña = 'EwnizEv5';    
$nombre_base_datos = 'elementalStay'; 

// Crear la conexión
$conn = new mysqli($host, $usuario, $contraseña, $nombre_base_datos);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Verificar si la sesión está activa
if (!isset($_SESSION['usuario'])) {
    header("Location: ../inicio_session/inicio_session.php");
    exit();
}

$correo = $_SESSION['usuario']['email'];

// Consulta para obtener los datos del usuario
$sql = "SELECT id_usuario, dni, nombre, apellidos, correo, telefono, nombre_pais, contraseña FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("No se encontraron datos para este usuario.");
}

// Array para almacenar mensajes de error
$errores = [];

// Si se envía el formulario para actualizar datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar los campos
    if (!empty($_POST['nombre_pais'])) {
        $query_pais = "SELECT COUNT(*) AS total FROM paises_usuario WHERE pais = ?";
        $stmt_pais = $conn->prepare($query_pais);
        $stmt_pais->bind_param("s", $_POST['nombre_pais']);
        $stmt_pais->execute();
        $result_pais = $stmt_pais->get_result();
        $pais_valido = $result_pais->fetch_assoc();

        if ($pais_valido['total'] == 0) {
            $errores[] = "El país ingresado no es válido.";
        }
    }

    if (!empty($_POST['telefono']) && !preg_match('/^\d{9}$/', $_POST['telefono'])) {
        $errores[] = "El teléfono debe tener 9 dígitos.";
    }

    if (!empty($_POST['dni']) && !preg_match('/^\d{8}[A-Z]$/', $_POST['dni'])) {
        $errores[] = "El DNI debe tener 8 números seguidos de una letra mayúscula.";
    }

    if (!empty($_POST['contraseña']) || !empty($_POST['repetir_contraseña'])) {
        if ($_POST['contraseña'] !== $_POST['repetir_contraseña']) {
            $errores[] = "Las contraseñas no coinciden.";
        }
    }

    if (empty($errores)) {
        $dni = !empty($_POST['dni']) ? $_POST['dni'] : $user['dni'];
        $nombre = !empty($_POST['nombre']) ? $_POST['nombre'] : $user['nombre'];
        $apellidos = !empty($_POST['apellidos']) ? $_POST['apellidos'] : $user['apellidos'];
        $telefono = !empty($_POST['telefono']) ? $_POST['telefono'] : $user['telefono'];
        $nombre_pais = !empty($_POST['nombre_pais']) ? $_POST['nombre_pais'] : $user['nombre_pais'];
        $nuevo_correo = !empty($_POST['correo']) ? $_POST['correo'] : $user['correo'];
        $contraseña = !empty($_POST['contraseña']) ? password_hash($_POST['contraseña'], PASSWORD_DEFAULT) : null;

        if ($contraseña) {
            $update_sql = "UPDATE usuarios SET dni = ?, nombre = ?, apellidos = ?, telefono = ?, nombre_pais = ?, correo = ?, contraseña = ? WHERE correo = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssssssss", $dni, $nombre, $apellidos, $telefono, $nombre_pais, $nuevo_correo, $contraseña, $correo);
        } else {
            $update_sql = "UPDATE usuarios SET dni = ?, nombre = ?, apellidos = ?, telefono = ?, nombre_pais = ?, correo = ? WHERE correo = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sssssss", $dni, $nombre, $apellidos, $telefono, $nombre_pais, $nuevo_correo, $correo);
        }

        if ($update_stmt->execute()) {
            // Solo actualizamos el email DENTRO del array de sesión
            $_SESSION['usuario']['email'] = $nuevo_correo;
            echo "<script>alert('Datos actualizados correctamente');</script>";
            header("Refresh:0");
            exit; // Para evitar que siga ejecutando el resto del script
        } else {
            $errores[] = "Error al actualizar los datos.";
        }
        
    }
}
?>
<!-- ----------------------------- FIN CODIGO PHP ----------------------------- -->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <link rel="stylesheet" href="../CSS/perfil/perfil.css">
    <link rel="stylesheet" href="../CSS/header/header.css">
    <link rel="stylesheet" href="../CSS/footer/footer.css">
    <link rel="shortcut icon" href="../img/logo.png"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
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

        // Cierra otros menús abiertos antes de abrir el actual
        document.querySelectorAll('.profile-menu').forEach(el => {
            if (el !== menu) el.classList.remove('show');
        });

        menu.classList.toggle('show');
    }

    // Cierra el menú si se hace clic fuera de él
    window.addEventListener('scroll', function() {
        const nav = document.querySelector('nav');
        if (window.scrollY > 50) { // Puedes ajustar este umbral
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    });
</script>

    <!-- ------------------ FORMULARIO INFORMACIÓN USUARIO - PERFIL.PHP ------------------ -->
    <div class="profile-container">
        <h1 class="a">Perfil del Usuario</h1>
        
        <!-- Mostrar mensajes de error -->
        <?php if (!empty($errores)): ?>
            <div class="error-container">
                <?php foreach ($errores as $error): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" value="<?php echo $user['dni']; ?>">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo $user['nombre']; ?>">
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo $user['apellidos']; ?>">
            </div>
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" value="<?php echo $user['correo']; ?>">
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo $user['telefono']; ?>">
            </div>
            <div class="form-group">
                <label for="nombre_pais">País:</label>
                <input type="text" id="nombre_pais" name="nombre_pais" value="<?php echo $user['nombre_pais']; ?>" autocomplete="off">
                <ul id="suggestions" style="display: none; list-style-type: none; padding: 0;"></ul>
            </div>
            <div class="form-group">
                <label for="contraseña">Nueva Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" placeholder="Introduce una nueva contraseña si deseas cambiarla">
            </div>
            <div class="form-group">
                <label for="repetir_contraseña">Repetir Contraseña:</label>
                <input type="password" id="repetir_contraseña" name="repetir_contraseña" placeholder="Repite la contraseña">
            </div>
            <button type="submit">Guardar Cambios</button>
            <a class="a1" href="../index.php">Atras</a>
        </form>
    </div>
    <!-- ------------------ FIN FORMULARIO INFORMACIÓN USUARIO - PERFIL.PHP ------------------ -->


    <!------------------------ FOOTER DE LA PESTAÑA --------------------- -->
    <footer>
        <div class="footer-container">

            <div class="footer-section">
                <h4>Sobre Nosotros</h4>
                <p>Elemental Stay es tu plataforma para encontrar los mejores alojamientos al mejor precio, con opciones personalizadas para cada tipo de viajero.</p>
            </div>

            <div class="footer-section">
                <h4>Enlaces Útiles</h4>
                <ul>
                    <li><a href="#">Inicio</a></li>
                    <li><a href="#">Acerca de</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Términos y Condiciones</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h4>Síguenos</h4>
                <div class="social-icons">
                    <a href="#"><img src="icon-facebook.svg" alt="Facebook"></a>
                    <a href="#"><img src="icon-twitter.svg" alt="Twitter"></a>
                    <a href="#"><img src="icon-instagram.svg" alt="Instagram"></a>
                    <a href="#"><img src="icon-youtube.svg" alt="YouTube"></a>
                </div>
            </div>

        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2024 Elemental Stay. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="../JS/search_pais.js"></script> <!-- SCRIPT QUE PERMITE BUSCAR PAISES , ARCHIVO PHP (search_pais.php) -->
</body>
</html>