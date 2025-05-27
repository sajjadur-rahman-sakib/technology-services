-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 08:57 PM
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
(10, 'Sajjadur Rahman Sakib', 'Charfashion, Bhola', 1518652610, 'contact@sakib.tech', 'PC and Mac notebook service', 'Description about your problem', '2025-05-27 18:51:43', 'Delivered'),
(11, 'Salina Akter', 'Charfashion, Bhola', 1728367287, 'sakib.info.x@gmail.com', 'Mobile Phone services', 'Description about your problem', '2025-05-27 18:52:20', 'Serviceing'),
(12, 'Sofiqur Rahman', 'Charfashion, Bhola', 1717096830, 'sakib.x@icloud.com', 'Personal devices security', 'Description about your problem', '2025-05-27 18:52:52', 'In progress'),
(13, 'Sofiqur Rahman', 'Charfashion, Bhola', 1717096830, 'sakib.x@icloud.com', 'Personal devices security', 'Description about your problem', '2025-05-27 18:54:45', 'Order placed');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(34) NOT NULL,
  `stars` int(11) DEFAULT NULL CHECK (`stars` between 1 and 5),
  `review` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `stars`, `review`) VALUES
(11, 'Sajjadur Rahman Sakib', 5, 'Very well service'),
(12, 'Shafayet Hossain Chowdhury', 5, 'fast service'),
(13, 'Suaib bin humayun sazid', 5, 'service is good'),
(14, 'Abdur Rahman Riyad', 3, 'Good service'),
(15, 'sadman samz', 2, 'slow service'),
(16, 'suhail jaad', 4, 'faster response'),
(17, 'suhail jaad', 4, 'faster response'),
(18, 'suhail jaad', 4, 'faster response'),
(19, 'suhail jaad', 4, 'faster response');

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
