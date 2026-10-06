<?php
/**
 * API - Validación del QR en el sistema de escaneo de la universidad (entrada).
 *
 * POST validar_qr.php   (application/x-www-form-urlencoded)
 *   token
 *
 * 1. Busca al usuario dueño del token.
 * 2. Decide si es ENTRADA o SALIDA según su último registro.
 * 3. Guarda el registro con fecha y hora de Colombia.
 * 4. "Quema" el QR: le asigna un token nuevo, así el mismo QR no sirve dos veces.
 */

require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responderJson(['status' => 'error', 'mensaje' => 'Método no permitido'], 405);
}

$token = trim($_POST['token'] ?? '');

if ($token === '' || !tokenTieneFormato($token)) {
    responderJson(['status' => 'error', 'mensaje' => 'QR inválido']);
}

try {
    $conexion->beginTransaction();

    /* BUSCAR USUARIO */
    $stmt = $conexion->prepare('SELECT id, nombre FROM usuarios WHERE qr_token = ?');
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        $conexion->rollBack();
        responderJson(['status' => 'error', 'mensaje' => 'QR inválido o ya utilizado']);
    }

    /* QUEMAR QR (solo una lectura puede ganar si llegan dos al tiempo) */
    $quemar = $conexion->prepare('UPDATE usuarios SET qr_token = ? WHERE id = ? AND qr_token = ?');
    $quemar->execute([generarToken(), $usuario['id'], $token]);

    if ($quemar->rowCount() === 0) {
        $conexion->rollBack();
        responderJson(['status' => 'error', 'mensaje' => 'QR inválido o ya utilizado']);
    }

    /* VERIFICAR ULTIMO REGISTRO */
    $ultimo = $conexion->prepare('SELECT tipo FROM registros WHERE usuario_id = ? ORDER BY id DESC LIMIT 1');
    $ultimo->execute([$usuario['id']]);
    $tipo = siguienteTipo($ultimo->fetchColumn() ?: null);

    /* GUARDAR REGISTRO */
    $conexion->prepare('INSERT INTO registros (usuario_id, fecha, hora, tipo) VALUES (?, ?, ?, ?)')
             ->execute([$usuario['id'], date('Y-m-d'), date('H:i:s'), $tipo]);

    $conexion->commit();
} catch (PDOException $e) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    error_log('validar_qr: ' . $e->getMessage());
    responderJson(['status' => 'error', 'mensaje' => 'Error del servidor'], 500);
}

responderJson([
    'status' => 'success',
    'nombre' => $usuario['nombre'],
    'tipo'   => $tipo,
]);
