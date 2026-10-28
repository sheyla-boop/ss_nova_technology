-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2026 a las 20:13:53
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
-- Base de datos: `ss_nova_technology`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_name` varchar(160) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address_line` varchar(255) NOT NULL,
  `address_extra` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Generica', 'generica', 1, '2026-08-22 04:52:29', '2026-08-22 04:52:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Celulares', 'celulares', 'Dispositivos moviles y telefonos inteligentes', 1, '2026-08-22 04:52:29', '2026-08-22 04:52:29'),
(2, 'Computadores', 'computadores', 'Computadores portatiles y de escritorio', 1, '2026-08-22 04:52:29', '2026-08-22 04:52:29'),
(3, 'Accesorios', 'accesorios', 'Accesorios y complementos tecnologicos', 1, '2026-08-22 04:52:29', '2026-08-22 04:52:29'),
(4, 'Tecnologia para el hogar', 'tecnologia-para-el-hogar', 'Dispositivos inteligentes para el hogar', 1, '2026-08-22 04:52:29', '2026-08-22 04:52:29'),
(5, 'Control e Infraestructura', 'control-infraestructura', 'Hubs, pantallas centrales, enchufes e interruptores inteligentes', 1, '2026-09-30 20:23:49', '2026-09-30 20:23:49'),
(6, 'Climatización y Calidad del Aire', 'climatizacion-calidad-aire', 'Termostatos, purificadores, humidificadores y deshumidificadores conectados', 1, '2026-09-30 20:23:49', '2026-09-30 20:26:12'),
(7, 'Limpieza y Mantenimiento', 'limpieza-mantenimiento', 'Robots aspiradores, mopas automaticas y basureros con sensor', 1, '2026-09-30 20:23:49', '2026-09-30 20:23:49'),
(8, 'Seguridad y Accesos', 'seguridad-accesos', 'Camaras IP, timbres con video, cerraduras y sensores', 1, '2026-09-30 20:23:49', '2026-09-30 20:23:49'),
(9, 'Iluminación Inteligente', 'iluminacion-inteligente', 'Bombillas RGB, tiras LED, paneles y lamparas inteligentes', 1, '2026-09-30 20:23:49', '2026-09-30 20:26:12'),
(10, 'Cocina y Electrodomésticos', 'cocina-electrodomesticos', 'Freidoras, cafeteras, ollas y electrodomesticos conectados', 1, '2026-09-30 20:23:49', '2026-09-30 20:26:12'),
(11, 'Audio y Entretenimiento', 'audio-entretenimiento', 'Altavoces, barras de sonido, streaming y proyectores conectados', 1, '2026-09-30 20:23:49', '2026-09-30 20:23:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory`
--

CREATE TABLE `inventory` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity_available` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `low_stock_threshold` int(10) UNSIGNED NOT NULL DEFAULT 5,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventory`
--

INSERT INTO `inventory` (`product_id`, `quantity_available`, `low_stock_threshold`, `updated_at`) VALUES
(1, 50, 5, '2026-08-28 20:30:25'),
(2, 58, 5, '2026-08-28 20:30:50'),
(3, 8326, 5, '2026-09-30 20:53:59'),
(35, 94, 5, '2026-09-30 20:56:14'),
(36, 279, 5, '2026-10-16 20:25:39'),
(37, 140, 5, '2026-09-30 20:31:28'),
(38, 110, 5, '2026-09-30 20:31:28'),
(39, 64, 5, '2026-09-30 20:56:14'),
(40, 50, 5, '2026-09-30 20:31:28'),
(41, 85, 5, '2026-09-30 20:31:28'),
(42, 39, 5, '2026-09-30 20:53:59'),
(43, 75, 5, '2026-09-30 20:31:28'),
(44, 160, 5, '2026-09-30 20:31:28'),
(45, 90, 5, '2026-09-30 20:31:28'),
(46, 44, 5, '2026-09-30 20:56:14'),
(47, 210, 5, '2026-09-30 20:31:28'),
(48, 350, 5, '2026-09-30 20:31:28'),
(49, 129, 5, '2026-09-30 20:53:59'),
(50, 78, 5, '2026-09-30 20:53:59'),
(51, 59, 5, '2026-09-30 20:56:14'),
(52, 35, 5, '2026-09-30 20:31:28'),
(53, 55, 5, '2026-09-30 20:31:28'),
(54, 120, 5, '2026-09-30 20:31:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity_change` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'pending',
  `recipient_name` varchar(160) NOT NULL,
  `recipient_phone` varchar(30) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `shipping_address_extra` varchar(255) DEFAULT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_postal_code` varchar(20) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `shipping_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `customer_notes` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `status_id`, `payment_method_id`, `payment_status`, `recipient_name`, `recipient_phone`, `shipping_address`, `shipping_address_extra`, `shipping_city`, `shipping_state`, `shipping_postal_code`, `subtotal`, `shipping_cost`, `discount_amount`, `total_amount`, `customer_notes`, `created_at`, `updated_at`) VALUES
(1, 'SN-20260930-CC3DFC', 3, 5, 1, 'pending', 'sheyla castaño', '3212123513', 'mi casa', 'el quiroga', 'Bogota', 'Cundinamarca', '', 4659600.00, 0.00, 0.00, 4659600.00, 'callejon donde roban mucho, gracias', '2026-09-30 20:53:59', '2026-09-30 21:00:34'),
(2, 'SN-20260930-05A766', 3, 5, 1, 'pending', 'samuel santofimio', '3507155536', 'mi casa', '', 'Bogota', 'Cundinamarca', '', 1759600.00, 0.00, 0.00, 1759600.00, 'no she xd ajajajaj', '2026-09-30 20:56:14', '2026-09-30 21:01:16'),
(3, 'SN-20261016-8A1E00', 3, 1, 1, 'pending', 'Samuel', '3212123513', 'cll 31d sur#18-02', '', 'bogota', 'Colombia', '', 59900.00, 0.00, 0.00, 59900.00, '', '2026-10-16 20:25:39', '2026-10-16 20:25:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(180) NOT NULL,
  `sku` varchar(80) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `line_total` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `sku`, `unit_price`, `quantity`, `line_total`) VALUES
(1, 1, 3, 'iphone 18 rojo', 'PROD-23CA662B', 3000000.00, 1, 3000000.00),
(2, 1, 42, 'Robot Aspirador y Trapeador con Mapeo Láser LiDAR', 'SMH-LIMP-001', 1199900.00, 1, 1199900.00),
(3, 1, 49, 'Tira LED Inteligente Neón con Sincronización de Música', 'SMH-LUZ-002', 139900.00, 1, 139900.00),
(4, 1, 50, 'Lámpara de Noche Inteligente con Carga Inalámbrica', 'SMH-LUZ-003', 159900.00, 2, 319800.00),
(5, 2, 35, 'Pantalla Inteligente con Asistente de Voz (Hub Central)', 'SMH-CTRL-001', 399900.00, 1, 399900.00),
(6, 2, 39, 'Termostato Inteligente Programable Wi-Fi', 'SMH-CLIMA-001', 519900.00, 1, 519900.00),
(7, 2, 46, 'Cerradura Digital Biométrica con Huella y Teclado', 'SMH-SEG-003', 479900.00, 1, 479900.00),
(8, 2, 51, 'Freidora de Aire Inteligente con Pantalla Táctil', 'SMH-COC-001', 359900.00, 1, 359900.00),
(9, 3, 36, 'Enchufe Inteligente Wi-Fi con Medidor de Consumo', 'SMH-CTRL-002', 59900.00, 1, 59900.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_statuses`
--

CREATE TABLE `order_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_final` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `order_statuses`
--

INSERT INTO `order_statuses` (`id`, `code`, `name`, `sort_order`, `is_final`) VALUES
(1, 'pending', 'Pendiente', 1, 0),
(2, 'confirmed', 'Confirmado', 2, 0),
(3, 'preparing', 'En preparacion', 3, 0),
(4, 'shipped', 'Enviado', 4, 0),
(5, 'delivered', 'Entregado', 5, 1),
(6, 'cancelled', 'Cancelado', 6, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status_id` bigint(20) UNSIGNED NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status_id`, `changed_by`, `note`, `created_at`) VALUES
(1, 1, 1, 3, 'Pedido creado desde la tienda.', '2026-09-30 20:53:59'),
(2, 2, 1, 3, 'Pedido creado desde la tienda.', '2026-09-30 20:56:14'),
(3, 2, 2, 14, 'Estado actualizado desde el panel de empresa.', '2026-09-30 21:00:27'),
(4, 1, 5, 14, 'Estado actualizado desde el panel de empresa.', '2026-09-30 21:00:34'),
(5, 2, 5, 14, 'Estado actualizado desde el panel de empresa.', '2026-09-30 21:01:16'),
(6, 3, 1, 3, 'Pedido creado desde la tienda.', '2026-10-16 20:25:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `code`, `name`, `is_active`) VALUES
(1, 'cash_on_delivery', 'Pago contraentrega', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'manage_users', 'Gestionar usuarios y roles', '2026-08-22 04:52:29'),
(2, 'manage_catalog', 'Gestionar categorias, marcas y productos', '2026-08-22 04:52:29'),
(3, 'manage_inventory', 'Gestionar existencias e inventario', '2026-08-22 04:52:29'),
(4, 'manage_orders', 'Consultar y actualizar pedidos', '2026-08-22 04:52:29'),
(5, 'view_reports', 'Consultar reportes administrativos', '2026-08-22 04:52:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(180) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `sku` varchar(80) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand_id`, `name`, `slug`, `sku`, `description`, `price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'iphone 15 pro max', 'iphone-15-pro-max-7f4c1991', 'PROD-3A659AF9', 'laptop lenovo de buena calidad e increible funcionamiento', 250000.00, 1, '2026-08-28 20:30:25', '2026-08-28 20:30:25'),
(2, 4, NULL, 'laptop lenovo 3-15', 'laptop-lenovo-3-15-1f2092ce', 'PROD-0B3C3272', 'loygtfmhnfjnydjh', 200000.00, 1, '2026-08-28 20:30:50', '2026-08-28 20:30:50'),
(3, 1, NULL, 'iphone 18 rojo', 'iphone-18-rojo-87d950dc', 'PROD-23CA662B', 'telefono rojo', 3000000.00, 1, '2026-08-28 21:00:40', '2026-08-28 21:00:40'),
(35, 5, NULL, 'Pantalla Inteligente con Asistente de Voz (Hub Central)', 'pantalla-inteligente-hub-central-asistente-voz', 'SMH-CTRL-001', 'Hub central para controlar dispositivos inteligentes del hogar mediante voz.', 399900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(36, 5, NULL, 'Enchufe Inteligente Wi-Fi con Medidor de Consumo', 'enchufe-inteligente-wifi-medidor-consumo', 'SMH-CTRL-002', 'Enchufe conectado con medicion del consumo electrico.', 59900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(37, 5, NULL, 'Interruptor Táctil Inteligente de Pared (3 Canales)', 'interruptor-tactil-inteligente-pared-3-canales', 'SMH-CTRL-003', 'Interruptor inteligente de pared con control independiente de tres canales.', 99900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(38, 5, NULL, 'Control Remoto Infrarrojo Universal Wi-Fi', 'control-remoto-infrarrojo-universal-wifi', 'SMH-CTRL-004', 'Control universal Wi-Fi para equipos con mando infrarrojo.', 79900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(39, 6, NULL, 'Termostato Inteligente Programable Wi-Fi', 'termostato-inteligente-programable-wifi', 'SMH-CLIMA-001', 'Termostato programable conectado para controlar la climatizacion.', 519900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(40, 6, NULL, 'Purificador de Aire Inteligente con Control de App', 'purificador-aire-inteligente-control-app', 'SMH-CLIMA-002', 'Purificador de aire con control desde aplicacion movil.', 599900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(41, 6, NULL, 'Humidificador Ultrasónico Wi-Fi compatible con Alexa', 'humidificador-ultrasonico-wifi-alexa', 'SMH-CLIMA-003', 'Humidificador ultrasonico conectado y compatible con asistentes de voz.', 183900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(42, 7, NULL, 'Robot Aspirador y Trapeador con Mapeo Láser LiDAR', 'robot-aspirador-trapeador-mapeo-lidar', 'SMH-LIMP-001', 'Robot de limpieza con aspirado, trapeado y navegacion LiDAR.', 1199900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(43, 7, NULL, 'Tacho de Basura Automático con Sensor de Movimiento', 'tacho-basura-automatico-sensor-movimiento', 'SMH-LIMP-002', 'Basurero con apertura automatica mediante sensor de movimiento.', 199900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(44, 8, NULL, 'Cámara de Seguridad IP 360° para Interior con IA', 'camara-seguridad-ip-360-interior-ia', 'SMH-SEG-001', 'Camara IP interior con vision panoramica y funciones de inteligencia artificial.', 159900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(45, 8, NULL, 'Timbre Inteligente con Videocámara HD y Visión Nocturna', 'timbre-inteligente-videocamara-hd-vision-nocturna', 'SMH-SEG-002', 'Timbre conectado con video HD y vision nocturna.', 319900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(46, 8, NULL, 'Cerradura Digital Biométrica con Huella y Teclado', 'cerradura-digital-biometrica-huella-teclado', 'SMH-SEG-003', 'Cerradura digital con apertura por huella y teclado.', 479900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(47, 8, NULL, 'Sensor de Movimiento y Apertura de Puertas Wi-Fi', 'sensor-movimiento-apertura-puertas-wifi', 'SMH-SEG-004', 'Sensor conectado para detectar movimiento y apertura de puertas.', 75900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(48, 9, NULL, 'Bombilla LED RGBW Inteligente Wi-Fi', 'bombilla-led-rgbw-inteligente-wifi', 'SMH-LUZ-001', 'Bombilla LED conectada con iluminacion RGBW regulable.', 51900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(49, 9, NULL, 'Tira LED Inteligente Neón con Sincronización de Música', 'tira-led-neon-sincronizacion-musica', 'SMH-LUZ-002', 'Tira LED inteligente con efectos sincronizados con musica.', 139900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(50, 9, NULL, 'Lámpara de Noche Inteligente con Carga Inalámbrica', 'lampara-noche-inteligente-carga-inalambrica', 'SMH-LUZ-003', 'Lampara de noche conectada con base de carga inalambrica.', 159900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(51, 10, NULL, 'Freidora de Aire Inteligente con Pantalla Táctil', 'freidora-aire-inteligente-pantalla-tactil', 'SMH-COC-001', 'Freidora de aire con pantalla tactil y controles inteligentes.', 359900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(52, 10, NULL, 'Cafetera Inteligente Programable vía Smartphone', 'cafetera-inteligente-programable-smartphone', 'SMH-COC-002', 'Cafetera programable y controlable desde un smartphone.', 279900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(53, 11, NULL, 'Barra de Sonido Inteligente con Dolby Atmos', 'barra-sonido-inteligente-dolby-atmos', 'SMH-AUDIO-001', 'Barra de sonido conectada compatible con Dolby Atmos.', 719900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28'),
(54, 11, NULL, 'Altavoz Inteligente Portátil con Bluetooth y Wi-Fi', 'altavoz-inteligente-portatil-bluetooth-wifi', 'SMH-AUDIO-002', 'Altavoz portatil inteligente con conectividad Bluetooth y Wi-Fi.', 239900.00, 1, '2026-09-30 20:31:28', '2026-09-30 20:31:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `alt_text` varchar(180) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `file_path`, `alt_text`, `sort_order`, `is_primary`, `created_at`) VALUES
(6, 3, 'assets/images/products/1949ad55090750377c13013efdfda48e.jpg', 'iphone 18 rojo', 0, 1, '2026-08-28 21:09:59'),
(7, 2, 'assets/images/products/457824874ff1f825d2f0fc5710acebaa.jpg', 'laptop lenovo 3-15', 0, 1, '2026-09-04 19:30:04'),
(8, 1, 'assets/images/products/597ba994436d97cf1051e88c3da5849a.jpg', 'iphone 15 pro max', 0, 1, '2026-09-04 19:30:35'),
(71, 53, 'assets/images/products/15789a5249fafa40c64760a8a9dc500d.jpg', 'Barra de Sonido Inteligente con Dolby Atmos', 0, 1, '2026-09-30 20:33:33'),
(72, 54, 'assets/images/products/5e8555e53c69850188c162ce07a0dab9.webp', 'Altavoz Inteligente Portátil con Bluetooth y Wi-Fi', 0, 1, '2026-09-30 20:34:00'),
(73, 39, 'assets/images/products/b5fd8cd3010334d074641746860d2e29.webp', 'Termostato Inteligente Programable Wi-Fi', 0, 1, '2026-09-30 20:34:29'),
(74, 40, 'assets/images/products/08fd6de9dd344d3c0da2104141d34b79.jpg', 'Purificador de Aire Inteligente con Control de App', 0, 1, '2026-09-30 20:34:58'),
(75, 41, 'assets/images/products/4baef3a25b2f4c026026a4fcfe26a271.webp', 'Humidificador Ultrasónico Wi-Fi compatible con Alexa', 0, 1, '2026-09-30 20:35:23'),
(76, 51, 'assets/images/products/fc5bf346decf0d11ed28b4d412da738f.webp', 'Freidora de Aire Inteligente con Pantalla Táctil', 0, 1, '2026-09-30 20:35:47'),
(77, 52, 'assets/images/products/4489c261aa4baa319ffc8a9d6fc98079.webp', 'Cafetera Inteligente Programable vía Smartphone', 0, 1, '2026-09-30 20:36:11'),
(78, 35, 'assets/images/products/0b291fda323ed08f2005ef5b08213e47.webp', 'Pantalla Inteligente con Asistente de Voz (Hub Central)', 0, 1, '2026-09-30 20:36:32'),
(79, 36, 'assets/images/products/4ce4801b59770f4b6b835cf96751e782.jpg', 'Enchufe Inteligente Wi-Fi con Medidor de Consumo', 0, 1, '2026-09-30 20:37:01'),
(80, 37, 'assets/images/products/06fc5091dc93135e3b678ce455dad3ab.webp', 'Interruptor Táctil Inteligente de Pared (3 Canales)', 0, 1, '2026-09-30 20:37:28'),
(81, 38, 'assets/images/products/e65cc2b27602ec8272b65529e3177bd1.jpg', 'Control Remoto Infrarrojo Universal Wi-Fi', 0, 1, '2026-09-30 20:38:03'),
(82, 48, 'assets/images/products/761e03ff7cfebb8678fd26ed814133e8.jpg', 'Bombilla LED RGBW Inteligente Wi-Fi', 0, 1, '2026-09-30 20:38:28'),
(83, 49, 'assets/images/products/953d034cf1b4dc709c9d88a504c9eddb.jpg', 'Tira LED Inteligente Neón con Sincronización de Música', 0, 1, '2026-09-30 20:38:53'),
(84, 50, 'assets/images/products/e3c00cc914f91bd1084445321a1b8b03.webp', 'Lámpara de Noche Inteligente con Carga Inalámbrica', 0, 1, '2026-09-30 20:39:23'),
(85, 42, 'assets/images/products/698e711bddb759218eec918da203cb60.webp', 'Robot Aspirador y Trapeador con Mapeo Láser LiDAR', 0, 1, '2026-09-30 20:39:46'),
(86, 43, 'assets/images/products/a4c223b8465cacccd2b6f432dfad44bf.jpg', 'Tacho de Basura Automático con Sensor de Movimiento', 0, 1, '2026-09-30 20:40:12'),
(87, 44, 'assets/images/products/02b19d6aac691c5eee71a25d5672106d.webp', 'Cámara de Seguridad IP 360° para Interior con IA', 0, 1, '2026-09-30 20:40:37'),
(88, 45, 'assets/images/products/db3ad806c44433d0a103d86ed5972eaa.webp', 'Timbre Inteligente con Videocámara HD y Visión Nocturna', 0, 1, '2026-09-30 20:41:04'),
(89, 46, 'assets/images/products/9fef201a07118f591130a93efbbb3559.webp', 'Cerradura Digital Biométrica con Huella y Teclado', 0, 1, '2026-09-30 20:41:25'),
(90, 47, 'assets/images/products/09e696cdeadeffa4cc1a612e1c16062b.jpg', 'Sensor de Movimiento y Apertura de Puertas Wi-Fi', 0, 1, '2026-09-30 20:41:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'customer', 'Cliente de la tienda', '2026-08-22 04:52:29'),
(2, 'admin', 'Administrador de la plataforma', '2026-08-22 04:52:29');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `role_permissions`
--

INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_name` varchar(160) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_name`, `email`, `phone`, `address`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'lenovo', 'DANIEL TORRES', 'lenovigsdbv@gmail.com', '3789652417', 'igwsdcbwsvcbhlidshlic', 1, '2026-08-28 20:39:20', '2026-09-04 19:44:31'),
(2, 'Mainframe Ltda.', 'ANDRES RODRIGUEZ', 'andres.rodriguez@gmail.com', '7456329786', 'carrera 45# 82-16', 1, '2026-09-04 19:45:00', '2026-09-04 19:46:10'),
(3, 'technoimport', 'LAURA MARTINEZ', 'laura.martinez@technoimportproveedores.com', '9846596594', 'Calle 72 # 18-35', 1, '2026-09-04 19:48:07', '2026-09-04 19:48:07'),
(4, 'Spritall Company S.A.S.', 'CARLOS GOMEZ', 'carlos.gomez@spritallproveedor.com', '7398209856', 'Carrera 30 # 45-28,', 1, '2026-09-04 19:48:44', '2026-09-04 19:48:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(80) NOT NULL,
  `last_name` varchar(80) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `email`, `password_hash`, `phone`, `is_active`, `last_login_at`, `created_at`, `updated_at`) VALUES
(2, 2, 'sheyla', 'castaño', 'shey@gmail.com', '$2y$10$5U6sDALwFCPgY4rk7rAor.wS/0Yk//bQ4NrC9x5i7612DbA7imtQu', NULL, 1, NULL, '2026-08-22 05:04:53', '2026-08-28 19:17:10'),
(3, 1, 'samuel', 'santofimio', 'samsantofimioc2009@gmail.com', '$2y$10$FknntkFGbRnErfwCBws8iOhRapompXOewVOEA9rEhcmabVV2VNMgG', NULL, 1, '2026-10-16 15:24:45', '2026-08-22 05:10:03', '2026-10-16 20:24:45'),
(12, 1, 'juan', 'sanchez', 'jsanchez@gmail.com', '$2y$10$8knHbLSODWpFvDdn0M.bLegBfa1OBUdXKdI3oF.wacJtZpAtJZ6g.', NULL, 1, '2026-09-30 13:56:41', '2026-09-16 18:54:11', '2026-09-30 18:56:41'),
(14, 2, 'Zaraya', 'Bermudez', 'zarayber@gmail.com', '$2y$10$6P32KBDmT0QXhA2FtYvp/.YTZQW7Qi6qIV/PfEujQlpEGnQcNaHEy', NULL, 1, '2026-10-21 14:23:47', '2026-09-30 18:59:02', '2026-10-21 19:23:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_permissions`
--

CREATE TABLE `user_permissions` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_addresses_user` (`user_id`,`is_default`);

--
-- Indices de la tabla `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_id`,`product_id`),
  ADD KEY `fk_cart_items_product` (`product_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`product_id`);

--
-- Indices de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_movements_user` (`user_id`),
  ADD KEY `idx_inventory_movements_product` (`product_id`,`created_at`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `fk_orders_payment_method` (`payment_method_id`),
  ADD KEY `idx_orders_user_date` (`user_id`,`created_at`),
  ADD KEY `idx_orders_status_date` (`status_id`,`created_at`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_product` (`product_id`);

--
-- Indices de la tabla `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_status_history_status` (`status_id`),
  ADD KEY `fk_order_status_history_user` (`changed_by`),
  ADD KEY `idx_order_status_history_order` (`order_id`,`created_at`);

--
-- Indices de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `fk_products_brand` (`brand_id`),
  ADD KEY `idx_products_catalog` (`is_active`,`category_id`,`brand_id`),
  ADD KEY `idx_products_name` (`name`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product_images_order` (`product_id`,`sort_order`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `fk_role_permissions_permission` (`permission_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_suppliers_active` (`is_active`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role_active` (`role_id`,`is_active`);

--
-- Indices de la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`user_id`,`permission_id`),
  ADD KEY `fk_user_permissions_permission` (`permission_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `fk_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Filtros para la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `fk_inventory_movements_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_inventory_movements_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_payment_method` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`),
  ADD CONSTRAINT `fk_orders_status` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_order_status_history_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_status_history_status` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`),
  ADD CONSTRAINT `fk_order_status_history_user` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `fk_role_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_role_permissions_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD CONSTRAINT `fk_user_permissions_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_permissions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
