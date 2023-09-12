-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2023 at 07:41 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `archive_table`
--

CREATE TABLE `archive_table` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Details` text DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `archive_table`
--

INSERT INTO `archive_table` (`id`, `Name`, `Details`, `Image`) VALUES
(3, 'Laptop', 'The Lenovo IdeaPad 330 i3 8th Gen is a reliable and efficient laptop. With its upgraded 8GB RAM and 512GB SSD, it offers improved performance and faster data access. The Full HD 15.6-inch display delivers sharp visuals, while the Intel Core i3 processor e', '1693141981_laptop.png'),
(4, 'Laptop', 'The Lenovo IdeaPad 330 i3 8th Gen is a reliable and efficient laptop. With its upgraded 8GB RAM and 512GB SSD, it offers improved performance and faster data access. The Full HD 15.6-inch display delivers sharp visuals, while the Intel Core i3 processor e', '1693143187_laptop.png');

-- --------------------------------------------------------

--
-- Table structure for table `backup_biddingincrement`
--

CREATE TABLE `backup_biddingincrement` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `increment_value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `backup_biddingincrement`
--

INSERT INTO `backup_biddingincrement` (`id`, `item_id`, `user_id`, `increment_value`, `created_at`) VALUES
(50, 1500, 2147483647, '1.00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `backup_items`
--

CREATE TABLE `backup_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `backup_items`
--

INSERT INTO `backup_items` (`id`, `name`, `end_time`) VALUES
(2, 'Item 1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `biddinghistory`
--

CREATE TABLE `biddinghistory` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bid_amount` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biddingincrement`
--

CREATE TABLE `biddingincrement` (
  `id` int(11) NOT NULL,
  `increment_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expired_table`
--

CREATE TABLE `expired_table` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Details` text DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expired_table`
--

INSERT INTO `expired_table` (`id`, `Name`, `Details`, `Image`, `CreatedAt`) VALUES
(2, 'Mobile', 'The OnePlus 11R is a remarkable smartphone that offers an impressive combination of features. Its Fluid AMOLED 120Hz display with HDR10+ provides vibrant visuals, while the Snapdragon 8+ Gen 1 processor ensures powerful performance. The versatile triple c', '1693141196_mobile.png', '2023-08-27 12:59:56'),
(5, 'Laptop', 'The Lenovo IdeaPad 330 i3 8th Gen is a reliable and efficient laptop. With its upgraded 8GB RAM and 512GB SSD, it offers improved performance and faster data access. The Full HD 15.6-inch display delivers sharp visuals, while the Intel Core i3 processor e', '1693224624_laptop.png', '2023-08-28 12:10:24');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `starting_price` decimal(10,2) DEFAULT 0.00,
  `end_time` datetime DEFAULT NULL,
  `seller` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `description`, `image`, `created_at`, `starting_price`, `end_time`, `seller`) VALUES
(20, 'Mobile', 'The OnePlus 11R is a remarkable smartphone that offers an impressive combination of features. Its Fluid AMOLED 120Hz display with HDR10+ provides vibrant visuals, while the Snapdragon 8+ Gen 1 processor ensures powerful performance. The versatile triple c', '1693224647_mobile.png', '2023-08-28 12:10:47', '500.00', '2023-08-28 17:57:00', 'Doreamon'),
(21, 'Lenovo ideapad', 'The Lenovo IdeaPad 330 i3 8th Gen is a reliable and efficient laptop. With its upgraded 8GB RAM and 512GB SSD, it offers improved performance and faster data access. The Full HD 15.6-inch display delivers sharp visuals, while the Intel Core i3 processor e', '1693228902_laptop.png', '2023-08-28 13:21:42', '100000.00', '2023-08-31 19:06:00', 'Zanavi'),
(23, 'Car', 'It\'s a popular mode of transportation due to its convenience, versatility, and widespread availability. ', '1693808251_car.png', '2023-09-04 06:17:31', '1000000.00', '2023-09-09 11:59:00', 'Naruto');

-- --------------------------------------------------------

--
-- Table structure for table `registration`
--

CREATE TABLE `registration` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','staff','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified` enum('true','false') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `verified`) VALUES
(11, 'User1', 'qpobejsrunynp@pretreer.com', '25f9e794323b453885f5181f1b624d0b', 'user', '2023-09-04 13:49:50', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `verify`
--

CREATE TABLE `verify` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `verification_code` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `archive_table`
--
ALTER TABLE `archive_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup_biddingincrement`
--
ALTER TABLE `backup_biddingincrement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup_items`
--
ALTER TABLE `backup_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `biddinghistory`
--
ALTER TABLE `biddinghistory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `biddingincrement`
--
ALTER TABLE `biddingincrement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `FK_item_id` (`item_id`);

--
-- Indexes for table `expired_table`
--
ALTER TABLE `expired_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registration`
--
ALTER TABLE `registration`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `verify`
--
ALTER TABLE `verify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biddinghistory`
--
ALTER TABLE `biddinghistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `biddingincrement`
--
ALTER TABLE `biddingincrement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `expired_table`
--
ALTER TABLE `expired_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `registration`
--
ALTER TABLE `registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `verify`
--
ALTER TABLE `verify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `biddinghistory`
--
ALTER TABLE `biddinghistory`
  ADD CONSTRAINT `biddinghistory_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `biddinghistory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `biddingincrement`
--
ALTER TABLE `biddingincrement`
  ADD CONSTRAINT `FK_item_id` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `biddingincrement_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `verify`
--
ALTER TABLE `verify`
  ADD CONSTRAINT `verify_ibfk_1` FOREIGN KEY (`email`) REFERENCES `registration` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
