-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2023 at 06:51 PM
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
-- Database: `pharma_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `name`) VALUES
(1, 'Vitamins'),
(2, 'Antipyretics'),
(3, 'Analgesics'),
(4, 'Antibiotics'),
(5, 'Antiseptics'),
(6, 'CNS'),
(11, 'Antacids'),
(13, 'Laxatives'),
(14, 'Antispasmodics'),
(15, 'Beta-blockers'),
(16, 'Diuretics'),
(17, 'Antiarrhythmics'),
(18, 'Anticoagulants'),
(19, 'Haemostatic'),
(20, 'Psychedelics'),
(21, 'Hypnotics'),
(22, 'Anaesthetics'),
(23, 'Antipsychotics'),
(24, 'Antidepressants'),
(25, 'Antiepileptics'),
(26, 'Barbiturates'),
(27, 'Antiviral'),
(28, 'Anti-fungal'),
(29, 'Anti-inflammatory'),
(30, 'Anti-allergy'),
(31, 'Medical Equipment'),
(34, 'Paracetamol'),
(44, 'Sample cat');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(30) NOT NULL,
  `product_id` int(30) NOT NULL,
  `batch_no` varchar(250) NOT NULL,
  `qty` int(30) NOT NULL,
  `expiry_date` date NOT NULL,
  `expired_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `batch_no`, `qty`, `expiry_date`, `expired_confirmed`, `date_updated`) VALUES
