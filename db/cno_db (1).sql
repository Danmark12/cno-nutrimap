-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 22, 2025 at 03:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cno_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bns_reports`
--

CREATE TABLE `bns_reports` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `barangay` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `title` varchar(255) NOT NULL,
  `ind1` int(11) DEFAULT NULL,
  `ind2` int(11) DEFAULT NULL,
  `ind3` int(11) DEFAULT NULL,
  `ind4a` int(11) DEFAULT NULL,
  `ind4b` int(11) DEFAULT NULL,
  `ind5` int(11) DEFAULT NULL,
  `ind6` int(11) DEFAULT NULL,
  `ind7a` decimal(5,2) DEFAULT NULL,
  `ind7b1_no` int(11) DEFAULT NULL,
  `ind7b1_pct` decimal(5,2) DEFAULT NULL,
  `ind7b2_no` int(11) DEFAULT NULL,
  `ind7b2_pct` decimal(5,2) DEFAULT NULL,
  `ind7b3_no` int(11) DEFAULT NULL,
  `ind7b3_pct` decimal(5,2) DEFAULT NULL,
  `ind7b4_no` int(11) DEFAULT NULL,
  `ind7b4_pct` decimal(5,2) DEFAULT NULL,
  `ind7b5_no` int(11) DEFAULT NULL,
  `ind7b5_pct` decimal(5,2) DEFAULT NULL,
  `ind7b6_no` int(11) DEFAULT NULL,
  `ind7b6_pct` decimal(5,2) DEFAULT NULL,
  `ind7b7_no` int(11) DEFAULT NULL,
  `ind7b7_pct` decimal(5,2) DEFAULT NULL,
  `ind7b8_no` int(11) DEFAULT NULL,
  `ind7b8_pct` decimal(5,2) DEFAULT NULL,
  `ind7b9_no` int(11) DEFAULT NULL,
  `ind7b9_pct` decimal(5,2) DEFAULT NULL,
  `ind8` int(11) DEFAULT NULL,
  `ind9` int(11) DEFAULT NULL,
  `ind10` int(11) DEFAULT NULL,
  `ind11` int(11) DEFAULT NULL,
  `ind12` int(11) DEFAULT NULL,
  `ind13` int(11) DEFAULT NULL,
  `ind14` int(11) DEFAULT NULL,
  `ind15a_public` int(11) DEFAULT NULL,
  `ind15a_private` int(11) DEFAULT NULL,
  `ind15b_public` int(11) DEFAULT NULL,
  `ind15b_private` int(11) DEFAULT NULL,
  `ind16` int(11) DEFAULT NULL,
  `ind17` int(11) DEFAULT NULL,
  `ind18` int(11) DEFAULT NULL,
  `ind19` decimal(5,2) DEFAULT NULL,
  `ind20a_no` int(11) DEFAULT NULL,
  `ind20a_pct` decimal(5,2) DEFAULT NULL,
  `ind20b_no` int(11) DEFAULT NULL,
  `ind20b_pct` decimal(5,2) DEFAULT NULL,
  `ind20c_no` int(11) DEFAULT NULL,
  `ind20c_pct` decimal(5,2) DEFAULT NULL,
  `ind20d_no` int(11) DEFAULT NULL,
  `ind20d_pct` decimal(5,2) DEFAULT NULL,
  `ind20e_no` int(11) DEFAULT NULL,
  `ind20e_pct` decimal(5,2) DEFAULT NULL,
  `ind21` int(11) DEFAULT NULL,
  `ind22` int(11) DEFAULT NULL,
  `ind23` int(11) DEFAULT NULL,
  `ind24` int(11) DEFAULT NULL,
  `ind25` int(11) DEFAULT NULL,
  `ind26a_no` int(11) DEFAULT NULL,
  `ind26a_pct` decimal(5,2) DEFAULT NULL,
  `ind26b_no` int(11) DEFAULT NULL,
  `ind26b_pct` decimal(5,2) DEFAULT NULL,
  `ind26c_no` int(11) DEFAULT NULL,
  `ind26c_pct` decimal(5,2) DEFAULT NULL,
  `ind26d_no` int(11) DEFAULT NULL,
  `ind26d_pct` decimal(5,2) DEFAULT NULL,
  `ind27a_no` int(11) DEFAULT NULL,
  `ind27a_pct` decimal(5,2) DEFAULT NULL,
  `ind27b_no` int(11) DEFAULT NULL,
  `ind27b_pct` decimal(5,2) DEFAULT NULL,
  `ind27c_no` int(11) DEFAULT NULL,
  `ind27c_pct` decimal(5,2) DEFAULT NULL,
  `ind27d_no` int(11) DEFAULT NULL,
  `ind27d_pct` decimal(5,2) DEFAULT NULL,
  `ind28a_no` int(11) DEFAULT NULL,
  `ind28a_pct` decimal(5,2) DEFAULT NULL,
  `ind28b_no` int(11) DEFAULT NULL,
  `ind28b_pct` decimal(5,2) DEFAULT NULL,
  `ind28c_no` int(11) DEFAULT NULL,
  `ind28c_pct` decimal(5,2) DEFAULT NULL,
  `ind28d_no` int(11) DEFAULT NULL,
  `ind28d_pct` decimal(5,2) DEFAULT NULL,
  `ind28e_no` int(11) DEFAULT NULL,
  `ind28e_pct` decimal(5,2) DEFAULT NULL,
  `ind29a_no` int(11) DEFAULT NULL,
  `ind29a_pct` decimal(5,2) DEFAULT NULL,
  `ind29b_no` int(11) DEFAULT NULL,
  `ind29b_pct` decimal(5,2) DEFAULT NULL,
  `ind29c_no` int(11) DEFAULT NULL,
  `ind29c_pct` decimal(5,2) DEFAULT NULL,
  `ind29d_no` int(11) DEFAULT NULL,
  `ind29d_pct` decimal(5,2) DEFAULT NULL,
  `ind29e_no` int(11) DEFAULT NULL,
  `ind29e_pct` decimal(5,2) DEFAULT NULL,
  `ind30a_no` int(11) DEFAULT NULL,
  `ind30a_pct` decimal(5,2) DEFAULT NULL,
  `ind30b_no` int(11) DEFAULT NULL,
  `ind30b_pct` decimal(5,2) DEFAULT NULL,
  `ind30c_no` int(11) DEFAULT NULL,
  `ind30c_pct` decimal(5,2) DEFAULT NULL,
  `ind30d_no` int(11) DEFAULT NULL,
  `ind30d_pct` decimal(5,2) DEFAULT NULL,
  `ind30e_no` int(11) DEFAULT NULL,
  `ind30e_pct` decimal(5,2) DEFAULT NULL,
  `ind31` int(11) DEFAULT NULL,
  `ind32` int(11) DEFAULT NULL,
  `ind33` int(11) DEFAULT NULL,
  `ind34` int(11) DEFAULT NULL,
  `ind35a` int(11) DEFAULT NULL,
  `ind35b` int(11) DEFAULT NULL,
  `ind36` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bns_reports`
