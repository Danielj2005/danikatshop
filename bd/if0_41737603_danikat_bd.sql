-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql204.infinityfree.com
-- Generation Time: Jul 04, 2026 at 01:17 PM
-- Server version: 11.4.12-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41737603_danikat_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
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
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL
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
(8, 1, 6),
(9, 2, 7),
(10, 3, 7),
(11, 5, 8),
(12, 3, 8),
(13, 5, 9),
(14, 4, 9),
(15, 3, 9),
(16, 3, 10),
(17, 1, 10),
(18, 3, 11),
(19, 2, 12),
(20, 3, 12),
(21, 2, 13),
(22, 3, 13),
(23, 3, 14),
(24, 1, 14),
(25, 12, 15),
(26, 3, 15),
(27, 1, 15),
(28, 3, 16),
(29, 7, 16),
(30, 7, 17),
(31, 7, 18),
(32, 3, 19);

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `image_hash` varchar(36) NOT NULL DEFAULT '1',
  `state` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `description`, `images`, `image_hash`, `state`, `created_at`) VALUES
(1, 'Caja de Mini Donas', '0.00', 'Mini donas con glaseados variados: chocolate, fresa y vainilla. Incluye toppings de lluvia de colores.\r\ncantidad a convenir con el cliente.', './storage/f346c9e0-de30-4ed5-b794-cca0ea96821a_1.jfif,./storage/f346c9e0-de30-4ed5-b794-cca0ea96821a_2.jpeg', '1', 0, '2026-04-27 22:21:42'),
(4, 'bandolero de Corazón', '15.00', 'Bandolero con forma de corazón en gran variedad de colores.', './storage/19d744e9-c73e-45f1-9620-e56a20d09e24_1.jpg', '1', 0, '2026-04-28 21:41:29'),
(5, 'cartera con llavero incluido', '18.00', 'contamos con una gran variedad de colores que se ajustan a tus gustos. ', './storage/5128ff24-70ab-4489-a3fe-85fe2dd88b2b_1.jpg', '1', 0, '2026-04-28 21:59:30'),
(6, 'Helados de pote', '5.00', 'Helados en una gran variedad de sabores como: oreo, samba,  taddy, galak, savoy, ovomaltina, pirulin, nutella, entre otros disponibles.', './storage/2aa9989f-8b51-48cf-a57d-4c861168e19b_1.jpg', 'e7c52a4bc3ff3aef088e0fddaa43cac1', 0, '2026-05-01 16:07:16'),
(7, 'Ramos de flores eternas', '0.00', 'Ramos de flores eternas personalizadas a gusto del cliente, para esas ocasiones especiales que merecen detalles únicos.', './storage/f3352a2d-5bb3-4826-94b8-94b08e104f02_1.jpg,./storage/f3352a2d-5bb3-4826-94b8-94b08e104f02_2.jpg,./storage/f3352a2d-5bb3-4826-94b8-94b08e104f02_3.jpg', '76d83a4841449926a255bffab847b592,815', 0, '2026-05-01 16:37:43'),
(8, 'Llaveros personalizados', '3.00', 'Se realizan llaveros personalizados para cualquier fechas especiales. ', './storage/a6c0c0a4-b827-4936-944c-cea939413eea_1.jpg', '5e842de191c28959393d9836f9f1377f', 1, '2026-05-24 00:27:20'),
(9, 'Franelas personalizadas', '18.00', 'Franelas personalizadas a gusto del cliente según la ocasión para la que lo desee', './storage/5f267c9a-a422-44ed-9cad-7bf574f51590_1.jpg', 'e9a7c6d99afd5156716f2948997e316c', 1, '2026-05-24 00:29:47'),
(10, 'Bandeja de mini donas', '2.50', 'Mini donas con glaseados variados: chocolate, fresa y vainilla. Incluye toppings de lluvia de colores.\r\ncantidad a convenir con el cliente.', './storage/de714cc4-05b0-46e4-9ea1-2c5e2d9eeb46_1.jpg', '9c517018bbd6ccfa4bca3288dbd31d4c', 1, '2026-05-24 00:55:05'),
(11, 'Combo del día del padre', '15.00', 'Este combo dispone de tasa y gorra personalizada, Incluye bolsa de regalo.', './storage/9b1c7e75-9b69-4107-8db0-2e688d1a81ae_1.jpg', '1d835a1a2056faea217ad5babc35acc5', 1, '2026-05-24 00:59:08'),
(12, 'Ramos de flores eternas personalizadas ', '5.00', 'Ramos personalizados y decorados al gusto del cliente.', './storage/8f11b88b-b28d-4391-829c-e2eda100048a_1.jpg,./storage/8f11b88b-b28d-4391-829c-e2eda100048a_2.jpg', '76b0e7d1c2c80e747f2662eed4d0d471,5c2', 1, '2026-05-24 01:02:38'),
(13, 'Cajas de regalo personalizadas', '7.00', 'Cajas personalizadas para cualquier tipo de ocasión.', './storage/00886955-ef77-4567-8ed0-3818bd547c02_1.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_2.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_3.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_4.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_5.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_6.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_7.jpg,./storage/00886955-ef77-4567-8ed0-3818bd547c02_8.jpg', 'bce13db39141fb2a0aa60f45a64e1ee6,34e', 1, '2026-05-24 01:04:47'),
(14, 'Tortas marquesas', '3.00', 'Tortas marquesas con variedad de toppings al gusto del cliente.', './storage/a29ef559-0d06-4295-ab24-74f1bf01953c_1.jpg,./storage/a29ef559-0d06-4295-ab24-74f1bf01953c_2.jpg', '50fab883249084a860246f75878d659f,f6a', 1, '2026-05-24 01:07:13'),
(15, 'Torta imposible o choco flan', '4.00', 'Venta de tortas completa o por porciones.', './storage/6817a007-6fff-48e8-bc8c-0c05f6920acd_1.jpg,./storage/6817a007-6fff-48e8-bc8c-0c05f6920acd_2.jpg,./storage/6817a007-6fff-48e8-bc8c-0c05f6920acd_3.jpg,./storage/6817a007-6fff-48e8-bc8c-0c05f6920acd_4.jpg', '99c66e0fce9bd8eeac6d15bc809887eb,0ed', 1, '2026-05-24 01:10:05'),
(16, 'Franelas de Frutinovela', '15.00', 'Franelas Oversize de frutinovela. De tela de algodón', './storage/16_1.jpg', '7ebb82e1a0972e950903752d7e4dd3a8', 1, '2026-05-25 19:33:59'),
(17, 'Franelas de tela de algodón', '15.00', 'Franelas en gran variedad de modelos y diseños. ', './storage/17_1.jpg,./storage/17_2.jpg,./storage/17_3.jpg,./storage/17_4.jpg', 'b6d3af3dad349f7fd587054cfb1ae624,e4d', 1, '2026-05-25 19:38:45'),
(18, 'Franelas de tela de algodón', '15.00', 'Franelas en gran variedad de modelos y diseños. ', './storage/18_1.jpg,./storage/18_2.jpg,./storage/18_3.jpg,./storage/18_4.jpg', 'b6d3af3dad349f7fd587054cfb1ae624,e4d', 0, '2026-05-25 19:38:45'),
(19, 'Tazas de café personalizadas', '10.00', 'Se realiza un dise�o o nos facilitas el dise�o que deseas para tu tasa personalizada.', './storage/19_1.png,./storage/19_2.png', '6af295aae951c15d1c4166500bd6059b,ff4', 1, '2026-05-25 19:46:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) NOT NULL DEFAULT 'example@ecomerce.com',
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL DEFAULT '+58',
  `role` tinyint(1) NOT NULL DEFAULT 2,
  `state` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `correo`, `password`, `full_name`, `telefono`, `role`, `state`, `created_at`) VALUES
(1, 'dbarrueta42@gmail.com', '$2y$10$LrwZdYXGT.2flzKungqTXOaG6pRW7bfp1CurM8GjVHTP7pi8sDt7G', 'Daniel', '+584244189963', 1, 1, '2026-04-23 22:29:52'),
(2, 'danikatshopoficial@gmail.com', '$2y$10$LrwZdYXGT.2flzKungqTXOaG6pRW7bfp1CurM8GjVHTP7pi8sDt7G', 'Katty', '+584244189963', 2, 1, '2026-04-23 22:29:52');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categorias_productos`
--
ALTER TABLE `categorias_productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
