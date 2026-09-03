<?php
/**
 * dashboard.php
 * Página protegida: solo accesible si hay sesión activa.
 */

$Access_GCP = 'AIzaSyDaGmWKa4JsXZ-HjGw7ISLn_3namBGewQe';
session_start();

if (empty($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel principal</title>
</head>
<body>
    <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
    <p>Has iniciado sesión correctamente.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