--

INSERT INTO `bns_reports` (`id`, `report_id`, `barangay`, `year`, `title`, `ind1`, `ind2`, `ind3`, `ind4a`, `ind4b`, `ind5`, `ind6`, `ind7a`, `ind7b1_no`, `ind7b1_pct`, `ind7b2_no`, `ind7b2_pct`, `ind7b3_no`, `ind7b3_pct`, `ind7b4_no`, `ind7b4_pct`, `ind7b5_no`, `ind7b5_pct`, `ind7b6_no`, `ind7b6_pct`, `ind7b7_no`, `ind7b7_pct`, `ind7b8_no`, `ind7b8_pct`, `ind7b9_no`, `ind7b9_pct`, `ind8`, `ind9`, `ind10`, `ind11`, `ind12`, `ind13`, `ind14`, `ind15a_public`, `ind15a_private`, `ind15b_public`, `ind15b_private`, `ind16`, `ind17`, `ind18`, `ind19`, `ind20a_no`, `ind20a_pct`, `ind20b_no`, `ind20b_pct`, `ind20c_no`, `ind20c_pct`, `ind20d_no`, `ind20d_pct`, `ind20e_no`, `ind20e_pct`, `ind21`, `ind22`, `ind23`, `ind24`, `ind25`, `ind26a_no`, `ind26a_pct`, `ind26b_no`, `ind26b_pct`, `ind26c_no`, `ind26c_pct`, `ind26d_no`, `ind26d_pct`, `ind27a_no`, `ind27a_pct`, `ind27b_no`, `ind27b_pct`, `ind27c_no`, `ind27c_pct`, `ind27d_no`, `ind27d_pct`, `ind28a_no`, `ind28a_pct`, `ind28b_no`, `ind28b_pct`, `ind28c_no`, `ind28c_pct`, `ind28d_no`, `ind28d_pct`, `ind28e_no`, `ind28e_pct`, `ind29a_no`, `ind29a_pct`, `ind29b_no`, `ind29b_pct`, `ind29c_no`, `ind29c_pct`, `ind29d_no`, `ind29d_pct`, `ind29e_no`, `ind29e_pct`, `ind30a_no`, `ind30a_pct`, `ind30b_no`, `ind30b_pct`, `ind30c_no`, `ind30c_pct`, `ind30d_no`, `ind30d_pct`, `ind30e_no`, `ind30e_pct`, `ind31`, `ind32`, `ind33`, `ind34`, `ind35a`, `ind35b`, `ind36`) VALUES
(12, 14, 'Amoros', '2025', 'health', 1, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0),
(13, 15, 'Amoros', '2025', 'NUTRITION', 1, 0, 0, 0, 0, 0, 0, 0.00, 1, 1.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 2, 1, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 1, 1.00, 0, 0, 0, 0, 0, 0, 1),
(14, 16, 'Amoros', '2025', 'danny adadn', 0, 0, 0, 0, 0, 0, 0, 0.00, 1, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(128) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_time` timestamp NULL DEFAULT NULL,
  `device_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `session_id`, `browser`, `ip_address`, `login_time`, `logout_time`, `device_token`) VALUES
