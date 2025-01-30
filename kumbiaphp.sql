-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para kumbiaphp
CREATE DATABASE IF NOT EXISTS `kumbiaphp` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `kumbiaphp`;

-- Volcando estructura para tabla kumbiaphp.acceso
CREATE TABLE IF NOT EXISTS `acceso` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del acceso',
  `usuario_id` int NOT NULL COMMENT 'Identificador del usuario que accede',
  `tipo_acceso` int NOT NULL DEFAULT '1' COMMENT 'Tipo de acceso (entrata o salida)',
  `ip` varchar(45) DEFAULT NULL COMMENT 'Dirección IP del usuario que ingresa',
  `acceso_at` datetime DEFAULT NULL COMMENT 'Fecha de registro del acceso',
  PRIMARY KEY (`id`),
  KEY `fk_acceso_usuario_idx` (`usuario_id`),
  CONSTRAINT `acceso_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb3 COMMENT='Tabla que registra los accesos de los usuarios al sistema';

-- Volcando datos para la tabla kumbiaphp.acceso: ~144 rows (aproximadamente)
REPLACE INTO `acceso` (`id`, `usuario_id`, `tipo_acceso`, `ip`, `acceso_at`) VALUES
	(1, 1, 2, '127.0.0.1', '2024-04-28 13:01:03'),
	(2, 2, 1, '127.0.0.1', '2024-04-28 13:03:15'),
	(3, 2, 2, '127.0.0.1', '2024-04-28 13:04:53'),
	(4, 2, 1, '127.0.0.1', '2024-04-28 13:04:57'),
	(5, 2, 2, '127.0.0.1', '2024-04-28 13:10:38'),
	(6, 1, 1, '127.0.0.1', '2024-04-28 13:38:01'),
	(7, 1, 2, '127.0.0.1', '2024-04-28 14:01:47'),
	(8, 1, 1, '127.0.0.1', '2024-04-28 14:36:58'),
	(9, 1, 2, '127.0.0.1', '2024-04-28 15:04:17'),
	(10, 2, 1, '127.0.0.1', '2024-04-28 15:04:24'),
	(11, 2, 2, '127.0.0.1', '2024-04-28 15:19:34'),
	(12, 1, 1, '127.0.0.1', '2024-04-28 17:43:24'),
	(13, 1, 2, '127.0.0.1', '2024-04-28 18:31:59'),
	(14, 1, 1, '127.0.0.1', '2024-04-28 18:46:35'),
	(15, 1, 2, '127.0.0.1', '2024-04-28 22:34:55'),
	(16, 1, 1, '127.0.0.1', '2024-04-28 22:35:12'),
	(17, 1, 2, '127.0.0.1', '2024-04-28 22:41:37'),
	(18, 1, 1, '127.0.0.1', '2024-04-28 22:44:45'),
	(19, 1, 2, '127.0.0.1', '2024-04-28 22:45:30'),
	(20, 1, 1, '127.0.0.1', '2024-04-28 22:46:13'),
	(21, 1, 2, '127.0.0.1', '2024-04-28 23:18:21'),
	(22, 2, 1, '127.0.0.1', '2024-04-28 23:18:26'),
	(23, 2, 2, '127.0.0.1', '2024-04-28 23:19:44'),
	(24, 1, 1, '127.0.0.1', '2024-04-28 23:19:51'),
	(25, 1, 2, '127.0.0.1', '2024-04-28 23:41:45'),
	(26, 1, 1, '127.0.0.1', '2024-04-28 23:42:02'),
	(27, 1, 2, '127.0.0.1', '2024-04-29 18:15:32'),
	(28, 1, 1, '127.0.0.1', '2024-04-29 22:03:52'),
	(29, 1, 2, '127.0.0.1', '2024-04-29 23:21:36'),
	(30, 1, 1, '127.0.0.1', '2024-04-29 23:24:24'),
	(31, 1, 2, '127.0.0.1', '2024-04-29 23:36:30'),
	(32, 2, 1, '127.0.0.1', '2024-04-29 23:36:34'),
	(33, 2, 1, '127.0.0.1', '2024-04-30 00:01:12'),
	(34, 1, 1, '127.0.0.1', '2024-06-07 18:44:46'),
	(35, 1, 2, '127.0.0.1', '2024-06-07 19:52:47'),
	(36, 1, 1, '127.0.0.1', '2024-06-07 20:42:56'),
	(37, 1, 2, '127.0.0.1', '2024-06-07 20:59:17'),
	(38, 1, 1, '127.0.0.1', '2024-06-07 21:00:21'),
	(39, 1, 2, '127.0.0.1', '2024-06-07 21:57:49'),
	(40, 1, 1, '127.0.0.1', '2024-06-08 10:40:29'),
	(41, 1, 2, '127.0.0.1', '2024-06-08 11:00:09'),
	(42, 1, 1, '127.0.0.1', '2024-06-08 11:00:21'),
	(43, 1, 2, '127.0.0.1', '2024-06-08 11:06:40'),
	(44, 1, 1, '127.0.0.1', '2024-06-08 11:14:17'),
	(45, 1, 2, '127.0.0.1', '2024-06-08 11:20:36'),
	(46, 1, 1, '127.0.0.1', '2024-06-08 11:25:02'),
	(47, 1, 2, '127.0.0.1', '2024-06-08 11:38:01'),
	(48, 1, 1, '127.0.0.1', '2024-06-08 12:25:28'),
	(49, 1, 2, '127.0.0.1', '2024-06-08 12:33:16'),
	(50, 1, 1, '127.0.0.1', '2024-06-08 12:36:23'),
	(51, 1, 2, '127.0.0.1', '2024-06-08 12:47:36'),
	(52, 1, 1, '127.0.0.1', '2024-06-08 12:48:58'),
	(53, 1, 2, '127.0.0.1', '2024-06-08 13:51:52'),
	(54, 1, 1, '127.0.0.1', '2024-06-08 13:55:55'),
	(55, 1, 2, '127.0.0.1', '2024-06-08 14:02:02'),
	(56, 1, 1, '127.0.0.1', '2024-06-08 14:04:58'),
	(57, 1, 2, '127.0.0.1', '2024-06-08 14:11:06'),
	(58, 1, 1, '127.0.0.1', '2024-06-08 14:11:13'),
	(59, 1, 2, '127.0.0.1', '2024-06-08 14:22:48'),
	(60, 1, 1, '127.0.0.1', '2024-06-08 14:30:33'),
	(61, 1, 2, '127.0.0.1', '2024-06-08 16:20:36'),
	(62, 1, 1, '127.0.0.1', '2024-06-08 16:27:06'),
	(63, 1, 1, '127.0.0.1', '2024-06-08 20:59:26'),
	(64, 1, 1, '127.0.0.1', '2024-06-08 21:12:52'),
	(65, 1, 1, '127.0.0.1', '2024-06-08 21:20:42'),
	(66, 2, 1, '127.0.0.1', '2024-06-08 21:30:24'),
	(67, 2, 2, '127.0.0.1', '2024-06-08 21:30:38'),
	(68, 1, 1, '127.0.0.1', '2024-06-08 21:30:55'),
	(69, 2, 1, '127.0.0.1', '2024-06-08 21:40:23'),
	(70, 2, 2, '127.0.0.1', '2024-06-10 17:12:28'),
	(71, 1, 1, '127.0.0.1', '2024-06-10 17:12:35'),
	(72, 1, 2, '127.0.0.1', '2024-06-10 18:32:26'),
	(73, 1, 2, '127.0.0.1', '2024-06-10 18:32:26'),
	(74, 1, 2, '127.0.0.1', '2024-06-10 18:36:02'),
	(75, 1, 2, '127.0.0.1', '2024-06-10 18:36:02'),
	(76, 1, 1, '127.0.0.1', '2024-06-10 18:36:22'),
	(77, 1, 1, '127.0.0.1', '2024-06-10 18:37:10'),
	(78, 1, 2, '127.0.0.1', '2024-06-10 18:37:23'),
	(79, 1, 2, '127.0.0.1', '2024-06-10 18:38:00'),
	(80, 1, 1, '127.0.0.1', '2024-06-10 18:39:35'),
	(81, 2, 1, '127.0.0.1', '2024-07-27 22:37:23'),
	(82, 2, 2, '127.0.0.1', '2024-07-31 22:14:13'),
	(83, 2, 1, '127.0.0.1', '2024-07-31 22:16:29'),
	(84, 2, 2, '127.0.0.1', '2024-07-31 22:17:43'),
	(85, 2, 1, '127.0.0.1', '2024-07-31 22:17:47'),
	(86, 2, 2, '127.0.0.1', '2024-07-31 22:18:53'),
	(87, 2, 1, '127.0.0.1', '2024-08-01 18:21:33'),
	(88, 2, 2, '127.0.0.1', '2024-08-01 18:22:09'),
	(89, 2, 1, '127.0.0.1', '2024-08-01 18:23:18'),
	(90, 2, 2, '127.0.0.1', '2024-08-01 19:03:18'),
	(91, 2, 1, '127.0.0.1', '2024-08-01 19:03:33'),
	(92, 2, 2, '127.0.0.1', '2024-08-04 14:41:09'),
	(93, 2, 1, '127.0.0.1', '2024-08-04 14:48:43'),
	(94, 2, 2, '127.0.0.1', '2024-08-04 14:54:29'),
	(95, 2, 1, '127.0.0.1', '2024-08-04 14:55:16'),
	(96, 2, 2, '127.0.0.1', '2024-08-04 15:13:35'),
	(97, 2, 1, '127.0.0.1', '2024-08-04 15:16:02'),
	(98, 2, 2, '127.0.0.1', '2024-08-04 15:48:05'),
	(99, 2, 1, '127.0.0.1', '2024-08-04 18:18:48'),
	(100, 2, 2, '127.0.0.1', '2024-08-04 19:24:56'),
	(101, 2, 1, '127.0.0.1', '2024-08-04 19:24:58'),
	(102, 2, 2, '127.0.0.1', '2024-08-04 19:42:01'),
	(103, 2, 1, '127.0.0.1', '2024-08-04 21:41:40'),
	(104, 2, 1, '127.0.0.1', '2024-08-05 18:05:05'),
	(105, 2, 2, '127.0.0.1', '2024-08-06 18:38:41'),
	(106, 2, 1, '127.0.0.1', '2024-08-06 18:38:43'),
	(107, 2, 2, '127.0.0.1', '2024-08-06 18:38:53'),
	(108, 2, 1, '127.0.0.1', '2024-08-06 18:38:58'),
	(109, 2, 2, '127.0.0.1', '2024-08-06 19:14:30'),
	(110, 2, 1, '127.0.0.1', '2024-08-07 21:08:04'),
	(111, 2, 2, '127.0.0.1', '2024-08-07 21:19:52'),
	(112, 1, 1, '127.0.0.1', '2024-08-07 21:21:22'),
	(113, 1, 2, '127.0.0.1', '2024-08-07 21:22:10'),
	(114, 1, 1, '127.0.0.1', '2024-08-07 21:22:13'),
	(115, 1, 2, '127.0.0.1', '2024-08-07 21:28:17'),
	(116, 1, 1, '127.0.0.1', '2024-08-07 21:31:12'),
	(117, 1, 2, '127.0.0.1', '2024-08-07 21:56:32'),
	(118, 1, 1, '127.0.0.1', '2024-08-07 21:57:16'),
	(119, 1, 2, '127.0.0.1', '2024-08-07 22:04:01'),
	(120, 1, 1, '127.0.0.1', '2024-08-07 22:04:56'),
	(121, 1, 2, '127.0.0.1', '2024-08-07 22:10:18'),
	(122, 1, 1, '127.0.0.1', '2024-08-07 22:10:48'),
	(123, 1, 2, '127.0.0.1', '2024-08-07 22:36:32'),
	(124, 1, 1, '127.0.0.1', '2024-08-10 13:53:40'),
	(125, 1, 2, '127.0.0.1', '2024-08-10 14:16:31'),
	(126, 1, 1, '127.0.0.1', '2024-08-10 15:29:58'),
	(127, 1, 2, '127.0.0.1', '2024-08-10 16:00:17'),
	(128, 1, 1, '127.0.0.1', '2024-08-10 16:59:59'),
	(129, 1, 2, '127.0.0.1', '2024-08-10 18:17:08'),
	(130, 1, 1, '127.0.0.1', '2024-08-10 18:34:12'),
	(131, 1, 2, '127.0.0.1', '2024-08-10 19:29:27'),
	(132, 1, 1, '127.0.0.1', '2024-08-10 19:31:02'),
	(133, 1, 2, '127.0.0.1', '2024-08-10 20:12:07'),
	(134, 1, 1, '127.0.0.1', '2024-08-10 20:36:00'),
	(135, 1, 2, '127.0.0.1', '2024-08-10 21:09:49'),
	(136, 1, 1, '127.0.0.1', '2024-08-10 23:04:59'),
	(137, 1, 2, '127.0.0.1', '2024-08-10 23:19:33'),
	(138, 1, 1, '127.0.0.1', '2024-08-10 23:21:04'),
	(139, 1, 2, '127.0.0.1', '2024-08-10 23:32:46'),
	(140, 1, 1, '127.0.0.1', '2024-08-11 10:50:51'),
	(141, 1, 2, '127.0.0.1', '2024-08-11 10:57:08'),
	(142, 1, 1, '127.0.0.1', '2024-08-11 11:08:37'),
	(143, 1, 2, '127.0.0.1', '2024-08-11 11:40:07'),
	(144, 1, 1, '127.0.0.1', '2024-08-11 11:42:20'),
	(145, 1, 2, '127.0.0.1', '2024-08-11 12:35:02'),
	(146, 1, 1, '127.0.0.1', '2024-08-11 12:40:21'),
	(147, 1, 2, '127.0.0.1', '2024-08-11 13:43:18'),
	(148, 2, 1, '127.0.0.1', '2024-08-11 13:43:22'),
	(149, 2, 2, '127.0.0.1', '2024-08-11 15:10:04'),
	(150, 2, 1, '127.0.0.1', '2024-08-11 15:46:03'),
	(151, 2, 2, '127.0.0.1', '2024-08-11 15:54:45'),
	(152, 2, 1, '127.0.0.1', '2024-08-11 16:53:41'),
	(153, 2, 2, '127.0.0.1', '2024-08-11 16:59:00'),
	(154, 2, 1, '127.0.0.1', '2024-08-11 17:01:00'),
	(155, 2, 2, '127.0.0.1', '2024-08-11 17:16:51'),
	(156, 2, 1, '127.0.0.1', '2024-08-11 17:43:17'),
	(157, 2, 2, '127.0.0.1', '2024-08-11 18:24:44'),
	(158, 1, 1, '127.0.0.1', '2024-08-11 18:33:05'),
	(159, 1, 2, '127.0.0.1', '2024-08-11 21:42:13'),
	(160, 1, 1, '127.0.0.1', '2024-08-11 21:42:21'),
	(161, 1, 2, '127.0.0.1', '2024-08-11 22:17:28'),
	(162, 1, 1, '127.0.0.1', '2024-08-12 18:40:26'),
	(163, 1, 2, '127.0.0.1', '2024-08-12 18:45:29'),
	(164, 1, 1, '127.0.0.1', '2024-08-12 21:35:21'),
	(165, 1, 2, '127.0.0.1', '2024-08-12 21:43:03'),
	(166, 1, 1, '127.0.0.1', '2024-08-30 21:38:46'),
	(167, 1, 2, '127.0.0.1', '2024-08-30 21:47:15'),
	(168, 1, 1, '127.0.0.1', '2024-08-31 08:45:30'),
	(169, 1, 2, '127.0.0.1', '2024-08-31 08:55:16'),
	(170, 2, 1, '127.0.0.1', '2024-11-22 23:24:24'),
	(171, 2, 1, '127.0.0.1', '2025-01-21 21:14:55'),
	(172, 2, 2, '127.0.0.1', '2025-01-21 21:33:14'),
	(173, 2, 1, '127.0.0.1', '2025-01-21 21:34:10'),
	(174, 2, 2, '127.0.0.1', '2025-01-21 21:40:01'),
	(175, 2, 1, '127.0.0.1', '2025-01-21 21:41:24'),
	(176, 2, 2, '127.0.0.1', '2025-01-21 21:51:07'),
	(177, 2, 1, '127.0.0.1', '2025-01-21 22:11:35'),
	(178, 2, 2, '127.0.0.1', '2025-01-21 22:31:48'),
	(179, 2, 1, '127.0.0.1', '2025-01-21 22:38:03');

-- Volcando estructura para tabla kumbiaphp.area
CREATE TABLE IF NOT EXISTS `area` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_area_departamento_idx` (`departamento_id`) USING BTREE,
  CONSTRAINT `fk_area_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.area: ~0 rows (aproximadamente)
REPLACE INTO `area` (`id`, `nombre`, `departamento_id`, `created_at`, `updated_at`) VALUES
	(1, 'MEZCLAS', 1, '2023-09-16 15:55:05', '2023-09-16 16:04:43'),
	(2, 'SERVICIOS GENERALES', 2, '2023-09-16 16:01:02', '2023-09-16 16:01:02'),
	(3, 'ENVASADO', 1, '2023-09-16 16:01:19', '2023-09-16 16:04:54'),
	(4, 'MANTENIMIENTO', 3, '2023-09-16 16:01:41', '2023-09-16 16:01:41'),
	(5, 'ADMINISTRACION (CARACAS)', 4, '2023-09-16 16:02:48', '2023-09-16 16:02:48'),
	(7, 'ALMACEN DE MPP', 5, '2023-09-16 16:03:44', '2023-09-16 16:03:44'),
	(8, 'PRODUCCION', 1, '2023-09-16 16:04:30', '2023-09-16 16:04:30'),
	(9, 'ALMACEN Y DESPACHO', 6, '2023-09-16 16:10:08', '2023-09-16 16:10:08'),
	(10, 'DIRECCION', 1, '2023-09-16 16:10:54', '2023-09-16 16:10:54'),
	(11, 'ADMINISTRACION (OCUMARE)', 4, '2023-09-16 16:11:10', '2023-09-16 16:11:10'),
	(12, 'DIRECCION', 8, '2023-09-16 16:11:55', '2023-09-16 16:11:55'),
	(13, 'DIRECCION', 10, '2023-09-16 16:23:46', '2023-09-16 16:23:46'),
	(14, 'DIRECCION', 11, '2023-09-16 16:23:56', '2023-09-16 16:23:56'),
	(15, 'DIRECCION', 12, '2023-09-16 16:24:08', '2023-09-16 16:24:08'),
	(16, 'DIRECCION', 9, '2023-09-16 16:24:45', '2023-09-16 16:24:45'),
	(17, 'ALMACEN DE REPUESTOS', 13, '2023-09-16 16:25:20', '2023-09-16 16:25:20'),
	(18, 'DIRECCION', 11, '2023-09-16 16:25:41', '2023-09-16 16:25:41'),
	(19, 'ALMACEN Y DESPACHO', 13, '2023-09-16 16:28:57', '2023-09-16 16:28:57'),
	(20, 'CONTROL DE CALIDAD', 9, '2023-09-16 16:29:45', '2023-09-16 16:29:45'),
	(21, 'DIRECCION', 17, '2023-09-16 16:30:56', '2023-09-16 16:30:56'),
	(23, 'RECURSOS HUMANOS (OCUMARE)', 15, '2023-09-16 16:33:55', '2023-09-16 16:33:55'),
	(24, 'SEGURIDAD INTEGRAL', 16, '2023-09-16 16:34:16', '2023-09-16 16:34:16'),
	(25, 'TRANSPORTE', 6, '2023-09-16 16:35:22', '2023-09-16 16:35:22'),
	(26, 'VENTAS', 12, '2023-09-16 16:35:52', '2023-09-16 16:36:26'),
	(27, 'DIRECCION', 3, NULL, NULL);

-- Volcando estructura para tabla kumbiaphp.cargo
CREATE TABLE IF NOT EXISTS `cargo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.cargo: ~0 rows (aproximadamente)
REPLACE INTO `cargo` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
	(1, 'OPERADOR DE REACTOR II', '2023-09-16 04:50:36', '2023-09-16 04:50:36'),
	(2, 'OPERADOR ESPECIALIZADO DE REACTOR', '2023-09-16 04:50:47', '2023-09-16 04:50:47'),
	(3, 'PINTOR', '2023-09-16 04:50:58', '2023-09-16 04:50:58'),
	(4, 'OPERADOR DE REACTOR I', '2023-09-16 04:51:09', '2023-09-16 04:51:09'),
	(5, 'OPERADOR DE ENVASADO', '2023-09-16 04:51:19', '2023-09-16 04:51:19'),
	(6, 'ELECTRICISTA', '2023-09-16 04:51:34', '2023-09-16 04:51:34'),
	(7, 'ASISTENTE ADMINISTRATIVO', '2023-09-16 04:51:44', '2023-09-16 04:51:44'),
	(8, 'AYUDANTE INTEGRAL', '2023-09-16 04:51:56', '2023-09-16 04:51:56'),
	(9, 'ADMINISTRADOR PLANTA', '2023-09-16 16:38:49', '2023-09-16 16:38:49'),
	(10, 'ALMACENISTA', '2023-09-16 16:39:03', '2023-09-16 16:39:03'),
	(11, 'ANALISTA ADMINISTRATIVO', '2023-09-16 16:40:05', '2023-09-16 16:40:05'),
	(12, 'ANALISTA DE CALIDAD', '2023-09-16 16:40:59', '2023-09-16 16:40:59'),
	(13, 'ANALISTA DE PRODUCCIÓN', '2023-09-16 16:41:19', '2023-09-16 16:41:19'),
	(14, 'ANALISTA DE R.R.H.H.', '2023-09-16 16:41:34', '2023-09-16 16:41:34'),
	(15, 'APRENDIZ INCE', '2023-09-16 16:41:49', '2023-09-16 16:41:49'),
	(16, 'ASESOR DE PROCESO DE MANUFACTURA', '2023-09-16 16:42:05', '2023-09-16 16:42:05'),
	(17, 'AYUDANTE ALMACEN', '2023-09-16 16:42:23', '2023-09-16 16:42:23'),
	(18, 'AYUDANTE DE  PRODUCCION', '2023-09-16 16:42:42', '2023-09-16 16:42:42'),
	(19, 'CHOFER', '2023-09-16 16:42:57', '2023-09-16 16:42:57'),
	(20, 'COORDINADOR DE MANTENIMIENTO', '2023-09-16 16:43:10', '2023-09-16 16:43:10'),
	(21, 'DIRECTIVO', '2023-09-16 16:43:22', '2023-09-16 16:43:22'),
	(23, 'ELECTROMECANICO', '2023-09-16 16:43:54', '2023-09-16 16:43:54'),
	(24, 'GERENTE DE DESARROLLO DE NUEVOS PRODUCTO', '2023-09-16 16:44:07', '2023-09-16 16:44:07'),
	(25, 'GERENTE DE IMPORTACIÓN Y EXPORTACIÓN', '2023-09-16 16:44:16', '2023-09-16 16:44:16'),
	(26, 'GERENTE DE MANTENIMIENTO', '2023-09-16 16:44:23', '2023-09-16 16:44:23'),
	(27, 'GERENTE DE PLANTA', '2023-09-16 16:44:31', '2023-09-16 16:44:31'),
	(28, 'GERENTE DE PRODUCCION', '2023-09-16 16:44:40', '2023-09-16 16:44:40'),
	(29, 'GERENTE DE VENTAS', '2023-09-16 16:44:47', '2023-09-16 16:44:47'),
	(30, 'GERENTE GENERAL', '2023-09-16 16:44:52', '2023-09-16 16:44:52'),
	(31, 'INSPECTOR DE CONTROL DE CALIDAD', '2023-09-16 16:44:59', '2023-09-16 16:44:59'),
	(32, 'JEFE CONTROL CALIDAD', '2023-09-16 16:45:14', '2023-09-16 16:45:14'),
	(33, 'JEFE DE ALMACEN M.P.', '2023-09-16 16:45:26', '2023-09-16 16:45:26'),
	(34, 'JEFE DE SEGURIDAD FISICA', '2023-09-16 16:45:34', '2023-09-16 16:45:34'),
	(35, 'JEFE DE TRANSPORTE', '2023-09-16 16:45:41', '2023-09-16 16:45:41'),
	(36, 'JEFE DPTO.TECNICO', '2023-09-16 16:45:50', '2023-09-16 16:45:50'),
	(37, 'MENSAJERO', '2023-09-16 16:45:58', '2023-09-16 16:45:58'),
	(38, 'MONTACARGUISTA', '2023-09-16 16:46:04', '2023-09-16 16:46:04'),
	(39, 'REPRESENTANTE  DE VENTAS', '2023-09-16 16:46:43', '2023-09-16 16:46:43'),
	(40, 'SUPERVISOR DE  PRODUCCION', '2023-09-16 16:47:03', '2023-09-16 16:47:03');

