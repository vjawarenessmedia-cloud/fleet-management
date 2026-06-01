-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2026 at 12:07 PM
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
-- Database: `fleet_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `hired_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_logs`
--

CREATE TABLE `fuel_logs` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `fuel_quantity` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `odometer` int(11) DEFAULT NULL,
  `full_refill` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pre_trip_inspections`
--

CREATE TABLE `pre_trip_inspections` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `inspection_date` datetime DEFAULT current_timestamp(),
  `checklist_items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`checklist_items`)),
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pre_trip_inspections`
--

INSERT INTO `pre_trip_inspections` (`id`, `vehicle_id`, `driver_id`, `inspection_date`, `checklist_items`, `notes`) VALUES
(2, 3, NULL, '2026-04-03 13:58:48', '{\"tires\":\"fail\",\"lights\":\"fail\",\"brakes\":\"fail\",\"fluids\":\"ok\",\"battery\":\"ok\",\"spare_tire\":\"ok\",\"fuel\":\"ok\",\"mirrors\":\"ok\",\"horn\":\"ok\",\"seatbelts\":\"ok\"}', '');

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `reminder_date` date NOT NULL,
  `reminder_type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `vehicle_id`, `reminder_date`, `reminder_type`, `message`, `status`, `created_at`) VALUES
(2, 3, '2026-04-03', 'Service', 'Next service due on 2026-04-03 or at 21000 km', 'completed', '2026-04-03 08:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `service_parts_used`
--

CREATE TABLE `service_parts_used` (
  `id` int(11) NOT NULL,
  `service_record_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `quantity_used` int(11) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_records`
--

CREATE TABLE `service_records` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `service_date` date NOT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `odometer` int(11) DEFAULT NULL,
  `next_service_date` date DEFAULT NULL,
  `next_service_odometer` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spare_parts`
--

CREATE TABLE `spare_parts` (
  `id` int(11) NOT NULL,
  `part_name` varchar(100) NOT NULL,
  `part_number` varchar(50) DEFAULT NULL,
  `quantity_in_stock` int(11) DEFAULT 0,
  `minimum_quantity` int(11) DEFAULT 5,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin', '2026-02-21 13:12:03');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(20) NOT NULL,
  `model` varchar(50) DEFAULT NULL,
  `make` varchar(50) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `fuel_type` enum('Petrol','Diesel','Electric','Hybrid') DEFAULT NULL,
  `odometer` int(11) DEFAULT 0,
  `status` enum('active','inactive','maintenance') DEFAULT 'active',
  `driver_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `registration_number`, `model`, `make`, `year`, `fuel_type`, `odometer`, `status`, `driver_id`, `created_at`) VALUES
(3, 'TN59DH6939', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:22:17'),
(4, 'TN59DH7141', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:22:57'),
(5, 'TN59DH7144', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:23:21'),
(6, 'TN59DH7156', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:23:40'),
(7, 'TN59DH7166', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:24:07'),
(8, 'TN59DH7410', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:24:28'),
(9, 'TN59DH7448', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:24:51'),
(10, 'TN59DH7528', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:25:23'),
(11, 'TN59DH7563', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:25:46'),
(12, 'TN59DH7567', 'BOLERO CAMPER GOLD ZXD', 'MAHINDRA & MAHINDRA LIMITED', 2025, 'Diesel', 0, 'active', NULL, '2026-03-04 13:26:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_number` (`license_number`);

--
-- Indexes for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `pre_trip_inspections`
--
ALTER TABLE `pre_trip_inspections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `service_parts_used`
--
ALTER TABLE `service_parts_used`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_record_id` (`service_record_id`),
  ADD KEY `part_id` (`part_id`);

--
-- Indexes for table `service_records`
--
ALTER TABLE `service_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `spare_parts`
--
ALTER TABLE `spare_parts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `part_number` (`part_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `driver_id` (`driver_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pre_trip_inspections`
--
ALTER TABLE `pre_trip_inspections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reminders`

--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_parts_used`
--
ALTER TABLE `service_parts_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_records`
--
ALTER TABLE `service_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `spare_parts`
--
ALTER TABLE `spare_parts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fuel_logs`
--
ALTER TABLE `fuel_logs`
  ADD CONSTRAINT `fuel_logs_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pre_trip_inspections`
--
ALTER TABLE `pre_trip_inspections`
  ADD CONSTRAINT `pre_trip_inspections_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pre_trip_inspections_ibfk_2` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_parts_used`
--
ALTER TABLE `service_parts_used`
  ADD CONSTRAINT `service_parts_used_ibfk_1` FOREIGN KEY (`service_record_id`) REFERENCES `service_records` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_parts_used_ibfk_2` FOREIGN KEY (`part_id`) REFERENCES `spare_parts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_records`
--
ALTER TABLE `service_records`
  ADD CONSTRAINT `service_records_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