(1, 2, '', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-09-21 13:19:01', '2025-09-21 14:24:03', NULL),
(2, 2, 'sf1e4vape9dt5j1k18c7bcibhn', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-09-21 14:12:55', NULL, NULL),
(4, 2, 'sf1e4vape9dt5j1k18c7bcibhn', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-09-21 14:40:35', NULL, NULL),
(5, 2, 'p012eglrt07qr22rk79860kh6j', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Sa', '::1', '2025-09-22 01:51:16', NULL, 'd3826b6dced5f9e342b7dc7d2df62d8a'),
(6, 2, 'p012eglrt07qr22rk79860kh6j', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Sa', '::1', '2025-09-22 01:51:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `user_id`, `otp_code`, `created_at`, `expires_at`) VALUES
(9, 2, '970074', '2025-09-15 05:23:48', '2025-09-15 07:28:48'),
(10, 2, '699247', '2025-09-15 11:57:40', '2025-09-15 14:02:40'),
(11, 5, '949655', '2025-09-15 14:36:52', '2025-09-15 16:41:52'),
(12, 5, '796328', '2025-09-15 14:37:01', '2025-09-15 16:42:01'),
(13, 2, '258143', '2025-09-16 03:31:44', '2025-09-16 05:36:44'),
(14, 2, '227212', '2025-09-17 13:22:29', '2025-09-17 15:27:29'),
(15, 2, '284633', '2025-09-18 01:00:43', '2025-09-18 03:05:43'),
(16, 2, '171406', '2025-09-18 01:00:49', '2025-09-18 03:05:49'),
(17, 2, '316970', '2025-09-18 03:05:36', '2025-09-18 05:10:36'),
(18, 2, '756661', '2025-09-19 02:55:05', '2025-09-19 05:00:05'),
(19, 2, '828712', '2025-09-20 08:10:19', '2025-09-20 10:15:19'),
(20, 2, '766044', '2025-09-20 13:43:26', '2025-09-20 15:48:26'),
(21, 2, '923118', '2025-09-21 12:21:48', '2025-09-21 14:26:48'),
(22, 2, '356644', '2025-09-21 13:19:01', '2025-09-21 15:24:01'),
(23, 2, '158273', '2025-09-21 14:12:55', '2025-09-21 16:17:55'),
(24, 2, '881612', '2025-09-21 14:40:12', '2025-09-21 16:45:12'),
(25, 2, '644275', '2025-09-22 01:50:30', '2025-09-22 03:55:30');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_time` time NOT NULL,
  `report_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `report_time`, `report_date`, `status`) VALUES
(3, 2, '04:15:32', '2025-09-18', 'Pending'),
(4, 2, '04:16:20', '2025-09-18', 'Pending'),
(5, 2, '04:23:42', '2025-09-18', 'Pending'),
(6, 2, '04:32:07', '2025-09-18', 'Pending'),
(7, 2, '04:41:52', '2025-09-18', 'Pending'),
(8, 2, '04:43:02', '2025-09-18', 'Pending'),
(9, 2, '05:06:26', '2025-09-18', 'Pending'),
(10, 2, '05:14:34', '2025-09-18', 'Pending'),
(11, 2, '05:18:59', '2025-09-18', 'Pending'),
(12, 2, '05:25:30', '2025-09-18', 'Pending'),
(13, 2, '05:34:31', '2025-09-18', 'Pending'),
(14, 2, '05:49:00', '2025-09-18', 'Pending'),
(15, 2, '09:53:34', '2025-09-18', 'Pending'),
(16, 2, '05:28:06', '2025-09-19', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `address` varchar(255) NOT NULL,
  `barangay` enum('CNO','Amoros','Bolisong','Cogon','Himaya','Hinigdaan','Kalabaylabay','Molugan','Pedro S. Baculio','Poblacion','Quibonbon','Sambulawan','San Francisco de Asis','Sinaloc','Taytay','Ulaliman') NOT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `user_type` enum('BNS','CNO') NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `current_session` varchar(128) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `password_changed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `phone_number`, `email`, `address`, `barangay`, `profile_pic`, `user_type`, `password_hash`, `current_session`, `created_at`, `password_changed`) VALUES
(2, 'Dan', 'Javier', 'mac', '09781716517', 'danmarkpetalcurin@gmail.com', 'st, joseph', 'Amoros', '1758381544_e11fa4849c43ba31181f7acff2522f8e.jpg', 'BNS', '$2y$10$FQUcASvaweBBaDSaPa6.f.dtIeFPb15/fGr/9maQPcbRl9516pcwO', 'p012eglrt07qr22rk79860kh6j', '2025-09-13 13:18:11', 1),
(5, 'arl', 'ly', 'arly', '09476445486', 'audreyabigailhisanza.9@gmail.com', 'Tankulan', 'CNO', NULL, 'CNO', '$2y$10$hTi9BrY3K5Q0NOo35AA9oO5ahG3KG4ZTzssgWVturWIwI2rRJQi06', NULL, '2025-09-15 14:36:11', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bns_reports`
--
ALTER TABLE `bns_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bns_reports`
--
ALTER TABLE `bns_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bns_reports`
--
ALTER TABLE `bns_reports`
  ADD CONSTRAINT `bns_reports_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