-- Volcando estructura para tabla kumbiaphp.categoria
CREATE TABLE IF NOT EXISTS `categoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.categoria: ~0 rows (aproximadamente)
REPLACE INTO `categoria` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
	(1, 'BATAS', '2023-09-16 17:27:32', '2023-09-16 17:27:32'),
	(2, 'BOTAS', '2023-09-16 17:27:40', '2023-09-16 17:27:40'),
	(3, 'BRAGAS', '2023-09-16 17:27:46', '2023-09-16 17:27:46'),
	(4, 'CAMISAS', '2023-09-16 17:27:59', '2023-09-16 17:27:59'),
	(5, 'CARETAS', '2023-09-16 17:28:06', '2023-09-16 17:28:06'),
	(6, 'CARTUCHOS', '2023-09-16 17:28:14', '2023-09-16 17:28:14'),
	(7, 'CASCOS', '2023-09-16 17:29:03', '2023-09-16 17:29:03'),
	(8, 'CHAQUETAS', '2023-09-16 17:29:13', '2023-09-16 17:29:13'),
	(9, 'CHEMISES', '2023-09-16 17:29:20', '2023-09-16 17:29:20'),
	(10, 'DELANTALES', '2023-09-16 17:29:30', '2023-09-16 17:29:30'),
	(11, 'FAJAS', '2023-09-16 17:29:35', '2023-09-16 17:29:35'),
	(12, 'FILTROS', '2023-09-16 17:29:43', '2023-09-16 17:29:43'),
	(13, 'FRANELAS', '2023-09-16 17:29:58', '2023-09-16 17:29:58'),
	(14, 'GUANTES', '2023-09-16 17:30:02', '2023-09-16 17:30:02'),
	(15, 'HIGIENE PERSONAL', '2023-09-16 17:30:08', '2023-09-16 17:30:08'),
	(16, 'LENTES', '2023-09-16 17:30:14', '2023-09-16 17:30:14'),
	(17, 'MASCARILLAS', '2023-09-16 17:30:19', '2023-09-16 17:30:19'),
	(18, 'PANTALONES', '2023-09-16 17:30:29', '2023-09-16 17:30:29'),
	(19, 'PROTECTORES', '2023-09-16 17:30:38', '2023-09-16 17:30:38'),
	(20, 'RESPIRADORES', '2023-09-16 17:30:48', '2023-09-16 17:30:48'),
	(21, 'RETENEDORES', '2023-09-16 17:30:56', '2023-09-16 17:30:56'),
	(22, 'TAPONES', '2023-09-16 17:31:05', '2023-09-16 17:31:05'),
	(23, 'BANDERAS', '2023-09-16 17:31:05', NULL);

-- Volcando estructura para tabla kumbiaphp.departamento
CREATE TABLE IF NOT EXISTS `departamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.departamento: ~0 rows (aproximadamente)
REPLACE INTO `departamento` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
	(1, 'PRODUCCION', '2023-09-16 05:14:42', '2023-09-16 05:14:42'),
	(2, 'SERVICIOS GENERALES', '2023-09-16 05:14:57', '2023-09-16 05:14:57'),
	(3, 'MANTENIMIENTO', '2023-09-16 05:15:07', '2023-09-16 05:15:07'),
	(4, 'ADMINISTRACION', '2023-09-16 05:15:21', '2023-09-16 16:02:14'),
	(5, 'ALMACEN DE MATERIA PRIMA', '2023-09-16 05:15:34', '2023-09-16 05:15:34'),
	(6, 'DESPACHO', '2023-09-16 05:15:43', '2023-09-16 05:15:43'),
	(8, 'MANO DE OBRA', '2023-09-16 05:16:04', '2023-09-16 05:16:04'),
	(9, 'CONTROL DE CALIDAD', '2023-09-16 05:16:17', '2023-09-16 05:16:17'),
	(10, 'IMPORTACIONES', '2023-09-16 05:16:25', '2023-09-16 05:16:25'),
	(11, 'GERENCIA DE PLANTA', '2023-09-16 05:16:33', '2023-09-16 05:16:33'),
	(12, 'VENTAS', '2023-09-16 05:16:43', '2023-09-16 05:16:43'),
	(13, 'ALMACEN GENERAL', '2023-09-16 05:17:05', '2023-09-16 16:15:38'),
	(15, 'RECURSOS HUMANOS', '2023-09-16 05:17:31', '2023-09-16 05:17:31'),
	(16, 'SEGURIDAD INTEGRAL', '2023-09-16 05:17:49', '2023-09-16 05:17:49'),
	(17, 'DIRECCION GENERAL', '2023-09-16 05:18:09', '2023-09-16 16:15:00'),
	(18, 'SEGURIDAD INDUSTRIAL', '2023-09-16 16:48:46', '2023-09-16 16:48:46');

