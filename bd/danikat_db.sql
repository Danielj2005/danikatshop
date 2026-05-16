-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2026 at 09:50 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `danikat_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci,
  `state` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `state`, `created_at`) VALUES
(1, 'Repostería', 'Postres de todo tipo tortas frías, personalizadas y mini donas', 1, '2026-04-27 19:07:03'),
(2, 'Manualidades', 'Creaciones hechas a mano, arreglos y detalles personalizados.', 1, '2026-04-27 22:45:24'),
(3, 'Pedidos Especiales', 'Combos de desayunos, cajas sorpresas y regalos por encargo.', 1, '2026-04-27 22:52:49'),
(4, 'Bisutería y Prendas', 'Collares, pulseras y relojes. Incluye piezas fabricadas a mano.', 1, '2026-04-27 22:52:49'),
(5, 'Accesorios de Moda', 'Zarcillos, anillos, carteras y complementos para el outfit.', 1, '2026-04-27 22:52:49'),
(6, 'Lencería de Hogar', 'Juegos de sábanas, fundas y manteles hechos a la medida.', 1, '2026-04-27 22:52:49'),
(7, 'Ropa y Calzado', 'Prendas de vestir para damas, caballeros y niños.', 1, '2026-04-27 22:52:49'),
(8, 'Electrónica y TV', 'Televisores, monitores y equipos de sonido para el hogar.', 1, '2026-04-27 22:52:49'),
(9, 'Computación', 'Periféricos, cables y accesorios para PC o laptops.', 1, '2026-04-27 22:52:49'),
(10, 'Gadgets y Celulares', 'Teléfonos móviles y accesorios tecnológicos modernos.', 1, '2026-04-27 22:52:49'),
(11, 'Personalizados Tech', 'Servicios de impresión 3D, diseño digital o software.', 1, '2026-04-27 22:52:49'),
(12, 'Alimentos Preparados/servicios', 'delivery, comidas listas para llevar (ej.  desayunos, o meriendas).', 1, '2026-04-28 22:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `categorias_productos`
--

CREATE TABLE `categorias_productos` (
  `id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `producto_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `categorias_productos`
--

INSERT INTO `categorias_productos` (`id`, `categoria_id`, `producto_id`) VALUES
(1, 1, 1),
(4, 5, 4),
(5, 5, 5),
(6, 3, 5),
(7, 12, 6),
(8, 2, 7),
(9, 3, 7);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int NOT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci,
  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci,
  `image_hash` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `state` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `description`, `images`, `image_hash`, `state`, `created_at`) VALUES
(1, 'Caja de Mini Donas', 0.00, 'Mini donas con glaseados variados: chocolate, fresa y vainilla. Incluye toppings de lluvia de colores.\r\ncantidad a convenir con el cliente.', './storage/f346c9e0-de30-4ed5-b794-cca0ea96821a_1.jfif,./storage/f346c9e0-de30-4ed5-b794-cca0ea96821a_2.jpeg', '2', 1, '2026-04-27 22:21:42'),
(4, 'bandolero de Corazón', 15.00, 'Bandolero con forma de corazón en gran variedad de colores.', './storage/19d744e9-c73e-45f1-9620-e56a20d09e24_1.jpg', '2', 1, '2026-04-28 21:41:29'),
(5, 'cartera con llavero incluido', 18.00, 'contamos con una gran variedad de colores que se ajustan a tus gustos. ', './storage/5128ff24-70ab-4489-a3fe-85fe2dd88b2b_1.jpg', '2', 1, '2026-04-28 21:59:30'),
(6, 'Helados de pote', 5.00, 'Helados en una gran variedad de sabores como: oreo, samba, taddy, galak, savoy, ovomaltina, pirulin, nutella, entre otros disponibles.', './storage/6_1.jpg', 'e7c52a4bc3ff3aef088e0fddaa43cac1', 1, '2026-05-09 18:08:16'),
(7, 'Ramos De Flores Eternas', 0.00, 'Ramos de flores eternas personalizadas a gusto del cliente, para esas ocasiones especiales que merecen detalles únicos.', './storage/f71d2529-b3b3-4878-b4f0-0565e79bfcf6_1.jpg,./storage/f71d2529-b3b3-4878-b4f0-0565e79bfcf6_2.jpg,./storage/f71d2529-b3b3-4878-b4f0-0565e79bfcf6_3.jpg', '76d83a4841449926a255bffab847b592,373a25a82064629d70ea44d9c91e4b19,8155b4ae62761dcfef7b6d5784f2e85e', 1, '2026-05-09 18:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT 'example@ecomerce.com',
  `password` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `full_name` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT '+58',
  `role` tinyint(1) NOT NULL DEFAULT '2',
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `correo`, `password`, `full_name`, `telefono`, `role`, `state`, `created_at`) VALUES
(1, 'dbarrueta42@gmail.com', '$2y$10$LrwZdYXGT.2flzKungqTXOaG6pRW7bfp1CurM8GjVHTP7pi8sDt7G', 'Daniel', '584244189963', 1, 1, '2026-04-23 22:29:52'),
(2, 'danikatshopoficial@gmail.com', '$2y$10$LrwZdYXGT.2flzKungqTXOaG6pRW7bfp1CurM8GjVHTP7pi8sDt7G', 'Katty', '584244189963', 2, 1, '2026-04-23 22:29:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  ADD CONSTRAINT `categorias_productos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `categorias_productos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
