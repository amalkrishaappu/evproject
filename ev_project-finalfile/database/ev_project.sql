-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 01, 2025 at 05:50 PM
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
-- Database: `ev_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `battery_bookings`
--

CREATE TABLE `battery_bookings` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `station_id` int(100) NOT NULL,
  `battery_id` int(100) NOT NULL,
  `rental_days` int(50) NOT NULL,
  `total_price` varchar(200) NOT NULL,
  `booked_on` datetime NOT NULL DEFAULT current_timestamp(),
  `return_due` date NOT NULL,
  `status` enum('Active','Cancelled','Returned','Return Requested') NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `battery_bookings`
--

INSERT INTO `battery_bookings` (`id`, `user_id`, `station_id`, `battery_id`, `rental_days`, `total_price`, `booked_on`, `return_due`, `status`) VALUES
(1, 1, 19, 3, 1, '200', '2025-11-01 13:04:18', '2025-11-02', 'Cancelled'),
(2, 1, 19, 3, 1, '200', '2025-11-01 13:06:33', '2025-11-02', 'Cancelled'),
(21, 1, 19, 3, 5, '1000', '2025-11-01 18:33:07', '2025-11-06', 'Returned'),
(22, 1, 19, 1, 3, '1050', '2025-11-01 18:33:17', '2025-11-04', 'Active'),
(23, 8, 20, 5, 3, '1200', '2025-11-01 21:50:18', '2025-11-04', 'Active'),
(24, 8, 19, 3, 3, '600', '2025-11-01 21:50:44', '2025-11-04', 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `battery_rentals`
--

CREATE TABLE `battery_rentals` (
  `id` int(100) NOT NULL,
  `station_id` int(100) NOT NULL,
  `brand` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `capacity` int(100) NOT NULL,
  `voltage` int(100) NOT NULL,
  `compatibility` enum('2 Wheeler','3 Wheeler','4 Wheeler') NOT NULL,
  `range_km` int(100) NOT NULL,
  `condition` enum('New','Used','Reconditioned') NOT NULL DEFAULT 'New',
  `life` varchar(200) NOT NULL,
  `rent_price` int(100) NOT NULL,
  `stock_count` int(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `status` enum('Available','Rented','Maintenance') DEFAULT 'Available',
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `battery_rentals`
--

INSERT INTO `battery_rentals` (`id`, `station_id`, `brand`, `model`, `capacity`, `voltage`, `compatibility`, `range_km`, `condition`, `life`, `rent_price`, `stock_count`, `image`, `status`, `added_on`) VALUES
(1, 19, 'Blaze', 'EVX-12', 80, 30, '3 Wheeler', 90, 'Used', '3000', 350, 0, '1761909370_battery1.webp', 'Rented', '2025-10-31 16:36:11'),
(3, 19, 'Exide', 'XP-500', 120, 72, '2 Wheeler', 100, 'Used', '2 years', 200, 3, '1761911533_batter2.webp', 'Available', '2025-10-31 17:22:13'),
(4, 20, 'Exide', 'ECX-12', 120, 72, '3 Wheeler', 2, 'Used', '2', 300, 5, '1762011917_levac-1-arai.webp', 'Available', '2025-11-01 21:15:17'),
(5, 20, 'Exide Dual', 'ECX-13', 120, 72, '4 Wheeler', 100, 'Used', '1', 400, 9, '1762011966_30kw-thumbnail-hover.webp', 'Available', '2025-11-01 21:16:06');

-- --------------------------------------------------------

--
-- Table structure for table `charging_slots`
--

CREATE TABLE `charging_slots` (
  `id` int(100) NOT NULL,
  `station_id` int(100) NOT NULL,
  `slot_name` varchar(200) NOT NULL,
  `charger_type` varchar(200) NOT NULL,
  `connector_type` varchar(200) NOT NULL,
  `power_kw` varchar(200) NOT NULL,
  `price_per_kwh` varchar(200) NOT NULL,
  `auth_methods` varchar(200) NOT NULL,
  `status` enum('Available','Occupied','Maintenance') NOT NULL DEFAULT 'Available',
  `added_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `charging_slots`
--

