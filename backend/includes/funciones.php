<?php
/**
 * Lógica de negocio de MICAJHOVIC.
 * Son funciones puras (no tocan la base de datos) para poder probarlas con PHPUnit.
 */

const TIPO_ENTRADA = 'ENTRADA';
const TIPO_SALIDA  = 'SALIDA';
const DOMINIO_CORREO = 'mcjv.com';

/**
 * Regla principal del control de acceso: los registros se alternan.
 * Si el último registro fue ENTRADA, el siguiente es SALIDA; en cualquier
 * otro caso (sin registros o última SALIDA) el siguiente es ENTRADA.
 */
function siguienteTipo(?string $ultimoTipo): string
{
    return strtoupper((string) $ultimoTipo) === TIPO_ENTRADA ? TIPO_SALIDA : TIPO_ENTRADA;
}

/**
 * Token del QR dinámico: 32 caracteres hexadecimales (128 bits aleatorios).
 * Se genera uno nuevo en cada login y después de cada lectura ("quemar" el QR).
 */
function generarToken(): string
{
    return bin2hex(random_bytes(16));
}

/** Contraseña de 8 caracteres (sin 0 ni O para no confundir al usuario). */
function generarPassword(int $longitud = 8): string
{
    $caracteres = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < $longitud; $i++) {
        $password .= $caracteres[random_int(0, strlen($caracteres) - 1)];
    }
    return $password;
}

/** Quita espacios sobrantes del nombre ("  juan   perez " -> "juan perez"). */
function normalizarNombre(string $nombre): string
{
    return preg_replace('/\s+/u', ' ', trim($nombre));
}

/**
 * Correo institucional único: nombresinespacios_ultimos4doc_aleatorio@mcjv.com
 * Se quitan tildes y caracteres raros para que el correo sea válido.
 */
function generarCorreo(string $nombre, string $documento, int $aleatorio): string
{
    $limpio = strtolower(normalizarNombre($nombre));
    $limpio = strtr($limpio, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);
    $limpio = preg_replace('/[^a-z0-9]/', '', $limpio);

    return sprintf('%s_%s_%d@%s', $limpio, substr($documento, -4), $aleatorio, DOMINIO_CORREO);
}

/**
 * Valida los datos del formulario de usuarios.
 * Devuelve un arreglo con los mensajes de error (vacío si todo está bien).
 */
function validarUsuario(string $nombre, string $documento): array
{
    $errores = [];
    $nombre = normalizarNombre($nombre);

    if ($nombre === '') {
        $errores[] = 'El nombre es obligatorio';
    } elseif (mb_strlen($nombre) < 3 || mb_strlen($nombre) > 100) {
        $errores[] = 'El nombre debe tener entre 3 y 100 caracteres';
    } elseif (!preg_match('/^[\p{L} ]+$/u', $nombre)) {
        $errores[] = 'El nombre solo puede tener letras y espacios';
    }

    if (!preg_match('/^\d{5,15}$/', trim($documento))) {
        $errores[] = 'El documento debe tener solo números (entre 5 y 15 dígitos)';
    }

    return $errores;
}

/** Token con el formato correcto (evita consultas con basura). */
function tokenTieneFormato(string $token): bool
{
    return (bool) preg_match('/^[a-f0-9]{32}$/', $token);
}

/**
 * Verifica la contraseña del administrador.
 * Acepta hash (password_hash) y, por compatibilidad con la base vieja,
 * texto plano; en ese caso $necesitaRehash queda en true para migrarla.
 */
function verificarPasswordAdmin(string $ingresada, string $guardada, ?bool &$necesitaRehash = null): bool
{
    $necesitaRehash = false;

    if (!empty(password_get_info($guardada)['algo'])) {
        return password_verify($ingresada, $guardada);
    }

    if (hash_equals($guardada, $ingresada)) {
        $necesitaRehash = true;
        return true;
    }

    return false;
}

/** Escapa texto antes de imprimirlo en HTML (evita XSS). */
function e($texto): string
{
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

/** Respuesta JSON estándar de la API. */
function responderJson(array $datos, int $codigo = 200): void
{
    http_response_code($codigo);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit();
}
