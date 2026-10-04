# MICAJHOVIC – Control de acceso con QR dinámicos

Sistema de registro de **entradas y salidas** con códigos QR que **cambian en cada uso**.
El usuario abre la app, inicia sesión y se le muestra un QR. En la portería, el vigilante lo escanea;
el sistema registra ENTRADA o SALIDA y ese QR deja de servir (queda "quemado").

Proyecto de aula – Desarrollo de aplicaciones en la nube · Fundación Universitaria Los Libertadores

| Integrante | Rol en la Fase 2 |
|---|---|
| Jhonatan Steven Florez Pinzon | Líder técnico / Arquitecto · DevOps |
| Camilo Gonzalez Bustos | Desarrollador Backend |
| Miguel Andres Rojas Hoyos | Desarrollador Frontend (panel web y app Android) |
| Victor Daniel Oviedo Fino | QA / Tester |

## Arquitectura

```
App Android (MCJV)  ──HTTPS/JSON──►  Backend PHP 8.2 (Render, Docker)  ──TLS──►  MySQL (Aiven)
Panel web admin     ──HTTPS/HTML──►        (mismo servicio)
```

- **Backend:** PHP 8.2 + PDO, endpoints REST para la app y páginas del panel administrativo.
- **Frontend web:** HTML + Bootstrap 5 + Font Awesome (panel del administrador).
- **App móvil:** Android (Java), Volley para HTTP y ZXing para generar y leer QR.
- **Base de datos:** MySQL 8 (en desarrollo: MariaDB de XAMPP).

## Estructura

```
backend/            Código PHP (se publica tal cual en el servidor)
  includes/         env.php (variables de entorno) y funciones.php (lógica de negocio)
  conexion.php      Conexión PDO usando variables de entorno
  login_app.php     API: login de la app y token nuevo
  validar_qr.php    API: validar QR y registrar ENTRADA/SALIDA
  *.php             Panel admin: login, dashboard, usuarios, registros
database/           control_qr.sql (esquema MySQL), seed_demo.sql, schema_sqlite.sql (solo pruebas)
docs/               openapi.yaml (Swagger) y colección de Postman
mobile/MCJVAPP/     Proyecto Android Studio
tests/              Pruebas unitarias e integración (PHPUnit)
.github/workflows/  CI/CD con GitHub Actions
Dockerfile          Imagen para el despliegue
render.yaml         Configuración del servicio en Render
```

## Ramas

| Rama | Uso |
|---|---|
| `main` | Versión estable. Cada push despliega a producción si pasan las pruebas. |
| `develop` | Integración del sprint. Aquí llegan los pull requests de las features. |
| `feature/<nombre>` | Una rama por historia de usuario, ej. `feature/qr-dinamico`. |
| `fix/<nombre>` | Corrección de bugs reportados en Issues. |

Flujo: `feature/*` → Pull Request a `develop` (revisión de al menos 1 compañero) → PR de `develop` a `main`.

## Correr en local (XAMPP)

1. Clonar el repositorio dentro de `C:\xampp\htdocs\micajhovic`.
2. En phpMyAdmin crear la base `control_qr` e importar `database/control_qr.sql`
   (opcional: `database/seed_demo.sql` para datos de prueba).
3. Copiar `.env.example` como `backend/.env` y ajustar los datos de MySQL.
4. Abrir `http://localhost/micajhovic/backend/` → admin `admin@gmail.com` / `1234`
   (cambiar la clave después del primer ingreso).
5. En la app, cambiar `api_base_url` en `mobile/MCJVAPP/app/src/main/res/values/strings.xml`
   (con ngrok o la IP del PC si se prueba en el celular).

## Pruebas

```bash
composer install
vendor/bin/phpunit --testsuite unit            # lógica de negocio

# Integración sin MySQL (usa SQLite):
php tests/Integration/preparar_sqlite.php /tmp/test.db
DB_DRIVER=sqlite DB_NAME=/tmp/test.db php -S 127.0.0.1:8000 -t backend &
BASE_URL=http://127.0.0.1:8000 DB_DRIVER=sqlite DB_NAME=/tmp/test.db vendor/bin/phpunit --testsuite integracion
```

En GitHub Actions la integración corre contra un MySQL 8 real.

## Despliegue en la nube

1. **Base de datos – Aiven (plan gratis de MySQL):** crear el servicio, descargar el `ca.pem`
   y ejecutar `database/control_qr.sql` desde MySQL Workbench o la consola.
2. **Backend – Render:** New → Blueprint → seleccionar este repositorio (usa `render.yaml`).
   En *Environment* llenar `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` y
   `DB_SSL_CA_CONTENT` (contenido del `ca.pem`).
3. **CI/CD:** en Render copiar el *Deploy Hook* y guardarlo en GitHub →
   Settings → Secrets → Actions como `RENDER_DEPLOY_HOOK_URL`.
   Desde ahí, cada push a `main` corre las pruebas y, si pasan, despliega.
4. **App:** poner la URL de Render en `api_base_url` y generar el APK.

> El plan gratis de Render apaga el servicio tras 15 min sin uso; la primera petición tarda unos segundos.

## API

Documentación completa en [`docs/openapi.yaml`](docs/openapi.yaml) (abrir en https://editor.swagger.io)
y colección en [`docs/MICAJHOVIC.postman_collection.json`](docs/MICAJHOVIC.postman_collection.json).

| Método | Ruta | Descripción |
|---|---|---|
| POST | `/login_app.php` | Login de la app, devuelve un token nuevo para el QR |
| POST | `/validar_qr.php` | Valida el QR, registra ENTRADA/SALIDA y quema el token |
| POST | `/validar.php` | Login del administrador |
| POST | `/guardar_usuario.php` | Crear usuario (genera correo, clave y token) |
| GET | `/eliminar_usuario.php?id=` | Eliminar usuario (conserva su historial) |
| GET | `/dashboard.php`, `/usuarios.php`, `/registros.php` | Vistas del panel |
