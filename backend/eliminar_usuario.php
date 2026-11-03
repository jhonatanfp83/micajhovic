<?php
/**
 * Elimina un usuario (solo administradores).
 * Sus registros de acceso se conservan para el historial (usuario_id queda en NULL).
 *
 * Solo acepta POST con el token CSRF de la sesión (BUG-15): un enlace o una
 * imagen en otra página ya no pueden borrar usuarios.
 */

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Método no permitido');
}

$csrf = (string) ($_POST['csrf'] ?? '');
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $csrf)) {
    http_response_code(403);
    exit('Solicitud no válida');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $conexion->prepare('UPDATE registros SET usuario_id = NULL WHERE usuario_id = ?')->execute([$id]);
    $conexion->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
}

header('Location: usuarios.php');
exit();
