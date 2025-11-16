-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 10:04 AM
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
-- Database: `inventory-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `approval_member`
--

CREATE TABLE `approval_member` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_member`
--

INSERT INTO `approval_member` (`id`, `user_id`, `username`, `level`) VALUES
(1, 2, 'User Approval', 1);

-- --------------------------------------------------------

--
-- Table structure for table `approval_request`
--

CREATE TABLE `approval_request` (
  `id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `approver_id` int(11) NOT NULL,
  `approver_name` varchar(200) NOT NULL,
  `status` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `approval_request`
--

INSERT INTO `approval_request` (`id`, `pr_id`, `approver_id`, `approver_name`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'User Approval', 2, 'Remarks Approval', '2025-11-07 17:45:44', '2025-11-16 15:28:21');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_detail`
--

CREATE TABLE `delivery_detail` (
  `id` bigint(20) NOT NULL,
  `do_id` varchar(50) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` varchar(50) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_order`
--

CREATE TABLE `delivery_order` (
  `id` bigint(20) NOT NULL,
  `do_code` varchar(50) NOT NULL,
  `do_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_bank`
--

CREATE TABLE `m_bank` (
  `id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` int(11) NOT NULL,
  `account_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_item`
--

CREATE TABLE `m_item` (
  `Id` int(11) NOT NULL,
  `item_name` varchar(250) NOT NULL,
  `type` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `sales_price` decimal(10,0) NOT NULL,
  `buy_price` decimal(10,0) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_item`
--

INSERT INTO `m_item` (`Id`, `item_name`, `type`, `category`, `qty`, `sales_price`, `buy_price`) VALUES
(1, 'Karbrurator', 'Matic', 'Sperpart', 100, '500000', NULL),
(2, 'Spion', 'All', 'Aksesoris', 500, '100000', NULL),
(3, 'Roller', 'Matic', 'Sperpart', 200, '300000', NULL),
(4, 'Oli', 'All', 'Sperpart', 50, '50000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `m_status`
--

CREATE TABLE `m_status` (
  `Id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(250) NOT NULL,
  `desc` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_status`
--

INSERT INTO `m_status` (`Id`, `value`, `code`, `name`, `desc`) VALUES
(1, 1, 'general', 'In active', 'In active'),
(2, 2, 'general', 'Active', 'Active'),
(3, 3, 'general', 'Delete', 'delete'),
(4, 1, 'transaction', 'Pending', 'Pending'),
(5, 2, 'transaction', 'Approve', 'Approve'),
(6, 3, 'transaction', 'Reject', 'Reject');

-- --------------------------------------------------------

--
-- Table structure for table `m_supplier`
--

CREATE TABLE `m_supplier` (
  `Id` int(11) NOT NULL,
  `supplier_code` varchar(100) NOT NULL,
  `supplier_name` varchar(250) NOT NULL,
  `supplier_address` varchar(1000) NOT NULL,
  `supplier_contact` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_supplier`
--

INSERT INTO `m_supplier` (`Id`, `supplier_code`, `supplier_name`, `supplier_address`, `supplier_contact`, `status`) VALUES
(1, 'SPL_00001', 'PT. Yamaha Indonesia', 'JL. Utama No 23 Blok C9', '0893735272', 2),
(2, 'SPL_00002', 'Honda Indonesia', 'JL.Petojo No 2 Blok KL9', '8487398903', 2);

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`id`, `username`, `password`, `email`, `role`) VALUES
(1, 'developer', '202cb962ac59075b964b07152d234b70', 'dev@mail.com', 'super_admin'),
(2, 'User Approval', '202cb962ac59075b964b07152d234b70', 'approval@mail.com', 'approval');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request`
--

CREATE TABLE `purchase_request` (
  `id` int(11) NOT NULL,
  `pr_code` varchar(50) NOT NULL,
  `request_date` date NOT NULL,
  `requester_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `store_address` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `supplier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_request`
--

INSERT INTO `purchase_request` (`id`, `pr_code`, `request_date`, `requester_id`, `status`, `store_address`, `created_at`, `updated_at`, `supplier_id`) VALUES
(1, 'PR-20251107174544', '2025-11-21', 1, 2, 'addres toko', '2025-11-07 17:45:44', '2025-11-16 15:28:21', 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_detail`
--

CREATE TABLE `purchase_request_detail` (
  `id` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `line_no` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `notes` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_request_detail`
--

INSERT INTO `purchase_request_detail` (`id`, `pr_id`, `line_no`, `item_id`, `qty`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 1, 'oli all matic', '2025-11-07 17:45:44', '2025-11-07 17:45:44'),
(2, 1, 2, 1, 2, 'karbu', '2025-11-07 17:45:44', '2025-11-07 17:45:44'),
(3, 1, 3, 2, 3, 'spion', '2025-11-07 17:45:44', '2025-11-07 17:45:44'),
(4, 1, 4, 3, 4, 'roller', '2025-11-07 17:45:44', '2025-11-07 17:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id` bigint(20) NOT NULL,
  `pr_id` varchar(50) NOT NULL,
  `date_in` date NOT NULL,
  `status` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `supplier_id` bigint(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_detail`
--

CREATE TABLE `warehouse_detail` (
  `id` bigint(20) NOT NULL,
  `prd_id` bigint(20) NOT NULL,
  `line_no` int(11) NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approval_member`
--
ALTER TABLE `approval_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_apm_user_id` (`user_id`);

--
-- Indexes for table `approval_request`
--
ALTER TABLE `approval_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_detail`
--
ALTER TABLE `delivery_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_order`
--
ALTER TABLE `delivery_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_bank`
--
ALTER TABLE `m_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_item`
--
ALTER TABLE `m_item`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `m_status`
--
ALTER TABLE `m_status`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `m_supplier`
--
ALTER TABLE `m_supplier`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_request`
--
ALTER TABLE `purchase_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_request_detail`
--
ALTER TABLE `purchase_request_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_detail`
--
ALTER TABLE `warehouse_detail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approval_member`
--
ALTER TABLE `approval_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `approval_request`
--
ALTER TABLE `approval_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_detail`
--
ALTER TABLE `delivery_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_order`
--
ALTER TABLE `delivery_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_bank`
--
ALTER TABLE `m_bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_item`
--
ALTER TABLE `m_item`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `m_status`
--
ALTER TABLE `m_status`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `m_supplier`
--
ALTER TABLE `m_supplier`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `m_user`
--
ALTER TABLE `m_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_request`
--
ALTER TABLE `purchase_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchase_request_detail`
--
ALTER TABLE `purchase_request_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouse_detail`
--
ALTER TABLE `warehouse_detail`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
