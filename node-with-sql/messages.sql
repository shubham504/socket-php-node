-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 14, 2024 at 09:37 PM
-- Server version: 8.0.37-0ubuntu0.20.04.3
-- PHP Version: 7.4.3-4ubuntu2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `user` varchar(50) DEFAULT NULL,
  `message` text,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user`, `message`, `timestamp`) VALUES
(12, 'hi', 'hi', '2024-08-11 16:12:48'),
(13, 'ok', 'ok', '2024-08-11 16:13:01'),
(14, 'xxx', 'hi', '2024-08-12 16:40:21'),
(15, 'www', 'ji', '2024-08-12 16:40:32'),
(16, 'xxx', 'hi', '2024-08-12 17:12:22'),
(17, 'xxx', 'kk', '2024-08-12 17:13:05'),
(18, 'ko', 'lo', '2024-08-12 17:30:17'),
(19, 'ko', 'lolo', '2024-08-12 17:31:36'),
(20, 'ko', 'lolo ji', '2024-08-12 17:35:19'),
(21, 'jj', '👍', '2024-08-13 14:58:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
