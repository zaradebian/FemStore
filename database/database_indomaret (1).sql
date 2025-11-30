-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2025 at 12:49 AM
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
-- Database: `database_indomaret`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashier`
--

CREATE TABLE `cashier` (
  `id` char(3) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashier`
--

INSERT INTO `cashier` (`id`, `name`) VALUES
('007', 'Nanina Faradilla'),
('01', 'Rara'),
('02', 'Restu'),
('05', 'Narinda');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` smallint(4) NOT NULL,
  `voucher_id` char(6) DEFAULT NULL,
  `name` varchar(35) NOT NULL,
  `prices` int(6) NOT NULL,
  `stock` smallint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `voucher_id`, `name`, `prices`, `stock`) VALUES
(32, NULL, 'VelvetMist Lipstick', 59000, 50),
(33, 'vo-002', 'PearlDew Body Lotion', 123600, 29),
(34, 'vo-002', 'Lavojoy Body Soap', 34300, 19),
(35, NULL, 'RadianceBloom Face Cream', 89762, 31),
(36, 'vo-001', 'Bare n Bliss Airy Cushion', 137820, 59),
(37, 'vo-003', 'LunaCharm Earrings', 50600, 30),
(38, NULL, 'GlowMuse Nail Kit', 15700, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(10) NOT NULL,
  `date` date DEFAULT NULL,
  `code` varchar(10) NOT NULL,
  `cashier_id` char(3) NOT NULL,
  `total` int(9) NOT NULL,
  `pay` int(10) NOT NULL,
  `spare_change` int(7) NOT NULL,
  `status` enum('paid','pending','voided') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `date`, `code`, `cashier_id`, `total`, `pay`, `spare_change`, `status`) VALUES
(1, '2025-11-29', 'TRX0002', '05', 150326, 152000, 1674, 'paid'),
(2, '2025-11-29', 'TRX0003', '01', 78500, 0, 0, 'pending'),
(3, '2025-11-29', 'TRX0004', '02', 15700, 0, 0, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `transaction_id` int(4) NOT NULL,
  `product_id` smallint(4) NOT NULL,
  `prices` int(10) NOT NULL,
  `discount` double DEFAULT NULL,
  `qty` int(5) NOT NULL,
  `sub_total` int(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_details`
--

INSERT INTO `transaction_details` (`transaction_id`, `product_id`, `prices`, `discount`, `qty`, `sub_total`) VALUES
(1, 33, 123600, 51, 1, 123600),
(1, 35, 89762, 0, 1, 89762),
(2, 38, 15700, 0, 5, 78500),
(3, 38, 15700, 0, 1, 15700);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id` char(6) NOT NULL,
  `name` varchar(35) NOT NULL,
  `discount` tinyint(2) NOT NULL,
  `max_discount` int(7) NOT NULL,
  `expired_date` datetime DEFAULT NULL,
  `status` enum('active','not_active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id`, `name`, `discount`, `max_discount`, `expired_date`, `status`) VALUES
('vo-001', 'bare n bliss product voucher', 25, 0, '2026-11-01 18:20:21', 'active'),
('vo-002', 'body care voucher', 51, 0, '2026-03-17 18:21:15', 'active'),
('vo-003', 'bundle accessories voucher', 10, 0, '2026-03-17 18:21:15', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashier`
--
ALTER TABLE `cashier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_voucher` (`voucher_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`,`code`),
  ADD KEY `fk_transactions_cashier` (`cashier_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`transaction_id`,`product_id`),
  ADD KEY `fk_transaction_details_product` (`product_id`),
  ADD KEY `fk_transaction_details_transactions` (`transaction_id`) USING BTREE;

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_produk_voucher` FOREIGN KEY (`voucher_id`) REFERENCES `voucher` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_cashier` FOREIGN KEY (`cashier_id`) REFERENCES `cashier` (`id`);

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `fk_transaction_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `fk_transaction_details_transactions` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
