-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 30, 2021 at 09:05 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `channel`
--

-- --------------------------------------------------------

--
-- Table structure for table `base`
--

CREATE TABLE `base` (
  `id` int(11) NOT NULL,
  `device_count` int(255) DEFAULT NULL,
  `channel_count` int(255) DEFAULT NULL,
  `priority_device` int(255) DEFAULT NULL,
  `base_x` int(4) NOT NULL,
  `base_y` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `base`
--

INSERT INTO `base` (`id`, `device_count`, `channel_count`, `priority_device`, `base_x`, `base_y`) VALUES
(52, 10, 5, 6, 8, 71);

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `dev_id` int(4) NOT NULL,
  `data_rate` int(4) DEFAULT NULL,
  `x_cod` int(4) DEFAULT NULL,
  `y_cod` int(4) DEFAULT NULL,
  `distance` float DEFAULT NULL,
  `tollerence` float DEFAULT NULL,
  `allocation` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`dev_id`, `data_rate`, `x_cod`, `y_cod`, `distance`, `tollerence`, `allocation`) VALUES
(1, 28, 182, 153, 192.354, 0.610122, 0),
(2, 21, 6, 193, 122.016, 0.934117, 0),
(3, 27, 72, 103, 71.5542, 0.645527, 0),
(4, 22, 92, 182, 139.201, 0.874472, 0),
(5, 28, 190, 105, 185.149, 0.610122, 0),
(6, 27, 137, 154, 153.395, 0.645527, 0),
(7, 18, 80, 131, 93.723, 1.15465, 0),
(8, 2, 69, 154, 103.005, 13.9327, 0),
(9, 16, 88, 40, 85.7963, 1.34934, 0),
(10, 7, 121, 83, 113.635, 3.64218, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `base`
--
ALTER TABLE `base`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`dev_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `base`
--
ALTER TABLE `base`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `dev_id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
