-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2016 at 12:40 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Structure for view `returnoninvestment`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `returnoninvestment`  AS  select `t`.`transactionID` AS `transactionID`,`t`.`doneOn` AS `doneOn`,`t`.`operation` AS `operation`,`t`.`itemCode` AS `itemCode`,`i`.`itemName` AS `itemName`,`t`.`qty` AS `qty`,`t`.`trUnityPrice` AS `trUnityPrice`,(select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`)))))) AS `PURCHASE_PRICE`,(`t`.`trUnityPrice` - (select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`))))))) AS `GAIN_UNIT`,((`t`.`trUnityPrice` - (select `p`.`trUnityPrice` from `transactions` `p` where ((`p`.`operation` = 'In') and (`p`.`itemCode` = `t`.`itemCode`) and (`p`.`transactionID` < `t`.`transactionID`) and (`p`.`transactionID` = (select max(`m`.`transactionID`) from `transactions` `m` where ((`m`.`operation` = 'In') and (`m`.`itemCode` = `p`.`itemCode`))))))) * `t`.`qty`) AS `GAIN_PER_OPERATION` from (`transactions` `t` join `items` `i` on(((`t`.`itemCode` = `i`.`itemId`) and (`t`.`operation` = 'Out')))) ;

--
-- VIEW  `returnoninvestment`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
