-- =====================================================================
-- MICAJHOVIC - Base de datos control_qr (MySQL 8 / MariaDB 10.4+)
-- Esquema de la Fase 2 (MVP). Ejecutar sobre una base vacía.
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '-05:00';

CREATE TABLE IF NOT EXISTS administradores (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  correo    VARCHAR(100) NOT NULL UNIQUE,
  password  VARCHAR(255) NOT NULL          -- hash bcrypt (password_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS usuarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nombre     VARCHAR(100) NOT NULL,
  documento  VARCHAR(20)  NOT NULL UNIQUE,
  correo     VARCHAR(100) NOT NULL UNIQUE,
  password   VARCHAR(100) NOT NULL,
  qr_token   CHAR(32)     NOT NULL UNIQUE    -- token del QR dinámico (cambia en cada uso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS registros (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id  INT NULL,                       -- NULL si el usuario fue eliminado (se conserva el historial)
  fecha       DATE NOT NULL,
  hora        TIME NOT NULL,
  tipo        ENUM('ENTRADA','SALIDA') NOT NULL,
  INDEX idx_registros_usuario (usuario_id, id),
  INDEX idx_registros_fecha (fecha, tipo),
  CONSTRAINT fk_registros_usuario FOREIGN KEY (usuario_id)
    REFERENCES usuarios (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Administrador inicial: admin@gmail.com / 1234  (CAMBIARLA después del primer ingreso)
INSERT INTO administradores (correo, password) VALUES
('admin@gmail.com', '$2y$10$EIrTH0ZU9bRb3kwMiRdtreqFg0VAVASN7ia4IBuuMZLoPprDvTdwW');
