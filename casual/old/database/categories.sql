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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `catName` varchar(255) NOT NULL,
  `catAmount` varchar(255) NOT NULL,
  `createdBy` varchar(255) DEFAULT NULL,
  `createdDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedBy` varchar(255) DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `arhived` enum('NO','YES') NOT NULL DEFAULT 'NO',
  `archivedBy` varchar(255) DEFAULT NULL,
  `archivedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `catName`, `catAmount`, `createdBy`, `createdDate`, `updatedBy`, `updatedDate`, `arhived`, `archivedBy`, `archivedDate`) VALUES
(1, 'UMUFUNDI', '10000', '1', '2018-08-03 13:00:10', NULL, NULL, 'NO', NULL, NULL),
(2, 'UMUYEDE', '5000', '1', '2018-08-03 13:00:10', NULL, NULL, 'NO', NULL, NULL),
(7, 'SUPPERVISOR', '15000', '1', '2018-08-11 13:01:08', NULL, NULL, 'NO', NULL, NULL),
(8, 'NIGHT CASUAL', '20000', '1', '2018-08-11 13:01:46', NULL, NULL, 'NO', NULL, NULL),
(9, 'DATA ENTRY', '32000', '1', '2018-08-11 13:02:05', NULL, NULL, 'NO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
