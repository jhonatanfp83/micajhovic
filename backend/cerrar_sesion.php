<?php
/**
 * Cierra la sesión del administrador.
 */

session_start();

/* BORRAR VARIABLES */
$_SESSION = [];

/* ELIMINAR COOKIE */
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

/* DESTRUIR SESION */
session_destroy();

/* BLOQUEAR CACHE */
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

/* REDIRECCION */
header('Location: login.php');
exit();
