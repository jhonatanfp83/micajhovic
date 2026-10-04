<?php
/**
 * Crea un usuario desde el panel (solo administradores).
 * Genera automáticamente correo, contraseña y el primer token del QR.
 */

session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/conexion.php';

function avisar(string $mensaje): void
{
    $mensaje = json_encode($mensaje, JSON_UNESCAPED_UNICODE);
    echo "<script>alert($mensaje); window.location='usuarios.php';</script>";
    exit();
}

/* DATOS */
$nombre    = normalizarNombre($_POST['nombre'] ?? '');
$documento = trim($_POST['documento'] ?? '');

/* VALIDACIONES */
$errores = validarUsuario($nombre, $documento);
if ($errores) {
    avisar(implode("\n", $errores));
}

/* VERIFICAR DOCUMENTO DUPLICADO */
$verificar = $conexion->prepare('SELECT COUNT(*) FROM usuarios WHERE documento = ?');
$verificar->execute([$documento]);
if ((int) $verificar->fetchColumn() > 0) {
    avisar('Documento ya registrado');
}

/* GENERAR CORREO UNICO, PASSWORD Y TOKEN */
$correo   = generarCorreo($nombre, $documento, random_int(1000, 9999));
$password = generarPassword();
$qrToken  = generarToken();

/* INSERTAR */
$conexion->prepare('INSERT INTO usuarios (nombre, documento, correo, password, qr_token) VALUES (?, ?, ?, ?, ?)')
         ->execute([$nombre, $documento, $correo, $password, $qrToken]);

avisar('Usuario creado correctamente');
