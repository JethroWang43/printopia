-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 05, 2026 at 06:39 AM
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
-- Database: `printopia_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `3D_design_tbl`
--

CREATE TABLE `3D_design_tbl` (
  `3D_design_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color` varchar(20) NOT NULL,
  `image` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `canvas_elements_tbl`
--

CREATE TABLE `canvas_elements_tbl` (
  `canvas_element_id` int(11) NOT NULL,
  `element_type_id` int(11) NOT NULL,
  `position_x` int(11) NOT NULL,
  `position_y` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `rotation` int(11) NOT NULL,
  `color` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_tbl`
--

CREATE TABLE `discount_tbl` (
  `discount_id` int(11) NOT NULL,
  `eligibility_id` int(11) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `code` varchar(20) NOT NULL,
  `selection` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `element_type_tbl`
--

CREATE TABLE `element_type_tbl` (
  `element_type_id` int(11) NOT NULL,
  `element_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `eligibility_status_tbl`
--

CREATE TABLE `eligibility_status_tbl` (
  `eligibility_status_id` int(11) NOT NULL,
  `eligibility_status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_role_tbl`
--

CREATE TABLE `employee_role_tbl` (
  `employee_role_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `employee_role_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_role_type_tbl`
--

CREATE TABLE `employee_role_type_tbl` (
  `employee_role_type_id` int(11) NOT NULL,
  `employee_role_type_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_type`
--

CREATE TABLE `image_type` (
  `canvas_element_id` int(11) NOT NULL,
  `image_path` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_tbl`
--

CREATE TABLE `inventory_tbl` (
  `inventory_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `stock_qty` int(100) NOT NULL,
  `reorder_level` int(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_notification_tbl`
--

CREATE TABLE `order_notification_tbl` (
  `order_notification` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `trigger_email` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_status_tbl`
--

CREATE TABLE `order_status_tbl` (
  `order_status_id` int(11) NOT NULL,
  `order_status_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_tbl`
--

CREATE TABLE `order_tbl` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `3d_design_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `order_name` varchar(20) NOT NULL,
  `order_type` varchar(20) NOT NULL,
  `material_type` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `delivery_method` varchar(20) NOT NULL,
  `address` varchar(100) NOT NULL,
  `image_file` varchar(100) NOT NULL,
  `internal_notes` varchar(100) NOT NULL,
  `installments` varchar(20) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_price` decimal(10,2) NOT NULL,
  `quantity` int(10) NOT NULL,
  `order_status_id` int(11) NOT NULL,
  `payment_status_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_tbl`
--

CREATE TABLE `payment_method_tbl` (
  `payment_method_id` int(11) NOT NULL,
  `payment_provider_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_provider_tbl`
--

CREATE TABLE `payment_provider_tbl` (
  `payment_provider_id` int(11) NOT NULL,
  `payment_provider_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_tbl`
--

CREATE TABLE `payment_tbl` (
  `payment_status_id` int(11) NOT NULL,
  `payment_status_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_transaction_tbl`
--

CREATE TABLE `payment_transaction_tbl` (
  `transaction_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type_id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `proof_of_payment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_step_tbl`
--

CREATE TABLE `product_step_tbl` (
  `product_step_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `step_name` varchar(20) NOT NULL,
  `assigned_to` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tbl`
--

CREATE TABLE `product_tbl` (
  `product_id` int(11) NOT NULL,
  `product_type_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_type_tbl`
--

CREATE TABLE `product_type_tbl` (
  `product_type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_tbl`
--

CREATE TABLE `role_tbl` (
  `role_id` int(11) NOT NULL,
  `role_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_type_tbl`
--

CREATE TABLE `role_type_tbl` (
  `role_type_id` int(11) NOT NULL,
  `role_type_name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_design_item_tbl`
--

CREATE TABLE `saved_design_item_tbl` (
  `saved_item_id` int(11) NOT NULL,
  `3D_design_id` int(11) NOT NULL,
  `canvas_element_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_design_tbl`
--

CREATE TABLE `saved_design_tbl` (
  `saved_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `saved_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `segment_name_tbl`
--

CREATE TABLE `segment_name_tbl` (
  `segment_name_id` int(11) NOT NULL,
  `segment_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shape_type_name_tbl`
--

CREATE TABLE `shape_type_name_tbl` (
  `shape_type_id` int(11) NOT NULL,
  `shape_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shape_type_tbl`
--

CREATE TABLE `shape_type_tbl` (
  `canvas_element_id` int(11) NOT NULL,
  `shape_type_id` int(11) NOT NULL,
  `shape_size` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_notification`
--

CREATE TABLE `task_notification` (
  `task_notification_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `trigger_email` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_status_tbl`
--

CREATE TABLE `task_status_tbl` (
  `task_status_id` int(11) NOT NULL,
  `task_status_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_tbl`
--

CREATE TABLE `task_tbl` (
  `task_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `due_date` datetime NOT NULL,
  `priority` varchar(20) NOT NULL,
  `task_status_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `text_type_tbl`
--

CREATE TABLE `text_type_tbl` (
  `canvas_element_id` int(11) NOT NULL,
  `text_element` text NOT NULL,
  `font_size` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type_tbl`
--

CREATE TABLE `transaction_type_tbl` (
  `transaction_type_id` int(11) NOT NULL,
  `transaction_type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_eligibility`
--

CREATE TABLE `user_eligibility` (
  `eligibility_id` int(11) NOT NULL,
  `discount_id` int(11) NOT NULL,
  `usage_left` int(11) NOT NULL,
  `eligibility_status_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `granted_at` datetime NOT NULL,
  `segment_name_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `role_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `3D_design_tbl`
--
ALTER TABLE `3D_design_tbl`
  ADD PRIMARY KEY (`3D_design_id`);

--
-- Indexes for table `canvas_elements_tbl`
--
ALTER TABLE `canvas_elements_tbl`
  ADD PRIMARY KEY (`canvas_element_id`);

--
-- Indexes for table `discount_tbl`
--
ALTER TABLE `discount_tbl`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `element_type_tbl`
--
ALTER TABLE `element_type_tbl`
  ADD PRIMARY KEY (`element_type_id`);

--
-- Indexes for table `eligibility_status_tbl`
--
ALTER TABLE `eligibility_status_tbl`
  ADD PRIMARY KEY (`eligibility_status_id`);

--
-- Indexes for table `employee_role_tbl`
--
ALTER TABLE `employee_role_tbl`
  ADD PRIMARY KEY (`employee_role_id`);

--
-- Indexes for table `employee_role_type_tbl`
--
ALTER TABLE `employee_role_type_tbl`
  ADD PRIMARY KEY (`employee_role_type_id`);

--
-- Indexes for table `image_type`
--
ALTER TABLE `image_type`
  ADD PRIMARY KEY (`canvas_element_id`);

--
-- Indexes for table `inventory_tbl`
--
ALTER TABLE `inventory_tbl`
  ADD PRIMARY KEY (`inventory_id`);

--
-- Indexes for table `order_notification_tbl`
--
ALTER TABLE `order_notification_tbl`
  ADD PRIMARY KEY (`order_notification`);

--
-- Indexes for table `order_status_tbl`
--
ALTER TABLE `order_status_tbl`
  ADD PRIMARY KEY (`order_status_id`);

--
-- Indexes for table `order_tbl`
--
ALTER TABLE `order_tbl`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `payment_method_tbl`
--
ALTER TABLE `payment_method_tbl`
  ADD PRIMARY KEY (`payment_method_id`);

--
-- Indexes for table `payment_provider_tbl`
--
ALTER TABLE `payment_provider_tbl`
  ADD PRIMARY KEY (`payment_provider_id`);

--
-- Indexes for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  ADD PRIMARY KEY (`payment_status_id`);

--
-- Indexes for table `payment_transaction_tbl`
--
ALTER TABLE `payment_transaction_tbl`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `product_step_tbl`
--
ALTER TABLE `product_step_tbl`
  ADD PRIMARY KEY (`product_step_id`);

--
-- Indexes for table `product_tbl`
--
ALTER TABLE `product_tbl`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_type_tbl`
--
ALTER TABLE `product_type_tbl`
  ADD PRIMARY KEY (`product_type_id`);

--
-- Indexes for table `role_tbl`
--
ALTER TABLE `role_tbl`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_type_tbl`
--
ALTER TABLE `role_type_tbl`
  ADD PRIMARY KEY (`role_type_id`);

--
-- Indexes for table `saved_design_item_tbl`
--
ALTER TABLE `saved_design_item_tbl`
  ADD PRIMARY KEY (`saved_item_id`);

--
-- Indexes for table `saved_design_tbl`
--
ALTER TABLE `saved_design_tbl`
  ADD PRIMARY KEY (`saved_id`);

--
-- Indexes for table `segment_name_tbl`
--
ALTER TABLE `segment_name_tbl`
  ADD PRIMARY KEY (`segment_name_id`);

--
-- Indexes for table `shape_type_name_tbl`
--
ALTER TABLE `shape_type_name_tbl`
  ADD PRIMARY KEY (`shape_type_id`);

--
-- Indexes for table `shape_type_tbl`
--
ALTER TABLE `shape_type_tbl`
  ADD PRIMARY KEY (`canvas_element_id`);

--
-- Indexes for table `task_notification`
--
ALTER TABLE `task_notification`
  ADD PRIMARY KEY (`task_notification_id`);

--
-- Indexes for table `task_status_tbl`
--
ALTER TABLE `task_status_tbl`
  ADD PRIMARY KEY (`task_status_id`);

--
-- Indexes for table `task_tbl`
--
ALTER TABLE `task_tbl`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `text_type_tbl`
--
ALTER TABLE `text_type_tbl`
  ADD PRIMARY KEY (`canvas_element_id`);

--
-- Indexes for table `transaction_type_tbl`
--
ALTER TABLE `transaction_type_tbl`
  ADD PRIMARY KEY (`transaction_type_id`);

--
-- Indexes for table `user_eligibility`
--
ALTER TABLE `user_eligibility`
  ADD PRIMARY KEY (`eligibility_id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `3D_design_tbl`
--
ALTER TABLE `3D_design_tbl`
  MODIFY `3D_design_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `canvas_elements_tbl`
--
ALTER TABLE `canvas_elements_tbl`
  MODIFY `canvas_element_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_tbl`
--
ALTER TABLE `discount_tbl`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `element_type_tbl`
--
ALTER TABLE `element_type_tbl`
  MODIFY `element_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `eligibility_status_tbl`
--
ALTER TABLE `eligibility_status_tbl`
  MODIFY `eligibility_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_role_tbl`
--
ALTER TABLE `employee_role_tbl`
  MODIFY `employee_role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_role_type_tbl`
--
ALTER TABLE `employee_role_type_tbl`
  MODIFY `employee_role_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_type`
--
ALTER TABLE `image_type`
  MODIFY `canvas_element_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_tbl`
--
ALTER TABLE `inventory_tbl`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_notification_tbl`
--
ALTER TABLE `order_notification_tbl`
  MODIFY `order_notification` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_tbl`
--
ALTER TABLE `order_status_tbl`
  MODIFY `order_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_tbl`
--
ALTER TABLE `order_tbl`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_method_tbl`
--
ALTER TABLE `payment_method_tbl`
  MODIFY `payment_method_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_provider_tbl`
--
ALTER TABLE `payment_provider_tbl`
  MODIFY `payment_provider_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_tbl`
--
ALTER TABLE `payment_tbl`
  MODIFY `payment_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_transaction_tbl`
--
ALTER TABLE `payment_transaction_tbl`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_step_tbl`
--
ALTER TABLE `product_step_tbl`
  MODIFY `product_step_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tbl`
--
ALTER TABLE `product_tbl`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_type_tbl`
--
ALTER TABLE `product_type_tbl`
  MODIFY `product_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_tbl`
--
ALTER TABLE `role_tbl`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_type_tbl`
--
ALTER TABLE `role_type_tbl`
  MODIFY `role_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_design_item_tbl`
--
ALTER TABLE `saved_design_item_tbl`
  MODIFY `saved_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_design_tbl`
--
ALTER TABLE `saved_design_tbl`
  MODIFY `saved_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `segment_name_tbl`
--
ALTER TABLE `segment_name_tbl`
  MODIFY `segment_name_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shape_type_name_tbl`
--
ALTER TABLE `shape_type_name_tbl`
  MODIFY `shape_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shape_type_tbl`
--
ALTER TABLE `shape_type_tbl`
  MODIFY `canvas_element_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_notification`
--
ALTER TABLE `task_notification`
  MODIFY `task_notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_status_tbl`
--
ALTER TABLE `task_status_tbl`
  MODIFY `task_status_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_tbl`
--
ALTER TABLE `task_tbl`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `text_type_tbl`
--
ALTER TABLE `text_type_tbl`
  MODIFY `canvas_element_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction_type_tbl`
--
ALTER TABLE `transaction_type_tbl`
  MODIFY `transaction_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_eligibility`
--
ALTER TABLE `user_eligibility`
  MODIFY `eligibility_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