INSERT INTO `charging_slots` (`id`, `station_id`, `slot_name`, `charger_type`, `connector_type`, `power_kw`, `price_per_kwh`, `auth_methods`, `status`, `added_on`) VALUES
(1, 19, 'Slot B1', 'Normal', 'CCS 2', '80', '90', 'QR Code', 'Occupied', '2025-10-31 15:18:39'),
(3, 19, 'Slot B3', 'Normal', 'CHAdeMO', '40', '34.5', 'Mobile App, QR Code', 'Available', '2025-10-31 23:12:50'),
(4, 19, 'SlotC4', 'Normal', 'Type 2', '60', '30', 'RFID, Mobile App, QR Code', 'Occupied', '2025-10-31 23:13:25'),
(5, 20, 'Slot D3', 'Normal', 'CCS 2', '90', '10', 'RFID, Mobile App, QR Code', 'Available', '2025-11-01 21:12:10'),
(6, 20, 'Slot D4', 'Normal', 'CCS 2', '80', '10', 'RFID, Mobile App, QR Code', 'Available', '2025-11-01 21:12:34');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `title` varchar(200) NOT NULL,
  `type` enum('feedback','complaint') NOT NULL DEFAULT 'feedback',
  `message` varchar(200) NOT NULL,
  `rating` int(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `title`, `type`, `message`, `rating`, `date`) VALUES
(9, 1, 'good', 'feedback', 'your connections are good', 3, '2025-11-01 15:51:04');

-- --------------------------------------------------------

--
-- Table structure for table `slots_bookings`
--

CREATE TABLE `slots_bookings` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `station_id` int(100) NOT NULL,
  `slot_id` int(100) NOT NULL,
  `booking_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Booked','Cancelled','Completed') NOT NULL DEFAULT 'Booked',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('Pending','Paid','Failed') NOT NULL DEFAULT 'Pending',
  `auth_method` varchar(200) NOT NULL,
  `estimated_amount` varchar(200) NOT NULL,
  `booking_time` datetime NOT NULL DEFAULT current_timestamp(),
  `duration` int(100) NOT NULL,
  `vehicle_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slots_bookings`
--

INSERT INTO `slots_bookings` (`id`, `user_id`, `station_id`, `slot_id`, `booking_date`, `status`, `created_at`, `payment_status`, `auth_method`, `estimated_amount`, `booking_time`, `duration`, `vehicle_type`) VALUES
(1, 1, 19, 1, '2025-10-31 00:00:00', 'Completed', '2025-10-31 22:44:25', 'Pending', '', '0', '2025-10-31 23:55:24', 0, ''),
(12, 1, 19, 3, '2025-11-01 07:21:22', 'Completed', '2025-11-01 11:51:22', 'Pending', 'QR Code', '69', '2025-11-01 11:51:22', 1, '2 Wheeler'),
(13, 1, 19, 1, '2025-11-01 07:37:58', 'Completed', '2025-11-01 12:07:58', 'Pending', 'RFID Card', '180', '2025-11-01 12:07:58', 1, '2 Wheeler'),
(14, 1, 19, 4, '2025-11-01 07:38:45', 'Completed', '2025-11-01 12:08:45', 'Pending', 'Mobile App', '60', '2025-11-01 12:08:45', 1, '2 Wheeler'),
(15, 1, 19, 4, '2025-11-01 14:02:28', 'Booked', '2025-11-01 18:32:28', 'Pending', 'QR Code', '180', '2025-11-01 18:32:28', 3, '2 Wheeler'),
(16, 1, 19, 1, '2025-11-01 14:02:41', 'Booked', '2025-11-01 18:32:41', 'Pending', 'Mobile App', '360', '2025-11-01 18:32:41', 2, '3 Wheeler'),
(17, 8, 20, 6, '2025-11-01 17:21:50', 'Cancelled', '2025-11-01 21:51:50', 'Pending', 'QR Code', '40', '2025-11-01 21:51:50', 2, '3 Wheeler');

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

CREATE TABLE `stations` (
  `id` int(11) NOT NULL,
  `station_name` varchar(200) NOT NULL,
  `owner_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `district` varchar(200) NOT NULL,
  `location` varchar(250) NOT NULL,
  `latitude` varchar(200) NOT NULL,
  `longitude` varchar(200) NOT NULL,
  `charger_type` varchar(200) NOT NULL,
  `battery_rental` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `registered_on` datetime NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`id`, `station_name`, `owner_name`, `email`, `phone`, `district`, `location`, `latitude`, `longitude`, `charger_type`, `battery_rental`, `username`, `password`, `image_path`, `registered_on`, `status`) VALUES
