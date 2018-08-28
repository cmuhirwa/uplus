-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 16, 2018 at 11:33 AM
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
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `startOn` time NOT NULL,
  `startOff` time NOT NULL,
  `stopOn` time NOT NULL,
  `stopOff` time NOT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedBy` varchar(255) DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `arhived` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `archivedBy` varchar(255) DEFAULT NULL,
  `archivedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `fromDate`, `toDate`, `startOn`, `startOff`, `stopOn`, `stopOff`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`, `arhived`, `archivedBy`, `archivedDate`) VALUES
(1, '2018-08-09', '2018-08-09', '03:00:00', '01:00:00', '01:59:00', '01:00:00', '1', '2018-08-09 14:05:34', NULL, NULL, 'NO', NULL, NULL),
(2, '2018-08-10', '2018-09-10', '06:00:00', '08:00:00', '16:00:00', '18:00:00', '1', '2018-08-09 14:33:49', NULL, NULL, 'NO', NULL, NULL),
(3, '2018-08-11', '2018-09-08', '06:00:00', '08:00:00', '16:00:00', '18:00:00', '1', '2018-08-10 07:21:42', NULL, NULL, 'NO', NULL, NULL),
(4, '2018-08-01', '2018-09-01', '06:00:00', '08:00:00', '16:00:00', '18:00:00', '1', '2018-08-10 09:15:03', NULL, NULL, 'NO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
