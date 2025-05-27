-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 07:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sakib`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(0, 'admin', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(100) NOT NULL,
  `name` varchar(32) NOT NULL,
  `address` varchar(50) NOT NULL,
  `phone` int(14) NOT NULL,
  `email` varchar(38) NOT NULL,
  `problem` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `tracking` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `name`, `address`, `phone`, `email`, `problem`, `description`, `submission_time`, `tracking`) VALUES
(1, 'Sajjadur Rahman Sakib', 'Charfashion, Bhola', 1518652610, 'sajjadur.rahman.sakib.x@gmail.com', 'PC and Mac notebook service', 'sakib', '2024-02-07 19:41:11', 'In progress'),
(2, 'Sajjadur Rahman Sakib', 'Charfashion, Bhola', 1518652610, 'sajjadur.rahman.sakib.x@gmail.com', 'Personal devices security', 'PSTU', '2024-02-07 19:42:11', 'Order placed'),
(3, 'Sajjadur Rahman Sakib', 'Charfashion, Bhola', 1518652610, 'sajjadur.rahman.sakib.x@gmail.com', 'Personal devices security', 'PSTU', '2024-02-07 19:50:33', 'Delivered'),
(4, 'Shorna Naima', 'Patuakhali', 1314638174, 'shorna@gmail.com', 'Smart Watche services', 'very big problem', '2024-02-07 19:51:44', 'Order placed'),
(5, 'Sofiqur Rahman Babul', 'Charfashion', 1717096830, 'babul@gmail.com', 'Personal devices security', 'abcdef', '2024-02-07 19:52:17', 'Ready for delivery'),
(6, 'Salina Akter', 'Monpura', 1728367287, 'salina@gmail.com', 'Mobile Phone services', 'abcdefgh', '2024-02-07 19:53:37', 'Delivered');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
