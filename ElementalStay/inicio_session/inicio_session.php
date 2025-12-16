<?php
// inicio_session.php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/conexion.php';  // Debe definir $servername, $username, $password, $dbname

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$error = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // Preparar la consulta para obtener id, correo y hash de contraseña
    $sql = "SELECT correo, contraseña, id_usuario FROM usuarios WHERE correo = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            // Asociar resultados a variables
            $stmt->bind_result($emailFetch, $hashedPassword, $idUsuario);            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['usuario'] = [
                    'id_usuario' => $idUsuario,
                    'email'      => $emailFetch,
                ];

                

                // Redirigir tras login exitoso
                header("Location: ../index.php");
                exit;
            } else {
                $error = "Contraseña incorrecta.";
            }
        } else {
            $error = "No se encontró una cuenta con ese correo.";
        }
        $stmt->close();
    } else {
        $error = "Error en la consulta: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Elemental Stay</title>
    <link rel="stylesheet" href="../CSS/inicio_sesion/inicio_session.css">
    <link rel="shortcut icon" href="../img/logo.png"/>
</head>
<body>
    <div class="login-container">
        <!-- Contenedor de la imagen -->
        <div class="image-container">
            <img src="../img/logo.png" alt="Imagen de inicio de sesión">
        </div>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <h2>Iniciar Sesión</h2>
            <p>Ingresa tus datos para acceder a tu cuenta</p>

            <?php if (!empty($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <input type="email" id="email" name="email" placeholder="Correo Electrónico" required>
                <input type="password" id="password" name="password" placeholder="Contraseña" required>
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="form-footer">
                <p>¿No tienes una cuenta? <a href="../registro/registro.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
</body>
</html>
