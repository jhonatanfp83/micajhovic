<?php

use PHPUnit\Framework\TestCase;

/**
 * Pruebas de integración: recorren el flujo completo
 * frontend (peticiones HTTP) -> backend PHP -> base de datos.
 *
 * Requiere el servidor corriendo y estas variables:
 *   BASE_URL   ej. http://127.0.0.1:8000
 *   DB_*       las mismas del backend (para preparar y revisar datos)
 *
 * Los métodos se ejecutan en orden porque cada uno usa el estado del anterior.
 */
final class FlujoCompletoTest extends TestCase
{
    private static string $base = '';
    private static string $cookies = '';
    private static ?PDO $db = null;

    /** Datos compartidos entre pasos */
    private static array $usuario = [];
    private static string $token = '';

    public static function setUpBeforeClass(): void
    {
        self::$base = rtrim((string) getenv('BASE_URL'), '/');
        self::$cookies = tempnam(sys_get_temp_dir(), 'cookies');

        if (self::$base !== '') {
            require_once __DIR__ . '/../../backend/conexion.php';
            self::$db = conectar();
            self::$db->exec("DELETE FROM usuarios WHERE documento IN ('900100200', '900100300')");
        }
    }

    protected function setUp(): void
    {
        if (self::$base === '') {
            $this->markTestSkipped('Definir BASE_URL para correr las pruebas de integración');
        }
    }

    /* ------------------------------------------------------------------ */

