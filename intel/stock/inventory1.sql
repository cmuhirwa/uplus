-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2018 at 11:39 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory1`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemId` bigint(20) NOT NULL,
  `itemName` varchar(50) NOT NULL,
  `kode` varchar(11) NOT NULL,
  `quantity` double NOT NULL,
  `unit` varchar(50) NOT NULL,
  `unityPrice` double NOT NULL,
  `inDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `addedBy` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemId`, `itemName`, `kode`, `quantity`, `unit`, `unityPrice`, `inDate`, `addedBy`) VALUES
(1, 'TEST', '123', 0, 'PC', 250, '2018-08-02 17:39:21', 'me'),
(2, 'PANADOR', '1234', 0, 'PC', 100, '2018-08-05 18:24:35', 'me');

-- --------------------------------------------------------

--
-- Stand-in structure for view `returnoninvestment`
-- (See below for the actual view)
--
CREATE TABLE `returnoninvestment` (
`transactionID` bigint(20)
,`doneOn` timestamp
,`operation` varchar(20)
,`itemCode` varchar(11)
,`itemName` varchar(50)
,`qty` decimal(20,5)
,`trUnityPrice` decimal(20,5)
,`PURCHASE_PRICE` decimal(20,5)
,`GAIN_UNIT` decimal(21,5)
,`GAIN_PER_OPERATION` decimal(41,10)
,`INVESTMENT` decimal(40,10)
);

-- --------------------------------------------------------

--
-- Table structure for table `serieid`
--

