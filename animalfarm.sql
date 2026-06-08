-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 04:53 PM
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
-- Database: `animalfarm`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `target_date` varchar(50) NOT NULL,
  `time_window` varchar(30) NOT NULL,
  `status` varchar(30) DEFAULT 'Pending Approval',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_name`, `target_date`, `time_window`, `status`, `created_at`) VALUES
(1, 'Allan', 'JUNE 25, 2026', '3:00 PM', 'Approved', '2026-06-08 14:45:41'),
(2, 'Tasnim', 'OCTOBER 22, 2026', '3:00 PM', 'Approved', '2026-06-08 14:48:37'),
(3, 'Tasnim', 'JULY 22, 2026', '10:00 AM', 'Rejected', '2026-06-08 14:48:44');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `alt_phone` varchar(30) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `email`, `password`, `phone`, `alt_phone`, `city`, `address`, `created_at`) VALUES
(1, 'Allan', 'a@gmail.com', '$2y$10$ZXMPhO4E1BLyRX7iTidO1Obj9J3LHPVyELtjyKNxo6sBnMryHu0TO', '01778999545', '01777444444', 'New York', '105 Street', '2026-06-08 14:43:43'),
(2, 'Tasnim', 'b@gmail.com', '$2y$10$MdT528gDovzY9cCe5OHEyecjA52aE4px1vd8X1Z12DiN79qRPBFB.', NULL, NULL, NULL, NULL, '2026-06-08 14:48:06');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `name`, `email`, `phone`, `password`, `role`) VALUES
(9, 'AF360-1002', 'Zamil Hossain', 'zamil@example.com', '01911998877', 'zamil789', 'Supervisor'),
(10, 'AF360-1003', 'Tariqul Islam', 'tariq@farm360.com', '01515443322', 'tariq2026', 'Field Staff'),
(11, 'AF360-1004', 'Sultana Razia', 'razia@farm360.com', '01818776655', 'razia990', 'Supervisor'),
(12, 'AF360-1005', 'Ahsan Habib', 'habib@farm360.com', '01313221100', 'habibpass', 'Field Staff'),
(13, 'AF360-1006', 'jay', 'jay@gmail.com', '015578889999', 'jay', 'Supervisor');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_city` varchar(100) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash On Delivery',
  `status` varchar(30) DEFAULT 'Processing',
  `created_date` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_name`, `customer_email`, `delivery_address`, `delivery_city`, `subtotal`, `total_amount`, `payment_method`, `status`, `created_date`) VALUES
(1, 'ID-182121858060', 'Allan', 'a@gmail.com', '108 Street', 'FL', 271.00, 280.35, 'Cash On Delivery', 'Cancelled', '08/06/2026'),
(2, 'ID-581501901851', 'Allan', 'a@gmail.com', '100 ST', 'CALIFORNIA', 1114.00, 996.90, 'Cash On Delivery', 'Completed', '08/06/2026'),
(3, 'ID-139828631389', 'Tasnim', 'b@gmail.com', '702 st', 'Utah', 21.00, 67.85, 'Cash On Delivery', 'Completed', '08/06/2026');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_number`, `product_name`, `unit_price`, `quantity`) VALUES
(1, 'ID-182121858060', 'Mozzarella Cheese - 1 kg', 21.00, 1),
(2, 'ID-182121858060', 'Dorper Sheep', 250.00, 1),
(3, 'ID-581501901851', 'Dorper Sheep', 250.00, 4),
(4, 'ID-581501901851', 'Mozzarella Cheese - 1 kg', 21.00, 1),
(5, 'ID-581501901851', 'Cow Milk - 1 liter', 17.00, 4),
(6, 'ID-581501901851', 'Duck Egg - 12 piece', 25.00, 1),
(7, 'ID-139828631389', 'Mozzarella Cheese - 1 kg', 21.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `sub_tag` varchar(50) NOT NULL,
  `sex` varchar(20) DEFAULT 'N/A',
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`, `sub_tag`, `sex`, `description`, `created_at`) VALUES
(1, 'Duck Egg - 12 piece', 25.00, 'images/egg.jpg', 'Produce', 'Egg', 'Female', 'Farm fresh gathered daily.', '2026-06-08 12:13:48'),
(2, 'Cow Milk - 1 liter', 17.00, 'images/images.jpg', 'Produce', 'Milk', 'N/A', 'Rich preservative free milk.', '2026-06-08 12:13:48'),
(3, 'Dorper Sheep', 250.00, 'images/dorpersheep.png', 'Livestock', 'Sheep', 'Male', 'Exceptional health meat sheep breed.', '2026-06-08 12:13:48'),
(4, 'Mozzarella Cheese - 1 kg', 21.00, 'images/mozarella.png', 'Produce', 'Cheese', 'N/A', 'Artisanal cultured cheese recipe.', '2026-06-08 12:13:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_number` (`order_number`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_number`) REFERENCES `orders` (`order_number`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
