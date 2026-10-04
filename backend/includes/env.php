<?php
/**
 * Carga variables de entorno desde backend/.env (solo en desarrollo local).
 * En la nube (Render) las variables se configuran en el panel del servicio,
 * por eso aquí nunca se sobrescribe una variable que ya exista.
 */

function cargarEnv(string $ruta): void
{
    if (!is_readable($ruta)) {
        return;
    }

    foreach (file($ruta, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
        $linea = trim($linea);

        // Ignorar comentarios y líneas sin "="
        if ($linea === '' || $linea[0] === '#' || strpos($linea, '=') === false) {
            continue;
        }

        [$clave, $valor] = array_map('trim', explode('=', $linea, 2));
        $valor = trim($valor, "\"'");

        if (getenv($clave) === false) {
            putenv("$clave=$valor");
        }
    }
}

/** Devuelve una variable de entorno o un valor por defecto. */
function env(string $clave, ?string $defecto = null): ?string
{
    $valor = getenv($clave);
    return ($valor === false || $valor === '') ? $defecto : $valor;
}

cargarEnv(__DIR__ . '/../.env');
