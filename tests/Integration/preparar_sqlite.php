<?php
/**
 * Crea una base SQLite limpia para correr las pruebas de integración en local
 * sin instalar MySQL.  Uso: php tests/Integration/preparar_sqlite.php /tmp/micajhovic_test.db
 */
$ruta = $argv[1] ?? sys_get_temp_dir() . '/micajhovic_test.db';
@unlink($ruta);

$db = new PDO('sqlite:' . $ruta);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec(file_get_contents(__DIR__ . '/../../database/schema_sqlite.sql'));
$db->prepare('INSERT INTO administradores (correo, password) VALUES (?, ?)')
   ->execute(['admin@gmail.com', password_hash('1234', PASSWORD_DEFAULT)]);

echo "Base de pruebas creada en $ruta\n";