(13, 2, 'hahaha', 2, '2022-12-08', 0, '2023-01-15 02:22:10'),
(14, 3, 'hahaha', 1, '2023-01-05', 0, '2023-01-15 02:22:10'),
(15, 2, '124568', 0, '2023-02-12', 0, '2023-01-18 21:57:00'),
(16, 3, '124568', 5, '2023-02-02', 0, '2023-01-15 12:20:05'),
(17, 4, '08743564', 23, '2023-01-19', 0, '2023-01-18 22:15:11'),
(18, 4, '123456', 5, '2023-01-20', 0, '2023-01-15 13:49:32'),
(19, 2, '36474', 4, '2023-02-03', 0, '2023-01-21 10:18:06'),
(20, 4, 'BN-87218', 2, '2023-01-20', 0, '2023-01-15 15:23:23'),
(21, 2, 'BIEF0832', 2, '2023-01-26', 0, '2023-01-20 23:39:35'),
(22, 3, 'JBFUR83B', 4, '2023-02-02', 0, '2023-01-21 10:18:06'),
(23, 2, 'J12220233', 100, '2023-02-06', 0, '2023-02-04 20:41:41'),
(24, 3, 'J12220233', 0, '2023-01-30', 0, '2023-01-24 21:51:54'),
(25, 4, 'J1242023', 190, '2023-02-08', 0, '2023-02-04 20:42:01'),
(26, 3, 'J1242023', 100, '2023-02-08', 0, '2023-01-24 21:51:54'),
(28, 3, 'F21223', 500, '2023-02-27', 0, '2023-02-12 21:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `type`, `message`, `user_id`, `date_updated`) VALUES
(1, 'Create', 'Product Added: Neozep ', 7, '2023-01-19 00:00:00'),
(2, 'Auth:Login', 'User login', 7, NULL),
(6, 'Auth:Login', 'User login', 7, '2023-01-20 05:33:53'),
(7, 'Auth:Logout', 'User logout', 7, '2023-01-20 05:34:26'),
(8, 'Auth:Login', 'User login', 7, '2023-01-20 05:34:31'),
(9, 'Create', 'New category added: Test', 7, '2023-01-20 05:44:41'),
(10, 'Created', 'Order Created - Ref #: 22774675\n', 7, '2023-01-20 05:45:56'),
(11, 'Auth:Logout', 'User logout', 7, '2023-01-20 05:52:43'),
(14, 'Auth:Login', 'User login', 7, '2023-01-20 05:53:16'),
(15, 'Create', 'Received Order Batch: JBFUR83B', 7, '2023-01-20 05:53:37'),
(16, 'Create', 'New product added: Test', 7, '2023-01-21 03:15:41'),
(17, 'Create', 'New Sales Ref #: 85934962\n', 7, '2023-01-21 03:18:06'),
(18, 'Update', 'Product has been updated: Test', 7, '2023-01-21 04:10:25'),
(19, 'Update', 'Product has been updated: Test', 7, '2023-01-21 04:32:15'),
(20, 'Create', 'Order Created - Ref #: 18483654\n', 7, '2023-01-21 04:41:51'),
(21, 'Update', 'Updated Order - Ref #: 18483654\r\n', 7, '2023-01-21 04:46:45'),
(22, 'Auth:Logout', 'User logout', 7, '2023-01-21 04:49:22'),
(25, 'Auth:Login', 'User login', 7, '2023-01-21 04:55:30'),
(26, 'Update', 'Product has been updated: Mefenamic Acid', 7, '2023-01-21 08:52:51'),
(27, 'Auth:Login', 'User login', 7, '2023-01-21 05:19:13'),
(28, 'Create', 'User has been added: Michael', 7, '2023-01-21 05:55:43'),
(29, 'Auth:Logout', 'User logout', 7, '2023-01-21 06:03:06'),
(35, 'Auth:Login', 'User login', 7, '2023-01-21 07:18:01'),
(36, 'Auth:Logout', 'User logout', 7, '2023-01-21 07:18:20'),
(43, 'Auth:Login', 'User login', 7, '2023-01-22 03:32:00'),
(44, 'Create', 'User has been added: Mark Teddy Dela Cruz Quiban', 7, '2023-01-22 03:34:26'),
(45, 'Delete', 'User deleted', 7, '2023-01-22 03:34:45'),
(46, 'Create', 'User has been added: Mark Teddy Dela Cruz Quiban', 7, '2023-01-22 03:42:41'),
(47, 'Auth:Logout', 'User logout', 7, '2023-01-22 03:43:38'),
(57, 'Auth:Login', 'User login', 7, '2023-01-22 03:58:12'),
(58, 'Create', 'User has been added: EUlo', 7, '2023-01-22 03:58:24'),
(59, 'Auth:Logout', 'User logout', 7, '2023-01-22 04:11:03'),
(60, 'Auth:Login', 'User login', 7, '2023-01-22 04:14:11'),
(61, 'Auth:Logout', 'User logout', 7, '2023-01-22 04:17:13'),
(62, 'Verification', 'User verified', 7, '2023-01-22 04:17:42'),
(63, 'Auth:Login', 'User login', 7, '2023-01-22 04:18:04'),
(64, 'Auth:Logout', 'User logout', 7, '2023-01-22 04:22:47'),
(65, 'Password Reset', 'Rest successful', 7, '2023-01-22 04:23:53'),
(66, 'Auth:Login', 'User login', 7, '2023-01-22 04:24:11'),
(67, 'Update', 'User has been updated: Joshua Marc Alcause', 7, '2023-01-22 04:24:17'),
(68, 'Auth:Logout', 'User logout', 7, '2023-01-22 04:37:53'),
(69, 'Auth:Login', 'User login', 7, '2023-01-22 05:59:34'),
(70, 'Password Reset', 'Rest successful', 7, '2023-01-22 07:31:09'),
(71, 'Auth:Login', 'User login', 7, '2023-01-22 07:32:31'),
(72, 'Update', 'User has been updated: Joshua Marc Alcause', 7, '2023-01-22 07:32:57'),
(73, 'Auth:Logout', 'User logout', 7, '2023-01-22 07:33:12'),
(74, 'Auth:Login', 'User login', 7, '2023-01-22 07:33:26'),
(75, 'Update', 'Product has been updated: Paracetamol', 7, '2023-01-22 07:34:17'),
(76, 'Update', 'Product has been updated: Mefenamic Acid', 7, '2023-01-22 07:34:34'),
(77, 'Create', 'Order Created - Ref #: 32676437\n', 7, '2023-01-22 07:35:13'),
(78, 'Auth:Logout', 'User logout', 7, '2023-01-22 02:47:28'),
(79, 'Auth:Login', 'User login', 7, '2023-01-22 02:49:49'),
(80, 'Delete', 'User deleted', 7, '2023-01-22 02:50:04'),
(81, 'Auth:Login', 'User login', 7, '2023-01-22 02:50:30'),
(82, 'Create', 'User has been added: Marc Dave', 7, '2023-01-22 02:51:42'),
(83, 'Auth:Logout', 'User logout', 7, '2023-01-22 02:52:01'),
(88, 'Delete', 'User deleted', 7, '2023-01-22 02:54:08'),
(91, 'Create', 'Order Created - Ref #: 47126158\n', 7, '2023-01-22 02:56:10'),
(92, 'Delete', 'Order deleted', 7, '2023-01-22 03:02:44'),
(93, 'Delete', 'Category deleted', 7, '2023-01-22 03:04:06'),
(94, 'Create', 'New category added: Test', 7, '2023-01-22 03:05:04'),
(95, 'Create', 'New type added: Test', 7, '2023-01-22 03:06:18'),
(96, 'Create', 'New product added: TIKI TIKI', 7, '2023-01-22 03:07:13'),
(97, 'Delete', 'Category deleted', 7, '2023-01-22 03:07:28'),
(98, 'Delete', 'Type deleted', 7, '2023-01-22 03:07:36'),
(99, 'Create', 'Received Order Batch: J12220233', 7, '2023-01-22 03:09:52'),
(100, 'Update', 'Product has been updated: Paracetamol', 7, '2023-01-22 03:12:58'),
(101, 'Create', 'User has been added: Marc Dave', 7, '2023-01-22 03:15:07'),
(106, 'Create', 'Supplier added: DIGICARE Medical Products Inc.', 7, '2023-01-22 03:41:15'),
(107, 'Update', 'Supplier has been updated: DIGICARE Medical Products Inc.', 7, '2023-01-22 03:41:29'),
(108, 'Update', 'Supplier has been updated: DIGICARE Medical Products Inc.', 7, '2023-01-22 03:41:37'),
(109, 'Create', 'Order Created - Ref #: 49783067\n', 7, '2023-01-22 03:44:17'),
(110, 'Auth:Login', 'User login', 7, '2023-01-24 08:01:41'),
(111, 'Auth:Logout', 'User logout', 7, '2023-01-24 08:04:33'),
(112, 'Password Reset', 'Rest successful', 7, '2023-01-24 08:05:50'),
(113, 'Auth:Login', 'User login', 7, '2023-01-24 08:06:22'),
(114, 'Update', 'User has been updated: Joshua Marc Alcause', 7, '2023-01-24 08:06:32'),
(115, 'Auth:Logout', 'User logout', 7, '2023-01-24 08:07:24'),
(116, 'Auth:Login', 'User login', 7, '2023-01-24 09:18:10'),
(117, 'Create', 'New category added: Sample cat', 7, '2023-01-24 09:21:10'),
(118, 'Update', 'Updated Order - Ref #: 32676437\r\n', 7, '2023-01-24 09:39:09'),
(119, 'Create', 'Received Order Batch: J1242023', 7, '2023-01-24 09:40:20'),
(120, 'Update', 'Updated Order - Ref #: 49783067\r\n', 7, '2023-01-24 09:41:15'),
(121, 'Create', 'Received Order Batch: J1242023', 7, '2023-01-24 09:41:52'),
(122, 'Delete', 'User deleted', 7, '2023-01-24 09:45:21'),
(123, 'Create', 'New Sales Ref #: 59500795\n', 7, '2023-01-24 09:51:54'),
(124, 'Create', 'New Sales Ref #: 72333691\n', 7, '2023-01-24 09:59:58'),
(125, 'Create', 'New product added: Canon', 7, '2023-01-24 10:25:45'),
(126, 'Update', 'Product has been updated: Canon', 7, '2023-01-24 10:26:58'),
(127, 'Delete', 'Product deleted', 7, '2023-01-24 10:27:36'),
(128, 'Auth:Logout', 'User logout', 7, '2023-01-24 10:38:07'),
(129, 'Auth:Login', 'User login', 7, '2023-01-28 05:01:16'),
(130, 'Auth:Login', 'User login', 7, '2023-01-29 12:59:11'),
(131, 'Auth:Login', 'User login', 7, '2023-02-01 08:55:40'),
(132, 'Auth:Login', 'User login', 7, '2023-02-02 09:17:55'),
(133, 'Auth:Login', 'User login', 7, '2023-02-04 08:31:22'),
(134, 'Create', 'New Sales Ref #: 80055248\n', 7, '2023-02-04 08:41:41'),
(135, 'Create', 'New Sales Ref #: 94669500\n', 7, '2023-02-04 08:42:01'),
(136, 'Create', 'Order Created - Ref #: 30555097\n', 7, '2023-02-04 08:42:33'),
(137, 'Create', 'Received Order Batch: F20423', 7, '2023-02-04 08:43:04'),
(138, 'Auth:Login', 'User login', 7, '2023-02-05 01:20:04'),
(139, 'Auth:Login', 'User login', 7, '2023-02-07 09:20:00'),
(140, 'Update', 'Supplier has been updated: DIGICARE Medical Products Inc.', 7, '2023-02-07 09:47:59'),
(141, 'Delete', 'Product deleted', 7, '2023-02-07 09:48:24'),
(142, 'Create', 'Order Created - Ref #: 10612218\n', 7, '2023-02-07 09:49:31'),
(143, 'Auth:Login', 'User login', 7, '2023-02-11 01:05:08'),
(144, 'Auth:Login', 'User login', 7, '2023-02-11 10:21:19'),
(145, 'Auth:Login', 'User login', 7, '2023-02-11 09:10:28'),
(146, 'Auth:Login', 'User login', 7, '2023-02-12 10:14:36'),
(147, 'Auth:Login', 'User login', 7, '2023-02-12 05:32:29'),
(148, 'Update', 'User has been updated: NOEME C. GAVINA, Rph', 7, '2023-02-12 06:48:59'),
(149, 'Update', 'User has been updated: NOEME C. GAVINA, Rph', 7, '2023-02-12 06:49:12'),
(150, 'Auth:Logout', 'User logout', 7, '2023-02-12 06:49:19'),
(151, 'Auth:Login', 'User login', 7, '2023-02-12 06:49:31'),
(152, 'Create', 'User has been added: Joshua Marc Alcause', 7, '2023-02-12 07:31:27'),
(153, 'Auth:Logout', 'User logout', 7, '2023-02-12 07:31:39'),
(154, 'Verification', 'User verified', 16, '2023-02-12 07:32:08'),
(155, 'Auth:Login', 'User login', 16, '2023-02-12 07:32:15'),
(156, 'Auth:Logout', 'User logout', 16, '2023-02-12 07:36:33'),
(157, 'Auth:Login', 'User login', 7, '2023-02-12 07:36:42'),
(158, 'Create', 'Received Order Batch: F21223', 7, '2023-02-12 09:08:01'),
(159, 'Auth:Logout', 'User logout', 7, '2023-02-13 01:51:21');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(30) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `batch_no` varchar(250) DEFAULT NULL,
  `supplier_id` int(30) NOT NULL,
  `total_amount` double NOT NULL,
  `status` int(11) NOT NULL,
  `date_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `ref_no`, `batch_no`, `supplier_id`, `total_amount`, `status`, `date_added`) VALUES
(16, '85692660\n', '124568', 5, 715, 2, '2023-01-15 02:25:51'),
(20, '60032795\n', '123456', 6, 0, 2, '2023-01-15 13:47:32'),
(21, '68740750\n', '36474', 6, 100, 2, '2023-01-15 13:56:03'),
(36, '89163014\r\n', 'BN-87218', 5, 1100, 2, '2023-01-15 15:09:07'),
(39, '48685720\r\n', 'BIEF0832', 6, 20, 2, '2023-01-18 22:25:01'),
(40, '22774675\n', 'JBFUR83B', 6, 738, 2, '2023-01-21 00:45:56'),
(44, '47126158\n', 'J12220233', 5, 66500, 2, '2023-01-22 14:56:10'),
(46, '32676437\r\n', 'J1242023', 6, 12500, 2, '2023-01-24 21:39:09'),
(47, '49783067\r\n', 'J1242023', 8, 61500, 2, '2023-01-24 21:41:15'),
(48, '30555097\n', 'F20423', 6, 23100, 2, '2023-02-04 20:42:33'),
(49, '10612218\n', 'F21223', 6, 61500, 2, '2023-02-07 21:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(30) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(30) NOT NULL,
  `qty` int(30) NOT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `qty`, `expiry_date`) VALUES
(13, 16, 3, 5, '2023-01-13'),
(14, 16, 2, 10, '2023-01-26'),
(18, 20, 4, 1, '2023-01-20'),
(19, 21, 2, 10, '2023-02-03'),
(39, 36, 4, 2, '2023-01-20'),
(42, 39, 2, 2, '2023-01-26'),
(43, 40, 3, 6, '2023-02-02'),
(49, 44, 2, 500, '2023-02-06'),
(50, 44, 3, 500, '2023-01-30'),
(52, 46, 4, 500, '2023-02-08'),
(53, 47, 3, 500, '2023-02-08'),
(55, 49, 3, 500, '2023-02-27');

-- --------------------------------------------------------

--
-- Table structure for table `product_list`
--

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `type_id` int(30) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `name` varchar(150) NOT NULL,
  `measurement` text NOT NULL,
  `description` text NOT NULL,
  `prescription` tinyint(1) NOT NULL DEFAULT 0,
  `selling_mode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_list`
--

INSERT INTO `product_list` (`id`, `category_id`, `type_id`, `sku`, `price`, `name`, `measurement`, `description`, `prescription`, `selling_mode`) VALUES
(2, 22, 2, '76102693', 10, 'Paracetamol', '1000 mg', 'test', 1, 'piece'),
(3, 3, 10, '76095218', 123, 'Neozep', '500 mg', '12345678', 0, 'piece'),
(4, 11, 10, '82109281', 25, 'Mefenamic Acid', '500 mg', '123456', 1, 'piece');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(30) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Cashier',
  `ref_no` varchar(30) NOT NULL,
  `total_amount` double NOT NULL,
  `amount_tendered` double NOT NULL,
  `amount_change` double NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `ref_no`, `total_amount`, `amount_tendered`, `amount_change`, `date_updated`) VALUES
(8, 7, '58883645\n', 80, 100, 20, '2023-01-15 10:29:29'),
(9, 7, '62474046\n', 20, 20, 0, '2023-01-18 21:57:00'),
(10, 7, '58979967\n', 1100, 1500, 400, '2023-01-18 22:15:11'),
(11, 7, '39481460\n', 10, 0, 0, '2023-01-20 20:10:09'),
(13, 7, '85934962\n', 266, 266, 0, '2023-01-21 10:18:06'),
(15, 7, '59500795\n', 110700, 200000, 89300, '2023-01-24 21:51:54'),
(16, 7, '72333691\n', 250, 250, 0, '2023-01-24 21:59:58'),
(17, 7, '80055248\n', 2000, 2000, 0, '2023-02-04 20:41:41'),
(18, 16, '94669500\n', 7500, 7500, 0, '2023-02-12 19:33:48');

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `sales_id` int(11) NOT NULL,
  `ref_no` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_items`
--

INSERT INTO `sales_items` (`id`, `sales_id`, `ref_no`, `inventory_id`, `qty`, `price`, `amount`) VALUES
(6, 8, 58883645, 15, 8, 10, 80),
(7, 9, 62474046, 15, 2, 10, 20),
(8, 10, 58979967, 17, 2, 550, 1100),
(9, 11, 39481460, 19, 1, 10, 10),
(11, 13, 85934962, 22, 2, 123, 246),
(12, 13, 85934962, 19, 2, 10, 20),
(14, 15, 59500795, 24, 500, 123, 61500),
(15, 15, 59500795, 26, 400, 123, 49200),
(16, 16, 72333691, 25, 10, 25, 250),
(17, 17, 80055248, 23, 200, 10, 2000),
(18, 18, 94669500, 25, 300, 25, 7500);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_list`
--

CREATE TABLE `supplier_list` (
  `id` int(30) NOT NULL,
  `supplier_name` text NOT NULL,
  `contact` varchar(30) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_list`
--

INSERT INTO `supplier_list` (`id`, `supplier_name`, `contact`, `address`, `email`) VALUES
(5, 'Sandoval Distributors, INC', '(02) 8687 4056', 'Oranbo Shaw Boulevard\r\n1600 Pasig City\r\nMetro Manila - Philippines', 'sandova@gmail.com'),
(6, 'GB Distributors, INC', '(02) 8772 5501', 'Bldg 5A. Sunblest Compound, Km23 West Service Rd.,, Cupang, Muntinlupa, 1771 Kalakhang Maynila', 'gb@gmail.com'),
(8, 'DIGICARE Medical Products Inc.', '(02) 87165935', 'Sta. Mesa, Manila, Philippines', 'info@digicarephils.com');

-- --------------------------------------------------------

--
-- Table structure for table `type_list`
--

CREATE TABLE `type_list` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_list`
--

INSERT INTO `type_list` (`id`, `name`) VALUES
(1, 'Liquid'),
(2, 'Capsule'),
(3, 'Drops'),
(4, 'Inhalers'),
(5, 'Tablet'),
(6, 'Sample Type'),
(7, 'Syringe'),
(8, 'Masks'),
(9, 'Sample 2'),
(10, 'Diapers'),
(11, 'Drugs');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` varchar(200) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(155) NOT NULL,
  `code` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '1=admin , 2 = cashier'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `email`, `code`, `status`, `type`) VALUES
(7, 'NOEME C. GAVINA, Rph', 'ICT-ADMIN-01', 'admin123', 'psumedict@gmail.com', 2430760, 1, 1),
(16, 'Joshua Marc Alcause', 'ICT-CSH-01', 'ICT-CSH-01', 'jmalcause00@gmail.com', 3213774, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_ibfk_1` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_list`
--
ALTER TABLE `product_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_id` (`sales_id`),
  ADD KEY `sales_items_ibfk_1` (`inventory_id`);

--
-- Indexes for table `supplier_list`
--
ALTER TABLE `supplier_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type_list`
--
ALTER TABLE `type_list`
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
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `product_list`
--
ALTER TABLE `product_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `supplier_list`
--
ALTER TABLE `supplier_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `type_list`
--
ALTER TABLE `type_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_list`
--
ALTER TABLE `product_list`
  ADD CONSTRAINT `product_list_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_list_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `type_list` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD CONSTRAINT `sales_items_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_items_ibfk_2` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
