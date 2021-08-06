-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2021 at 04:41 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `base_id` varchar(25) NOT NULL,
  `x_cod` int(11) NOT NULL,
  `y_cod` int(11) NOT NULL,
  `channel_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `base`
--

INSERT INTO `base` (`base_id`, `x_cod`, `y_cod`, `channel_count`) VALUES
('base610ccdd560276', 150, 150, 9);

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `dev_id` varchar(25) NOT NULL,
  `data_rate` int(3) NOT NULL,
  `x_cod` int(3) NOT NULL,
  `y_cod` int(3) NOT NULL,
  `distance` double NOT NULL,
  `tollerence` double NOT NULL,
  `allocation` int(11) NOT NULL,
  `data_size` float NOT NULL,
  `priority` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`dev_id`, `data_rate`, `x_cod`, `y_cod`, `distance`, `tollerence`, `allocation`, `data_size`, `priority`) VALUES
('dev610d3e511ce8e', 14, 103, 56, 105.09519494249, 1.6012687359157, 1, 43, 0),
('dev610d3e550fdca', 20, 44, 175, 108.90821823903, 1, 1, 231, 0),
('dev610d3e5858e8f', 22, 80, 81, 98.290386101592, 0.87447220404753, 1, 139, 0),
('dev610d3e5b7d23b', 24, 67, 160, 83.600239234107, 0.77077426842293, 1, 74, 0),
('dev610d3e5ebeaee', 24, 46, 78, 126.49110640674, 0.77077426842293, 1, 202, 0),
('dev610d3e61f1d02', 23, 136, 67, 84.172442046076, 0.82025105080123, 1, 69, 0),
('dev610d3e6558d1f', 5, 90, 95, 81.394102980499, 5.2852135078832, 1, 193, 0),
('dev610d3e689f0f9', 1, 199, 180, 57.454329688893, 28.356788873216, 1, 75, 0),
('dev610d3e6b1b153', 6, 28, 186, 127.20062892926, 4.3262996735629, 1, 382, 0),
('dev610d3e6e91003', 15, 49, 44, 146.41379716407, 1.4667212021806, 1, 331, 0),
('dev610d3e71b140d', 15, 14, 182, 139.71399357258, 1.4667212021806, 1, 80, 0),
('dev610d3e7602f88', 15, 195, 197, 65.06919393999, 1.4667212021806, 1, 295, 0),
('dev610d3e796b9ee', 29, 89, 47, 119.70797801316, 0.57734036737948, 1, 291, 0),
('dev610d3e7cb588f', 7, 118, 3, 150.44268011439, 3.6421828204712, 1, 74, 0),
('dev610d3e8044322', 13, 181, 166, 34.885527085025, 1.756949891773, 1, 371, 0),
('dev610d3e83a87c7', 4, 126, 150, 24, 6.7250239588726, 1, 285, 0),
('dev610d3e86de724', 28, 163, 48, 102.82509421343, 0.61012223729291, 1, 93, 0),
('dev610d3e89ebb69', 6, 92, 37, 127.01574705524, 4.3262996735629, 1, 312, 0),
('dev610d3e8d581f9', 2, 165, 47, 104.08650248711, 13.932726172913, 1, 397, 0),
('dev610d3e90783f7', 6, 36, 174, 116.49892703369, 4.3262996735629, 1, 266, 0),
('dev610d3e93a9c30', 27, 104, 163, 47.801673610868, 0.64552726070068, 1, 367, 0),
('dev610d3e96cd99a', 12, 21, 10, 190.37069102149, 1.9390495961115, 1, 162, 0),
('dev610d3e9a0a15a', 20, 14, 154, 136.05881081356, 1, 1, 208, 0),
('dev610d3e9d5c0cd', 17, 91, 69, 100.20977996184, 1.2461044823391, 1, 206, 0),
('dev610d3ea0eab0f', 28, 26, 171, 125.76565508914, 0.61012223729291, 1, 160, 0),
('dev610d3ea468c28', 29, 153, 115, 35.128336140501, 0.57734036737948, 1, 176, 0),
('dev610d3ea77d827', 20, 195, 136, 47.127486671793, 1, 1, 244, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `base`
--
ALTER TABLE `base`
  ADD PRIMARY KEY (`base_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
