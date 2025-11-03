-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 01:16 PM
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
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
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
  `ind_male` int(11) DEFAULT NULL,
  `ind_female` int(11) DEFAULT NULL,
  `ind2` int(11) DEFAULT NULL,
  `ind3` int(11) DEFAULT NULL,
  `ind4` int(11) DEFAULT NULL,
  `ind5` int(11) DEFAULT NULL,
  `ind6a` int(11) DEFAULT NULL,
  `ind6b` int(11) DEFAULT NULL,
  `ind7` int(11) DEFAULT NULL,
  `ind8` int(11) DEFAULT NULL,
  `ind9` int(11) DEFAULT NULL,
  `ind9a` decimal(5,2) DEFAULT NULL,
  `ind9b1_no` int(11) DEFAULT NULL,
  `ind9b1_pct` decimal(5,2) DEFAULT NULL,
  `ind9b2_no` int(11) DEFAULT NULL,
  `ind9b2_pct` decimal(5,2) DEFAULT NULL,
  `ind9b3_no` int(11) DEFAULT NULL,
  `ind9b3_pct` decimal(5,2) DEFAULT NULL,
  `ind9b4_no` int(11) DEFAULT NULL,
  `ind9b4_pct` decimal(5,2) DEFAULT NULL,
  `ind9b5_no` int(11) DEFAULT NULL,
  `ind9b5_pct` decimal(5,2) DEFAULT NULL,
  `ind9b6_no` int(11) DEFAULT NULL,
  `ind9b6_pct` decimal(5,2) DEFAULT NULL,
  `ind9b7_no` int(11) DEFAULT NULL,
  `ind9b7_pct` decimal(5,2) DEFAULT NULL,
  `ind9b8_no` int(11) DEFAULT NULL,
  `ind9b8_pct` decimal(5,2) DEFAULT NULL,
  `ind9b9_no` int(11) DEFAULT NULL,
  `ind9b9_pct` decimal(5,2) DEFAULT NULL,
  `ind10` int(11) DEFAULT NULL,
  `ind11` int(11) DEFAULT NULL,
  `ind12` int(11) DEFAULT NULL,
  `ind13` int(11) DEFAULT NULL,
  `ind14` int(11) DEFAULT NULL,
  `ind15` int(11) DEFAULT NULL,
  `ind16` int(11) DEFAULT NULL,
  `ind17a_public` int(11) DEFAULT NULL,
  `ind17a_private` int(11) DEFAULT NULL,
  `ind17b_public` int(11) DEFAULT NULL,
  `ind17b_private` int(11) DEFAULT NULL,
  `ind18` int(11) DEFAULT NULL,
  `ind19` int(11) DEFAULT NULL,
  `ind20` int(11) DEFAULT NULL,
  `ind21` decimal(5,2) DEFAULT NULL,
  `ind22a_no` int(11) DEFAULT NULL,
  `ind22a_pct` decimal(5,2) DEFAULT NULL,
  `ind22b_no` int(11) DEFAULT NULL,
  `ind22b_pct` decimal(5,2) DEFAULT NULL,
  `ind22c_no` int(11) DEFAULT NULL,
  `ind22c_pct` decimal(5,2) DEFAULT NULL,
  `ind22d_no` int(11) DEFAULT NULL,
  `ind22d_pct` decimal(5,2) DEFAULT NULL,
  `ind22e_no` int(11) DEFAULT NULL,
  `ind22e_pct` decimal(5,2) DEFAULT NULL,
  `ind22f_no` int(11) DEFAULT NULL,
  `ind22f_pct` decimal(5,2) DEFAULT NULL,
  `ind22g_no` int(11) DEFAULT NULL,
  `ind22g_pct` decimal(5,2) DEFAULT NULL,
  `ind23` int(11) DEFAULT NULL,
  `ind24` int(11) DEFAULT NULL,
  `ind25` int(11) DEFAULT NULL,
  `ind26` int(11) DEFAULT NULL,
  `ind27a_no` int(11) DEFAULT NULL,
  `ind27a_pct` decimal(5,2) DEFAULT NULL,
  `ind27b_no` int(11) DEFAULT NULL,
  `ind27b_pct` decimal(5,2) DEFAULT NULL,
  `ind27c_no` int(11) DEFAULT NULL,
  `ind27c_pct` decimal(5,2) DEFAULT NULL,
  `ind27d_no` int(11) DEFAULT NULL,
  `ind27d_pct` decimal(5,2) DEFAULT NULL,
  `ind27e_no` int(11) DEFAULT NULL,
  `ind27e_pct` decimal(5,2) DEFAULT NULL,
  `ind28a_no` int(11) DEFAULT NULL,
  `ind28a_pct` decimal(5,2) DEFAULT NULL,
  `ind28b_no` int(11) DEFAULT NULL,
  `ind28b_pct` decimal(5,2) DEFAULT NULL,
  `ind28c_no` int(11) DEFAULT NULL,
  `ind28c_pct` decimal(5,2) DEFAULT NULL,
  `ind28d_no` int(11) DEFAULT NULL,
  `ind28d_pct` decimal(5,2) DEFAULT NULL,
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
  `ind29f_no` int(11) DEFAULT NULL,
  `ind29f_pct` decimal(5,2) DEFAULT NULL,
  `ind29g_no` int(11) DEFAULT NULL,
  `ind29g_pct` decimal(5,2) DEFAULT NULL,
  `ind30a_no` int(11) DEFAULT NULL,
  `ind30a_pct` decimal(5,2) DEFAULT NULL,
  `ind30b_no` int(11) DEFAULT NULL,
  `ind30b_pct` decimal(5,2) DEFAULT NULL,
  `ind30c_no` int(11) DEFAULT NULL,
  `ind30c_pct` decimal(5,2) DEFAULT NULL,
  `ind30d_no` int(11) DEFAULT NULL,
  `ind30d_pct` decimal(5,2) DEFAULT NULL,
  `ind31a_no` int(11) DEFAULT NULL,
  `ind31a_pct` decimal(5,2) DEFAULT NULL,
  `ind31b_no` int(11) DEFAULT NULL,
  `ind31b_pct` decimal(5,2) DEFAULT NULL,
  `ind31c_no` int(11) DEFAULT NULL,
  `ind31c_pct` decimal(5,2) DEFAULT NULL,
  `ind31d_no` int(11) DEFAULT NULL,
  `ind31d_pct` decimal(5,2) DEFAULT NULL,
  `ind31e_no` int(11) DEFAULT NULL,
  `ind31e_pct` decimal(5,2) DEFAULT NULL,
  `ind31f_no` int(11) DEFAULT NULL,
  `ind31f_pct` decimal(5,2) DEFAULT NULL,
  `ind32_no` int(11) DEFAULT NULL,
  `ind32_pct` decimal(5,2) DEFAULT NULL,
  `ind33_no` int(11) DEFAULT NULL,
  `ind33_pct` decimal(5,2) DEFAULT NULL,
  `ind34_no` int(11) DEFAULT NULL,
  `ind34_pct` decimal(5,2) DEFAULT NULL,
  `ind35_no` int(11) DEFAULT NULL,
  `ind35_pct` decimal(5,2) DEFAULT NULL,
  `ind36_no` int(11) DEFAULT NULL,
  `ind36_pct` decimal(5,2) DEFAULT NULL,
  `ind37a` int(11) DEFAULT NULL,
  `ind37b` int(11) DEFAULT NULL,
  `ind38` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bns_reports`
--

-- --------------------------------------------------------

--
-- Table structure for table `consolidated_reports`
--

CREATE TABLE `consolidated_reports` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consolidated_reports`
--