-- Volcando estructura para tabla kumbiaphp.empleado
CREATE TABLE IF NOT EXISTS `empleado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_completo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci GENERATED ALWAYS AS (concat(`apellido`,_utf8mb4' ',`nombre`)) VIRTUAL,
  `departamento_id` int NOT NULL,
  `area_id` int NOT NULL,
  `cargo_id` int NOT NULL,
  `nac` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'V',
  `cedula` int NOT NULL,
  `f_ing` date DEFAULT NULL,
  `f_nac` date DEFAULT NULL,
  `sexo` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'M',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_empleado_departamento_idx` (`departamento_id`) USING BTREE,
  KEY `fk_empleado_cargo_idx` (`cargo_id`) USING BTREE,
  KEY `fk_empleado_area_idx` (`area_id`) USING BTREE,
  CONSTRAINT `fk_empleado_area` FOREIGN KEY (`area_id`) REFERENCES `area` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_empleado_cargo` FOREIGN KEY (`cargo_id`) REFERENCES `cargo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_empleado_departamento` FOREIGN KEY (`departamento_id`) REFERENCES `departamento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.empleado: ~0 rows (aproximadamente)
REPLACE INTO `empleado` (`id`, `codigo`, `nombre`, `apellido`, `departamento_id`, `area_id`, `cargo_id`, `nac`, `cedula`, `f_ing`, `f_nac`, `sexo`, `created_at`, `updated_at`) VALUES
	(1, '30110', 'SOLEDAD', 'RIVAS', 1, 1, 1, 'V', 14013798, '2000-01-17', '1976-08-15', 'M', '2023-09-17 00:21:51', NULL),
	(2, '30114', 'ANGEL GREGORIO', 'GONZALEZ RIVAS', 1, 1, 1, 'V', 13217087, '2003-09-08', '1973-03-12', 'M', '2023-09-17 00:21:51', NULL),
	(3, '30116', 'LUQUE JOSE', 'HERRERA NAGUANAGUA', 1, 1, 2, 'V', 13217891, '1993-08-11', '1974-10-08', 'M', '2023-09-17 00:21:51', NULL),
	(4, '30126', 'JOSE GREGORIO', 'VIÑA FAGUNDEZ', 1, 1, 2, 'V', 15224165, '1997-02-11', '1977-07-04', 'M', '2023-09-17 00:21:51', NULL),
	(5, '30700', 'ANDRES DIOBELINO', 'SARMIENTO', 2, 2, 3, 'V', 3631961, '1990-04-25', '1953-11-10', 'M', '2023-09-17 00:21:51', NULL),
	(6, '30818', 'JOSE PASCUAL', 'TORREALBA ALVARADO', 1, 1, 4, 'V', 17685676, '2011-11-17', '1978-12-25', 'M', '2023-09-17 00:21:51', NULL),
	(7, '30907', 'DIONICIO', 'NARES CHACOA', 1, 3, 5, 'V', 12613293, '2014-01-27', '1968-11-09', 'M', '2023-09-17 00:21:51', NULL),
	(8, '30970', 'HECTOR JOSE', 'GONZALEZ ROJAS', 3, 4, 6, 'V', 12821653, '2016-02-16', '1974-11-24', 'M', '2023-09-17 00:21:51', NULL),
	(9, '31016', 'MIRLEN THAIS', 'BONIEL CASTRO', 4, 5, 7, 'V', 5960532, '1995-09-12', '1959-11-19', 'F', '2023-09-17 00:21:51', NULL),
	(10, '31077', 'DIONI JOSE', 'ORTUÑO FLORES', 2, 2, 8, 'V', 15646927, '2019-10-29', '1981-10-11', 'M', '2023-09-17 00:21:51', NULL),
	(11, '31079', 'JUAN JOSE', 'LAMON MUÑOZ', 1, 3, 18, 'V', 13834390, '2020-02-03', '1977-12-11', 'M', '2023-09-17 00:21:51', NULL),
	(12, '31083', 'YHIMY EDUARDO', 'PEREZ', 2, 2, 8, 'V', 14838379, '2020-08-17', '1981-07-27', 'M', '2023-09-17 00:21:51', NULL),
	(13, '31086', 'ABEL ERNESTO', 'LOPEZ ESPEJO', 1, 1, 18, 'V', 13457556, '2021-02-17', '1978-06-16', 'M', '2023-09-17 00:21:51', NULL),
	(14, '31095', 'JOSE ANTONIO', 'DIAZ BAEZ', 1, 3, 8, 'V', 13218943, '2022-02-14', '1976-11-19', 'M', '2023-09-17 00:21:51', NULL),
	(15, '31096', 'DARWIN MIGUEL', 'RODRIGUEZ CISNEROS', 5, 7, 17, 'V', 14326870, '2022-03-02', '1980-09-29', 'M', '2023-09-17 00:21:51', NULL),
	(16, '31099', 'ANTHONY JESUS', 'DIAZ RIERA', 5, 7, 8, 'V', 27771829, '2022-06-15', '2000-10-27', 'M', '2023-09-17 00:21:51', NULL),
	(17, '31100', 'CARLOS ENRIQUE', 'BLANCO PEREZ', 1, 3, 8, 'V', 14327265, '2022-07-19', '1975-10-04', 'M', '2023-09-17 00:21:51', NULL),
	(18, '31102', 'PEDRO MIGUEL', 'ROJAS QUINTERO', 1, 10, 8, 'V', 26794482, '2022-08-01', '1997-04-08', 'M', '2023-09-17 00:21:51', NULL),
	(19, '31104', 'EBER OTNIEL', 'MARTINEZ PEREZ', 6, 9, 38, 'V', 18388951, '2022-09-26', '1985-02-24', 'M', '2023-09-17 00:21:51', NULL),
	(20, '31107', 'MIRLENY JOSEFINA', 'PIÑERO APARICIO', 2, 2, 8, 'V', 15091383, '2023-03-20', '1980-05-12', 'F', '2023-09-17 00:21:51', NULL),
	(21, '31109', 'OSCAR EFRAIN', 'COLMENARES AGUIN', 2, 2, 8, 'V', 18906738, '2023-08-16', '1984-12-18', 'M', '2023-09-17 00:21:51', NULL),
	(22, '34003', 'JOSE RAFAEL', 'ROMERO PALENCIA', 1, 10, 16, 'V', 10073048, '1992-01-27', '1966-09-09', 'M', '2023-09-17 00:21:51', NULL),
	(23, '34018', 'JOSE TARCISIO', 'RUIZ REVERON', 8, 12, 36, 'V', 4419582, '1992-02-03', '1956-04-21', 'M', '2023-09-17 00:21:51', NULL),
	(24, '34012', 'MIRTA YANIRA', 'OCHOA PRISQUEZ', 4, 11, 9, 'V', 6024593, '1996-02-11', '1962-01-22', 'F', '2023-09-17 00:21:51', '2023-09-17 00:21:51'),
	(25, '34046', 'RAFAEL SIMON', 'MIERES SANTANA', 9, 16, 24, 'V', 8615655, '2001-03-01', '1955-10-25', 'M', '2023-09-17 00:21:51', NULL),
	(26, '34087', 'YELITZA NATALIA', 'CASTILLEJO CERMEÑO', 10, 13, 25, 'V', 14287335, '2008-04-07', '1980-11-30', 'F', '2023-09-17 00:21:51', NULL),
	(27, '34098', 'LUIS ROLANDO', 'TAPPI RODRIGUEZ', 11, 18, 30, 'V', 8392907, '2009-01-15', '1962-02-22', 'M', '2023-09-17 00:21:51', NULL),
	(28, '34121', 'FRANKLIM ANTONIO', 'FLORES', 12, 15, 29, 'V', 12032974, '2011-01-10', '1972-12-23', 'M', '2023-09-17 00:21:51', NULL),
	(29, '34124', 'JOSE RAFAEL', 'EGAÑEZ FIGUERA', 13, 17, 10, 'V', 21407644, '2011-02-14', '1991-05-08', 'M', '2023-09-17 00:21:51', NULL),
	(30, '34135', 'HENRY WILLIAMS', 'MORENO GONZALEZ', 11, 14, 27, 'V', 12166749, '2012-01-25', '1973-10-20', 'M', '2023-09-17 00:21:51', NULL),
	(31, '34162', 'JUAN ANGEL', 'TRAVIEZO LOPEZ', 4, 5, 37, 'V', 18493797, '2013-07-01', '1988-01-03', 'M', '2023-09-17 00:21:51', NULL),
	(32, '34214', 'YHOVANY JOSE', 'CENTENO RODRIGUEZ', 13, 19, 33, 'V', 14097715, '2016-09-01', '1979-12-21', 'M', '2023-09-17 00:21:51', NULL),
	(33, '34219', 'LUIS RUBEN', 'MORALES', 1, 3, 40, 'V', 12302398, '2017-03-14', '1974-10-09', 'M', '2023-09-17 00:21:51', NULL),
	(34, '34220', 'HECTOR JOSE', 'ABREU OROPEZA', 6, 25, 35, 'V', 6115742, '2017-03-21', '1963-02-25', 'M', '2023-09-17 00:21:51', NULL),
	(35, '34229', 'GUSTAVO ADOLFO', 'HERRERA HICLE', 9, 20, 32, 'V', 6849606, '2017-10-16', '1965-05-08', 'M', '2023-09-17 00:21:51', NULL),
	(36, '34236', 'LILIANA', 'IZQUIEL MARTINEZ', 15, 23, 14, 'V', 18841761, '2018-05-14', '1989-06-28', 'F', '2023-09-17 00:21:51', NULL),
	(37, '34239', 'LILIWESKA DE JESUS', 'SUAREZ TORRES', 9, 20, 12, 'V', 14013021, '2018-08-06', '1978-08-28', 'F', '2023-09-17 00:21:51', NULL),
	(38, '34242', 'WILLBRY ALEJANDRO', 'BRICEÑO SOLORZANO', 9, 20, 31, 'V', 13218598, '2019-05-20', '1977-12-22', 'M', '2023-09-17 00:21:51', NULL),
	(39, '34245', 'VICTOR ARNALDO', 'MELENDEZ DIAZ', 3, 27, 26, 'V', 15646598, '2019-08-01', '1983-01-22', 'M', '2023-09-17 00:21:51', NULL),
	(40, '34249', 'CELIANA DEL CARMEN', 'FIGUEROA ROMERO', 4, 5, 11, 'V', 13068856, '2019-10-07', '1978-03-01', 'F', '2023-09-17 00:21:51', NULL),
	(41, '34256', 'DIRSO', 'MENESES CORREA', 3, 4, 23, 'V', 22567773, '2020-08-24', '1994-06-23', 'M', '2023-09-17 00:21:51', NULL),
	(42, '34257', 'CRISMAR VERIOZKA', 'CABRERA BARRADAS', 9, 20, 12, 'V', 18759373, '2021-02-01', '1988-03-08', 'F', '2023-09-17 00:21:51', NULL),
	(43, '34261', 'DANNY ARGENIS', 'MOLINA CARTALLA', 3, 4, 20, 'V', 16936641, '2021-07-06', '1984-04-11', 'M', '2023-09-17 00:21:51', NULL),
	(44, '34263', 'LUIS EDUARDO', 'KEY BRICEÑO', 12, 15, 39, 'V', 16084603, '2021-08-02', '1983-02-22', 'M', '2023-09-17 00:21:51', NULL),
	(45, '34265', 'ERISON ALBERTO', 'ABREU DIAZ', 6, 25, 19, 'V', 15099454, '2021-10-13', '1978-09-19', 'M', '2023-09-17 00:21:51', NULL),
	(46, '34266', 'ENGERBERTH JOSE', 'RAMOS RAMIREZ', 1, 10, 28, 'V', 16028334, '2021-11-01', '1983-03-18', 'M', '2023-09-17 00:21:51', NULL),
	(47, '34268', 'ALI ELEAZAR', 'RODRIGUEZ GIL', 3, 4, 23, 'V', 12304161, '2021-11-15', '1975-07-13', 'M', '2023-09-17 00:21:51', NULL),
	(48, '34270', 'LEONARDO JOSE', 'ACOSTA QUINTERO', 3, 4, 23, 'V', 11835890, '2022-02-16', '1972-02-21', 'M', '2023-09-17 00:21:51', NULL),
	(49, '34271', 'LUIS JOSE', 'GARCIA CANACHE', 6, 25, 19, 'V', 16935819, '2022-03-02', '1984-01-23', 'M', '2023-09-17 00:21:51', NULL),
	(50, '34273', 'JHONER ENRIQUE', 'OLIVO', 1, 10, 13, 'V', 21408632, '2022-03-16', '1992-05-17', 'M', '2023-09-17 00:21:51', NULL),
	(51, '34276', 'GRISEL DEL CARMEN', 'RAMIREZ ESPINOZA', 12, 26, 11, 'V', 19453116, '2022-06-13', '1989-07-15', 'F', '2023-09-17 00:21:51', NULL),
	(52, '34278', 'NEOMAR RAFAEL', 'ISTURIZ', 6, 25, 19, 'V', 15890618, '2022-09-13', '1982-05-22', 'M', '2023-09-17 00:21:51', NULL),
	(53, '34279', 'ENDER JOSE', 'CEDEÑO JAIMES', 16, 24, 34, 'V', 15645863, '2022-09-14', '1981-04-03', 'M', '2023-09-17 00:21:51', NULL),
	(54, '34280', 'JOSE BERNANDO', 'JIMENEZ GONZALEZ', 3, 4, 23, 'V', 21605253, '2022-10-24', '1994-04-13', 'M', '2023-09-17 00:21:51', NULL),
	(55, '34281', 'SANDRA FABIOLA', 'CASTRO BOLIVAR', 4, 11, 11, 'V', 18729680, '2023-01-09', '1987-12-15', 'F', '2023-09-17 00:21:51', NULL),
	(56, '34282', 'KRISNEY DEL VALLE', 'GIL JAIMES', 4, 11, 15, 'V', 31062940, '2023-07-17', '2004-08-06', 'M', '2023-09-17 00:21:51', NULL),
	(57, '39002', 'ADRIANO', 'TAVARES GARRIDO', 17, 21, 21, 'V', 13748464, '1978-10-01', '1959-08-22', 'M', '2023-09-17 00:21:51', NULL),
	(58, '39003', 'ANTONIO', 'MARQUES ALMEIDA', 17, 21, 21, 'V', 6031621, '1978-10-01', '1958-04-19', 'M', '2023-09-17 00:21:51', NULL);

