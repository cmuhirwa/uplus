-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2018 at 11:32 AM
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
-- Table structure for table `casuals`
--

CREATE TABLE `casuals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nid` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `finger` varchar(255) DEFAULT NULL,
  `handleId` varchar(255) DEFAULT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedBy` varchar(255) DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `arhived` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `archivedBy` varchar(255) DEFAULT NULL,
  `archivedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `casuals`
--

INSERT INTO `casuals` (`id`, `name`, `nid`, `phone`, `img`, `finger`, `handleId`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`, `arhived`, `archivedBy`, `archivedDate`) VALUES
(1, 'MUHIRWA CLEMENT', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '25.001/CREDITSCORE/1199380018360077', '1', '2018-08-11 13:33:04', '1', '2018-08-11 15:50:26', 'NO', NULL, NULL),
(2, 'TEST', '123', '12345', 'avatar/1.jpg', '1', NULL, '1', '2018-08-11 13:35:49', '1', '2018-08-11 15:39:45', 'NO', NULL, NULL),
(3, 'NEW', '1234321', '0987765', 'avatar/1.jpg', '1', '25.001/CREDITSCORE/1234321', '1', '2018-08-11 14:14:53', '1', '2018-08-11 16:14:53', 'NO', NULL, NULL),
(4, 'TEST', '1212', '1234', 'avatar/1.jpg', '1', NULL, '1', '2018-08-16 07:49:13', NULL, NULL, 'NO', NULL, NULL),
(5, 'TEST2', '54321', '321', 'avatar/1.jpg', '1', '25.001/CREDITSCORE/54321', '1', '2018-08-16 07:55:14', '1', '2018-08-16 09:55:19', 'NO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `casuals`
--
ALTER TABLE `casuals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `casuals`
--
ALTER TABLE `casuals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