(17, 'Tata Power Station', 'Rathan Tata', 'tata@gmail.com', '1800833223', 'Thrissur', '', '10.546682839170602', '76.19157573194306', 'Fast Charger', 'Yes', 'Tata', 'Tata@12345', 'uploads/1761883111_general_specs.webp', '2025-10-31 09:28:31', 'pending'),
(18, 'ChargeMOD Charging Station', 'Hill berg', 'chargemod@gmail.com', '0812954729', 'Thrissur', '', '10.476807728507657', '76.25749369873877', 'Normal Charger', 'Yes', 'chargemod', 'Chargemode@123', 'uploads/1761883534_60to120kw-thumbnail-hover.webp', '2025-10-31 09:35:34', 'approved'),
(19, 'GOEC Charging Station', 'Hill berg', 'goec@gmail.com', '0999518841', 'Thrissur', 'SOBHA CITY, Puzhakkal, Thrissur, Kerala 680553', '10.549040206301362', '76.18265934472984', 'Normal', 'Yes', 'GOEC', 'Goec@1234', 'uploads/1761901003_60to120kw-thumbnail-hover.webp', '2025-10-31 13:35:26', 'approved'),
(20, 'Zeon Charging Station', 'Zeon Zono', 'zeoncharge@gmail.com', '7885553333', 'Kannur', 'Down town mall, Jubilee Rd, opp. Malabar Gold, Pilakool, Thalassery,kannur Kerala 670101', '11.762698475808678', '75.48967123140346', 'Normal', 'Yes', 'zeoncharging', 'Zeon@12345', 'uploads/1762011650_images (2).jpeg', '2025-11-01 21:08:09', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `age` int(100) NOT NULL,
  `address` varchar(200) NOT NULL,
  `phoneno` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `age`, `address`, `phoneno`, `email`, `username`, `password`, `image`, `created_at`) VALUES
(1, 'Malavaika', 18, 'Malolipuram House,Mala', '9145637281', 'malavika@gmail.com', 'malavika', 'Malavika@123', 'uploads/1761921139_beautiful-girl-stands-park_8353-5084.jpg', '2025-10-31 13:13:06'),
(3, 'AmalKrishna', 19, 'amalabavanam house', '7623893662', 'amal@gmail.com', 'amalkrishna', 'Amal@12345', '1761916819_download.jpeg', '2025-10-31 13:20:20'),
(4, 'Stebin Johnson', 28, 'Kannanaykkal House\r\nchevvor', '7623893662', 'stebin@gmail.com', 'stebin', 'Stebin@123', '1761917332_download (1).jpeg', '2025-10-31 13:28:52'),
(7, 'Manusha', 20, 'Manushanagaram HouseMalappuram', '9156734988', 'manusha@gmail.com', 'manusha', 'Manusha@123', 'uploads/1762013361_images.jpeg', '2025-11-01 16:07:59'),
(8, 'Roshan', 20, 'Chankan HouseChalakkudi', '9122776655', 'roshan@gmail.com', 'roshan', 'Roshan@123', 'uploads/1762013941_download.jpeg', '2025-11-01 16:18:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL,
  `status` enum('active','logged_out') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_logins`
--

INSERT INTO `user_logins` (`id`, `user_id`, `login_time`, `logout_time`, `status`) VALUES
(1, 1, '2025-11-01 17:39:55', NULL, 'active'),
(2, 1, '2025-11-01 17:40:07', NULL, 'active'),
(3, 1, '2025-11-01 17:43:05', '2025-11-01 17:43:28', 'logged_out'),
(4, 1, '2025-11-01 18:32:06', NULL, 'active'),
(5, 7, '2025-11-01 21:38:14', NULL, 'active'),
(6, 8, '2025-11-01 21:48:43', NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `battery_bookings`
--
ALTER TABLE `battery_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `battery_rentals`
--
ALTER TABLE `battery_rentals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `charging_slots`
--
ALTER TABLE `charging_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slots_bookings`
--
ALTER TABLE `slots_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stations`
--
ALTER TABLE `stations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `battery_bookings`
--
ALTER TABLE `battery_bookings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `battery_rentals`
--
ALTER TABLE `battery_rentals`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `charging_slots`
--
ALTER TABLE `charging_slots`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `slots_bookings`
--
ALTER TABLE `slots_bookings`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `stations`
--
ALTER TABLE `stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
