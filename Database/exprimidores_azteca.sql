-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 15-08-2026 a las 01:17:20
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
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `tipo_cliente` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `tipo_cliente`, `fecha`) VALUES
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
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `detalles_pedidos`
--

INSERT INTO `detalles_pedidos` (`id_detalle_pedido`, `id_pedido`, `id_producto`, `cantidad`, `subtotal`) VALUES
(54, 40, 4, 3, 45.00),
(55, 41, 4, 5, 75.00),
(56, 41, 3, 3, 84.00),
(57, 42, 3, 3, 84.00),
(58, 43, 3, 3, 84.00),
(59, 43, 4, 5, 75.00),
(60, 44, 3, 5, 140.00),
(61, 45, 4, 100, 1500.00),
(62, 46, 3, 3, 84.00),
(63, 47, 4, 3, 45.00),
(64, 48, 3, 100, 2800.00),
(65, 49, 3, 3, 84.00),
(66, 50, 4, 5, 75.00);

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
(2, 2, 'Greenlife - México', 'BERLHASRD0101', 'greenlife@gmail.com', '4491232495', 0),
(3, 4, 'Leillo01', 'BERJLF', 'leo@gmail.com', '4491192495', 0),
(4, 5, 'Alexa', 'ALEQFASRD0201', 'ale.nails@gmail.com', '44521232592', 0),
(5, 6, 'Alekita', 'JJFKSKJFJSKFJ', 'aleka@gmail.com', '4429293929291', 1),
(6, 7, 'Alekita', 'WIFDJFOIEJFJE', 'LEO@gmail.com', '442929392332', 0),
(7, 8, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 0),
(8, 9, 'Leo', 'BERLHASRD0101', 'maria@example.com', '555-3030', 0),
(9, 10, 'Leo', 'BERLHASRD0101', 'maria@example.com', '555-3030', 1),
(10, 11, 'Leo y Lekita', 'BERLHASRD0101', 'maria@example.com', '555-3030', 0);

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
  `pesaje_total` double(7,2) DEFAULT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `id_cliente` (`id_cliente`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `id_cliente`, `fecha`, `etapa`, `tipo_observacion`, `pesaje_total`) VALUES
