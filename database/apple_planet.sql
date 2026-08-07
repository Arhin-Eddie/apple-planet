-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2026
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `apple_planet`
--
DROP DATABASE IF EXISTS `apple_planet`;
CREATE DATABASE `apple_planet` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `apple_planet`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admins`
-- password is 'admin123' hashed with password_hash() using PASSWORD_DEFAULT
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$wN7KToG9u2Rj2r.u6nF7L.o1J3u2YkX1z6K.X4r4b4x8y9C5v8m2O');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'iPhone'),
(2, 'Mac'),
(3, 'iPad'),
(4, 'Apple Watch'),
(5, 'AirPods'),
(6, 'Android Phones'),
(7, 'Accessories'),
(8, 'Audio'),
(9, 'Gaming'),
(10, 'Smart Home'),
(11, 'Wearables');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `specifications` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `badge` varchar(20) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `brand`, `product_name`, `description`, `specifications`, `price`, `quantity`, `badge`, `is_featured`, `image`, `created_at`) VALUES
(1, 1, 'Apple', 'iPhone 15 Pro Max', 'Forged in titanium and featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever.', 'Display: 6.7-inch Super Retina XDR\r\nChip: A17 Pro\r\nStorage: 256GB\r\nCamera: 48MP Main | Ultra Wide | Telephoto', 1199.00, 50, 'NEW', 1, 'iphone_15_pro_max.jpg', '2026-08-01 10:00:00'),
(2, 2, 'Apple', 'MacBook Pro 16"', 'The most advanced Mac laptop for demanding workflows. Powered by M3 Max for massive performance and capabilities.', 'Display: 16.2-inch Liquid Retina XDR\r\nChip: M3 Max\r\nMemory: 36GB\r\nStorage: 1TB SSD', 3499.00, 20, 'BEST SELLER', 1, 'macbook_pro_16.jpg', '2026-08-01 10:05:00'),
(3, 3, 'Apple', 'iPad Pro 12.9"', 'The ultimate iPad experience with the most advanced display, M2 chip, and blazing-fast wireless connectivity.', 'Display: 12.9-inch Liquid Retina XDR\r\nChip: M2\r\nStorage: 512GB\r\nConnectivity: Wi-Fi + Cellular', 1299.00, 30, NULL, 0, 'ipad_pro_12_9.jpg', '2026-08-01 10:10:00'),
(4, 4, 'Apple', 'Apple Watch Ultra 2', 'The most rugged and capable Apple Watch. Designed for outdoor adventures and supercharged workouts with a lightweight titanium case.', 'Case: 49mm Titanium\r\nDisplay: Always-On Retina up to 3000 nits\r\nWater Resistance: 100m', 799.00, 45, 'LIMITED', 1, 'apple_watch_ultra_2.jpg', '2026-08-01 10:15:00'),
(5, 5, 'Apple', 'AirPods Pro (2nd Gen)', 'Re-engineered for even richer audio. Next-level Active Noise Cancellation and Adaptive Transparency reduce more external noise.', 'Audio: Spatial Audio with dynamic head tracking\r\nBattery: Up to 6 hours listening time\r\nCharging: MagSafe Charging Case', 249.00, 100, NULL, 0, 'airpods_pro_2.jpg', '2026-08-01 10:20:00'),
(6, 6, 'Samsung', 'Galaxy S24 Ultra', 'Welcome to the era of mobile AI. With Galaxy S24 Ultra in your hands, you can unleash whole new levels of creativity and productivity.', 'Display: 6.8-inch Dynamic AMOLED 2X\r\nProcessor: Snapdragon 8 Gen 3\r\nCamera: 200MP Main\r\nS Pen: Built-in', 1299.00, 40, 'NEW', 1, 'galaxy_s24_ultra.jpg', '2026-08-01 10:25:00'),
(7, 6, 'Google', 'Pixel 8 Pro', 'The all-pro phone engineered by Google. It\'s sleek, sophisticated, and packed with the latest AI features.', 'Display: 6.7-inch Super Actua\r\nProcessor: Google Tensor G3\r\nCamera: 50MP Main\r\nBattery: 5050 mAh', 999.00, 35, NULL, 0, 'pixel_8_pro.jpg', '2026-08-01 10:30:00'),
(8, 7, 'Anker', 'Prime 20,000mAh Power Bank', 'Ultra-high capacity power bank with 200W total output, capable of charging two laptops simultaneously.', 'Capacity: 20,000mAh\r\nOutput: 200W Total (2x USB-C, 1x USB-A)\r\nDisplay: Smart Digital Display', 129.99, 80, NULL, 0, 'anker_prime_20k.jpg', '2026-08-01 10:35:00'),
(9, 7, 'Apple', 'MagSafe Charger', 'The MagSafe Charger makes wireless charging a snap. The perfectly aligned magnets attach to your iPhone.', 'Output: Up to 15W\r\nConnection: USB-C', 39.00, 150, NULL, 0, 'magsafe_charger.jpg', '2026-08-01 10:40:00'),
(10, 8, 'Sony', 'WH-1000XM5', 'Industry-leading noise cancelation optimized to you. Magnificent sound, engineered to perfection.', 'Battery: Up to 30 hours\r\nNoise Cancellation: Dual Noise Sensor technology\r\nMicrophone: 4 beamforming mics', 398.00, 25, 'BEST SELLER', 0, 'sony_wh1000xm5.jpg', '2026-08-01 10:45:00'),
(11, 8, 'JBL', 'Flip 6', 'Bold sound for every adventure. The JBL Flip 6 delivers powerful JBL Original Pro Sound with exceptional clarity.', 'Waterproof: IP67\r\nBattery Life: Up to 12 hours\r\nOutput Power: 20W RMS', 129.95, 60, NULL, 0, 'jbl_flip_6.jpg', '2026-08-01 10:50:00'),
(12, 9, 'Logitech', 'G Pro X Superlight', 'Less than 63 grams. Advanced low-latency LIGHTSPEED wireless. Sub-micron precision with HERO 25K sensor.', 'Weight: <63g\r\nSensor: HERO 25K\r\nBattery Life: 70 hours', 159.99, 40, NULL, 0, 'logitech_gpro_x.jpg', '2026-08-01 10:55:00'),
(13, 9, 'Sony', 'PlayStation 5 Console', 'Experience lightning-fast loading with an ultra-high speed SSD, deeper immersion with support for haptic feedback.', 'Storage: 825GB SSD\r\nOutput: Up to 4K 120Hz', 499.99, 15, 'LIMITED', 0, 'ps5_console.jpg', '2026-08-01 11:00:00'),
(14, 10, 'Google', 'Nest Hub (2nd Gen)', 'Meet the second-gen Nest Hub from Google, the center of your helpful home. With a 7-inch display and improved speaker.', 'Display: 7-inch touchscreen\r\nFeatures: Sleep Sensing, Google Assistant', 99.99, 50, NULL, 0, 'nest_hub_2.jpg', '2026-08-01 11:05:00'),
(15, 11, 'Garmin', 'Fenix 7X Pro', 'Ultimate multisport GPS smartwatch with a large 1.4” display, built-in LED flashlight and solar charging lens.', 'Display: 1.4-inch Memory-in-Pixel\r\nBattery: Up to 37 days (Solar)\r\nWater Rating: 10 ATM', 899.99, 20, NULL, 0, 'garmin_fenix_7x.jpg', '2026-08-01 11:10:00'),
(16, 1, 'Apple', 'iPhone 15', 'Dynamic Island stays on top of it all. New 48MP Main camera. USB-C. All in a durable color-infused glass and aluminum design.', 'Display: 6.1-inch Super Retina XDR\r\nChip: A16 Bionic\r\nStorage: 128GB', 799.00, 60, NULL, 0, 'iphone_15.jpg', '2026-08-01 11:15:00'),
(17, 2, 'Apple', 'MacBook Air M3', 'Lean. Mean. M3 machine. The MacBook Air breezes through work and play, now featuring the blazing-fast M3 chip.', 'Display: 13.6-inch Liquid Retina\r\nChip: M3\r\nMemory: 8GB\r\nStorage: 256GB SSD', 1099.00, 40, 'NEW', 0, 'macbook_air_m3.jpg', '2026-08-01 11:20:00'),
(18, 3, 'Apple', 'iPad Air', 'Supercharged by M1. 10.9-inch Liquid Retina display. Supports Apple Pencil (2nd gen).', 'Display: 10.9-inch Liquid Retina\r\nChip: M1\r\nStorage: 64GB', 599.00, 50, NULL, 0, 'ipad_air.jpg', '2026-08-01 11:25:00'),
(19, 4, 'Apple', 'Apple Watch Series 9', 'Smarter. Brighter. Mightier. With the new S9 chip, a brighter display, and the magical double tap gesture.', 'Case: 45mm Aluminum\r\nDisplay: Always-On Retina up to 2000 nits', 429.00, 45, NULL, 0, 'apple_watch_s9.jpg', '2026-08-01 11:30:00'),
(20, 6, 'Samsung', 'Galaxy Z Fold5', 'Unfold a massive screen and get more done. The Galaxy Z Fold5 is your portable powerhouse.', 'Main Display: 7.6-inch Dynamic AMOLED 2X\r\nCover Display: 6.2-inch\r\nProcessor: Snapdragon 8 Gen 2', 1799.00, 15, 'EXCLUSIVE', 0, 'galaxy_z_fold5.jpg', '2026-08-01 11:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('currency_symbol', '$');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
