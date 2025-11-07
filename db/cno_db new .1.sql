-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 10:25 AM
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
-- Table structure for table `barangay_consolidated_reports`
--

CREATE TABLE `barangay_consolidated_reports` (
  `id` int(11) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `year` year(4) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `bns_reports` (`id`, `report_id`, `barangay`, `year`, `title`, `ind1`, `ind_male`, `ind_female`, `ind2`, `ind3`, `ind4`, `ind5`, `ind6a`, `ind6b`, `ind7`, `ind8`, `ind9`, `ind9a`, `ind9b1_no`, `ind9b1_pct`, `ind9b2_no`, `ind9b2_pct`, `ind9b3_no`, `ind9b3_pct`, `ind9b4_no`, `ind9b4_pct`, `ind9b5_no`, `ind9b5_pct`, `ind9b6_no`, `ind9b6_pct`, `ind9b7_no`, `ind9b7_pct`, `ind9b8_no`, `ind9b8_pct`, `ind9b9_no`, `ind9b9_pct`, `ind10`, `ind11`, `ind12`, `ind13`, `ind14`, `ind15`, `ind16`, `ind17a_public`, `ind17a_private`, `ind17b_public`, `ind17b_private`, `ind18`, `ind19`, `ind20`, `ind21`, `ind22a_no`, `ind22a_pct`, `ind22b_no`, `ind22b_pct`, `ind22c_no`, `ind22c_pct`, `ind22d_no`, `ind22d_pct`, `ind22e_no`, `ind22e_pct`, `ind22f_no`, `ind22f_pct`, `ind22g_no`, `ind22g_pct`, `ind23`, `ind24`, `ind25`, `ind26`, `ind27a_no`, `ind27a_pct`, `ind27b_no`, `ind27b_pct`, `ind27c_no`, `ind27c_pct`, `ind27d_no`, `ind27d_pct`, `ind27e_no`, `ind27e_pct`, `ind28a_no`, `ind28a_pct`, `ind28b_no`, `ind28b_pct`, `ind28c_no`, `ind28c_pct`, `ind28d_no`, `ind28d_pct`, `ind29a_no`, `ind29a_pct`, `ind29b_no`, `ind29b_pct`, `ind29c_no`, `ind29c_pct`, `ind29d_no`, `ind29d_pct`, `ind29e_no`, `ind29e_pct`, `ind29f_no`, `ind29f_pct`, `ind29g_no`, `ind29g_pct`, `ind30a_no`, `ind30a_pct`, `ind30b_no`, `ind30b_pct`, `ind30c_no`, `ind30c_pct`, `ind30d_no`, `ind30d_pct`, `ind31a_no`, `ind31a_pct`, `ind31b_no`, `ind31b_pct`, `ind31c_no`, `ind31c_pct`, `ind31d_no`, `ind31d_pct`, `ind31e_no`, `ind31e_pct`, `ind31f_no`, `ind31f_pct`, `ind32_no`, `ind32_pct`, `ind33_no`, `ind33_pct`, `ind34_no`, `ind34_pct`, `ind35_no`, `ind35_pct`, `ind36_no`, `ind36_pct`, `ind37a`, `ind37b`, `ind38`) VALUES
(0, 249, 'Ulaliman', '2025', 's1', 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, NULL, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0),
(0, 250, 'Taytay', '2025', 's1', 1, 1, 1, 1, 1, 3, 3, 0, 0, 0, 0, NULL, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0),
(0, 251, 'Taytay', '2025', 's2', 1, 2, 3, 4, 0, 0, 0, 0, 0, 0, 0, NULL, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0, 0, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0.00, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `consolidated_reports`
--

CREATE TABLE `consolidated_reports` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `barangay` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consolidated_reports`
--

INSERT INTO `consolidated_reports` (`id`, `year`, `file_name`, `created_at`, `barangay`, `file_path`, `updated_at`) VALUES
(9, '2025', 'consolidated_health_nutrition_2025.json', '2025-11-06 07:11:07', NULL, NULL, '2025-11-06 07:11:07');

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
(11, 6, '0j13qjomsqtgnuevglg5qg1qmp', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-10-21 12:12:35', NULL, '5f5e2f5902be71ed469b32bb0b42eac1'),
(12, 6, 'f2vk8e7csrlte2sr28obljui7h', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-09-25 03:08:42', NULL, NULL),
(15, 6, 'n0c545f4h3kcqkgs7g81nbfct3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Sa', '::1', '2025-11-06 06:44:43', NULL, 'd3826b6dced5f9e342b7dc7d2df62d8a'),
(16, 6, 'r74h2cl1imh21ruccmjetfai26', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Sa', '::1', '2025-09-26 15:03:26', NULL, NULL),
(21, 10, 'qhsr5d0takuq933oueh4gc578k', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-11-06 09:22:28', NULL, '5f5e2f5902be71ed469b32bb0b42eac1'),
(22, 10, '6q21u3tjoe48rbe8nhoi2dt865', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-10-04 06:07:28', NULL, NULL),
(23, 11, '83vmm7lvl1j1aj786irqc53poq', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Sa', '::1', '2025-10-06 13:30:59', NULL, 'e71e8f55abea227acfdf3eb4338a9718'),
(24, 11, '83vmm7lvl1j1aj786irqc53poq', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Sa', '::1', '2025-10-06 13:31:51', NULL, NULL),
(25, 11, 'f1t4jvaj3d7nvhoccobb23p04r', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Sa', '::1', '2025-10-08 13:57:42', NULL, '5f5e2f5902be71ed469b32bb0b42eac1'),
(26, 10, 't7leu3t16co2fjirmj333ngdrg', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Sa', '::1', '2025-11-06 02:56:14', NULL, 'd3826b6dced5f9e342b7dc7d2df62d8a'),
(27, 10, 't7leu3t16co2fjirmj333ngdrg', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Sa', '::1', '2025-11-06 02:57:22', NULL, NULL),
(28, 11, 'n0c545f4h3kcqkgs7g81nbfct3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Sa', '::1', '2025-11-06 06:55:02', NULL, 'd3826b6dced5f9e342b7dc7d2df62d8a'),
(29, 11, 'n0c545f4h3kcqkgs7g81nbfct3', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Sa', '::1', '2025-11-06 06:55:49', NULL, NULL);

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
--

INSERT INTO `notifications` (`id`, `user_id`, `actor_id`, `sender_id`, `receiver_type`, `type`, `related_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(223, 6, NULL, 10, 'BNS', 'report_approved', 199, 'Your report \"3\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=199', 0, '2025-10-15 12:32:39'),
(224, 6, NULL, 10, 'BNS', 'report_approved', 199, 'Your report \"3\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=199', 0, '2025-10-15 12:32:45'),
(225, 6, NULL, 10, 'BNS', 'report_approved', 198, 'Your report \"2\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=198', 0, '2025-10-15 12:32:52'),
(226, 6, NULL, 10, 'BNS', 'report_approved', 197, 'Your report \"1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=197', 1, '2025-10-15 12:32:57'),
(227, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>q1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=200', 0, '2025-10-15 12:34:03'),
(228, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>sample</strong> has been submitted and is pending your review.', '/bns/reports.php?id=201', 0, '2025-10-25 14:19:15'),
(229, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=202', 0, '2025-10-25 14:42:35'),
(230, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s2</strong> has been submitted and is pending your review.', '/bns/reports.php?id=204', 0, '2025-10-25 15:03:36'),
(231, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s3</strong> has been submitted and is pending your review.', '/bns/reports.php?id=205', 0, '2025-10-25 15:05:33'),
(232, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s4</strong> has been submitted and is pending your review.', '/bns/reports.php?id=207', 0, '2025-10-26 03:08:35'),
(233, 6, NULL, 10, 'BNS', 'report_approved', 207, 'Your report \"s4\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=207', 0, '2025-10-26 14:28:04'),
(234, 6, NULL, 10, 'BNS', 'report_approved', 201, 'Your report \"sample\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=201', 0, '2025-10-27 13:18:54'),
(235, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s5</strong> has been submitted and is pending your review.', '/bns/reports.php?id=208', 0, '2025-10-27 13:57:16'),
(236, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>a1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=212', 0, '2025-10-28 13:09:28'),
(237, 6, NULL, 10, 'BNS', 'report_approved', 213, 'Your report \"a1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=213', 0, '2025-10-28 13:14:35'),
(238, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>q1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=214', 0, '2025-10-29 03:09:36'),
(239, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>q1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=215', 0, '2025-10-29 03:13:52'),
(240, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>q1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=216', 0, '2025-10-29 03:15:21'),
(241, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>d1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=217', 0, '2025-10-29 03:44:18'),
(242, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>d1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=218', 0, '2025-10-29 04:02:27'),
(243, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>d2</strong> has been submitted and is pending your review.', '/bns/reports.php?id=219', 0, '2025-10-29 04:03:51'),
(244, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>D1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=220', 0, '2025-10-29 06:11:12'),
(245, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>D2</strong> has been submitted and is pending your review.', '/bns/reports.php?id=221', 0, '2025-10-29 06:13:28'),
(246, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>F1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=222', 0, '2025-10-29 06:17:08'),
(247, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>C1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=223', 0, '2025-10-29 06:51:32'),
(248, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>a1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=224', 0, '2025-10-29 08:33:32'),
(249, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>z1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=225', 0, '2025-10-29 08:48:31'),
(250, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>q1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=226', 0, '2025-10-29 09:15:19'),
(251, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>f1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=227', 0, '2025-10-29 09:17:48'),
(252, 6, NULL, 10, 'BNS', 'report_rejected', 227, 'Your report \"f1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=227', 0, '2025-10-29 14:20:05'),
(253, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>G1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=228', 0, '2025-10-29 14:20:47'),
(254, 6, NULL, 10, 'BNS', 'report_rejected', 228, 'Your report \"G1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=228', 0, '2025-10-29 14:24:18'),
(255, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>eqwewqewqeqwe</strong> has been submitted and is pending your review.', '/bns/reports.php?id=229', 0, '2025-10-29 14:27:11'),
(256, 6, NULL, 10, 'BNS', 'report_rejected', 229, 'Your report \"eqwewqewqeqwe\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=229', 0, '2025-10-29 14:27:28'),
(257, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>asdsadasdsad</strong> has been submitted and is pending your review.', '/bns/reports.php?id=230', 0, '2025-10-29 14:29:47'),
(258, 6, NULL, 10, 'BNS', 'report_approved', 230, 'Your report \"asdsadasdsad\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=230', 0, '2025-10-29 14:31:13'),
(259, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>kfdskfnlakfkaslf</strong> has been submitted and is pending your review.', '/bns/reports.php?id=231', 0, '2025-10-29 14:32:55'),
(260, 6, NULL, 10, 'BNS', 'report_rejected', 231, 'Your report \"kfdskfnlakfkaslf\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=231', 0, '2025-10-29 14:34:30'),
(261, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>KKKKKK</strong> has been submitted and is pending your review.', '/bns/reports.php?id=232', 0, '2025-10-29 14:40:04'),
(262, 6, NULL, 10, 'BNS', 'report_rejected', 232, 'Your report \"KKKKKK\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=232', 0, '2025-10-29 14:40:45'),
(263, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>AAAAAAAAA</strong> has been submitted and is pending your review.', '/bns/reports.php?id=233', 0, '2025-10-29 14:41:47'),
(264, 6, NULL, 10, 'BNS', 'report_rejected', 233, 'Your report \"AAAAAAAAA\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=233', 0, '2025-10-29 14:42:29'),
(265, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>DDDDDDDDDDD</strong> has been submitted and is pending your review.', '/bns/reports.php?id=234', 0, '2025-10-29 14:46:32'),
(266, 6, NULL, 10, 'BNS', 'report_rejected', 234, 'Your report \"DDDDDDDDDDD\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=234', 0, '2025-10-29 14:47:04'),
(267, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>dan1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=235', 0, '2025-10-30 05:51:15'),
(268, 6, NULL, 10, 'BNS', 'report_rejected', 235, 'Your report \"dan1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=235', 0, '2025-10-30 05:51:39'),
(269, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>mac1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=236', 0, '2025-10-30 05:52:57'),
(270, 6, NULL, 10, 'BNS', 'report_rejected', 236, 'Your report \"mac1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=236', 0, '2025-10-30 05:53:48'),
(271, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>dasq1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=237', 0, '2025-10-30 12:02:47'),
(272, 6, NULL, 10, 'BNS', 'report_rejected', 237, 'Your report \"dasq1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=237', 1, '2025-10-30 12:03:20'),
(273, 6, NULL, 10, 'BNS', 'report_rejected', 237, 'Your report \"dasq1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=237', 0, '2025-10-30 12:06:00'),
(274, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>faq1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=238', 0, '2025-10-30 12:06:41'),
(275, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>daw2</strong> has been submitted and is pending your review.', '/bns/reports.php?id=239', 0, '2025-10-30 12:06:56'),
(276, 6, NULL, 10, 'BNS', 'report_approved', 239, 'Your report \"daw2\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=239', 0, '2025-10-30 12:07:31'),
(277, 6, NULL, 10, 'BNS', 'report_rejected', 240, 'Your report \"daw2\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=240', 0, '2025-10-30 12:09:01'),
(278, 6, NULL, 10, 'BNS', 'report_approved', 238, 'Your report \"faq1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=238', 0, '2025-10-30 12:10:45'),
(279, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>HAI</strong> has been submitted and is pending your review.', '/bns/reports.php?id=241', 0, '2025-10-30 12:34:28'),
(280, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>DASD</strong> has been submitted and is pending your review.', '/bns/reports.php?id=242', 0, '2025-10-30 13:05:45'),
(281, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>DASASA1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=243', 0, '2025-11-03 13:52:18'),
(282, 6, NULL, 10, 'BNS', 'report_rejected', 243, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=243', 0, '2025-11-03 13:52:42'),
(283, 6, NULL, 10, 'BNS', 'report_rejected', 243, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=243', 0, '2025-11-03 13:59:12'),
(284, 6, NULL, 10, 'BNS', 'report_rejected', 244, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=244', 0, '2025-11-03 14:02:44'),
(285, 6, NULL, 10, 'BNS', 'report_rejected', 244, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=244', 0, '2025-11-03 14:03:27'),
(286, 6, NULL, 10, 'BNS', 'report_rejected', 244, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=244', 0, '2025-11-03 14:20:05'),
(287, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>123</strong> has been submitted and is pending your review.', '/bns/reports.php?id=245', 0, '2025-11-03 14:22:49'),
(288, 6, NULL, 10, 'BNS', 'report_rejected', 245, 'Your report \"123\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=245', 0, '2025-11-03 14:24:34'),
(289, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>321</strong> has been submitted and is pending your review.', '/bns/reports.php?id=246', 0, '2025-11-03 14:27:05'),
(290, 6, NULL, 10, 'BNS', 'report_approved', 245, 'Your report \"123\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=245', 0, '2025-11-03 14:28:40'),
(291, 6, NULL, 10, 'BNS', 'report_rejected', 247, 'Your report \"123\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=247', 0, '2025-11-04 07:39:35'),
(292, 6, NULL, 10, 'BNS', 'report_rejected', 246, 'Your report \"321\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=246', 0, '2025-11-04 07:44:04'),
(293, 6, NULL, 10, 'BNS', 'report_rejected', 247, 'Your report \"123\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=247', 0, '2025-11-04 12:49:31'),
(294, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>dasd</strong> has been submitted and is pending your review.', '/bns/reports.php?id=248', 0, '2025-11-04 12:51:36'),
(295, 6, NULL, 10, 'BNS', 'report_rejected', 248, 'Your report \"dasd\" has been <b style=\'color:black;\'>Rejected</b> by <b>andro a</b>.', 'view_report.php?id=248', 0, '2025-11-04 12:51:52'),
(296, 6, NULL, 10, 'BNS', 'report_approved', 248, 'Your report \"dasd\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=248', 0, '2025-11-04 13:22:09'),
(297, 6, NULL, 10, 'BNS', 'report_approved', 247, 'Your report \"123\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=247', 0, '2025-11-04 13:27:23'),
(298, 6, NULL, 10, 'BNS', 'report_approved', 244, 'Your report \"DASASA1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=244', 0, '2025-11-04 13:40:02'),
(299, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=249', 0, '2025-11-06 06:53:39'),
(300, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s1</strong> has been submitted and is pending your review.', '/bns/reports.php?id=250', 0, '2025-11-06 06:56:12'),
(301, 11, NULL, 10, 'BNS', 'report_approved', 250, 'Your report \"s1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=250', 0, '2025-11-06 06:56:39'),
(302, 6, NULL, 10, 'BNS', 'report_approved', 249, 'Your report \"s1\" has been <b style=\'color:black;\'>Approved</b> by <b>andro a</b>.', 'view_report.php?id=249', 0, '2025-11-06 06:56:44'),
(303, 10, NULL, NULL, 'CNO', 'report_submitted', NULL, 'A new report titled <strong>s2</strong> has been submitted and is pending your review.', '/bns/reports.php?id=251', 0, '2025-11-06 09:11:24');

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
(25, 2, '644275', '2025-09-22 01:50:30', '2025-09-22 03:55:30'),
(26, 2, '959883', '2025-09-22 02:02:31', '2025-09-22 04:07:31'),
(27, 5, '753434', '2025-09-22 07:45:29', '2025-09-22 09:50:29'),
(28, 6, '467913', '2025-09-25 03:08:06', '2025-09-25 05:13:06'),
(29, 2, '195426', '2025-09-25 03:21:38', '2025-09-25 05:26:38'),
(30, 6, '788651', '2025-09-26 15:02:48', '2025-09-26 17:07:48'),
(31, 1, '714144', '2025-10-01 02:48:33', '2025-10-01 04:53:33'),
(32, 7, '600323', '2025-10-03 06:15:56', '2025-10-03 08:20:56'),
(33, 10, '540487', '2025-10-04 06:07:00', '2025-10-04 08:12:00'),
(34, 11, '738154', '2025-10-06 13:30:59', '2025-10-06 15:35:59'),
(35, 11, '705111', '2025-10-08 13:15:25', '2025-10-08 15:20:25'),
(36, 10, '317314', '2025-11-06 02:56:14', '2025-11-06 04:01:14'),
(37, 11, '841845', '2025-11-06 06:55:02', '2025-11-06 08:00:02');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `report_time` time NOT NULL,
  `report_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Archived') DEFAULT 'Pending',
  `prev_status` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_submitted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `report_time`, `report_date`, `status`, `prev_status`, `created_at`, `is_submitted`) VALUES
(249, 6, '07:53:39', '2025-11-06', 'Approved', NULL, '2025-11-06 14:53:39', 1),
(250, 11, '07:56:12', '2025-11-06', 'Approved', NULL, '2025-11-06 14:56:12', 1),
(251, 11, '10:11:24', '2025-11-06', 'Pending', NULL, '2025-11-06 17:11:24', 0);

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
  `password_changed` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `phone_number`, `email`, `address`, `barangay`, `profile_pic`, `user_type`, `password_hash`, `current_session`, `created_at`, `password_changed`, `status`) VALUES
(6, 'a', 'a', 'aa', '0975762768', 'danmarkjavier123@gmail.com', 'st. joseph', 'Ulaliman', '1759383214_97a19c3d1303fb74808d4f343c057863.jpg', 'BNS', '$2y$10$W35Mikc.DpXFSB5.BZQTvOU/90r5N065atESfS1YhfbmVo297G416', 'n0c545f4h3kcqkgs7g81nbfct3', '2025-09-25 03:07:34', 1, 'Active'),
(10, 'andro', 'a', 'andro', '09476445486', 'danmarkpetalcurin@gmail.com', 'st. joseph', 'CNO', '1759568774_e11fa4849c43ba31181f7acff2522f8e.jpg', 'CNO', '$2y$10$3Q3i6zgF2qghNrCBG4r3ve5EQbxBbA3g3BcAyfJdX7EW1FheGctsa', 'qhsr5d0takuq933oueh4gc578k', '2025-10-04 06:06:07', 0, 'Active'),
(11, 'amor', 'mor', 'amor', '978645372', 'amorzesa16@gmail.com', 'Tankulan', 'Taytay', NULL, 'BNS', '$2y$10$QI/ISj2GhXHPch3H7sD7HudqltAPQdMho30mN6x3rMFj7f4jOSfAu', 'n0c545f4h3kcqkgs7g81nbfct3', '2025-10-06 13:19:59', 0, 'Active');

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
-- Indexes for table `barangay_consolidated_reports`
--
ALTER TABLE `barangay_consolidated_reports`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1486;

--
-- AUTO_INCREMENT for table `barangay_consolidated_reports`
--
ALTER TABLE `barangay_consolidated_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `consolidated_reports`
--
ALTER TABLE `consolidated_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=304;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;

--
-- AUTO_INCREMENT for table `report_archives`
--
ALTER TABLE `report_archives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

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
