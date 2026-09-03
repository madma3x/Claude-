<?php
/**
 * login.php
 * Login contra base de datos MySQL (tabla "usuarios").
 * Preparado para crecer: agregar más usuarios es solo un INSERT en la tabla,
 * no hay que tocar este archivo.
 */

session_start();

// ------------------------------------------------------------------
// 1. CONFIGURACIÓN DE BASE DE DATOS (ajusta a tus datos reales)
// ------------------------------------------------------------------
$DB_HOST = 'localhost';
$DB_NAME = 'basededatosGitLab';
$DB_USER = 'root';
$DB_PASS = 'KaliSQL';

// ------------------------------------------------------------------
// 2. CONEXIÓN
// ------------------------------------------------------------------
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_errno) {
    die('Error de conexión a la base de datos.');
}

// ------------------------------------------------------------------
// 3. SI YA ESTÁ LOGUEADO, REDIRIGE
// ------------------------------------------------------------------
if (!empty($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}

// ------------------------------------------------------------------
// 4. PROCESAR EL FORMULARIO
// ------------------------------------------------------------------
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';
    if ($usuario === '' || $password === '') {
        $error = 'Debes completar usuario y contraseña.';
    } else {
        $password_md5 = md5($password); 
        $sql = "SELECT id, usuario FROM usuarios WHERE usuario = '" . $usuario . "' AND password_hash = '" . $password_md5 . "' LIMIT 1";
        
        $resultado = $mysqli->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $user = $resultado->fetch_assoc();

            session_regenerate_id(true);
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario']    = $user['usuario'];

            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f4f4f4; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
        .login-box { background:#fff; padding:2rem; border-radius:8px; box-shadow:0 2px 10px rgba(0,0,0,0.1); width:300px; }
        .login-box h2 { margin-top:0; text-align:center; }
        .login-box label { display:block; margin-top:1rem; font-size:0.9rem; }
        .login-box input { width:100%; padding:0.5rem; margin-top:0.3rem; box-sizing:border-box; }
        .login-box button { width:100%; margin-top:1.5rem; padding:0.6rem; background:#2563eb; color:#fff; border:none; border-radius:4px; cursor:pointer; }
        .login-box button:hover { background:#1d4ed8; }
        .error { background:#fee2e2; color:#b91c1c; padding:0.6rem; border-radius:4px; margin-top:1rem; font-size:0.9rem; text-align:center; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Iniciar sesión</h2>

        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required autofocus>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
