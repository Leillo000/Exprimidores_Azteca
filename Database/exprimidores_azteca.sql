-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 13-07-2026 a las 06:19:37
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `exprimidores_azteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

DROP TABLE IF EXISTS `carrito`;
CREATE TABLE IF NOT EXISTS `carrito` (
  `id_carrito` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id_carrito`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `tipo_cliente` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` datetime NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `tipo_cliente`, `fecha_registro`) VALUES
(1, 'empresa', '2025-11-19 00:00:00'),
(2, 'empresa', '2025-11-22 01:44:31'),
(3, 'empresa', '2026-01-21 00:16:01'),
(4, 'empresa', '2026-01-21 00:16:46'),
(5, 'empresa', '2026-01-21 00:17:35'),
(6, 'empresa', '2026-07-09 13:42:18'),
(7, 'empresa', '2026-07-09 13:42:39'),
(8, 'empresa', '2026-07-09 13:42:51'),
(9, 'empresa', '2026-07-09 13:43:03'),
(10, 'empresa', '2026-07-09 13:43:08'),
(11, 'empresa', '2026-07-09 13:43:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_observaciones`
--

DROP TABLE IF EXISTS `detalles_observaciones`;
CREATE TABLE IF NOT EXISTS `detalles_observaciones` (
  `id_detalle_observacion` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `id_pieza` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  PRIMARY KEY (`id_detalle_observacion`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_pieza` (`id_pieza`),
  KEY `fk_detalles_observaciones_producto` (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `detalles_observaciones`
--

INSERT INTO `detalles_observaciones` (`id_detalle_observacion`, `id_pedido`, `id_pieza`, `id_producto`, `cantidad`) VALUES
(1, 15, 4, 3, 3),
(2, 15, 4, 3, 3),
(3, 12, 4, 3, 5),
(17, 35, 4, 3, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedidos`
--

DROP TABLE IF EXISTS `detalles_pedidos`;
CREATE TABLE IF NOT EXISTS `detalles_pedidos` (
  `id_detalle_pedido` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int DEFAULT NULL,
  `id_producto` int DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_detalle_pedido`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `detalles_pedidos`
--

INSERT INTO `detalles_pedidos` (`id_detalle_pedido`, `id_pedido`, `id_producto`, `cantidad`, `subtotal`) VALUES
(16, 9, 3, 5, 140.00),
(15, 8, 3, 5, 140.00),
(17, 10, 3, 999999, 27999972.00),
(18, 11, 3, 1000000010, 99999999.99),
(19, 12, 3, 1000000000, 99999999.99),
(20, 13, 3, 2147483647, 99999999.99),
(21, 14, 3, 3, 84.00),
(22, 15, 3, 5, 140.00),
(23, 16, 4, 100, 1500.00),
(24, 17, 4, 3, 45.00),
(25, 17, 3, 8, 224.00),
(26, 18, 3, 5, 140.00),
(27, 18, 4, 3, 45.00),
(28, 19, 4, 200, 3000.00),
(29, 19, 3, 100, 2800.00),
(30, 20, 4, 5, 75.00),
(31, 20, 3, 3, 84.00),
(32, 21, 4, 5, 75.00),
(33, 21, 3, 3, 84.00),
(34, 22, 3, 3, 84.00),
(35, 23, 3, 3, 84.00),
(36, 24, 3, 3, 84.00),
(37, 25, 3, 400, 11200.00),
(38, 25, 4, 400, 6000.00),
(39, 26, 4, 100, 1500.00),
(40, 27, 4, 50, 750.00),
(41, 28, 4, 10, 150.00),
(42, 29, 3, 1, 28.00),
(43, 30, 4, 30, 450.00),
(44, 31, 3, 3, 84.00),
(45, 32, 3, 3, 84.00),
(46, 32, 4, 1, 15.00),
(47, 33, 3, 3, 84.00),
(48, 34, 3, 10, 280.00),
(49, 35, 3, 100, 2800.00),
(50, 36, 3, 3, 84.00),
(51, 37, 3, 3, 84.00),
(52, 38, 3, 3, 84.00),
(53, 39, 3, 3, 84.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE IF NOT EXISTS `empresas` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `rfc` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `id_cliente` (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id_empresa`, `id_cliente`, `nombre`, `rfc`, `correo`, `telefono`, `activo`) VALUES
(1, 1, 'Canadian Food', 'BERL081208HAR', 'o.bernal@gmail.com', '+52 551 234 5678', 0),
(2, 2, 'Greenlife - México', 'BERLHASRD0101', 'greenlife@gmail.com', '4491232495', 1),
(3, 4, 'Leillo01', 'BERJLF', 'leo@gmail.com', '4491192495', 1),
(4, 5, 'Alexa Nails', 'ALEQFASRD0201', 'ale.nails@gmail.com', '44521232592', 1),
(5, 6, 'Alekita', 'JJFKSKJFJSKFJ', 'aleka@gmail.com', '4429293929291', 1),
(6, 7, 'Alekita', 'WIFDJFOIEJFJE', 'LEO@gmail.com', '442929392332', 1),
(7, 8, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 1),
(8, 9, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 1),
(9, 10, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 1),
(10, 11, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` int NOT NULL AUTO_INCREMENT,
  `id_cliente` int DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `etapa` varchar(30) DEFAULT NULL,
  `tipo_observacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `fecha`, `etapa`, `tipo_observacion`) VALUES
(8, 1, '2025-11-18 19:12:15', 'Fundición', 'Ninguna'),
(9, 1, '2025-11-19 19:05:27', 'Fundición', 'Ninguna'),
(10, 1, '2025-11-20 07:26:40', 'Lijado', 'Ninguna'),
(11, 1, '2025-11-20 08:15:38', 'Fundición', 'Ninguna'),
(12, 1, '2025-11-20 08:18:05', 'Fundición', 'Faltan piezas'),
(13, 1, '2025-11-20 08:21:18', 'Fundición', 'Ninguna'),
(14, 1, '2025-11-20 08:30:30', 'Fundición', 'Ninguna'),
(15, 1, '2025-11-20 08:33:04', 'Fundición', 'Faltan piezas'),
(16, 1, '2025-11-21 18:55:17', 'Fundición', 'Ninguna'),
(31, 0, '2026-01-22 01:30:00', 'Fundición', 'Ninguna'),
(17, 1, '2025-11-21 21:30:11', 'Completado', 'Ninguna'),
(18, 1, '2025-11-21 21:35:11', 'Fundición', 'Ninguna'),
(19, 1, '2025-11-21 23:47:27', 'Fundición', 'Ninguna'),
(20, 0, '2025-11-22 17:56:41', 'Fundición', 'Ninguna'),
(33, 0, '2026-01-26 15:42:46', 'Fundición', 'Ninguna'),
(22, 1, '2025-11-22 19:46:28', 'Completado', 'Ninguna'),
(23, 2, '2025-11-22 19:55:02', 'Completado', 'Ninguna'),
(25, 2, '2025-11-23 15:59:30', 'Lijado', 'Ninguna'),
(26, 2, '2025-11-23 16:18:27', 'Completado', 'Ninguna'),
(27, 2, '2025-11-23 16:19:26', 'Fundición', 'Ninguna'),
(30, 2, '2025-11-23 16:32:18', 'Fundición', 'Ninguna'),
(32, 5, '2026-01-22 01:39:14', 'Fundición', 'Ninguna'),
(34, 4, '2026-01-26 15:43:51', 'Fundición', 'Ninguna'),
(35, 5, '2026-02-07 20:24:35', 'Fundición', 'Faltan piezas'),
(36, 5, '2026-04-21 12:32:15', 'Fundición', 'Ninguna'),
(37, 2, '2026-07-13 00:07:34', 'Fundición', 'Ninguna'),
(38, 2, '2026-07-13 00:07:48', 'Fundición', 'Ninguna'),
(39, 2, '2026-07-13 00:09:59', 'Fundición', 'Ninguna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `piezas`
--

DROP TABLE IF EXISTS `piezas`;
CREATE TABLE IF NOT EXISTS `piezas` (
  `id_pieza` int NOT NULL AUTO_INCREMENT,
  `id_producto` int DEFAULT NULL,
  `nombre_pieza` varchar(100) DEFAULT NULL,
  `peso` int DEFAULT NULL,
  PRIMARY KEY (`id_pieza`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `piezas`
--

INSERT INTO `piezas` (`id_pieza`, `id_producto`, `nombre_pieza`, `peso`) VALUES
(4, 4, 'Macho', 100),
(3, 3, 'Hembra', 100),
(5, 3, 'Palanca', 285),
(6, 3, 'Cono', 270),
(7, 3, 'Cedazo', 280),
(8, 4, 'Codo', 280),
(9, 4, 'Base', 280),
(10, 4, 'Cuerpo Campana', 280);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `peso` int NOT NULL,
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `precio_unitario`, `peso`) VALUES
(3, 'Exprimidor Mod. Limon Económico Azteca', 28.00, 200),
(4, 'Exprimidor Mod. Naranja Chico', 15.00, 1675),
(5, 'Gorra', 12.00, 30);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `zona` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock_aluminio`
--

DROP TABLE IF EXISTS `stock_aluminio`;
CREATE TABLE IF NOT EXISTS `stock_aluminio` (
  `id_stock` int NOT NULL AUTO_INCREMENT,
  `cantidad_kg` decimal(10,2) NOT NULL,
  `fecha` datetime NOT NULL,
  `tipo` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_stock`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_aluminio`
--

INSERT INTO `stock_aluminio` (`id_stock`, `cantidad_kg`, `fecha`, `tipo`, `descripcion`) VALUES
(24, 2344.00, '2026-07-13 00:09:47', 'Entrada', 'Entrada de 2344kg de aluminio'),
(25, 2343.34, '2026-07-13 00:09:59', 'Salida', 'Salida de 0.66kg de aluminio en el pedido No. 39');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `empresas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
