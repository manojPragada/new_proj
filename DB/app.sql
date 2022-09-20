-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 20, 2022 at 10:29 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_rights`
--

CREATE TABLE `access_rights` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `privileges_code` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_dashboard_main_menu`
--

CREATE TABLE `admin_dashboard_main_menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_icon` varchar(100) DEFAULT NULL,
  `display_order` int(11) NOT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `menu_url` varchar(100) NOT NULL DEFAULT '#',
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_dashboard_main_menu`
--

INSERT INTO `admin_dashboard_main_menu` (`id`, `menu_name`, `menu_icon`, `display_order`, `created_at`, `updated_at`, `menu_url`, `status`) VALUES
(1, 'Admin Control Panel', 'fa fa-th-large', 0, '1623047703', '', '#', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_dashboard_sub_menu`
--

CREATE TABLE `admin_dashboard_sub_menu` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_name` varchar(100) NOT NULL,
  `menu_url` varchar(100) NOT NULL,
  `main_menu_id` int(10) UNSIGNED NOT NULL,
  `display_order` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_dashboard_sub_menu`
--

INSERT INTO `admin_dashboard_sub_menu` (`id`, `menu_name`, `menu_url`, `main_menu_id`, `display_order`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Database Backup', 'dbbackup', 1, 1, '1623050682', '', 1),
(2, 'Site Settings', 'site_settings', 1, 3, '1623050729', '', 1),
(3, 'Manage Admin', 'manage_admin', 1, 0, '1623050771', '1663703198', 1);

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `created_at` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`id`, `name`, `ip_address`, `created_at`) VALUES
(1, 'admin@app.com', '::1', '1663701943'),
(2, 'admin@app.com', '::1', '1663703459'),
(3, 'admin@app.com', '::1', '1663703482');

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `logo` varchar(100) NOT NULL COMMENT '<font color="red">Note:</font> Upload Height: 160px; Width: 184px',
  `favicon` varchar(100) NOT NULL COMMENT '<font color="red">Note:</font> Upload Height: 160px; Width: 184px',
  `contact_number` varchar(100) NOT NULL,
  `contact_email` varchar(60) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` varchar(25) NOT NULL,
  `updated_at` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `site_name`, `logo`, `favicon`, `contact_number`, `contact_email`, `address`, `created_at`, `updated_at`) VALUES
(1, 'Application', '5dcf9dd85d6bc00cfc0cc9c18dc92389.png', '20d9966632a615291ceab6f37222960b.png', '(+91) 998 877 6655', 'support@app.in', '<p>Address&nbsp;Address</p>\r\n\r\n<p>AddressAddressAddress</p>\r\n\r\n<p>Address&nbsp;Address 500001</p>\r\n', '', '1663702380');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(60) NOT NULL COMMENT 'This will be username, email is username',
  `mobile` varchar(20) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(25) DEFAULT NULL,
  `created_at` varchar(30) DEFAULT NULL,
  `updated_at` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `mobile`, `password`, `salt`, `created_at`, `updated_at`, `status`) VALUES
(1, 1, 'Admin', 'admin@app.com', '9900998877', '8eb043b4f136e1bfa0d0f8dee8c47091', 'Dq6fcemamH255', '1479899025', '1663703473', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_rights`
--
ALTER TABLE `access_rights`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `admin_dashboard_main_menu`
--
ALTER TABLE `admin_dashboard_main_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_dashboard_sub_menu`
--
ALTER TABLE `admin_dashboard_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
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
-- AUTO_INCREMENT for table `admin_dashboard_main_menu`
--
ALTER TABLE `admin_dashboard_main_menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_dashboard_sub_menu`
--
ALTER TABLE `admin_dashboard_sub_menu`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
