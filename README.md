# MICAJHOVIC – Control de acceso con QR dinámicos

Sistema de registro de **entradas y salidas** con códigos QR que **cambian en cada uso**.
El usuario abre la app, inicia sesión y se le muestra un QR. En la entrada, el sistema de escaneo de la universidad lo lee;
el sistema registra ENTRADA o SALIDA y ese QR deja de servir (queda "quemado").

Proyecto de aula – Desarrollo de aplicaciones en la nube · Fundación Universitaria Los Libertadores

## Enlaces

| Qué | Enlace |
|---|---|
| Panel web y API (producción) | https://micajhovic-api.onrender.com |
| App Android (APK) | https://github.com/jhonatanfp83/micajhovic/releases/latest |
| Documentación de la API | [`docs/openapi.yaml`](docs/openapi.yaml) · [Postman](docs/MICAJHOVIC.postman_collection.json) |
| Pipeline CI/CD | https://github.com/jhonatanfp83/micajhovic/actions |
| Bugs e historias | https://github.com/jhonatanfp83/micajhovic/issues |

| Integrante | Rol |
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
  token_actual.php  API: token vigente (la app refresca el QR tras cada escaneo)
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

## Requisitos

| Para | Necesitas |
|---|---|
| Backend en local | PHP 8.1 o superior con `pdo_mysql` (XAMPP 8.2 sirve) y MySQL 8 o MariaDB 10.4+ |
| Pruebas | Composer 2 (instala PHPUnit 10) y la extensión `pdo_sqlite` |
| Contenedor (opcional) | Docker Desktop |
| App móvil | Android Studio (o solo instalar el APK de la release `app-movil`) |

## Variables de entorno

En local se leen de `backend/.env` (copiar `.env.example`). En la nube se configuran en Render → *Environment*.
El archivo `.env` nunca se sube al repositorio (está en `.gitignore`).

| Variable | Ejemplo | Para qué sirve |
|---|---|---|
| `DB_DRIVER` | `mysql` | `mysql` en local y producción; `sqlite` solo para pruebas |
| `DB_HOST` | `localhost` | Servidor de MySQL (en producción, el host de Aiven) |
| `DB_PORT` | `3306` | Puerto de MySQL |
| `DB_NAME` | `control_qr` | Nombre de la base de datos |
| `DB_USER` | `root` | Usuario de MySQL |
| `DB_PASS` | *(vacío en XAMPP)* | Clave de MySQL |
| `DB_SSL` | `false` | `true` si la base exige TLS (Aiven) |
| `DB_SSL_CA_CONTENT` | *(contenido del `ca.pem`)* | Certificado de la base en la nube |
| `APP_TIMEZONE` | `America/Bogota` | Zona horaria de los registros |
| `AUTO_MIGRAR` | `true` | Crea las tablas solas en el primer arranque (poner `false` para desactivarlo) |
| `ADMIN_EMAIL` | `admin@gmail.com` | Correo del primer administrador (solo si la tabla está vacía) |
| `ADMIN_PASSWORD` | *(definir)* | Clave del primer administrador (si no se pone, es `1234`; cambiarla) |

Secreto de GitHub Actions: `RENDER_DEPLOY_HOOK_URL` (despliegue automático en Render).

## Correr en local (XAMPP)

1. Clonar el repositorio dentro de `C:\xampp\htdocs\micajhovic`.
2. En phpMyAdmin crear la base `control_qr` e importar `database/control_qr.sql`
   (opcional: `database/seed_demo.sql` para datos de prueba).
3. Copiar `.env.example` como `backend/.env` y ajustar los datos de MySQL.
4. Abrir `http://localhost/micajhovic/backend/` → admin `admin@gmail.com` / `1234`
   (cambiar la clave después del primer ingreso).
5. En la app, cambiar `api_base_url` en `mobile/MCJVAPP/app/src/main/res/values/strings.xml`
   (con ngrok o la IP del PC si se prueba en el celular).

## Correr en local con Docker (igual que en producción)

```bash
docker build -t micajhovic .
docker run --rm -p 8080:8080 --env-file backend/.env micajhovic
# abrir http://localhost:8080/login.php
```

Si MySQL corre en el mismo PC, en `backend/.env` usar `DB_HOST=host.docker.internal`.
Con `AUTO_MIGRAR=true` las tablas y el primer administrador se crean solos.

## Uso rápido

1. El administrador entra al panel, crea el usuario con nombre y documento, y le entrega el correo y la clave que muestra el aviso (solo se ven una vez).
2. El usuario instala la app, inicia sesión y ve su QR.
3. En la entrada, el sistema de escaneo lee el QR: registra ENTRADA o SALIDA y el QR cambia solo en la app.
4. El administrador revisa el conteo del día en *Inicio* y el historial en *Registros*.

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
| POST | `/token_actual.php` | Token vigente del QR; la app lo consulta para mostrar el QR nuevo después de cada escaneo |
| POST | `/validar_qr.php` | Valida el QR, registra ENTRADA/SALIDA y quema el token |
| POST | `/validar.php` | Login del administrador |
| POST | `/guardar_usuario.php` | Crear usuario (genera correo, clave y token; la clave se muestra una sola vez y se guarda cifrada) |
| POST | `/eliminar_usuario.php` | Eliminar usuario con token CSRF (conserva su historial) |
| GET | `/dashboard.php`, `/usuarios.php`, `/registros.php` | Vistas del panel |
