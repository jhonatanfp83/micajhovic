<?php
/**
 * API - Login del usuario en la app móvil.
 *
 * POST login_app.php   (application/x-www-form-urlencoded)
 *   correo, password
 *
 * Si los datos son correctos genera un token NUEVO para el QR dinámico
 * y lo devuelve. El token anterior deja de servir.
 */

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJson(['status' => 'error', 'mensaje' => 'Método no permitido'], 405);
}

$correo   = trim($_POST['correo'] ?? '');
$password = trim($_POST['password'] ?? '');

/* VALIDAR DATOS */
if ($correo === '' || $password === '') {
    responderJson(['status' => 'error', 'mensaje' => 'Faltan datos']);
}

/* CONSULTA (sentencia preparada: evita inyección SQL) */
$stmt = $conexion->prepare('SELECT id, nombre, correo, password FROM usuarios WHERE correo = ?');
$stmt->execute([$correo]);
$usuario = $stmt->fetch();

/* La contraseña está cifrada con password_hash (BUG-13). Si es una clave antigua
   en texto plano se acepta una vez y se cifra en ese momento. */
$necesitaRehash = false;
if (!$usuario || !verificarPasswordAdmin($password, (string) $usuario['password'], $necesitaRehash)) {
    responderJson(['status' => 'error', 'mensaje' => 'Credenciales incorrectas']);
}

if ($necesitaRehash) {
    $conexion->prepare('UPDATE usuarios SET password = ? WHERE id = ?')
             ->execute([password_hash($password, PASSWORD_DEFAULT), $usuario['id']]);
}

/* GENERAR Y GUARDAR NUEVO TOKEN */
$nuevoToken = generarToken();
$conexion->prepare('UPDATE usuarios SET qr_token = ? WHERE id = ?')
         ->execute([$nuevoToken, $usuario['id']]);

responderJson([
    'status' => 'success',
    'nombre' => $usuario['nombre'],
    'correo' => $usuario['correo'],
    'token'  => $nuevoToken,
]);
