<?php
/**
 * Elimina un usuario (solo administradores).
 * Sus registros de acceso se conservan para el historial (usuario_id queda en NULL).
 */

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $conexion->prepare('UPDATE registros SET usuario_id = NULL WHERE usuario_id = ?')->execute([$id]);
    $conexion->prepare('DELETE FROM usuarios WHERE id = ?')->execute([$id]);
}

header('Location: usuarios.php');
exit();
