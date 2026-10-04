<?php
/**
 * Conexión a la base de datos con PDO.
 *
 * Toda la configuración sale de variables de entorno (ver .env.example):
 *   DB_DRIVER  mysql | sqlite   (sqlite solo se usa en pruebas)
 *   DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS
 *   DB_SSL=true y DB_SSL_CA_CONTENT  -> para bases en la nube que exigen TLS (Aiven)
 *   APP_TIMEZONE -> zona horaria con la que se guardan fecha y hora de los registros
 */

require_once __DIR__ . '/includes/env.php';
require_once __DIR__ . '/includes/funciones.php';

date_default_timezone_set(env('APP_TIMEZONE', 'America/Bogota'));

function conectar(): PDO
{
    $driver = env('DB_DRIVER', 'mysql');

    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    if ($driver === 'sqlite') {
        $pdo = new PDO('sqlite:' . env('DB_NAME', ':memory:'), null, null, $opciones);
        $pdo->exec('PRAGMA foreign_keys = ON');
        return $pdo;
    }

    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        env('DB_HOST', 'localhost'),
        env('DB_PORT', '3306'),
        env('DB_NAME', 'control_qr')
    );

    // Conexión cifrada (las bases administradas en la nube la exigen)
    if (env('DB_SSL', 'false') === 'true') {
        $ca = env('DB_SSL_CA_CONTENT');
        if ($ca) {
            $rutaCa = sys_get_temp_dir() . '/db-ca.pem';
            file_put_contents($rutaCa, str_replace('\n', "\n", $ca));
            $opciones[PDO::MYSQL_ATTR_SSL_CA] = $rutaCa;
        } else {
            $opciones[PDO::MYSQL_ATTR_SSL_CA] = '';
            $opciones[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        }
    }

    return new PDO($dsn, env('DB_USER', 'root'), env('DB_PASS', ''), $opciones);
}

try {
    $conexion = conectar();
} catch (PDOException $e) {
    error_log('Error de conexión: ' . $e->getMessage());
    http_response_code(500);
    die('Error de conexión con la base de datos');
}
