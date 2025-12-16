<!-- views/Error/NoLogueado.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Atención – ElementalStay</title>
  <link rel="stylesheet" href="../CSS/errorlog/nologueado.css">
  <!-- Redirección automática tras <?= htmlspecialchars($delay) ?> segundos -->
  <meta http-equiv="refresh" content="<?= htmlspecialchars($delay) ?>;url=<?= htmlspecialchars($redirect) ?>">

</head>
<body>
  <div class="error-container">
    <div class="error-box">
      <h2>¡Atención!</h2>
      <p><?= htmlspecialchars($mensaje) ?></p>
      <a href="<?= htmlspecialchars($redirect) ?>" class="btn-action">Iniciar Sesión</a>
    </div>
  </div>
</body>
</html>
