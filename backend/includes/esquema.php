<?php
/**
 * Crea las tablas de MICAJHOVIC si todavía no existen.
 *
 * Sirve para el primer arranque en la nube: la base de Aiven llega vacía y así
 * no hay que importar el .sql a mano. Usa CREATE TABLE IF NOT EXISTS, por lo que
 * no toca datos existentes. Corre una sola vez por contenedor (archivo bandera).
 *
 * Variables de entorno:
 *   AUTO_MIGRAR=false     para desactivarlo
 *   ADMIN_EMAIL           correo del primer administrador (por defecto admin@gmail.com)
 *   ADMIN_PASSWORD        clave del primer administrador (por defecto 1234, cambiarla)
 */

function asegurarEsquema(PDO $db): void
{
    if (env('DB_DRIVER', 'mysql') !== 'mysql' || env('AUTO_MIGRAR', 'true') !== 'true') {
        return;
    }

    $bandera = sys_get_temp_dir() . '/micajhovic_esquema_ok';
    if (is_file($bandera)) {
        return;
    }

    $db->exec("CREATE TABLE IF NOT EXISTS administradores (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        correo    VARCHAR(100) NOT NULL UNIQUE,
        password  VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id         INT AUTO_INCREMENT PRIMARY KEY,
        nombre     VARCHAR(100) NOT NULL,
        documento  VARCHAR(20)  NOT NULL UNIQUE,
        correo     VARCHAR(100) NOT NULL UNIQUE,
        password   VARCHAR(100) NOT NULL,
        qr_token   CHAR(32)     NOT NULL UNIQUE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $db->exec("CREATE TABLE IF NOT EXISTS registros (
        id          INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id  INT NULL,
        fecha       DATE NOT NULL,
        hora        TIME NOT NULL,
        tipo        ENUM('ENTRADA','SALIDA') NOT NULL,
        INDEX idx_registros_usuario (usuario_id, id),
        INDEX idx_registros_fecha (fecha, tipo),
        CONSTRAINT fk_registros_usuario FOREIGN KEY (usuario_id)
          REFERENCES usuarios (id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // Primer administrador (solo si la tabla está vacía)
    if ((int) $db->query('SELECT COUNT(*) FROM administradores')->fetchColumn() === 0) {
        $db->prepare('INSERT INTO administradores (correo, password) VALUES (?, ?)')
           ->execute([
               env('ADMIN_EMAIL', 'admin@gmail.com'),
               password_hash(env('ADMIN_PASSWORD', '1234'), PASSWORD_DEFAULT),
           ]);
    }

    @touch($bandera);
}
