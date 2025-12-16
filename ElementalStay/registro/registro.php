<?php
require_once '../config/conexion.php';

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validación de correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El correo electrónico no es válido.";
    } elseif ($password !== $confirmPassword) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // Verificar si el correo ya existe
        $sql = "SELECT correo FROM usuarios WHERE correo = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error = "Ya existe una cuenta con ese correo electrónico.";
            } else {
                // Hashear la contraseña antes de guardarla
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Preparar la consulta SQL para insertar los datos
                $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sss", $nombre, $email, $hashedPassword);

                    if ($stmt->execute()) {
                        // Redirigir a la página de inicio de sesión
                        header("Location: ../inicio_session/inicio_session.php");
                        exit();
                    } else {
                        $error = "Error al registrar el usuario: " . $stmt->error;
                    }
                    $stmt->close();
                } else {
                    $error = "Error en la preparación de la consulta: " . $conn->error;
                }
            }
            $stmt->close();
        } else {
            $error = "Error al consultar la base de datos: " . $conn->error;
        }
    }
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../CSS/registro/registro.css">
    <link rel="shortcut icon" href="../img/logo.png"/>
</head>
<body>
    <div class="login-container">
        <!-- Contenedor de la imagen -->
        <div class="image-container">
            <img src="../img/logo.png" alt="Imagen de Registro">
        </div>

        <!-- Contenedor del formulario -->
        <div class="form-container">
            <h2>Regístrate</h2>
            <p>Crea tu cuenta para Elemental Stay y sus servicios</p>

            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="registro.php" method="POST">
                <input type="text" name="nombre" placeholder="Nombre completo" required>
                <input type="email" name="email" placeholder="Correo electrónico" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <input type="password" name="confirm-password" placeholder="Confirma tu contraseña" required>
                <button type="submit" class="btn-login">Registrarse</button>
            </form>

            <div class="form-footer">
                <a href="#">Tienes alguna duda?</a>
                <a href="../inicio_session/inicio_session.php">Volver</a>
            </div>
        </div>
    </div>
</body>
</html>
