-- Esquema equivalente en SQLite. SOLO para pruebas automáticas locales.
-- En desarrollo y producción se usa control_qr.sql (MySQL).
CREATE TABLE administradores (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  correo TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL
);
CREATE TABLE usuarios (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  nombre TEXT NOT NULL,
  documento TEXT NOT NULL UNIQUE,
  correo TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  qr_token TEXT NOT NULL UNIQUE
);
CREATE TABLE registros (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  usuario_id INTEGER NULL REFERENCES usuarios(id) ON DELETE SET NULL,
  fecha TEXT NOT NULL,
  hora TEXT NOT NULL,
  tipo TEXT NOT NULL CHECK (tipo IN ('ENTRADA','SALIDA'))
);
