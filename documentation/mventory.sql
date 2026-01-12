-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 09:04 AM
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
-- Database: `mventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `created_at`) VALUES
(1, 'Peripherals', 'External or internal auxiliary hardware devices that connect to a computer to expand its functions.', '2025-12-05 10:55:12'),
(6, '(POS) Hardware', 'Devices used specifically in retail or transactional environments.', '2025-12-05 13:15:30'),
(7, 'Consumables', 'Items that are used up or replaced periodically.', '2025-12-05 13:15:57'),
(8, 'Cables & Connectors', 'Wires and connectors that connect devices to computers, power, or networks.', '2025-12-05 13:16:25'),
(9, 'Adapters & Converters', 'Devices that allow compatibility between different types of connections.', '2025-12-05 13:16:51'),
(10, 'Storage Devices', 'Hardware used to store digital data, either temporarily or permanently.', '2025-12-05 13:17:32'),
(11, 'Networking Devices', 'Equipment that enables communication and connectivity between computers, devices, and networks.', '2025-12-05 13:17:47'),
(12, 'Audio/Visual Devices', 'Devices used to capture, output, or enhance sound and visual media.', '2025-12-05 13:18:04');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'Administrator', 'Full access to all settings and inventory data.'),
(2, 'Inventory Manager', 'Can add, edit, and remove stock and products.'),
(3, 'Stock Handler', 'Can only update stock levels (receive/ship).'),
(4, 'Viewer', 'Read-only access to inventory data.');

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_login` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_logs`
--

INSERT INTO `security_logs` (`log_id`, `user_id`, `browser`, `ip_address`, `login_time`, `status`, `is_active`, `is_login`) VALUES
(1, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 11:28:56', 'Success', 0, 0),
(2, 4, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 11:38:17', 'Success', 0, 0),
(3, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 11:50:15', 'Success', 0, 0),
(4, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:15:32', 'Success', 0, 0),
(5, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:20:34', 'Success', 0, 0),
(6, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:20:43', 'Logout', 0, 0),
(7, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:20:50', 'Success', 0, 0),
(8, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:21:55', 'Logout', 0, 0),
(9, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:22:03', 'Success', 0, 1),
(10, 4, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-09 13:30:41', 'Success', 0, 1),
(11, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-10 08:38:41', 'Success', 0, 1),
(12, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-10 08:39:28', 'Logout', 0, 0),
(13, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-10 08:39:35', 'Success', 0, 1),
(14, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-10 09:19:00', 'Logout', 0, 0),
(15, 9, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '::1', '2026-01-10 09:19:06', 'Success', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_audit_logs`
--

CREATE TABLE `system_audit_logs` (
  `audit_id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` varchar(50) NOT NULL,
  `action_type` enum('INSERT','UPDATE','DELETE','RESTORED') NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` int(11) DEFAULT NULL,
  `timestamp` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `setting_type` enum('string','number','boolean','select') NOT NULL,
  `category` enum('general','inventory','notifications','security') NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `setting_type`, `category`, `description`) VALUES
('app_logo', 'varay_logo.png', 'string', 'general', 'The Logo image of the application'),
('app_name', 'M Ventory', 'string', 'general', 'The display name of the application.'),
('auto_assign_sku', '0', 'boolean', 'inventory', 'Automatically generate SKU for new products (0=No, 1=Yes).'),
('dark_mode_default', '0', 'boolean', 'general', 'Enable dark mode by default (0=No, 1=Yes).'),
('date_format', 'DD/MM/YYYY', 'select', 'general', 'Default date format for reports.'),
('default_unit_of_measure', 'Pieces', 'select', 'inventory', 'Default unit for new products.'),
('eol_duration_years', '3', '', 'general', NULL),
('last_backup_datetime', '2026-01-07 08:52:50', 'string', 'general', 'Stores the last Excel/SQL backup date and time'),
('liquidation_percentage', '60', '', 'general', NULL),
('log_all_actions', '1', 'boolean', 'security', 'Enable detailed user audit logging.'),
('low_stock_threshold_percent', '10', 'number', 'inventory', 'Global percentage for low stock warning.'),
('password_min_length', '10', 'number', 'security', 'Minimum length for user passwords.'),
('trigger_low_stock', '0', 'boolean', 'notifications', 'Enable low stock notifications.'),
('trigger_new_user', '0', 'boolean', 'notifications', 'Notify admins on new user creation.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` varchar(15) NOT NULL,
  `username` varchar(9999) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `address` varchar(500) DEFAULT NULL,
  `role` varchar(30) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(999) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_login` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `username`, `first_name`, `last_name`, `phone`, `address`, `role`, `status`, `email`, `password`, `profile_image`, `is_active`, `is_login`, `last_login`, `created_at`, `deleted_at`) VALUES
(4, 'DMN122025002', 'admin', 'ADMIN', 'ACCOUNT', '09123456789', '', 'Administrator', 'active', 'admin@gmail.com', '$2y$10$qKfllJ6.qy8RfwQYy8ZEOe0OmhmiaLMl05/zVzm4/rjrnQWVo3KHi', '../src/image/profiles/user_4_1767929939.png', 0, 1, '2026-01-09 13:30:41', '2025-12-04 23:55:37', NULL),
(8, 'DMN122025008', 'abancia', 'Danilo', 'Abancia Jr.', '', '', 'Administrator', 'active', 'akosidhandhan07@gmail.com', '$2y$10$X7.2G7BGacEnUELePJur8O1YjKFy9M2O7Z1.ibUSXrkdMElRLh3G.', '', 1, 0, NULL, '2025-12-16 00:20:59', NULL),
(9, 'DMN122025009', 'deguzman', 'Ron Justin', 'De Guzman', '09091231234', 'Tazna, Cavite', 'Administrator', 'active', 'ronjustin22@gmail.com', '$2y$10$40XUu4uDhR6sA.CntW1HJOHgOeZl9ZiziOL0NhL8n9BATTubqjePC', '../src/image/profiles/user_9_1767928291.jpg', 1, 1, '2026-01-10 09:19:06', '2025-12-16 00:21:59', NULL),
(10, 'DMN122025010', 'trinidad', 'Herminio', 'Trinidad', '', '', 'Administrator', 'active', 'itdept@michaela.com.ph', '$2y$10$HLgRGedcF/rHW46s7dW0t.MNH7K3aWO3jzDYNqMuFBkdt2Hv.Pt7W', '', 1, 0, NULL, '2025-12-16 00:23:46', NULL),
(12, 'DMN122025011', 'superadmin', 'Super Admin', 'Account', '09090912345', '', 'Super Administrator', 'active', 'super.admin@gmail.com', '$2y$10$SjCJmJhVXaSnhBGsT9mvTO6YZrTLvzL6kKZqvaPVb8ARurpiML.Fe', '', 1, 0, '2026-01-03 13:52:43', '2025-12-26 00:01:02', NULL),
(17, 'DMN012026013', 'justine317', 'Justine', 'Feranil', '', '', 'Administrator', 'active', 'justine@gmail.com', '$2y$10$bJTgh4irigBiFe6JgWUsW.fmIp0o5eA6enNiu5UeFu0ZjDZVfWROW', '', 1, 0, NULL, '2026-01-03 00:19:26', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `system_audit_logs`
--
ALTER TABLE `system_audit_logs`
  ADD PRIMARY KEY (`audit_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `system_audit_logs`
--
ALTER TABLE `system_audit_logs`
  MODIFY `audit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD CONSTRAINT `security_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
