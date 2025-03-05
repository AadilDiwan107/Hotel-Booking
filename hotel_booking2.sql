-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 07:01 AM
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
-- Database: `hotel_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_type` enum('normal','delux','super_delux') NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `status` enum('pending','checked_in','checked_out') DEFAULT 'pending',
  `name` varchar(100) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_type`, `check_in_date`, `check_out_date`, `status`, `name`, `mobile_number`, `address`) VALUES
(3, 1, 'normal', '2025-02-25', '2025-02-26', 'checked_in', '', '', ''),
(5, 4, 'normal', '2025-03-06', '2025-03-07', 'pending', '', '', ''),
(6, 4, 'normal', '2025-03-05', '2025-03-06', 'pending', 'qwerty', '0901020507', 'alien from mars'),
(7, 4, 'normal', '2025-03-05', '2025-03-14', 'pending', 'asdfg', '0147852369', 'asdfghjk');

-- --------------------------------------------------------

--
-- Table structure for table `dash`
--

CREATE TABLE `dash` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dash`
--

INSERT INTO `dash` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'dash', 'd@gmail.com', '$2y$10$PGyvZa8lf7yY87WA2yfuoudmVKlo/S7tjwpsDMvQjC7g5ag9baD1C', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `id` int(11) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `total_halls` int(11) NOT NULL,
  `booked_halls` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`id`, `hall_name`, `total_halls`, `booked_halls`) VALUES
(1, 'Conference Hall', 5, 0),
(2, 'Banquet Hall', 3, 1),
(3, 'Wedding Hall', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `hall_bookings`
--

CREATE TABLE `hall_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hall_name` varchar(100) NOT NULL,
  `event_type` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hall_bookings`
--

INSERT INTO `hall_bookings` (`id`, `user_id`, `hall_name`, `event_type`, `booking_date`, `booking_time`, `status`) VALUES
(2, 1, 'Banquet Hall', ';lkjhg', '2025-02-12', '14:44:00', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_type` enum('normal','delux','super_delux') NOT NULL,
  `total_rooms` int(11) NOT NULL,
  `booked_rooms` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_type`, `total_rooms`, `booked_rooms`) VALUES
(1, 'normal', 10, 5),
(2, 'delux', 5, 0),
(3, 'super_delux', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'aadil', 'a@gmail.com', '$2y$10$w3YsIzvxd6ot9RuzEpGT4OuLqd7aI0Wcdr1Yrkwv5zqVdsdJBN6hS', 'user'),
(3, 'dash user', 'dash@gmail.com', '$2y$10$w3YsIzvxd6ot9RuzEpGT4OuLqd7aI0Wcdr1Yrkwv5zqVdsdJBN6hS', 'admin'),
(4, 'delight', 'd@gmail.com', '$2y$10$31UUo/aTg0Nizfs0ycOnzu2Vgw9cztLE9yE9pPxzHUrr50/wUl8dy', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dash`
--
ALTER TABLE `dash`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hall_bookings`
--
ALTER TABLE `hall_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `dash`
--
ALTER TABLE `dash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hall_bookings`
--
ALTER TABLE `hall_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `hall_bookings`
--
ALTER TABLE `hall_bookings`
  ADD CONSTRAINT `hall_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
