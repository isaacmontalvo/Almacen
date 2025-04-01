-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 06:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `almacen`
--

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`, `updated_at`) VALUES
(1, 'dias_alerta_expiracion', '30', 'Días de anticipación para alertar sobre productos por expirar', '2025-03-13 01:49:32'),
(2, 'nombre_empresa', 'Taller', 'Nombre de la empresa', '2025-03-13 01:49:32'),
(3, 'logo_url', '', 'URL del logo de la empresa', '2025-03-13 01:49:32'),
(4, 'email_notificaciones', '', 'Email para enviar notificaciones', '2025-03-13 01:49:32');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `tabla` varchar(100) NOT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_previos` text DEFAULT NULL,
  `datos_nuevos` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_salida` timestamp NOT NULL DEFAULT current_timestamp(),
  `ot` varchar(100) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `lote` varchar(100) NOT NULL,
  `fecha_exp` date NOT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `part_number` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movimientos`
--

INSERT INTO `movimientos` (`id`, `producto_id`, `tipo`, `cantidad`, `fecha_salida`, `ot`, `matricula`, `lote`, `fecha_exp`, `ubicacion`, `part_number`, `descripcion`, `created_at`) VALUES
(1, 3, 'salida', 4, '2025-03-13 14:40:50', '3', '203-HGDT', '108652/70623-01', '2025-03-13', '13-H-0267', 'AS3578-03656', 'ok', '2025-03-13 14:40:50'),
(2, 1, 'salida', 2, '2025-03-13 15:03:16', '2412008', '203-HGDT', '0080267046', '2025-03-28', '67b7767197689fa3c5d2c', 'AS3578-038', 'pp', '2025-03-13 15:03:16'),
(3, 1, 'salida', 56, '2025-03-13 16:04:18', '2412008', 'HK5217G', '0080267046', '2025-03-28', '67b7767197689fa3c5d2c', 'AS3578-038', 'pp', '2025-03-13 16:04:18'),
(4, 1, 'salida', 1, '2025-03-13 18:38:47', 'uooo', 'we-0987', '0080267046', '2025-03-28', '67b7767197689fa3c5d2c', 'AS3578-038', 'pp', '2025-03-13 18:38:47'),
(5, 1, 'salida', 30, '2025-03-13 19:30:04', 'uooo', 'we-0987', '0080267046', '2025-03-28', '67b7767197689fa3c5d2c', 'AS3578-038', 'pp', '2025-03-13 19:30:04'),
(6, 1, 'salida', 1, '2025-03-13 19:30:47', 'uooo', 'we-0987', '0080267046', '2025-03-28', '67b7767197689fa3c5d2c', 'AS3578-038', 'pp', '2025-03-13 19:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `part_number` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `lote` varchar(100) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_exp` date DEFAULT NULL,
  `inspeccion_recibo` text DEFAULT NULL,
  `ubicacion` varchar(100) NOT NULL,
  `codigo_barra` varchar(100) NOT NULL,
  `ot` varchar(100) NOT NULL,
  `matricula` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `part_number`, `descripcion`, `lote`, `cantidad`, `fecha_entrada`, `fecha_exp`, `inspeccion_recibo`, `ubicacion`, `codigo_barra`, `ot`, `matricula`, `created_at`, `updated_at`) VALUES
(1, 'AS3578-038', 'pp', '0080267046', 0, '2025-03-13', '2025-03-28', 'ok', '67b7767197689fa3c5d2c', '67d2ce9c16d14e313807d', '', '', '2025-03-13 12:25:00', '2025-03-13 19:30:47'),
(2, 'AS3578-036', 'ffg', '108652/70623-01', 17, '2025-03-13', '2025-03-26', 'ok', '13-H-01', '67d2d9dd690ceda6555fe', '', '', '2025-03-13 13:13:01', '2025-03-13 13:13:01'),
(3, 'AS3578-03656', 'ok', '108652/70623-01', 4, '2025-03-13', '2025-03-13', 'ok', '13-H-0267', '67d2da113ed441b8ee2ba', '', '', '2025-03-13 13:13:53', '2025-03-13 14:40:50'),
(4, 'AS3578-038', 'ol', '455ff', 90, '2025-03-13', NULL, 'ok', 'lfjk-ffER', '67d2f0771b07ece616edf', '', '', '2025-03-13 14:49:27', '2025-03-13 14:49:27'),
(5, 'AS357', 'ok', '108652/70623-8', 100, '2025-03-29', NULL, 'ok', '13-H-01', '67d2f613b9c3d38eec050', '', '', '2025-03-13 15:13:23', '2025-03-13 15:13:23'),
(6, 'AS3578-038', 'pp', '108652/70623-01', 900, '2025-03-29', NULL, 'ok', '13-H-01', '67d30da01227e5df81fcc', '', '', '2025-03-13 16:53:52', '2025-03-13 16:53:52'),
(7, '2345', 'ffgff', '3445', 11, '2025-03-21', NULL, 'ok', 'tyy', '67d32bddbe46340eb59e2', '', '', '2025-03-13 19:02:53', '2025-03-13 19:02:53'),
(8, '2345', '3455', '3445', 40000, '2025-03-13', NULL, 'ok', 'tyy', '67d340c65b77440eb59e2', '', '', '2025-03-13 20:32:06', '2025-03-13 20:32:06'),
(9, '35364', 'ok', 'fgdg', 77, '2025-04-26', '2025-04-24', 'ok', '67jfg', '67ec102d00113256bd7a6', '', '', '2025-04-01 16:11:25', '2025-04-01 16:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indexes for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto_id` (`producto_id`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_fecha_salida` (`fecha_salida`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barra` (`codigo_barra`),
  ADD KEY `idx_part_number` (`part_number`),
  ADD KEY `idx_lote` (`lote`),
  ADD KEY `idx_codigo_barra` (`codigo_barra`),
  ADD KEY `idx_fecha_exp` (`fecha_exp`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
