-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 07:16 AM
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
-- Database: `sbtbsphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(100) NOT NULL,
  `booking_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `customer_route` varchar(200) NOT NULL,
  `booked_amount` int(100) NOT NULL,
  `booked_seat` varchar(100) NOT NULL,
  `booking_created` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` varchar(20) DEFAULT 'PENDING',
  `payment_method` varchar(20) DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `booking_id`, `customer_id`, `route_id`, `customer_route`, `booked_amount`, `booked_seat`, `booking_created`, `payment_status`, `payment_method`, `payment_date`) VALUES
(5, 'BKG00001', '927623BCS006', 'RT-1908653', 'Chennai → Coimbatore', 500, '22', '2025-05-16 00:37:23', 'PENDING', NULL, NULL),
(8, 'BKG00003', '927623BCS008', 'RT-1908653', 'Chennai → Coimbatore', 500, '32', '2025-05-16 10:43:01', 'PENDING', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(100) NOT NULL,
  `bus_no` varchar(255) NOT NULL,
  `bus_assigned` tinyint(1) NOT NULL DEFAULT 0,
  `bus_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `bus_no`, `bus_assigned`, `bus_created`) VALUES
(44, 'TNSTC (Tamil Nadu State Transport Corporation)', 0, '2025-05-07 11:34:10'),
(45, 'Vinayaga Bus', 1, '2025-05-07 11:34:10'),
(46, 'Praveen Travels', 0, '2025-05-07 11:34:10'),
(47, 'Orange Tours and Travels', 0, '2025-05-07 11:34:10'),
(48, 'KPN Travels', 1, '2025-05-07 11:34:10'),
(49, 'Kallada Travels', 1, '2025-05-07 11:34:10'),
(50, 'Sri Krishna Travels', 1, '2025-05-07 11:34:10'),
(53, 'SRS Travels', 0, '2025-05-07 11:34:10'),
(55, 'Royal Bus', 0, '2025-05-07 11:34:10'),
(56, 'SK Balu', 0, '2025-05-07 11:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(100) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `customer_name` varchar(30) NOT NULL,
  `customer_phone` varchar(10) NOT NULL,
  `customer_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_id`, `customer_name`, `customer_phone`, `customer_created`) VALUES
(34, '927623BCS001', 'ASWANTH', '9876543210', '2025-05-07 11:34:10'),
(35, '927623BCS002', 'KISHORE', '9123456780', '2025-05-07 11:34:10'),
(36, '927623BCS003', 'BHAVA', '9988776655', '2025-05-07 11:34:10'),
(37, '927623BCS004', 'MOHI ASWATH', '9090909090', '2025-05-07 11:34:10'),
(38, '927623BCS005', 'DHARUN', '9000000001', '2025-05-07 11:34:10'),
(78, '927623BCS006', 'HARSHAN K', '9876541230', '2025-05-16 00:37:22'),
(79, '927623BCS007', 'DHARUN  M', '0944335508', '2025-05-16 09:00:49'),
(81, '927623BCS008', 'DHARUN KUMAR M', '9443355081', '2025-05-16 10:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(100) NOT NULL,
  `route_id` varchar(255) NOT NULL,
  `bus_no` varchar(155) NOT NULL,
  `route_cities` varchar(255) NOT NULL,
  `route_dep_date` date NOT NULL,
  `route_dep_time` time NOT NULL,
  `route_step_cost` int(100) NOT NULL,
  `route_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route_id`, `bus_no`, `route_cities`, `route_dep_date`, `route_dep_time`, `route_step_cost`, `route_created`) VALUES
(54, 'RT-3835554', 'TN 02 AA 1001', 'Madurai,Salem', '2021-10-19', '23:13:00', 70, '2025-05-07 11:34:10'),
(55, 'RT-9941455', 'TN 03 AA 1002', 'Trichy,Thanjavur', '2021-10-18', '10:00:00', 110, '2025-05-07 11:34:10'),
(56, 'RT-9069556', 'TN 04 AA 1003', 'Erode,Namakkal', '2021-10-19', '11:40:00', 85, '2025-05-07 11:34:10'),
(57, 'RT-775557', 'TN 05 AA 1004', 'Tirunelveli,Nagercoil', '2021-10-19', '13:30:00', 131, '2025-05-07 11:34:10'),
(58, 'RT-753558', 'TN 06 AA 1005', 'Vellore,Tiruvannamalai', '2021-10-20', '12:04:00', 55, '2025-05-07 11:34:10'),
(59, 'RT-6028759', 'TN 07 AA 1006', 'Karur,Namakkal', '2021-10-20', '13:50:00', 500, '2025-05-07 11:34:10'),
(60, 'RT-5887160', 'TN 08 AA 1007', 'Karur,Ariyalur', '2021-10-19', '10:30:00', 118, '2025-05-07 11:34:10'),
(61, 'RT-1908653', 'TN 01 AA 1000', 'Chennai,Coimbatore', '2021-10-17', '22:05:00', 500, '2025-05-07 11:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `bus_no` varchar(155) NOT NULL,
  `seat_booked` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`bus_no`, `seat_booked`) VALUES
('XYZ7890', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_fullname`, `user_name`, `user_password`, `user_created`) VALUES
(1, 'M S Arun Sanjeev', 'admin', '$2y$10$7rLSvRVyTQORapkDOqmkhetjF6H9lJHngr4hJMSM2lHObJbW5EQh6', '2025-05-07 11:34:10'),
(2, 'Test Admin', 'testadmin', '$2y$10$A2eGOu1K1TSBqMwjrEJZg.lgy.FmCUPl/l5ugcYOXv4qKWkFEwcqS', '2025-05-07 11:34:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`bus_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
