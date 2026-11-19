-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 03, 2026 at 09:42 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `panaderia2`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id` int NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id`, `usuario`, `password`) VALUES
(1, 'admin', '$2y$10$0l.D/PX9mCsrtJWopqO7juagePzUJPlZlODsLB1PVm4Kxw4ANYUAW');

-- --------------------------------------------------------

--
-- Table structure for table `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int NOT NULL,
  `pedido_id` int NOT NULL,
  `producto_id` int UNSIGNED NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mensajes`
--

CREATE TABLE `mensajes` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mensajes`
--

INSERT INTO `mensajes` (`id`, `nombre`, `email`, `mensaje`, `fecha`) VALUES
(1, 'nicol', 'ncljmn18@gmail.com', 'hoal nicol', '2026-10-31 02:05:07'),
(4, 'laralalra', 'ncljmn18@gmail.com', 'putitax', '2026-11-03 19:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int NOT NULL,
  `email` varchar(150) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(30) NOT NULL DEFAULT 'Pendiente',
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`id`, `email`, `total`, `estado`, `fecha`) VALUES
(15, 'ncljmn18@gmail.com', 2500.00, 'Entregado', '2026-11-03 16:29:02'),
(16, 'ncljmn18@gmail.com', 2500.00, 'Pendiente', '2026-11-03 16:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` varchar(80) NOT NULL DEFAULT 'Panaderia',
  `imagen` varchar(255) NOT NULL DEFAULT 'pan.png',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `categoria`, `imagen`, `creado_en`, `actualizado_en`) VALUES
(9, 'Pan francisco (kg)', 2500.00, 'Panadería', 'producto_6aea3ad50d7f08.37598910.webp', '2026-11-03 19:49:41', '2026-11-03 19:49:41'),
(10, 'Pan de molde (500g)', 2200.00, 'Panadería', 'producto_6aea3b467f11b9.33166174.webp', '2026-11-03 19:51:34', '2026-11-03 19:51:34'),
(11, 'Pan de campo (500g)', 2800.00, 'Panadería', 'producto_6aea3b6934ffc9.17313264.webp', '2026-11-03 19:52:09', '2026-11-03 19:52:09'),
(12, 'Chipá (docena)', 3000.00, 'Panadería', 'producto_6aea3b8430d6c0.05099139.webp', '2026-11-03 19:52:36', '2026-11-03 19:52:36'),
(13, 'Chipacito (docena)', 2500.00, 'Panadería', 'producto_6aea3b9db2dc73.76137792.webp', '2026-11-03 19:53:01', '2026-11-03 19:53:01'),
(14, 'Grissines (paquete)', 1800.00, 'Panadería', 'producto_6aea3bb3519229.17313089.webp', '2026-11-03 19:53:23', '2026-11-03 19:53:23'),
(15, 'Medialunas (docena)', 4500.00, 'Facturería', 'producto_6aea3bd7deaf05.78119780.webp', '2026-11-03 19:53:59', '2026-11-03 19:53:59'),
(16, 'Facturas surtidas (docena)', 5000.00, 'Facturería', 'producto_6aea3bf38a8b38.27106233.webp', '2026-11-03 19:54:27', '2026-11-03 19:54:27'),
(17, 'Tortitas negras (docena)', 3000.00, 'Facturería', 'producto_6aea3c142cd465.68149904.webp', '2026-11-03 19:55:00', '2026-11-03 19:55:00'),
(18, 'Pastelitos (docena)', 4000.00, 'Facturería', 'producto_6aea3c323d5ca2.47951933.webp', '2026-11-03 19:55:30', '2026-11-03 19:55:30'),
(19, 'Tarta de frutilla (porción)', 2500.00, 'Pastelería', 'producto_6aea3c5a45c2b0.12228515.jpg', '2026-11-03 19:56:10', '2026-11-03 19:56:10'),
(20, 'Tarta de frutilla (completa)', 12000.00, 'Pastelería', 'producto_6aea3c77f03940.20595767.jpg', '2026-11-03 19:56:39', '2026-11-03 19:56:39'),
(21, 'Alfajores de maicena (unidad)', 800.00, 'Pastelería', 'producto_6aea3c91ebd333.40407957.webp', '2026-11-03 19:57:05', '2026-11-03 19:57:05'),
(22, 'Alfajores de maicena (caja x6)', 4500.00, 'Pastelería', 'producto_6aea3cb34f3e88.50917453.webp', '2026-11-03 19:57:39', '2026-11-03 19:57:39'),
(23, 'Lemon pie (porción)', 2500.00, 'Pastelería', 'producto_6aea3cd4720b72.03196005.webp', '2026-11-03 19:58:12', '2026-11-03 19:58:12'),
(24, 'Lemon pie (completo)', 10000.00, 'Pastelería', 'producto_6aea3cf4bc2688.50798451.jpg', '2026-11-03 19:58:44', '2026-11-03 19:58:44'),
(25, 'Café', 1200.00, 'Cafetería', 'producto_6aea3d090dadf0.17517761.webp', '2026-11-03 19:59:05', '2026-11-03 19:59:05'),
(26, 'Café con leche', 1500.00, 'Cafetería', 'producto_6aea3d1d0db3e4.64429286.webp', '2026-11-03 19:59:25', '2026-11-03 19:59:25'),
(27, 'Capuchino', 2000.00, 'Cafetería', 'producto_6aea3d2be9f827.31150438.webp', '2026-11-03 19:59:39', '2026-11-03 19:59:39'),
(28, 'Té', 1000.00, 'Cafetería', 'producto_6aea3d3e33ebb2.16260647.webp', '2026-11-03 19:59:58', '2026-11-03 19:59:58'),
(29, 'Jugo natural', 1500.00, 'Cafetería', 'producto_6aea3d511a3f28.92102962.webp', '2026-11-03 20:00:17', '2026-11-03 20:00:17'),
(30, 'Gaseosa (lata)', 1200.00, 'Cafetería', 'producto_6aea3d65720ad0.75076703.webp', '2026-11-03 20:00:37', '2026-11-03 20:00:37'),
(31, 'Agua saborizada', 1000.00, 'Cafetería', 'producto_6aea3d74e4c361.44079330.webp', '2026-11-03 20:00:52', '2026-11-03 20:00:52'),
(32, 'Sándwich de miga (docena)', 6000.00, 'Sandwiches', 'producto_6aea3daba3be91.79406826.webp', '2026-11-03 20:01:47', '2026-11-03 20:01:47'),
(33, 'Sándwich de miga (unidad)', 600.00, 'Sandwiches', 'producto_6aea3dbe110e87.24447777.webp', '2026-11-03 20:02:06', '2026-11-03 20:02:06'),
(34, 'Sándwich de pan francés (jamón y queso))', 2500.00, 'Sandwiches', 'producto_6aea3de76224d0.05116458.jpg', '2026-11-03 20:02:47', '2026-11-03 20:02:47'),
(35, 'Sándwich de pan francés (milanesa)', 3000.00, 'Sandwiches', 'producto_6aea3e0b82b5b3.27113495.jpg', '2026-11-03 20:03:23', '2026-11-03 20:03:23'),
(36, 'Sándwich de pan de campo (completo)', 4000.00, 'Sandwiches', 'producto_6aea3e34d5fbe0.60464527.jpg', '2026-11-03 20:04:04', '2026-11-03 20:04:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indexes for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detalle_pedido` (`pedido_id`),
  ADD KEY `fk_detalle_producto` (`producto_id`);

--
-- Indexes for table `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `fk_detalle_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
