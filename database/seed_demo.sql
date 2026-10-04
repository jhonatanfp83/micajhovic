-- Datos de demostración (tomados de la base de desarrollo local)
-- Ejecutar DESPUÉS de control_qr.sql

INSERT INTO usuarios (id, nombre, documento, correo, password, qr_token) VALUES
(28, 'carolina', '10', 'carolina_10_7365@mcjv.com', 'L6T9W7UQ', 'e20a0bdbbc983b5d0890b995f21c59c9'),
(29, 'esneider', '14', 'esneider_14_1963@mcjv.com', '1UMBJE38', 'b756a86999f62f9b6611c00886307839'),
(30, 'mateo', '15', 'mateo_15_4444@mcjv.com', 'F5XMUSB2', 'adbed4c1a0222e423401b7614430de59'),
(31, 'dennys', '100000', 'dennys_0000_6295@mcjv.com', 'PISN15BZ', '379afd598d127323687740413a1d8310'),
(32, 'fernanda', '11', 'fernanda_11_8700@mcjv.com', 'LSDXGYBI', '7525de4b5bdf92c12c32e482137d8f01'),
(33, 'laura', '16', 'laura_16_5629@mcjv.com', 'RZJ9FCQK', 'f884c492282d3aadb2c6389b4fb0fa05'),
(34, 'santiago', '17', 'santiago_17_4517@mcjv.com', 'ZWFVYLKG', '617a8779348065a9c0b5843ca8ca3869'),
(35, 'camilo', '100', 'camilo_100_5955@mcjv.com', 'XAZGJ3NF', 'a68e11839884a21150358af697656ef1');

INSERT INTO registros (usuario_id, fecha, hora, tipo) VALUES
(28, '2026-05-27', '17:14:46', 'ENTRADA'),
(28, '2026-05-27', '17:17:57', 'SALIDA'),
(28, '2026-05-27', '17:18:10', 'ENTRADA'),
(28, '2026-05-27', '17:29:45', 'SALIDA'),
(29, '2026-05-27', '17:36:04', 'ENTRADA'),
(30, '2026-05-27', '17:47:10', 'ENTRADA'),
(30, '2026-05-27', '17:47:56', 'SALIDA'),
(31, '2026-05-27', '18:04:26', 'ENTRADA'),
(31, '2026-05-27', '18:05:29', 'SALIDA'),
(31, '2026-05-27', '18:11:14', 'ENTRADA'),
(32, '2026-05-27', '19:11:37', 'ENTRADA'),
(32, '2026-05-27', '19:12:35', 'SALIDA'),
(33, '2026-05-27', '19:28:44', 'ENTRADA'),
(33, '2026-05-27', '19:29:32', 'SALIDA'),
(34, '2026-05-27', '19:40:29', 'ENTRADA'),
(34, '2026-05-27', '19:41:10', 'SALIDA'),
(35, '2026-05-27', '20:26:22', 'ENTRADA'),
(35, '2026-05-27', '20:27:03', 'SALIDA');
