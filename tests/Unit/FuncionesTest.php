<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../backend/includes/funciones.php';

/**
 * Pruebas unitarias de la lógica de negocio (backend/includes/funciones.php).
 */
final class FuncionesTest extends TestCase
{
    /* ---------- Alternancia ENTRADA / SALIDA ---------- */

    public function testPrimerRegistroEsEntrada(): void
    {
        $this->assertSame('ENTRADA', siguienteTipo(null));
    }

    public function testDespuesDeEntradaVieneSalida(): void
    {
        $this->assertSame('SALIDA', siguienteTipo('ENTRADA'));
    }

    public function testDespuesDeSalidaVieneEntrada(): void
    {
        $this->assertSame('ENTRADA', siguienteTipo('SALIDA'));
    }

    public function testTipoEnMinusculaSeInterpretaIgual(): void
    {
        $this->assertSame('SALIDA', siguienteTipo('entrada'));
    }

    /* ---------- Token del QR dinámico ---------- */

    public function testTokenTiene32CaracteresHex(): void
    {
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', generarToken());
    }

    public function testTokensNoSeRepiten(): void
    {
        $tokens = [];
        for ($i = 0; $i < 1000; $i++) {
            $tokens[] = generarToken();
        }
        $this->assertCount(1000, array_unique($tokens));
    }

    public function testFormatoDeTokenValido(): void
    {
        $this->assertTrue(tokenTieneFormato(generarToken()));
    }

    public function testFormatoDeTokenInvalido(): void
    {
        $this->assertFalse(tokenTieneFormato(''));
        $this->assertFalse(tokenTieneFormato("' OR '1'='1"));
        $this->assertFalse(tokenTieneFormato(str_repeat('z', 32)));
        $this->assertFalse(tokenTieneFormato(str_repeat('a', 31)));
    }

    /* ---------- Credenciales generadas ---------- */

    public function testPasswordTiene8CaracteresPermitidos(): void
    {
        $password = generarPassword();
        $this->assertSame(8, strlen($password));
        $this->assertMatchesRegularExpression('/^[1-9A-NP-Z]{8}$/', $password);
    }

    public function testCorreoSeGeneraConFormatoEsperado(): void
    {
        $this->assertSame(
            'juanperez_5678_1234@mcjv.com',
            generarCorreo('Juan  Perez', '1012345678', 1234)
        );
    }

    public function testCorreoQuitaTildesYEnes(): void
    {
        $this->assertSame(
            'mariaagudelonunez_4321_9999@mcjv.com',
            generarCorreo('María Agudelo Núñez', '87654321', 9999)
        );
    }

    public function testCorreoEsValido(): void
    {
        $correo = generarCorreo('Víctor Oviedo', '1000123456', 4321);
        $this->assertNotFalse(filter_var($correo, FILTER_VALIDATE_EMAIL));
    }

    /* ---------- Validaciones del formulario ---------- */

    public function testUsuarioValidoNoTieneErrores(): void
    {
        $this->assertSame([], validarUsuario('Camilo Gonzalez', '1012345678'));
    }

    public function testNombreVacioEsRechazado(): void
    {
        $this->assertContains('El nombre es obligatorio', validarUsuario('   ', '1012345678'));
    }

    public function testNombreConNumerosOEtiquetasEsRechazado(): void
    {
        $this->assertNotEmpty(validarUsuario('Pedro123', '1012345678'));
        $this->assertNotEmpty(validarUsuario('<script>alert(1)</script>', '1012345678'));
    }

    public function testDocumentoConLetrasEsRechazado(): void
    {
        $this->assertNotEmpty(validarUsuario('Laura Diaz', '10AB345'));
    }

    public function testDocumentoMuyCortoEsRechazado(): void
    {
        $this->assertNotEmpty(validarUsuario('Laura Diaz', '123'));
    }

    public function testNormalizarNombreQuitaEspacios(): void
    {
        $this->assertSame('Miguel Rojas', normalizarNombre("  Miguel    Rojas  "));
    }

    /* ---------- Contraseña del administrador ---------- */

    public function testPasswordAdminConHash(): void
    {
        $hash = password_hash('1234', PASSWORD_DEFAULT);
        $this->assertTrue(verificarPasswordAdmin('1234', $hash, $rehash));
        $this->assertFalse($rehash);
        $this->assertFalse(verificarPasswordAdmin('0000', $hash));
    }

    public function testPasswordAdminEnTextoPlanoPideMigrar(): void
    {
        $this->assertTrue(verificarPasswordAdmin('1234', '1234', $rehash));
        $this->assertTrue($rehash);
    }

    /* ---------- Escape HTML ---------- */

    public function testEscapeEvitaXss(): void
    {
        $this->assertSame('&lt;b&gt;hola&lt;/b&gt;', e('<b>hola</b>'));
    }
}
