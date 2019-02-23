-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Feb 23, 2019 at 06:49 PM
-- Server version: 5.6.34-log
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projet`
--

-- --------------------------------------------------------

--
-- Table structure for table `chatrooms`
--

CREATE TABLE `chatrooms` (
  `name` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `creator` int(45) NOT NULL,
  `ID` int(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chatrooms`
--

INSERT INTO `chatrooms` (`name`, `creator`, `ID`) VALUES
('Machatroom', 13, 11),
('ok', 13, 12);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `chatroom` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT 'Anonyme'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `content`, `chatroom`, `username`) VALUES
(47, 13, 'Mon message', '11', 'Leo VdW'),
(48, 13, 'aeae', '12', 'Leo VdW');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `login` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(80) DEFAULT NULL,
  `lastname` varchar(80) DEFAULT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `created`, `modified`, `login`, `password`, `firstname`, `lastname`, `token`) VALUES
(1, '2019-02-15 09:08:46', '2019-02-15 09:08:46', 'test', '$2y$10$gTJcM/ayHMMSvpAOhFzCXO256KYEBNl98mc1hx', 'lepuimhonùk', 'ouhinmo', ''),
(2, '2019-02-15 14:41:00', '2019-02-15 14:41:00', 'mako', '$2y$10$uFvco7e88ocl2iLokX0wpuKjGqdP0gydm8j1hF', 'leo', 'vdw', ''),
(3, '2019-02-21 14:46:14', '2019-02-21 14:46:14', 'atestuser', '$2y$10$7o3QGEyLValE34aaenB55OXvR7dvEKW4tznlNt', 'leo', 'leo', ''),
(4, '2019-02-21 15:05:33', '2019-02-21 15:05:33', 'atestusersecond', '$2y$10$4wRYZ8RZXpLU9xYcTfMpoeGMqUlr0SJZlFJrz3', 'xv', 'xv', ''),
(5, '2019-02-22 08:25:47', '2019-02-22 08:25:47', 'atestsecond', '$2y$10$kRYCLiv3w8pOGAxhts3osONt1ddp0xQxwqX3GSXNI/A/cXq5zdpcG', 'xozeprojep', 'azoirjozjrozejrz', ''),
(6, '2019-02-22 08:26:49', '2019-02-22 08:26:49', 'atestusertest', '$2y$10$Akb./9j8aVNMUkTFBb29t.FYhn6DLEC9byg8Rn8NggUNI8uK9CVCe', 'x', 'x', ''),
(7, '2019-02-22 13:08:01', '2019-02-22 13:08:01', 'testname', '$2y$10$jPfIPrmFGVvLPodPxyDbzuQmgPd4EYffOHJ/KrZsTCnCXFQ1e15T.', 'name', NULL, ''),
(8, '2019-02-22 13:10:11', '2019-02-22 13:10:11', 'anewtestuser', '$2y$10$4VgIKCGzZDdmviYeqBmXwOXQCBuMNtMiKU/3U2t9rIh7/F2yp3CTG', 'MyName', NULL, ''),
(9, '2019-02-22 13:12:12', '2019-02-22 13:12:12', 'mylogin', '$2y$10$lJXpRuUbWwlQYs6RozwRpuMHu8RCLyAcyOP5eP9YEGqUYrXVo7cWa', 'mynewname', NULL, '1ea66a40ebe80158'),
(10, '2019-02-23 11:23:07', '2019-02-23 11:23:07', 'Mycoollogin', '$2y$10$HDI6QeU65DfYpWA.0y.e1Ooh6ndNBE58eBV85pJ1iMh9CRNH18BK.', 'myname', NULL, 'd7be31da150ec1f0'),
(11, '2019-02-23 13:40:25', '2019-02-23 13:40:25', 'MyNewacc', '$2y$10$xF0KObwc5wNhD7nTkMXKIuKjazB3XZ5nUkrr9CHnrQ75YurI3hWPy', 'myname', NULL, ''),
(12, '2019-02-23 13:41:48', '2019-02-23 13:41:48', 'MyNewAccount', '$2y$10$BYCktOMGMK63mDKD/b.P.uwE4f..of7Dy7554W7k4YPFoZqB5wBSC', 'Leo2', NULL, '78eefd2a7ccdcd04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chatrooms`
--
ALTER TABLE `chatrooms`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chatrooms`
--
ALTER TABLE `chatrooms`
  MODIFY `ID` int(45) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