-- Volcando estructura para tabla kumbiaphp.entrada
CREATE TABLE IF NOT EXISTS `entrada` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `costo_total` decimal(10,2) DEFAULT '0.00',
  `fecha` date DEFAULT NULL,
  `dolar` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_entrada_usuario_idx` (`usuario_id`) USING BTREE,
  CONSTRAINT `fk_entrada_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.entrada: ~0 rows (aproximadamente)

-- Volcando estructura para tabla kumbiaphp.entrada_detalle
CREATE TABLE IF NOT EXISTS `entrada_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entrada_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `costo` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_entrada_detalle_entrada_idx` (`entrada_id`) USING BTREE,
  KEY `fk_entrada_detalle_producto_idx` (`producto_id`) USING BTREE,
  CONSTRAINT `fk_entrada_detalle_entrada` FOREIGN KEY (`entrada_id`) REFERENCES `entrada` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_entrada_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.entrada_detalle: ~0 rows (aproximadamente)

-- Volcando estructura para tabla kumbiaphp.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del menu',
  `menu_id` int DEFAULT NULL COMMENT 'Identificador del menu padre',
  `recurso_id` int DEFAULT NULL COMMENT 'Identificador del recurso',
  `menu` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Texto a mostrar del menu',
  `url` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Url del menu',
  `posicion` int DEFAULT '0' COMMENT 'Posision dentro de otros items',
  `icono` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Icono a mostrar ',
  `activo` int NOT NULL DEFAULT '1' COMMENT 'Menu activo o inactivo',
  `visibilidad` int NOT NULL DEFAULT '1' COMMENT 'Indica si el menu se muestra en el backend o en el frontend',
  `custom` int DEFAULT '1',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_menu_recurso_idx` (`recurso_id`) USING BTREE,
  KEY `fk_menu_menu_idx` (`menu_id`) USING BTREE,
  CONSTRAINT `fk_menu_menu` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_menu_recurso` FOREIGN KEY (`recurso_id`) REFERENCES `recurso` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='Tabla que contiene los menús para los usuario';

