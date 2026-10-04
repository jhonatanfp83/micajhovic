-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-06-2026 a las 12:59:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `control_qr`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id`, `correo`, `password`) VALUES
(1, 'admin@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `tipo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id`, `usuario_id`, `fecha`, `hora`, `tipo`) VALUES
(1, 13, '2026-05-27', '07:55:32', 'ENTRADA'),
(2, 13, '2026-05-27', '08:05:04', 'ENTRADA'),
(3, 13, '2026-05-27', '08:05:45', 'ENTRADA'),
(4, 13, '2026-05-27', '08:08:50', 'SALIDA'),
(5, 15, '2026-05-27', '08:25:33', 'ENTRADA'),
(6, 15, '2026-05-27', '08:26:24', 'SALIDA'),
(7, 15, '2026-05-27', '09:35:39', 'ENTRADA'),
(8, 15, '2026-05-27', '09:36:25', 'SALIDA'),
(9, 15, '2026-05-27', '10:18:34', 'ENTRADA'),
(10, 20, '2026-05-27', '11:47:56', 'ENTRADA'),
(11, 19, '2026-05-27', '11:48:34', 'ENTRADA'),
(12, 19, '2026-05-27', '11:49:26', 'SALIDA'),
(13, 20, '2026-05-27', '12:10:07', 'SALIDA'),
(14, 20, '2026-05-27', '12:11:59', 'ENTRADA'),
(15, 21, '2026-05-27', '12:16:00', 'ENTRADA'),
(16, 22, '2026-05-27', '12:22:02', 'ENTRADA'),
(17, 22, '2026-05-27', '12:23:13', 'SALIDA'),
(18, 22, '2026-05-27', '13:07:39', 'ENTRADA'),
(19, 23, '2026-05-27', '15:14:06', 'ENTRADA'),
(20, 23, '2026-05-27', '15:14:34', 'SALIDA'),
(21, 24, '2026-05-27', '16:31:23', 'ENTRADA'),
(22, 24, '2026-05-27', '16:33:23', 'SALIDA'),
(23, 25, '2026-05-27', '16:43:47', 'ENTRADA'),
(24, 25, '2026-05-27', '16:44:38', 'SALIDA'),
(25, 26, '2026-05-27', '16:51:00', 'ENTRADA'),
(26, 26, '2026-05-27', '16:51:29', 'SALIDA'),
(27, 27, '2026-05-27', '16:57:45', 'ENTRADA'),
(28, 27, '2026-05-27', '16:58:21', 'SALIDA'),
(29, 28, '2026-05-27', '17:14:46', 'ENTRADA'),
(30, 28, '2026-05-27', '17:17:57', 'SALIDA'),
(31, 28, '2026-05-27', '17:18:10', 'ENTRADA'),
(32, 28, '2026-05-27', '17:29:45', 'SALIDA'),
(33, 29, '2026-05-27', '17:36:04', 'ENTRADA'),
(34, 30, '2026-05-27', '17:47:10', 'ENTRADA'),
(35, 30, '2026-05-27', '17:47:56', 'SALIDA'),
(36, 31, '2026-05-27', '18:04:26', 'ENTRADA'),
(37, 31, '2026-05-27', '18:05:29', 'SALIDA'),
(38, 31, '2026-05-27', '18:11:14', 'ENTRADA'),
(39, 32, '2026-05-27', '19:11:37', 'ENTRADA'),
(40, 32, '2026-05-27', '19:12:35', 'SALIDA'),
(41, 33, '2026-05-27', '19:28:44', 'ENTRADA'),
(42, 33, '2026-05-27', '19:29:32', 'SALIDA'),
(43, 34, '2026-05-27', '19:40:29', 'ENTRADA'),
(44, 34, '2026-05-27', '19:41:10', 'SALIDA'),
(45, 35, '2026-05-27', '20:26:22', 'ENTRADA'),
(46, 35, '2026-05-27', '20:27:03', 'SALIDA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `documento` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `qr_token` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `documento`, `correo`, `password`, `qr_token`) VALUES
(28, 'carolina', '10', 'carolina_10_7365@mcjv.com', 'L6T9W7UQ', 'e20a0bdbbc983b5d0890b995f21c59c9'),
(29, 'esneider', '14', 'esneider_14_1963@mcjv.com', '1UMBJE38', 'b756a86999f62f9b6611c00886307839'),
(30, 'mateo', '15', 'mateo_15_4444@mcjv.com', 'F5XMUSB2', 'adbed4c1a0222e423401b7614430de59'),
(31, 'dennys', '100000', 'dennys_0000_6295@mcjv.com', 'PISN15BZ', '379afd598d127323687740413a1d8310'),
(32, 'fernanda', '11', 'fernanda_11_8700@mcjv.com', 'LSDXGYBI', '7525de4b5bdf92c12c32e482137d8f01'),
(33, 'laura', '16', 'laura_16_5629@mcjv.com', 'RZJ9FCQK', 'f884c492282d3aadb2c6389b4fb0fa05'),
(34, 'santiago', '17', 'santiago_17_4517@mcjv.com', 'ZWFVYLKG', '617a8779348065a9c0b5843ca8ca3869'),
(35, 'camilo', '100', 'camilo_100_5955@mcjv.com', 'XAZGJ3NF', 'a68e11839884a21150358af697656ef1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
