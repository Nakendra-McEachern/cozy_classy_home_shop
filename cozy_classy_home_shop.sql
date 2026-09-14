-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2026 at 06:46 AM
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
-- Database: `cozy_classy_home_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `available_quantity` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `category`, `price`, `image_filename`, `available_quantity`) VALUES
(1, 'Blush Velvet Throw Pillow', 'Soft blush pink velvet pillow for a cozy living space.', 'Pillows', 24.99, 'blush_pillow.jpg', 15),
(2, 'Gold Accent Table Lamp', 'Elegant gold table lamp with a warm modern design.', 'Lighting', 49.99, 'gold_lamp.jpg', 10),
(3, 'Black Decorative Vase', 'Modern black ceramic vase for tables and shelves.', 'Decor', 34.99, 'black_vase.jpg', 12),
(4, 'Cozy Knit Throw Blanket', 'Soft neutral knit blanket perfect for couches and beds.', 'Blankets', 39.99, 'knit_blanket.jpg', 20),
(5, 'Gold Wall Mirror', 'Round decorative wall mirror with a polished gold frame.', 'Wall Decor', 69.99, 'gold_mirror.jpg', 8),
(6, 'Pink Scented Candle', 'Soft floral scented candle in a decorative pink jar.', 'Candles', 18.99, 'pink_candle.jpg', 25);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
