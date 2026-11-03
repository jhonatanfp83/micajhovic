<?php
/**
 * API - Token vigente del QR (BUG-14).
 *
 * POST token_actual.php   (application/x-www-form-urlencoded)
 *   correo, password
 *
 * Devuelve el token actual SIN cambiarlo. Cuando el sistema de escaneo usa el QR,
 * validar_qr.php le asigna un token nuevo; la app consulta este endpoint cada pocos
 * segundos y, si el token cambió, muestra el QR nuevo sin volver a iniciar sesión.
 */

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJson(['status' => 'error', 'mensaje' => 'Método no permitido'], 405);
}

$correo   = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($correo === '' || $password === '') {
    responderJson(['status' => 'error', 'mensaje' => 'Faltan datos']);
}

$stmt = $conexion->prepare('SELECT id, password, qr_token FROM usuarios WHERE correo = ?');
$stmt->execute([$correo]);
$usuario = $stmt->fetch();

if (!$usuario || !verificarPasswordAdmin($password, (string) $usuario['password'])) {
    responderJson(['status' => 'error', 'mensaje' => 'Credenciales incorrectas']);
}

responderJson([
    'status' => 'success',
    'token'  => $usuario['qr_token'],
]);