-- Volcando datos para la tabla kumbiaphp.menu: ~14 rows (aproximadamente)
REPLACE INTO `menu` (`id`, `menu_id`, `recurso_id`, `menu`, `url`, `posicion`, `icono`, `activo`, `visibilidad`, `custom`) VALUES
	(1, NULL, NULL, 'Dashboard', '#', 10, 'fas fa-home', 1, 1, NULL),
	(2, 1, 2, 'Dashboard', '/dashboard/', 11, 'fas fa-home', 1, 1, NULL),
	(3, NULL, NULL, 'Sistema', '#', 900, 'fas fa-cogs', 1, 1, NULL),
	(4, 3, 4, 'Accesos', '/sistema/accesos/listar/', 901, 'fas fa-exchange', 1, 1, NULL),
	(5, 3, 5, 'Auditorías', '/sistema/auditorias/', 902, 'fas fa-eye', 1, 1, 0),
	(7, 3, 7, 'Mantenimiento', '/sistema/mantenimiento/', 904, 'fas fa-bolt', 2, 1, 0),
	(8, 3, 8, 'Menús', '/sistema/menus/listar/', 905, 'fas fa-list', 1, 1, NULL),
	(9, 3, 9, 'Perfiles', '/sistema/perfiles/listar/', 906, 'fas fa-users-cog', 1, 1, NULL),
	(10, 3, 10, 'Permisos', '/sistema/permisos/listar/', 907, 'fas fa-magic', 1, 1, NULL),
	(11, 3, 11, 'Recursos', '/sistema/recursos/listar/', 908, 'fas fa-lock', 1, 1, NULL),
	(12, 3, 12, 'Usuarios', '/sistema/usuarios/listar/', 909, 'fas fa-users', 1, 1, 1),
	(13, 3, 13, 'Visor de sucesos', '/sistema/sucesos/', 910, 'fas fa-filter', 1, 1, NULL),
	(14, 3, 14, 'Setup', '/sistema/configuracion/', 999, 'fas fa-wrench', 1, 1, NULL),
	(15, NULL, NULL, 'Admin', '#', 200, 'fas fa-filter', 1, 1, 0),
	(16, 15, 15, 'Productos', '/admin/productos/', 201, 'fas fa-archive', 1, 1, 0);

