-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2018 at 11:31 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `casual`
--

-- --------------------------------------------------------

--
-- Table structure for table `casualpayroll`
--

CREATE TABLE `casualpayroll` (
  `id` int(11) NOT NULL,
  `casualCode` int(11) NOT NULL,
  `categoryCode` int(11) NOT NULL,
  `payrollCode` int(11) NOT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedBy` varchar(255) DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `arhived` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `archivedBy` varchar(255) DEFAULT NULL,
  `archivedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `casualpayroll`
--

INSERT INTO `casualpayroll` (`id`, `casualCode`, `categoryCode`, `payrollCode`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`, `arhived`, `archivedBy`, `archivedDate`) VALUES
(1, 1, 1, 1, '1', '2018-08-09 14:05:45', NULL, NULL, 'NO', NULL, NULL),
(2, 2, 2, 2, '1', '2018-08-09 14:34:52', NULL, NULL, 'NO', NULL, NULL),
(3, 1, 1, 2, '1', '2018-08-09 14:35:00', NULL, NULL, 'NO', NULL, NULL),
(4, 2, 2, 3, '1', '2018-08-10 07:22:04', NULL, NULL, 'NO', NULL, NULL),
(5, 1, 1, 3, '1', '2018-08-10 07:22:08', NULL, NULL, 'NO', NULL, NULL),
(6, 3, 2, 4, '1', '2018-08-10 09:15:33', '1', '2018-08-10 20:04:20', 'NO', NULL, NULL),
(7, 2, 1, 4, '1', '2018-08-10 09:15:39', '1', '2018-08-10 20:04:21', 'NO', NULL, NULL),
(8, 1, 2, 4, '1', '2018-08-10 09:15:45', '1', '2018-08-10 20:04:23', 'NO', NULL, NULL),
(9, 5, 2, 4, '1', '2018-08-10 17:54:52', '1', '2018-08-10 20:04:16', 'NO', NULL, NULL),
(10, 4, 2, 4, '1', '2018-08-10 17:54:54', '1', '2018-08-10 20:04:18', 'NO', NULL, NULL),
(11, 14, 2, 4, '1', '2018-08-10 18:03:59', NULL, NULL, 'NO', NULL, NULL),
(12, 13, 1, 4, '1', '2018-08-10 18:04:01', NULL, NULL, 'NO', NULL, NULL),
(13, 12, 2, 4, '1', '2018-08-10 18:04:02', NULL, NULL, 'NO', NULL, NULL),
(14, 10, 1, 4, '1', '2018-08-10 18:04:04', NULL, NULL, 'NO', NULL, NULL),
(15, 11, 1, 4, '1', '2018-08-10 18:04:07', NULL, NULL, 'NO', NULL, NULL),
(16, 8, 2, 4, '1', '2018-08-10 18:04:08', NULL, NULL, 'NO', NULL, NULL),
(17, 9, 2, 4, '1', '2018-08-10 18:04:10', NULL, NULL, 'NO', NULL, NULL),
(18, 7, 2, 4, '1', '2018-08-10 18:04:12', NULL, NULL, 'NO', NULL, NULL),
(19, 6, 1, 4, '1', '2018-08-10 18:04:14', NULL, NULL, 'NO', NULL, NULL),
(20, 2, 9, 1, '1', '2018-08-11 13:02:27', NULL, NULL, 'NO', NULL, NULL),
(21, 3, 8, 1, '1', '2018-08-11 13:02:31', NULL, NULL, 'NO', NULL, NULL),
(22, 14, 7, 1, '1', '2018-08-11 13:02:47', NULL, NULL, 'NO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `casualpayroll`
--
ALTER TABLE `casualpayroll`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `casualpayroll`
--
ALTER TABLE `casualpayroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
