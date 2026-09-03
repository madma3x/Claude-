<?php
/**
 * logout.php
 * Destruye la sesión y regresa al login.
 */
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
