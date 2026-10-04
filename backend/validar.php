<?php
/**
 * Login del administrador (panel web).
 * POST validar.php -> correo, password
 */

session_start();
require_once __DIR__ . '/conexion.php';

$correo   = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $conexion->prepare('SELECT id, correo, password FROM administradores WHERE correo = ?');
$stmt->execute([$correo]);
$admin = $stmt->fetch();

$necesitaRehash = false;

if ($admin && verificarPasswordAdmin($password, (string) $admin['password'], $necesitaRehash)) {

    // Si la contraseña estaba en texto plano (base vieja) se guarda como hash
    if ($necesitaRehash) {
        $conexion->prepare('UPDATE administradores SET password = ? WHERE id = ?')
                 ->execute([password_hash($password, PASSWORD_DEFAULT), $admin['id']]);
    }

    session_regenerate_id(true);
    $_SESSION['admin'] = $admin['correo'];
    header('Location: dashboard.php');
    exit();
}

echo "
<script>
alert('Datos incorrectos');
window.location='login.php';
</script>
";
