-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 11, 2026 at 11:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `equipmate_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories_list`
--

CREATE TABLE `categories_list` (
  `cat_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text NOT NULL,
  `tags` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories_list`
--

INSERT INTO `categories_list` (`cat_id`, `category_name`, `category_description`, `tags`, `date_added`) VALUES
(1, 'Writing Tools', 'Lorem ipsum', 'pen,paper,chalk', '2025-10-03 11:56:13'),
(2, 'Paper Goods', 'lorem ipsum dolor sit amet', 'paper,Composition books,notebooks,folders', '2025-10-03 13:06:27'),
(3, 'Art Supplies', 'lorem ipsum', 'crayon,marker,glue,paste', '2025-10-03 13:06:57'),
(4, 'Organizational & Storage', 'lorem ipsum', 'binders,bag packs,Book bags', '2025-10-03 13:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `department_list`
--

CREATE TABLE `department_list` (
  `d_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_abbrv` varchar(25) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_list`
--

INSERT INTO `department_list` (`d_id`, `department_name`, `department_abbrv`, `date_added`) VALUES
(1, 'Math Department', 'Math101', '2026-01-11 13:34:51');

-- --------------------------------------------------------

--
-- Table structure for table `distribution_list`
--

CREATE TABLE `distribution_list` (
  `distribution_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_distributed` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribution_list`
--

INSERT INTO `distribution_list` (`distribution_id`, `material_id`, `employee_id`, `quantity`, `date_distributed`) VALUES
(1, 1, 1, 1, '2025-10-03 14:47:12'),
(4, 1, 1, 5, '2025-10-03 15:17:54'),
(5, 1, 1, 2, '2025-10-03 15:18:06'),
(6, 1, 1, 5, '2025-11-05 22:52:59');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employee_id` int(11) NOT NULL,
  `eIDno` varchar(50) NOT NULL,
  `department_id` int(11) NOT NULL,
  `empType` enum('faculty','staff') NOT NULL,
  `preName` varchar(50) DEFAULT NULL,
  `lName` varchar(100) NOT NULL,
  `fName` varchar(100) NOT NULL,
  `mName` varchar(100) DEFAULT NULL,
  `sName` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employee_id`, `eIDno`, `department_id`, `empType`, `preName`, `lName`, `fName`, `mName`, `sName`, `created_at`) VALUES
(1, '2021123', 1, 'staff', 'Mr.', 'Doe', 'John', 'Dela', 'DIT', '2025-10-03 06:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `materials_list`
--

CREATE TABLE `materials_list` (
  `material_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `initial_quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `date_added` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `materials_list`
--

INSERT INTO `materials_list` (`material_id`, `supplier_id`, `material_name`, `category_id`, `initial_quantity`, `unit`, `date_added`) VALUES
(1, 2, 'A4 Bond Paper', 1, 10, 'ream', '2025-10-03 13:14:52'),
(2, 2, 'Long Folder', 1, 50, 'pcs', '2025-10-03 14:25:55'),
(3, 2, 'Chalk', 1, 50, 'box', '2025-10-03 14:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `restock_list`
--

CREATE TABLE `restock_list` (
  `restock_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restock_list`
--

INSERT INTO `restock_list` (`restock_id`, `material_id`, `quantity`, `date_added`) VALUES
(1, 1, 2, '2025-10-03 07:25:23');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_list`
--

CREATE TABLE `supplier_list` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `supplier_address` varchar(255) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_list`
--

INSERT INTO `supplier_list` (`supplier_id`, `supplier_name`, `supplier_address`, `date_added`) VALUES
(2, 'Some Supplier Name', 'Cabagan, Isabela', '2026-01-11 18:05:16');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Thesis Management', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `isVerified` tinyint(4) NOT NULL DEFAULT 1,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `isVerified`, `date_created`) VALUES
(1, 'Administrator', '', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', '1607135820_avatar.jpg', 1, '2020-11-26 10:57:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories_list`
--
ALTER TABLE `categories_list`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `department_list`
--
ALTER TABLE `department_list`
  ADD PRIMARY KEY (`d_id`);

--
-- Indexes for table `distribution_list`
--
ALTER TABLE `distribution_list`
  ADD PRIMARY KEY (`distribution_id`),
  ADD KEY `material_id` (`material_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `eIDno` (`eIDno`);

--
-- Indexes for table `materials_list`
--
ALTER TABLE `materials_list`
  ADD PRIMARY KEY (`material_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `restock_list`
--
ALTER TABLE `restock_list`
  ADD PRIMARY KEY (`restock_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories_list`
--
ALTER TABLE `categories_list`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `department_list`
--
ALTER TABLE `department_list`
  MODIFY `d_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `distribution_list`
--
ALTER TABLE `distribution_list`
  MODIFY `distribution_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `materials_list`
--
ALTER TABLE `materials_list`
  MODIFY `material_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `restock_list`
--
ALTER TABLE `restock_list`
  MODIFY `restock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribution_list`
--
ALTER TABLE `distribution_list`
  ADD CONSTRAINT `distribution_list_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials_list` (`material_id`),
  ADD CONSTRAINT `distribution_list_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`employee_id`);

--
-- Constraints for table `materials_list`
--
ALTER TABLE `materials_list`
  ADD CONSTRAINT `materials_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories_list` (`cat_id`);

--
-- Constraints for table `restock_list`
--
ALTER TABLE `restock_list`
  ADD CONSTRAINT `restock_list_ibfk_1` FOREIGN KEY (`material_id`) REFERENCES `materials_list` (`material_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