-- Volcando estructura para tabla kumbiaphp.perfil
CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del perfil',
  `perfil` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Nombre del perfil',
  `estado` int NOT NULL DEFAULT '1' COMMENT 'Indica si el perfil esta activo o inactivo',
  `plantilla` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'coreui' COMMENT 'Plantilla para usar en el sitema',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='Tabla que contiene los grupos de los usuario';

-- Volcando datos para la tabla kumbiaphp.perfil: ~0 rows (aproximadamente)
REPLACE INTO `perfil` (`id`, `perfil`, `estado`, `plantilla`, `created_at`, `updated_at`) VALUES
	(1, 'Super Usuario', 1, 'coreui', '2014-01-01 04:00:01', '2024-04-29 03:21:18'),
	(2, 'Administrador', 1, 'coreui', '2021-01-27 03:06:06', '2024-04-28 17:04:32'),
	(3, 'Usuario', 1, 'coreui', '2024-04-25 03:06:06', '2024-04-28 22:43:55'),
	(4, 'Otro', 1, 'coreui', '2024-08-11 23:22:53', '2024-08-11 23:23:29'),
	(5, 'Prueba', 1, 'coreui', '2024-08-31 12:46:32', '2024-08-31 12:46:32');

-- Volcando estructura para tabla kumbiaphp.producto
CREATE TABLE IF NOT EXISTS `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` int NOT NULL,
  `unidad_id` int NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `existencia` int NOT NULL,
  `existencia_min` int unsigned NOT NULL DEFAULT '1',
  `costo` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_producto_categoria_idx` (`categoria_id`) USING BTREE,
  KEY `fk_producto_unidad_idx` (`unidad_id`) USING BTREE,
  CONSTRAINT `fk_producto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_producto_unidad` FOREIGN KEY (`unidad_id`) REFERENCES `unidad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.producto: ~0 rows (aproximadamente)
