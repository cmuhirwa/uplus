-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2018 at 12:24 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_22468831_bazimya`
--

-- --------------------------------------------------------

--
-- Table structure for table `datas`
--

CREATE TABLE `datas` (
  `dat_id` int(11) NOT NULL,
  `dat_` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `devices_list`
--

CREATE TABLE `devices_list` (
  `deviceId` int(11) NOT NULL,
  `device_name` varchar(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DeviceCode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices_list`
--

INSERT INTO `devices_list` (`deviceId`, `device_name`, `dateAdded`, `DeviceCode`) VALUES
(1, 'Kigali', '2018-08-17 01:03:27', 'tyyw100hjsd'),
(2, 'fs', '2018-08-17 01:26:14', 'dfs'),
(3, 'sss', '2018-08-17 01:28:12', 'sss'),
(4, 'sss', '2018-08-17 01:29:30', 'sdf'),
(5, 'fablab', '2018-08-17 01:35:22', 'GF34FG23KKgJ'),
(6, 'sss', '2018-08-18 16:40:41', 'dsdsds'),
(7, 'dsdsdsdsd', '2018-08-18 16:40:52', '11111111111111'),
(8, 'ddd', '2018-08-18 16:41:02', 'dddd');

-- --------------------------------------------------------

--
-- Table structure for table `devices_network`
--

CREATE TABLE `devices_network` (
  `deviceId` int(11) NOT NULL,
  `device_IP_address` varchar(20) NOT NULL,
  `deviceCode` varchar(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `devices_network`
--

INSERT INTO `devices_network` (`deviceId`, `device_IP_address`, `deviceCode`, `date`) VALUES
(1, '192.168.11.203', 'GF34FG23KKgJ', '2018-08-17 15:06:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datas`
--
ALTER TABLE `datas`
  ADD PRIMARY KEY (`dat_id`);

--
-- Indexes for table `devices_list`
--
ALTER TABLE `devices_list`
  ADD PRIMARY KEY (`deviceId`),
  ADD UNIQUE KEY `DeviceCode` (`DeviceCode`);

--
-- Indexes for table `devices_network`
--
ALTER TABLE `devices_network`
  ADD PRIMARY KEY (`deviceId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datas`
--
ALTER TABLE `datas`
  MODIFY `dat_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `devices_list`
--
ALTER TABLE `devices_list`
  MODIFY `deviceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `devices_network`
--
ALTER TABLE `devices_network`
  MODIFY `deviceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