CREATE TABLE `serieid` (
  `serieID` bigint(50) NOT NULL,
  `serieDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userOn` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `serieid`
--

INSERT INTO `serieid` (`serieID`, `serieDate`, `userOn`) VALUES
(1, '2018-08-05 05:52:50', 'admin'),
(2, '2018-08-05 06:21:24', 'admin'),
(3, '2018-08-05 18:30:37', 'admin'),
(4, '2018-08-05 19:15:56', 'admin'),
(5, '2018-08-05 19:18:11', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transactionID` bigint(20) NOT NULL,
  `trUnityPrice` decimal(20,5) NOT NULL,
  `qty` decimal(20,5) NOT NULL,
  `itemCode` varchar(11) NOT NULL,
  `operation` varchar(20) NOT NULL,
  `purchaseOrder` varchar(50) NOT NULL,
  `deliverlyNote` varchar(50) NOT NULL,
  `docRefNumber` varchar(50) NOT NULL,
  `customerName` varchar(50) NOT NULL,
  `customerRef` varchar(50) NOT NULL,
  `operationNotes` varchar(300) NOT NULL,
  `operationStatus` int(2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `doneOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `doneBy` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`transactionID`, `trUnityPrice`, `qty`, `itemCode`, `operation`, `purchaseOrder`, `deliverlyNote`, `docRefNumber`, `customerName`, `customerRef`, `operationNotes`, `operationStatus`, `description`, `doneOn`, `doneBy`) VALUES
(1, '200.00000', '500.00000', '1', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 05:52:45', 'Administrator'),
(2, '250.00000', '5.00000', '1', 'Out', 'INV2018-2', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 05:52:58', 'Administrator'),
(3, '80.00000', '5000.00000', '2', 'In', 'PO123', 'DEV123', 'BK5893', 'FIXTEC', '25478644674', 'Hari ikarito twasubijeyo kuko yangiritse', 1, '', '2018-08-05 18:29:35', 'Administrator'),
(4, '100.00000', '100.00000', '2', 'Out', 'INV2018-4', 'Kigali, Nyarugenge KN 13', 'BK5389', 'KIFARMA', '0788302529', '', 1, '', '2018-08-05 18:31:42', 'Administrator'),
(5, '250.00000', '30.00000', '1', 'Out', 'INV2018-4', 'Kigali, Nyarugenge KN 13', 'BK5389', 'KIFARMA', '0788302529', '', 1, '', '2018-08-05 18:31:50', 'Administrator'),
(6, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:39', 'Administrator'),
(7, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:42', 'Administrator'),
(8, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:42', 'Administrator'),
(9, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:43', 'Administrator'),
(10, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:43', 'Administrator'),
(11, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:43', 'Administrator'),
(12, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:43', 'Administrator'),
(13, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:44', 'Administrator'),
(14, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:44', 'Administrator'),
(15, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:44', 'Administrator'),
(16, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:44', 'Administrator'),
(17, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:44', 'Administrator'),
(18, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:45', 'Administrator'),
(19, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:45', 'Administrator'),
(20, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:45', 'Administrator'),
(21, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:45', 'Administrator'),
(22, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:45', 'Administrator'),
(23, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:46', 'Administrator'),
(24, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:46', 'Administrator'),
(62, '1200.00000', '45.00000', '2', 'In', '4578', '782', '52fr', 'camerw', '125olg', '', 1, '', '2018-08-05 19:17:47', 'Administrator'),
(26, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:46', 'Administrator'),
(27, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:46', 'Administrator'),
(28, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:47', 'Administrator'),
(29, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:47', 'Administrator'),
(30, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:47', 'Administrator'),
(31, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:47', 'Administrator'),
(32, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:48', 'Administrator'),
(33, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:48', 'Administrator'),
(34, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:48', 'Administrator'),
(35, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:48', 'Administrator'),
(36, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:49', 'Administrator'),
(37, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:49', 'Administrator'),
(38, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:49', 'Administrator'),
(39, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:49', 'Administrator'),
(40, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:49', 'Administrator'),
(41, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:50', 'Administrator'),
(42, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:50', 'Administrator'),
(43, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:50', 'Administrator'),
(44, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:50', 'Administrator'),
(45, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:50', 'Administrator'),
(46, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:51', 'Administrator'),
(47, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:51', 'Administrator'),
(48, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:51', 'Administrator'),
(49, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:51', 'Administrator'),
(50, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:51', 'Administrator'),
(51, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:52', 'Administrator'),
(52, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:52', 'Administrator'),
(53, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:52', 'Administrator'),
(54, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:52', 'Administrator'),
(55, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:52', 'Administrator'),
(56, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:53', 'Administrator'),
(57, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:53', 'Administrator'),
(58, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:53', 'Administrator'),
(59, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:53', 'Administrator'),
(60, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:53', 'Administrator'),
(61, '1200.00000', '45.00000', '2', 'In', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '', 1, '', '2018-08-05 19:16:54', 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `loginId` varchar(100) NOT NULL,
  `pwd` varchar(250) NOT NULL,
  `names` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `residence` varchar(250) DEFAULT NULL,
  `workPlace` varchar(250) DEFAULT NULL,
  `account_type` enum('user','admin') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `loginId`, `pwd`, `names`, `phone`, `email`, `residence`, `workPlace`, `account_type`) VALUES
(1, 'admin', 'admin123', 'Administrator', '0000', 'admin@stock.rw', 'online', 'online', 'admin');

-- --------------------------------------------------------

--
-- Structure for view `returnoninvestment`
--
DROP TABLE IF EXISTS `returnoninvestment`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `returnoninvestment`  AS  select `t`.`transactionID` AS `transactionID`,`t`.`doneOn` AS `doneOn`,`t`.`operation` AS `operation`,`t`.`itemCode` AS `itemCode`,`i`.`itemName` AS `itemName`,`t`.`qty` AS `qty`,`t`.`trUnityPrice` AS `trUnityPrice`,(select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`)))))) AS `PURCHASE_PRICE`,(`t`.`trUnityPrice` - (select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`))))))) AS `GAIN_UNIT`,((`t`.`trUnityPrice` - (select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`))))))) * `t`.`qty`) AS `GAIN_PER_OPERATION`,((select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`)))))) * `t`.`qty`) AS `INVESTMENT` from (`transactions` `t` join `items` `i` on(((`t`.`itemCode` = `i`.`itemId`) and (`t`.`operation` = 'Out')))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemId`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `serieid`
--
ALTER TABLE `serieid`
  ADD PRIMARY KEY (`serieID`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transactionID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `loginId` (`loginId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `itemId` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `serieid`
--
ALTER TABLE `serieid`
  MODIFY `serieID` bigint(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transactionID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