REPLACE INTO `producto` (`id`, `codigo`, `nombre`, `categoria_id`, `unidad_id`, `descripcion`, `existencia`, `existencia_min`, `costo`, `created_at`, `updated_at`) VALUES
	(1, 'IS-08-01-02', 'GUANTES CARNAZA CORTO REFORZADO PALMAS', 14, 1, NULL, 13, 1, 10.50, '2023-09-25 19:24:58', '2024-02-27 20:18:25'),
	(2, 'IS-08-01-04', 'GUANTES SOLDADOR 16" REFORZADO PALMAS', 14, 1, '', 6, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(3, 'IS-08-02-01', 'GUANTES ANTICORTES 73347/73349 MAXX-GRIP', 14, 1, NULL, 10, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:59:11'),
	(4, 'IS-08-05-02', 'GUANTES DE NITRILO VERDE TALLA 8 1/2', 14, 1, NULL, 4, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:58:19'),
	(5, 'IS-08-06-01', 'GUANTES DE NEOPRENO 14" NEGRO', 14, 1, '', 7, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(6, 'IS-08-06-02', 'GUANTES DE NEOPRENO 18"', 14, 1, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(7, 'IS-10-01-35', 'BOTA DE SEGURIDAD N§35', 2, 1, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:49:07'),
	(8, 'IS-10-01-36', 'BOTA DE SEGURIDAD N§36', 2, 1, '', 0, 1, 0.00, '2023-09-25 19:24:58', '2023-09-29 13:55:31'),
	(9, 'IS-10-01-37', 'BOTA DE SEGURIDAD N§37', 2, 1, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:49:18'),
	(10, 'IS-10-01-38', 'BOTA DE SEGURIDAD N§38', 2, 1, NULL, 3, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:49:29'),
	(11, 'IS-10-01-39', 'BOTA DE SEGURIDAD N§39', 2, 1, NULL, 7, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:49:37'),
	(12, 'IS-10-01-40', 'BOTA DE SEGURIDAD N§40', 2, 1, '', 8, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 12:00:26'),
	(13, 'IS-10-01-41', 'BOTA DE SEGURIDAD N§41', 2, 1, '', 15, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(14, 'IS-10-01-42', 'BOTA DE SEGURIDAD N§42', 2, 1, '', 8, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:30:42'),
	(15, 'IS-10-01-43', 'BOTA DE SEGURIDAD N§43', 2, 1, NULL, 0, 1, 0.00, '2023-09-25 19:24:58', '2023-12-12 19:19:04'),
	(16, 'IS-10-01-44', 'BOTA DE SEGURIDAD N§44', 2, 1, NULL, 3, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:14:07'),
	(17, 'IS-10-01-45', 'BOTA DE SEGURIDAD N§45', 2, 1, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(18, 'IS-10-01-46', 'BOTA DE SEGURIDAD N§46', 2, 1, '', 0, 1, 0.00, '2023-09-25 19:24:58', '2023-12-13 11:17:22'),
	(19, 'IS-06-04-06', 'CARTUCHO 3M VAPORES Y GASES 6003', 6, 2, NULL, 5, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:46:16'),
	(20, 'IS-02-02-02', 'VIDRIO BLANCO PARA CARETA DE SOLDAR', 5, 3, '', 5, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(21, 'IS-03-01-01', 'CARETA PARA SOLDAR', 5, 3, '', 0, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(22, 'IS-03-02-01', 'CARETA PARA ESMERILAR PRO-LIFE PLASTICA', 5, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(23, 'IS-03-04-01', 'LENTES DE SEGURIDAD ANTI-IMPACTO', 16, 3, NULL, 1, 1, 0.00, '2023-09-25 19:24:58', '2023-12-12 19:38:11'),
	(24, 'IS-03-05-01', 'LENTES DE SEGURIDAD OXICORTE', 16, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:40:33'),
	(25, 'IS-04-01-03', 'FAJA SOPORTE ESPALDA TALLA L', 11, 3, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(26, 'IS-04-01-04', 'FAJA FLEXIBLE - NEGRA TALLA S', 11, 3, '', 3, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(27, 'IS-04-03-03', 'BANDERA DE SEGURIDAD', 23, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(28, 'IS-05-01-01', 'PROTECTOR AUDITIVO T/{OREJERA} 1435', 19, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(29, 'IS-05-02-01', 'TAPONES DE SILICONE PARA OIDOS', 22, 3, NULL, 16, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:41:43'),
	(30, 'IS-06-01-03', 'RESPIRADOR MEDIA CARA SERIE 6-6200-S', 20, 3, NULL, 5, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:42:08'),
	(31, 'IS-06-02-01', 'MASCARILLA 3M DESECHABLE P/POLVO NO.8210', 17, 3, '', 14, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:53:13'),
	(32, 'IS-06-03-03', 'FILTRO 3M  5 N11 CONTRA PARTICULAS', 12, 3, NULL, 7, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:35:24'),
	(33, 'IS-06-04-08', 'RETENEDOR PARA FILTRO 3M 501', 12, 3, NULL, 29, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:46:36'),
	(34, 'IS-07-01-01', 'CASCO DE SEGURIDAD AZUL', 7, 3, '', 4, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(35, 'IS-08-05-04', 'BRAGA TYVEK C/GORRO TALLA L', 3, 3, '', 0, 1, 0.00, '2023-09-25 19:24:58', '2024-02-09 11:50:38'),
	(36, 'IS-09-01-01', 'DELANTAL DE CARNAZA P/SOLDADOR', 10, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(37, 'IS-09-01-02', 'DELANTAL DE PVC', 10, 3, NULL, 10, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:48:45'),
	(38, 'UT-01-01-40', 'CAMISA AZUL CELESTE M/C 40 (L)', 4, 3, '', 4, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(39, 'UT-01-02-36', 'CAMISA AZUL MECANICO M/L TALLA 36', 4, 3, '', 6, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(40, 'UT-01-02-38', 'CAMISA AZUL MECANICO M/L TALLA 38', 4, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:51:16'),
	(41, 'UT-01-02-40', 'CAMISA AZUL MECANICO M/L TALLA 40', 4, 3, NULL, 5, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:51:31'),
	(42, 'UT-01-02-42', 'CAMISA AZUL MECANICO M/L TALLA 42', 4, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(43, 'UT-01-02-44', 'CAMISA AZUL MECANICO M/L TALLA 44', 4, 3, NULL, 1, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:51:43'),
	(44, 'UT-01-02-46', 'CAMISA AZUL MECANICO M/L TALLA 46', 4, 3, NULL, 1, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:51:58'),
	(45, 'UT-01-03-36', 'CAMISA AZUL MECANICO M/CORTA 36', 4, 3, '', 4, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(46, 'UT-01-03-38', 'CAMISA AZUL MECANICO M/CORTA 38', 4, 3, NULL, 5, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:52:14'),
	(47, 'UT-01-03-40', 'CAMISA AZUL MECANICO M/CORTA 40', 4, 3, NULL, 4, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:52:22'),
	(48, 'UT-01-03-46', 'CAMISA AZUL MECANICO M/CORTA 46', 4, 3, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(49, 'UT-01-05-42', 'CAMISA COLUMBIA P/CABALLERO TALLA XL/42', 4, 3, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(50, 'UT-02-05-38', 'BATA BLANCA MANGA LARGA TALLA 38', 1, 3, '', 3, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(51, 'UT-02-06-36', 'BATA DE LABORATORIO M/CORTA 36', 1, 3, NULL, 7, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:53:14'),
	(52, 'UT-02-06-38', 'BATA DE LABORATORIO M/CORTA 38', 1, 3, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(53, 'UT-04-02-26', 'PANTALON JEAN AZUL CABALLERO TALLA 26', 18, 3, '', 4, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(54, 'UT-04-02-28', 'PANTALON JEAN AZUL CABALLERO TALLA 28', 18, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:57:27'),
	(55, 'UT-04-02-30', 'PANTALON JEAN AZUL CABALLERO TALLA 30', 18, 3, NULL, 0, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:58:13'),
	(56, 'UT-04-02-32', 'PANTALON JEAN AZUL CABALLERO TALLA 32', 18, 3, NULL, 30, 1, 0.00, '2023-09-25 19:24:58', '2023-12-12 18:20:32'),
	(57, 'UT-04-02-34', 'PANTALON JEAN AZUL CABALLERO TALLA 34', 18, 3, NULL, 1, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:57:50'),
	(58, 'UT-04-02-36', 'PANTALON JEAN AZUL CABALLERO TALLA 36', 18, 3, NULL, 10, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:58:00'),
	(59, 'UT-04-02-38', 'PANTALON JEAN AZUL CABALLERO TALLA 38', 18, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(60, 'UT-04-02-40', 'PANTALON JEAN AZUL CABALLERO TALLA 40', 18, 3, NULL, 4, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:58:30'),
	(61, 'UT-04-02-42', 'PANTALON JEAN AZUL CABALLERO TALLA 42', 18, 3, NULL, 1, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:58:40'),
	(62, 'UT-04-02-46', 'PANTALON JEAN AZUL CABALLERO  TALLA 46', 18, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(63, 'UT-04-02-47', 'PANTALON JEAN AZUL T-8 STRECH DAMA', 18, 3, '', 19, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(64, 'UT-04-02-48', 'PANTALON JEAN AZUL T-10 STRECH DAMA', 18, 3, '', 10, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(65, 'UT-04-02-49', 'PANTALON JEAN T-12 STRECH DAMA', 18, 3, NULL, 3, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:59:22'),
	(66, 'UT-04-02-50', 'PANTALON JEAN T-14 STRECH DAMA', 18, 3, NULL, 17, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:59:33'),
	(67, 'UT-04-02-51', 'PANTALON JEAN T-18 STRECH DAMA', 18, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(68, 'UT-04-02-52', 'PANTALON JEAN T-16 STRECH DAMA', 18, 3, NULL, 8, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:59:48'),
	(69, 'UT-04-02-54', 'PANTALON JEAN  STRCH DAMA T-20', 18, 3, '', 6, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(70, 'UT-04-02-55', 'PANTALON JEAN STRECH DAMA T-22', 18, 3, NULL, 4, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 16:00:00'),
	(71, 'UT-06-01-40', 'FRANELA GRIS OSCURO TALLA "S"', 13, 3, '', 17, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(72, 'UT-03-05-01', 'FRANELA GRIS OSCURO TALLA M', 13, 3, NULL, 11, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:53:58'),
	(73, 'UT-06-02-40', 'FRANELA GRIS OSCURO TALLA "L"', 13, 3, NULL, 0, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:54:25'),
	(74, 'UT-02-08-18', 'FRANELA GRIS OSCURO XL', 13, 3, NULL, 11, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:53:38'),
	(75, 'UT-06-03-01', 'CHEMISE GRIS CABALLERO TALLA "M"', 9, 3, NULL, 5, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:55:04'),
	(76, 'UT-06-03-02', 'CHEMISE  GRIS CABALLERO TALLA "L"', 9, 3, NULL, 4, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:55:17'),
	(77, 'UT-06-03-03', 'CHEMISE GRIS CABALLERO TALLA "XL"', 9, 3, '', 8, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(78, 'UT-06-03-04', 'CHEMISE  GRIS CABALLERO TALLA "S"', 9, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-12-12 18:09:52'),
	(79, 'UT-06-03-05', 'CHEMISE  GRIS CABALLERO TALLA "XXL"', 9, 3, '', 2, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(80, 'UT-06-03-06', 'CHEMISE GRIS DAMA TALLA S', 9, 3, '', 9, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(81, 'UT-06-03-07', 'CHEMISE GRIS DAMA TALLA M', 9, 3, NULL, 6, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:55:54'),
	(82, 'UT-06-03-08', 'CHEMISE GRIS DAMA TALLA L', 9, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:56:06'),
	(83, 'UT-06-03-09', 'CHEMISE GRIS DAMA TALLA XL', 9, 3, NULL, 2, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:56:14'),
	(84, 'UT-06-04-03', 'CHEMISE AZUL MARINO CABALLERO TALLA M', 9, 3, NULL, 3, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:56:37'),
	(85, 'UT-06-04-01', 'CHEMISE AZUL MARINO CABALLERO TALLA "L"', 9, 3, NULL, 3, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 15:56:48'),
	(86, 'UT-06-04-02', 'CHEMISE AZUL MARINO CABALLERO TALLA "XL"', 9, 3, '', 3, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(87, 'IS-08-05-01', 'GUANTES DE NITRILO AZUL DESECHABLE TALLA XL MARCA SYNGUARD', 14, 4, NULL, 627, 1, 0.00, '2023-09-25 19:24:58', '2023-12-13 11:59:59'),
	(88, 'As-01-01-02', 'PAPEL HIGIENICO', 15, 4, NULL, 150, 1, 0.00, '2023-09-25 19:24:58', '2023-11-07 13:01:24'),
	(89, 'As-01-01-01', 'JABON DE TOCADOR', 15, 4, NULL, 470, 1, 0.00, '2023-09-25 19:24:58', '2023-11-07 13:01:41'),
	(90, 'GEN', 'PAÑO ', 15, 4, '', 42, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(91, 'UT-02-03-38', 'CHAQUETA TALLA L', 8, 4, '', 1, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(92, 'UT-04-02-53', 'PANTALON JEAN T-24 STRECH DAMA', 18, 4, NULL, 0, 1, 0.00, '2023-09-25 19:24:58', '2023-11-06 16:00:21'),
	(93, 'UT-03-01-03', 'CHEMISSE AZUL MARINO CABALLERO TALLA "S"', 9, 4, '', 4, 1, 0.00, '2023-09-25 19:24:58', NULL),
	(94, 'IS-06-01-05', 'RESPIRADOR CARA COMPLETA ULTRA TWINMFA', 20, 3, NULL, 2, 1, 0.00, '2023-11-06 15:44:34', '2023-11-06 15:45:09'),
	(95, 'HH-96-10-02', 'VASOS', 15, 4, NULL, 20, 1, 0.00, '2023-11-07 12:54:54', '2024-02-09 11:52:14'),
	(96, 'As-07-03-02', 'TOALLA', 15, 4, NULL, 8, 1, 0.00, '2023-11-07 13:02:28', '2023-11-07 13:02:28'),
	(97, 'As-11-01-01 ', 'GUANTES DE GOMA DE LIMPIEZA ', 14, 1, NULL, 4, 1, 0.00, '2023-11-08 14:35:08', '2023-11-08 14:35:08');

-- Volcando estructura para tabla kumbiaphp.recurso
CREATE TABLE IF NOT EXISTS `recurso` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Identificador del recurso',
  `modulo` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Nombre del m?dulo',
  `controlador` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Nombre del controlador',
  `accion` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Nombre de la acci',
  `recurso` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Nombre del recurso',
  `descripcion` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Descripci?n del recurso',
  `activo` int NOT NULL DEFAULT '1' COMMENT 'Estado del recurso',
  `custom` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de actualizacion',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='Tabla que contiene los recursos a los que acceden los usuario';

-- Volcando datos para la tabla kumbiaphp.recurso: ~0 rows (aproximadamente)
REPLACE INTO `recurso` (`id`, `modulo`, `controlador`, `accion`, `recurso`, `descripcion`, `activo`, `custom`, `created_at`, `updated_at`) VALUES
	(1, '*', 'NULL', 'NULL', '*', 'Comodín para la administración total (usar con cuidado).', 1, 0, '2014-01-01 04:00:01', '2024-08-10 21:44:13'),
	(2, 'dashboard', '*', '*', 'dashboard/*/*', 'Página principal del sistema.', 1, 0, '2014-01-01 04:00:01', NULL),
	(3, 'sistema', 'mi_cuenta', '*', 'sistema/mi_cuenta/*', 'Gestión de la cuenta del usuario logueado.', 1, 0, '2014-01-01 04:00:01', NULL),
	(4, 'sistema', 'accesos', '*', 'sistema/accesos/*', 'Submódulo para la gestión de ingresos al sistema.', 1, 0, '2014-01-01 04:00:01', NULL),
	(5, 'sistema', 'auditorias', '*', 'sistema/auditorias/*', 'Submódulo para el control de las acciones de los usuario.', 1, 0, '2014-01-01 04:00:01', NULL),
	(6, 'sistema', 'backups', '*', 'sistema/backups/*', 'Submódulo para la gestión de las copias de seguridad.', 1, 0, '2014-01-01 04:00:01', NULL),
	(7, 'sistema', 'mantenimiento', '*', 'sistema/mantenimiento/*', 'Submódulo para el mantenimiento de las tablas.', 1, 0, '2014-01-01 04:00:01', NULL),
	(8, 'sistema', 'menus', '*', 'sistema/menus/*', 'Submódulo del sistema para la creación de menús.', 1, 0, '2014-01-01 04:00:01', NULL),
	(9, 'sistema', 'perfiles', '*', 'sistema/perfiles/*', 'Submódulo del sistema para los perfiles de usuario.', 1, 0, '2014-01-01 04:00:01', NULL),
	(10, 'sistema', 'permisos', '*', 'sistema/permisos/*', 'Submódulo del sistema para asignar recursos a los perfiles.', 1, 0, '2014-01-01 04:00:01', NULL),
	(11, 'sistema', 'recursos', '*', 'sistema/recursos/*', 'Submódulo del sistema para la gestión de los recursos.', 1, 0, '2014-01-01 04:00:01', NULL),
	(12, 'sistema', 'usuarios', '*', 'sistema/usuarios/*', 'Submódulo para la administración de los usuario del sistema.', 1, 0, '2014-01-01 04:00:01', '2024-08-05 22:02:33'),
	(13, 'sistema', 'sucesos', '*', 'sistema/sucesos/*', 'Submódulo para el listado de los logs del sistema.', 1, 0, '2014-01-01 04:00:01', NULL),
	(14, 'sistema', 'configuracion', '*', 'sistema/configuracion/*', 'Submódulo para la configuracion de la aplicacion (.ini).', 1, 0, '2014-01-01 04:00:01', NULL),
	(15, 'producto', 'productos', '*', 'admin/productos/*', 'Modulo para la gestión de Productos', 1, 0, '2024-04-28 22:49:35', '2024-04-29 02:48:56');

-- Volcando estructura para tabla kumbiaphp.recurso_perfil
CREATE TABLE IF NOT EXISTS `recurso_perfil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `recurso_id` int NOT NULL,
  `perfil_id` int NOT NULL,
  `recurso_perfil_at` datetime DEFAULT NULL,
  `recurso_perfil_in` datetime DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_recurso_perfil_recurso_idx` (`recurso_id`) USING BTREE,
  KEY `fk_recurso_perfil_perfil_idx` (`perfil_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=171 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC COMMENT='Tabla que contiene los recursos del usuario en el sistema según su perfl';

-- Volcando datos para la tabla kumbiaphp.recurso_perfil: ~19 rows (aproximadamente)
REPLACE INTO `recurso_perfil` (`id`, `recurso_id`, `perfil_id`, `recurso_perfil_at`, `recurso_perfil_in`) VALUES
	(1, 1, 1, '2014-01-01 00:00:01', NULL),
	(2, 2, 2, '2014-03-31 23:35:39', NULL),
	(3, 3, 2, '2014-03-31 23:45:17', NULL),
	(13, 2, 3, '2024-04-28 13:05:29', NULL),
	(14, 3, 3, '2024-04-28 13:05:29', NULL),
	(16, 2, 4, '2024-08-11 19:22:53', NULL),
	(17, 3, 4, '2024-08-11 19:22:53', NULL),
	(135, 12, 2, '2024-08-11 19:47:15', NULL),
	(158, 15, 2, '2024-08-11 19:50:40', NULL),
	(159, 15, 3, '2024-08-11 19:50:40', NULL),
	(160, 15, 4, '2024-08-11 19:50:40', NULL),
	(161, 4, 2, '2024-08-11 19:50:40', NULL),
	(162, 5, 2, '2024-08-11 19:50:40', NULL),
	(163, 6, 2, '2024-08-11 19:50:40', NULL),
	(164, 7, 2, '2024-08-11 19:50:40', NULL),
	(165, 8, 2, '2024-08-11 19:50:40', NULL),
	(166, 9, 2, '2024-08-11 19:50:40', NULL),
	(167, 10, 2, '2024-08-11 19:50:40', NULL),
	(168, 11, 2, '2024-08-11 19:50:40', NULL),
	(169, 2, 5, '2024-08-31 08:46:32', NULL),
	(170, 3, 5, '2024-08-31 08:46:32', NULL);

-- Volcando estructura para tabla kumbiaphp.salida
CREATE TABLE IF NOT EXISTS `salida` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `empleado_id` int NOT NULL,
  `costo_total` decimal(10,2) DEFAULT '0.00',
  `fecha` date NOT NULL,
  `dolar` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_salida_usuario_idx` (`usuario_id`) USING BTREE,
  KEY `fk_salida_empleado_idx` (`empleado_id`) USING BTREE,
  CONSTRAINT `fk_salida_empleado` FOREIGN KEY (`empleado_id`) REFERENCES `empleado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_salida_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.salida: ~0 rows (aproximadamente)

-- Volcando estructura para tabla kumbiaphp.salida_detalle
CREATE TABLE IF NOT EXISTS `salida_detalle` (
  `id` int NOT NULL AUTO_INCREMENT,
  `salida_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `talla` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'N/A',
  `cantidad` int NOT NULL,
  `motivo` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'D',
  `costo` decimal(10,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `fk_salida_detalle_salida_idx` (`salida_id`) USING BTREE,
  KEY `fk_salida_producto_idx` (`producto_id`) USING BTREE,
  CONSTRAINT `fk_salida_detalle_salida` FOREIGN KEY (`salida_id`) REFERENCES `salida` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_salida_producto` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.salida_detalle: ~0 rows (aproximadamente)

-- Volcando estructura para tabla kumbiaphp.unidad
CREATE TABLE IF NOT EXISTS `unidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.unidad: ~0 rows (aproximadamente)
REPLACE INTO `unidad` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
	(1, 'PAR', NULL, NULL),
	(2, 'PQT', NULL, NULL),
	(3, 'PZA', NULL, NULL),
	(4, 'UND', NULL, NULL);

-- Volcando estructura para tabla kumbiaphp.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL COMMENT 'Nombre del Usuario',
  `apellido` varchar(70) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Apellido del usuario',
  `login` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Usuario',
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Contraseña de acceso',
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Dirección del correo electónico',
  `perfil_id` int NOT NULL DEFAULT '3' COMMENT 'Identificador del perfil',
  `fotografia` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'default.png' COMMENT 'Foto del Usuario',
  `tema` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT 'default' COMMENT 'Tema aplicable para la interfaz',
  `app_ajax` int DEFAULT '1' COMMENT 'Indica si la app se trabaja con ajax o peticiones normales',
  `datagrid` int DEFAULT '30' COMMENT 'Datos por página en los datagrid',
  `pool` varchar(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1' COMMENT 'Indica si el usuario esta activo o inactivo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Fecha de la última modificación',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `usuario_email_UNIQUE` (`email`) USING BTREE,
  UNIQUE KEY `usuario_usuario_UNIQUE` (`login`) USING BTREE,
  KEY `fk_usuario_perfil_idx` (`perfil_id`) USING BTREE,
  CONSTRAINT `fk_usuario_perfil` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=DYNAMIC;

-- Volcando datos para la tabla kumbiaphp.usuario: ~2 rows (aproximadamente)
REPLACE INTO `usuario` (`id`, `nombre`, `apellido`, `login`, `password`, `email`, `perfil_id`, `fotografia`, `tema`, `app_ajax`, `datagrid`, `pool`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'Programador', 'del Sistema', 'programador', '5b5b2b169c2618291e1a025af3792447180f7f95', 'programador@gmail.com', 1, 'default.png', 'default', 1, 30, NULL, 1, '2024-04-01 16:14:18', '2024-08-08 01:21:58'),
	(2, 'Administrador', 'del Sistema', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'admin@gmail.com', 2, 'default.png', 'default', 1, 30, NULL, 1, '2024-04-01 16:14:18', '2024-08-08 01:58:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
