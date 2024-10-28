-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 02:59 PM
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
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('hod','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `role`) VALUES
(1, 'h1', '$2y$10$PbKs.b5VwN8BEsce1p3Q6uVpyPB4J0zaD9XWbGLbV39DmP1vLrW2q', 'hod'),
(2, 'a1', '$2y$10$acWpQsD8AFu5uvKWDX5ueuJmAfHGNt.ehygtj4aVOMlIMikE8.ImS', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `approved_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `photo`, `status`, `approved_by`) VALUES
(1, 'Monojit Upadhayay', 'hardc6700@gmail.com', '$2y$10$zSUA7q5zUX52H/P3Qu/saO4fcW8erOy96cY1HXg3eNTThM100vhwO', '69.jpg', 'approved', 'Head of The Department'),
(4, 'Shreyasree Saha', 'shreyasreesaha4@gmail.com', '$2y$10$1I6.AJfYSpfqoKkSPM536uNqf6Z1v43MwOwabZXCbdo.EWD7tLkYG', 'ss.jpeg', 'approved', 'Administrator'),
(5, 'Alok Guha Roy', 'guhaalok19@gmail.com', '$2y$10$lO7YFpmhVHSQfbI6zZtfO.k9ewKsLyN30o4XfE2Odmo4uTrqBucCu', 'IMG_8183.JPG', 'approved', 'Head of The Department'),
(6, 'Kalyan Mahato', 'km@ac.in', '$2y$10$F/pcwjCmB5KPJ6c2QdBL2e5MF26ulMdgC3XN6zVz8/bWos3U9oIYO', 'IMG-20240831-WA0105.jpg', 'approved', 'Head of The Department'),
(7, 'Diganta Biswas ', 'db@ac.com', '$2y$10$oFCxcku6K8vLjDKFsL6wfOC3jRWlEDhJeUYD7ImlvpStudEYJqFMO', 'IMG_20241027_180016.jpg', 'approved', 'Head of The Department'),
(8, 'Subham Mandal', 'sm@ac.in', '$2y$10$ulekFcThCCBW.TGyt6LztemZbIaadHLCfP2caQnZTaMsDoi027tZS', 'KK.jpeg', 'approved', 'Head of The Department'),
(9, 'Gokul Kumar Saha', 'gks@gmail.com', '$2y$10$WeJSfrQ1GxsDyxwlGhhodug7il0NLKfqe2BUQuXKhCyIRrhtDyrue', 'square.jpeg', 'approved', 'Head of The Department');

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