    /** Petición HTTP sin seguir redirecciones. Devuelve [codigo, headers, cuerpo]. */
    private function http(string $metodo, string $ruta, array $datos = [], bool $conSesion = true): array
    {
        $ch = curl_init(self::$base . '/' . ltrim($ruta, '/'));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CUSTOMREQUEST  => $metodo,
            CURLOPT_TIMEOUT        => 20,
        ]);
        if ($conSesion) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, self::$cookies);
            curl_setopt($ch, CURLOPT_COOKIEFILE, self::$cookies);
        }
        if ($metodo === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));
        }

        $respuesta = curl_exec($ch);
        $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $tamHeader = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        return [$codigo, substr($respuesta, 0, $tamHeader), substr($respuesta, $tamHeader)];
    }

    private function json(string $ruta, array $datos): array
    {
        [, , $cuerpo] = $this->http('POST', $ruta, $datos, false);
        $json = json_decode($cuerpo, true);
        $this->assertIsArray($json, "La respuesta de $ruta no es JSON: $cuerpo");
        return $json;
    }

    /* ---------------------- Panel administrativo ---------------------- */

    public function test01PanelSinSesionRedirigeAlLogin(): void
    {
        [$codigo, $headers] = $this->http('GET', 'dashboard.php', [], false);
        $this->assertSame(302, $codigo);
        $this->assertStringContainsString('Location: login.php', $headers);
    }

    public function test02LoginAdminConClaveIncorrecta(): void
    {
        [, , $cuerpo] = $this->http('POST', 'validar.php', ['correo' => 'admin@gmail.com', 'password' => 'mala']);
        $this->assertStringContainsString('Datos incorrectos', $cuerpo);
    }

    public function test03LoginAdminConInyeccionSql(): void
    {
        [, , $cuerpo] = $this->http('POST', 'validar.php', ['correo' => "' OR '1'='1", 'password' => "' OR '1'='1"]);
        $this->assertStringContainsString('Datos incorrectos', $cuerpo);
    }

    public function test04LoginAdminCorrecto(): void
    {
        [$codigo, $headers] = $this->http('POST', 'validar.php', [
            'correo'   => getenv('TEST_ADMIN_EMAIL') ?: 'admin@gmail.com',
            'password' => getenv('TEST_ADMIN_PASS') ?: '1234',
        ]);
        $this->assertSame(302, $codigo);
        $this->assertStringContainsString('Location: dashboard.php', $headers);
    }

    public function test05CrearUsuario(): void
    {
        [, , $cuerpo] = $this->http('POST', 'guardar_usuario.php', ['nombre' => 'Prueba Integracion', 'documento' => '900100200']);
        $this->assertStringContainsString('Usuario creado correctamente', $cuerpo);

        $stmt = self::$db->prepare('SELECT * FROM usuarios WHERE documento = ?');
        $stmt->execute(['900100200']);
        self::$usuario = $stmt->fetch();

        $this->assertNotEmpty(self::$usuario);
        $this->assertStringEndsWith('@mcjv.com', self::$usuario['correo']);
        $this->assertSame(8, strlen(self::$usuario['password']));
    }

    public function test06DocumentoDuplicadoEsRechazado(): void
    {
        [, , $cuerpo] = $this->http('POST', 'guardar_usuario.php', ['nombre' => 'Otra Persona', 'documento' => '900100200']);
        $this->assertStringContainsString('Documento ya registrado', $cuerpo);
    }

    public function test07DocumentoConLetrasEsRechazado(): void
    {
        [, , $cuerpo] = $this->http('POST', 'guardar_usuario.php', ['nombre' => 'Otra Persona', 'documento' => '12AB45']);
        $this->assertStringContainsString('El documento debe tener solo números', $cuerpo);
    }

    public function test08CamposVaciosSonRechazados(): void
    {
        [, , $cuerpo] = $this->http('POST', 'guardar_usuario.php', ['nombre' => '', 'documento' => '']);
        $this->assertStringContainsString('El nombre es obligatorio', $cuerpo);
    }

    public function test09CrearUsuarioSinSesionNoSePermite(): void
    {
        [$codigo] = $this->http('POST', 'guardar_usuario.php', ['nombre' => 'Intruso Externo', 'documento' => '900100300'], false);
        $this->assertSame(302, $codigo);

        $stmt = self::$db->prepare('SELECT COUNT(*) FROM usuarios WHERE documento = ?');
        $stmt->execute(['900100300']);
        $this->assertSame(0, (int) $stmt->fetchColumn());
    }

    /* ---------------------- API de la app móvil ---------------------- */

    public function test10LoginAppSinDatos(): void
    {
        $json = $this->json('login_app.php', []);
        $this->assertSame('error', $json['status']);
        $this->assertSame('Faltan datos', $json['mensaje']);
    }

    public function test11LoginAppConClaveIncorrecta(): void
    {
        $json = $this->json('login_app.php', ['correo' => self::$usuario['correo'], 'password' => 'XXXXXXXX']);
        $this->assertSame('error', $json['status']);
    }

    public function test12LoginAppConInyeccionSql(): void
    {
        $json = $this->json('login_app.php', ['correo' => "' OR '1'='1' -- ", 'password' => "' OR '1'='1' -- "]);
        $this->assertSame('error', $json['status']);
    }

    public function test13LoginAppCorrectoEntregaTokenNuevo(): void
    {
        $json = $this->json('login_app.php', ['correo' => self::$usuario['correo'], 'password' => self::$usuario['password']]);
        $this->assertSame('success', $json['status']);
        $this->assertSame('Prueba Integracion', $json['nombre']);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $json['token']);
        $this->assertNotSame(self::$usuario['qr_token'], $json['token']);
        self::$token = $json['token'];
    }

    public function test14PrimerEscaneoRegistraEntrada(): void
    {
        $json = $this->json('validar_qr.php', ['token' => self::$token]);
        $this->assertSame('success', $json['status']);
        $this->assertSame('ENTRADA', $json['tipo']);

        $stmt = self::$db->prepare('SELECT tipo, fecha FROM registros WHERE usuario_id = ? ORDER BY id DESC LIMIT 1');
        $stmt->execute([self::$usuario['id']]);
        $registro = $stmt->fetch();
        $this->assertSame('ENTRADA', $registro['tipo']);
        $this->assertSame((new DateTime('now', new DateTimeZone('America/Bogota')))->format('Y-m-d'), $registro['fecha']);
    }

    public function test15ElMismoQrNoSirveDosVeces(): void
    {
        $json = $this->json('validar_qr.php', ['token' => self::$token]);
        $this->assertSame('error', $json['status']);
        $this->assertSame('QR inválido o ya utilizado', $json['mensaje']);
    }

    public function test16NuevoQrRegistraSalida(): void
    {
        $login = $this->json('login_app.php', ['correo' => self::$usuario['correo'], 'password' => self::$usuario['password']]);
        $json = $this->json('validar_qr.php', ['token' => $login['token']]);
        $this->assertSame('success', $json['status']);
        $this->assertSame('SALIDA', $json['tipo']);
    }

    public function test17TokenConFormatoInvalido(): void
    {
        $json = $this->json('validar_qr.php', ['token' => "abc' OR '1'='1"]);
        $this->assertSame('error', $json['status']);
    }

    public function test18ValidarQrSoloAceptaPost(): void
    {
        [$codigo] = $this->http('GET', 'validar_qr.php', [], false);
        $this->assertSame(405, $codigo);
    }

    /* ---------------------- Vistas y cierre ---------------------- */

    public function test19DashboardMuestraConteosDelDia(): void
    {
        [$codigo, , $cuerpo] = $this->http('GET', 'dashboard.php');
        $this->assertSame(200, $codigo);
        $this->assertStringContainsString('Entradas Hoy', $cuerpo);
        $this->assertMatchesRegularExpression('/Entradas Hoy<\/h5>\s*<div class="numero">\s*[1-9]/', $cuerpo);
        $this->assertMatchesRegularExpression('/Salidas Hoy<\/h5>\s*<div class="numero">\s*[1-9]/', $cuerpo);
    }

    public function test20RegistrosMuestraElHistorial(): void
    {
        [, , $cuerpo] = $this->http('GET', 'registros.php');
        $this->assertStringContainsString('Prueba Integracion', $cuerpo);
    }

    public function test21NombresSeMuestranEscapados(): void
    {
        self::$db->prepare('UPDATE usuarios SET nombre = ? WHERE id = ?')
                 ->execute(['<b>Prueba Integracion</b>', self::$usuario['id']]);

        [, , $cuerpo] = $this->http('GET', 'usuarios.php');
        $this->assertStringContainsString('&lt;b&gt;Prueba Integracion&lt;/b&gt;', $cuerpo);
        $this->assertStringNotContainsString('<b>Prueba Integracion</b>', $cuerpo);
    }

    public function test22EliminarUsuarioConservaHistorial(): void
    {
        $this->http('GET', 'eliminar_usuario.php?id=' . self::$usuario['id']);

        $stmt = self::$db->prepare('SELECT COUNT(*) FROM usuarios WHERE id = ?');
        $stmt->execute([self::$usuario['id']]);
        $this->assertSame(0, (int) $stmt->fetchColumn());

        [, , $cuerpo] = $this->http('GET', 'registros.php');
        $this->assertStringContainsString('(usuario eliminado)', $cuerpo);
    }

    public function test23CerrarSesion(): void
    {
        [$codigo, $headers] = $this->http('GET', 'cerrar_sesion.php');
        $this->assertSame(302, $codigo);
        $this->assertStringContainsString('Location: login.php', $headers);

        [$codigo] = $this->http('GET', 'usuarios.php');
        $this->assertSame(302, $codigo);
    }
}
