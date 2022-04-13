-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 13, 2022 at 11:15 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safeboda`
--

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL,
  `amount` int(11) NOT NULL,
  `is_active` tinyint(4) NOT NULL,
  `code` varchar(190) NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `radius` int(11) NOT NULL,
  `name` varchar(190) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `exp_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `type`, `amount`, `is_active`, `code`, `longitude`, `latitude`, `radius`, `name`, `details`, `exp_date`) VALUES
(8, 'fixed', 15, 1, 'd3b0c5b0ce', 12.258, 36.59845, 14, 'Promo Name', 'Alot Of Talk and details bla bla blaaaa bla lbalbla lbaaaaa blah', '2022-12-12'),
(9, 'fixed', 15, 1, '493bc5f961', 12.258, 36.59845, 3, 'Promo Name', '852085208528525252', '2022-12-12'),
(10, 'fixed', 15, 1, 'fcb9f7978f', 12.258, 36.59845, 25, 'Promo Name', '852085208528525252', '2022-12-12'),
(11, 'percentage', 15, 0, 'c08a85a393', 12.258, 36.59845, 26, 'Promo Name', 'Alot Of Talk and details bla bla blaaaa bla lbalbla lbaaaaa blah', '2022-12-12'),
(12, 'percentage', 0, 1, '52f5ef94d5', 12.258, 36.59845, 12, 'Promo Name', '852085208528525252', '2022-12-12'),
(13, 'percentage', 59, 0, '5adbfee462', 12.258, 36.59845, 26, 'Promo Name', 'Alot Of Talk and details bla bla blaaaa bla lbalbla lbaaaaa blah', '2022-12-12'),
(14, 'percentage', 10, 1, '92b71c2524', 12.258, 36.59845, 7, 'Promo Name', '852085208528525252', '2022-12-12'),
(15, 'percentage', 35, 1, '25d522de4c', 33.258, 36.59845, 5, 'Promo Name2', 'Alot Of Talk and details bla bla blaaaa bla lbalbla lbaaaaa blah', '2022-12-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
