-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2025 at 10:41 AM
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
-- Database: `oceanic`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `shipping_address` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `shipping_address`, `created_at`) VALUES
(1, 1, 13972.85, 'Sean Denzel Bumanlag, 771 Kowloon St., O-Block, UK', '2025-07-03 07:11:47'),
(2, 1, 6986.43, 'Sean Denzel Bum, 771 MC Street, Hong Kong, UK', '2025-07-03 08:36:16');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 2, 6499.00),
(2, 2, 1, 1, 6499.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `category` enum('input','output','io') NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `stock` int(11) DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `specs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `old_price`, `category`, `image_path`, `is_featured`, `stock`, `average_rating`, `created_at`, `specs`) VALUES
(1, 'Logitech MX Mechanical', 'Premium mechanical keyboard', 6499.00, 7499.00, 'input', 'images/keyboard.jpg', 1, 12, 4.70, '2025-07-01 09:49:50', 'Switch Type: Tactile; Connectivity: Wireless'),
(2, 'Razer DeathAdder V3', 'Gaming mouse', 2999.00, NULL, 'input', 'images/mouse.jpg', 0, 20, 4.50, '2025-07-01 09:49:50', 'Sensor: Focus Pro 30K; DPI: 30000'),
(3, 'Wacom Intuos Pro', 'Drawing tablet', 9999.00, NULL, 'input', 'images/tablet.jpg', 1, 8, 4.60, '2025-07-01 09:49:50', 'Pen: Pressure-sensitive; Size: Medium'),
(4, 'Logitech C920 HD Pro', 'Webcam', 3999.00, NULL, 'input', 'images/webcam.jpg', 0, 12, 4.30, '2025-07-01 09:49:50', 'Resolution: 1080p; Microphone: Dual'),
(5, 'SteelSeries Apex Pro TKL', 'Mechanical keyboard', 8999.00, NULL, 'input', 'images/keyboard2.jpg', 0, 5, 4.40, '2025-07-01 09:49:50', 'Switch: OmniPoint; Layout: TKL'),
(6, 'Lamzu Atlantis Pro', 'Gaming mouse', 3499.00, NULL, 'input', 'images/mouse2.jpg', 0, 18, 4.50, '2025-07-01 09:49:50', 'Weight: 55g; Connectivity: Wireless'),
(7, 'Finalmouse UltralightX', 'Ultralight gaming mouse', 7999.00, NULL, 'input', 'images/mouse3.jpg', 0, 3, 4.20, '2025-07-01 09:49:50', 'Weight: 47g; DPI: 20000'),
(8, 'ASUS ROG Swift PG279QM', 'Gaming monitor', 17499.00, 19999.00, 'output', 'images/monitor.jpg', 1, 7, 4.90, '2025-07-01 09:49:50', 'Size: 27\"; Refresh Rate: 240Hz; G-SYNC'),
(9, 'SteelSeries Arctis 7P', 'Wireless headset', 6499.00, NULL, 'output', 'images/headset.jpg', 0, 10, 4.40, '2025-07-01 09:49:50', 'Wireless: Yes; Platform: PS5/PC'),
(10, 'Logitech G560 LIGHTSYNC', 'Gaming speakers', 8999.00, NULL, 'output', 'images/speakers.jpg', 0, 4, 4.10, '2025-07-01 09:49:50', 'Lighting: RGB; Channels: 2.1'),
(11, 'Samsung Odyssey G9', 'Ultrawide monitor', 54999.00, NULL, 'output', 'images/monitor2.jpg', 1, 2, 4.80, '2025-07-01 09:49:50', 'Resolution: 5120x1440; Refresh Rate: 240Hz'),
(12, 'Razer Nommo Pro', 'Premium speakers', 24999.00, NULL, 'output', 'images/speakers2.jpg', 0, 6, 4.30, '2025-07-01 09:49:50', 'THX Certified; Subwoofer Included'),
(13, 'HyperX Cloud Alpha', 'Gaming headset', 3999.00, 4999.00, 'output', 'images/headset2.jpg', 0, 14, 4.50, '2025-07-01 09:49:50', 'Driver: Dual Chamber; Mic: Detachable'),
(14, 'Canon PIXMA G7020', 'All-in-one printer', 15999.00, NULL, 'io', 'images/printer.jpg', 0, 9, 4.20, '2025-07-01 09:49:50', 'Print: Color; Features: Scan/Copy/Fax'),
(15, 'Anker PowerExpand Elite', 'USB-C docking station', 4999.00, NULL, 'io', 'images/dock.jpg', 0, 11, 4.60, '2025-07-01 09:49:50', 'Ports: 13-in-1; Power Delivery: 85W'),
(16, 'Belkin 3-in-1 Wireless Charger', 'Wireless charger', 5999.00, NULL, 'io', 'images/charger.jpg', 0, 7, 4.30, '2025-07-01 09:49:50', 'Supports: iPhone, AirPods, Apple Watch'),
(17, 'Epson WorkForce ES-50', 'Document scanner', 5999.00, NULL, 'io', 'images/scanner.jpg', 0, 5, 4.10, '2025-07-01 09:49:50', 'Scan Speed: 5.5 ppm; Connectivity: USB'),
(18, 'ATEN CS22U 2-Port USB KVM', 'KVM switch', 2499.00, NULL, 'io', 'images/kvm.jpg', 0, 13, 4.00, '2025-07-01 09:49:50', 'Ports: 2; Type: USB VGA'),
(19, 'Seagate Backup Plus Hub', 'External HDD', 6999.00, NULL, 'io', 'images/hdd.jpg', 0, 8, 4.40, '2025-07-01 09:49:50', 'Capacity: 8TB; Interface: USB 3.0'),
(20, 'Elgato Facecam', 'Streaming camera', 8999.00, 10999.00, 'io', 'images/camera.jpg', 1, 4, 4.60, '2025-07-01 09:49:50', 'Resolution: 1080p60; Sensor: Sony STARVIS');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `street` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `country` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `gender`, `dob`, `phone`, `email`, `street`, `city`, `province`, `zip`, `country`, `username`, `password`, `created_at`) VALUES
(1, 'Sean Denzel Bumanlag', 'male', '2004-09-07', '09391233214', 'bumanlag@ue.edu.ph', '771 Maria Cristina St.', 'Metro Manila', 'Manila', '1111', 'PH', 'SeanZZZ', '$2y$10$HfU6Al3ds1Pc1Iv/PB4BRuCI1Sd.HlIdBP7h8YrnThzP4SJWwO7Ie', '2025-07-01 12:34:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