(40, 2, '2026-07-13 01:19:08', 'Completado', 'Ninguna', 20000.34),
(41, 4, '2026-07-13 01:38:44', 'Lijado', 'Ninguna', 999.99),
(42, 2, '2026-07-15 21:03:40', 'Fundición', 'Ninguna', 0.66),
(43, 6, '2026-08-04 17:42:53', 'Fundición', 'Ninguna', 9.87),
(44, 6, '2026-08-04 18:26:27', 'Fundición', 'Ninguna', 1.10),
(45, 6, '2026-08-04 18:26:38', 'Fundición', 'Ninguna', 184.25),
(46, 6, '2026-08-04 18:26:56', 'Fundición', 'Ninguna', 0.66),
(47, 6, '2026-08-04 18:27:10', 'Fundición', 'Ninguna', 5.53),
(48, 10, '2026-08-04 18:27:28', 'Fundición', 'Ninguna', 22.00),
(49, 6, '2026-08-05 01:15:33', 'Fundición', 'Ninguna', 0.66),
(50, 10, '2026-08-05 01:15:45', 'Fundición', 'Ninguna', 9.21);

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
  `activo` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_pieza`),
  KEY `id_producto` (`id_producto`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `piezas`
--

INSERT INTO `piezas` (`id_pieza`, `id_producto`, `nombre_pieza`, `peso`, `activo`) VALUES
(3, 3, 'Hembra', 100, 0),
(5, 3, 'Palanca', 285, 1),
(6, 3, 'Cono', 270, 1),
(7, 3, 'Cedazo', 280, 0),
(26, 6, 'Palanca Media', 500, 0),
(25, 4, 'Palanca macho', 200, 0),
(10, 4, 'Cuerpo Campana', 280, 0),
(12, 3, 'Palanca Macho', 100, 1),
(13, 3, 'Palanca Hembra', 200, 1),
(14, 3, 'Cabeza blanda', 200, 1),
(19, 6, 'Palanca Hembra', 200, 1),
(18, 6, 'Palanca Hembra', 200, 1),
(20, 6, 'Palanca Hembra', 200, 1),
(21, 6, 'Palanca Hembra', 200, 1),
(23, 4, 'Palanca Hembra', 200, 1),
(24, 3, 'Palanca Macho', 200, 1),
(27, 6, 'Cuerpo Grande', 100, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `peso` int NOT NULL DEFAULT '0',
  `activo` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `precio_unitario`, `peso`, `activo`) VALUES
(3, 'Exprimidor Mod. Limon Económico Azteca', 28.00, 1255, 1),
(4, 'Exprimidor Mod. Naranja Chico', 15.00, 680, 1),
(6, 'Exprimidor de Toronjas Grande', 20.00, 900, 1);

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
  `descripcion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_stock`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `stock_aluminio`
--

INSERT INTO `stock_aluminio` (`id_stock`, `cantidad_kg`, `fecha`, `tipo`, `descripcion`) VALUES
(24, 2344.00, '2026-07-13 00:09:47', 'Entrada', 'Entrada de 2344kg de aluminio'),
(25, 2343.34, '2026-07-13 00:09:59', 'Salida', 'Salida de 0.66kg de aluminio en el pedido No. 39'),
(26, 2337.81, '2026-07-13 01:19:08', 'Salida', 'Salida de 5.5275kg de aluminio en el pedido No. 40'),
(27, 2327.94, '2026-07-13 01:38:44', 'Salida', 'Salida de 9.8725kg de aluminio en el pedido No. 41'),
(28, 2327.28, '2026-07-15 21:03:40', 'Salida', 'Salida de 0.66kg de aluminio en el pedido No. 42'),
(29, 2317.41, '2026-08-04 17:42:53', 'Salida', 'Salida de 9.8725kg de aluminio en el pedido No. 43 del cliente Alekita'),
(30, 2316.31, '2026-08-04 18:26:27', 'Salida', 'Salida de 1.1kg de aluminio en el pedido No. 44 del cliente Alekita'),
(31, 2132.06, '2026-08-04 18:26:38', 'Salida', 'Salida de 184.25kg de aluminio en el pedido No. 45 del cliente Alekita'),
(32, 2131.40, '2026-08-04 18:26:56', 'Salida', 'Salida de 0.66kg de aluminio en el pedido No. 46 del cliente Alekita'),
(33, 2125.87, '2026-08-04 18:27:10', 'Salida', 'Salida de 5.5275kg de aluminio en el pedido No. 47 del cliente Alekita'),
(34, 2103.87, '2026-08-04 18:27:28', 'Salida', 'Salida de 22kg de aluminio en el pedido No. 48 del cliente Leo y Lekita'),
(35, 2103.21, '2026-08-05 01:15:33', 'Salida', 'Salida de 0.66kg de aluminio en el pedido No. 49 del cliente Alekita'),
(36, 2094.00, '2026-08-05 01:15:45', 'Salida', 'Salida de 9.2125kg de aluminio en el pedido No. 50 del cliente Leo y Lekita'),
(41, 2324.00, '2026-08-12 22:14:50', 'Salida', 'Salida de 20 kg de aluminio para la liberación de 100 piezas de Palanca macho del producto Exprimido'),
(42, 2343.72, '2026-08-12 22:18:02', 'Salida', 'Salida de 0.308 kg de aluminio para la liberación de 1 piezas de Cuerpo Campana del producto Exprimidor Mod. Naranja Chico del pedido No. 50'),
(43, 2343.56, '2026-08-12 22:19:11', 'Salida', 'Salida de 0.484 kg de aluminio para la liberación de 2 piezas de Palanca macho del producto Exprimidor Mod. Naranja Chico del pedido No. 50'),
(44, 2343.78, '2026-08-12 22:21:06', 'Salida', 'Salida de 0.22 kg de aluminio para la liberación de 1 piezas de Palanca macho del producto Exprimidor Mod. Naranja Chico del pedido No. 50'),
(45, 2342.90, '2026-08-12 22:22:38', 'Salida', 'Salida de 1.1 kg de aluminio para la liberación de 5 piezas de Palanca macho del producto Exprimidor Mod. Naranja Chico del pedido No. 50'),
(46, 2343.34, '2026-08-13 18:41:40', 'Salida', 'Salida de 0.66 kg de aluminio para la liberación de 3 piezas de Palanca macho del producto Exprimidor Mod. Naranja Chico del pedido No. 50'),
(47, 2342.02, '2026-08-13 18:56:22', 'Salida', 'Salida de 1.32 kg de aluminio para la liberación de 6 piezas de Palanca macho del producto Exprimidor Mod. Naranja Chico del pedido No. 50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) DEFAULT NULL,
  `_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`user_id`, `email`, `_password`) VALUES
(4, 'o.leobernal@gmail.com', '$2y$10$10g6LOCSH0EV5EVzt22BWOSdJj4AL7XIUC0SvReN3UhojrptxKZyy'),
(5, 'Joaquin@gmail.com', '$2y$10$jGHuv3iQC8f4nwshDPfOJubPj9ZyOShYILT.mxlF6WX5ho93t9NNS'),
(6, 'wafflingshark18@gmail.com', '$2y$10$05q.ATK.YNQW.5SaS19KNO49G.eJDNQyAK2bvcbBJ8ayaorRZgrau'),
(7, 'wafflingshark18@gmail.com', '$2y$10$SMld7k7/UGCZGlRMF/chlOgkIqS5Tfyi/SK/wOLzr6jVb6Q4K5Ugy');

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