INSERT INTO `consolidated_reports` (`id`, `year`, `file_name`, `created_at`) VALUES
(7, '2025', 'consolidated_health_nutrition_2025.json', '2025-10-10 12:45:24');

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


-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_type` enum('CNO','BNS') NOT NULL,
  `type` enum('report_submitted','report_updated','save_changes','report_approved','report_rejected') NOT NULL,
  `related_id` int(11) DEFAULT NULL,
  `message` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`

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


-- --------------------------------------------------------

--
-- Table structure for table `report_archives`
--

CREATE TABLE `report_archives` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('BNS','CNO') NOT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `is_deleted` tinyint(1) DEFAULT 0,
  `archived_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_archives`
--


-- --------------------------------------------------------
CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_time` time NOT NULL,
  `report_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Archived') DEFAULT 'Pending',
  `prev_status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
  `password_changed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `consolidated_reports`
--
ALTER TABLE `consolidated_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`);

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
-- Indexes for table `report_archives`
--
ALTER TABLE `report_archives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_report` (`report_id`,`user_id`,`user_type`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1377;

--
-- AUTO_INCREMENT for table `consolidated_reports`
--
ALTER TABLE `consolidated_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=243;

--
-- AUTO_INCREMENT for table `report_archives`
--
ALTER TABLE `report_archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
