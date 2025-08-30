-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2025 at 07:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ceirs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `authorities`
--

CREATE TABLE `authorities` (
  `authority_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) NOT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authorities`
--

INSERT INTO `authorities` (`authority_id`, `name`, `department`, `contact_email`, `contact_phone`, `email`, `password`) VALUES
(4, 'campus security', 'Security Dept', 'soultrinity91@gmail.com', '07056374443', 'soultrinity91@gmail.com', '$2y$10$OnLKLsL4EaU0Kgq2rLT83eaCQ2PP7hunKftJTFp8fwL.kF8wfTfH6');

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` int(11) NOT NULL,
  `incident_code` varchar(20) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `incident_type` enum('fire','medical','security','accident') NOT NULL,
  `description` text NOT NULL,
  `location` varchar(150) NOT NULL,
  `media` varchar(255) DEFAULT NULL,
  `status` enum('pending','responding','resolved') DEFAULT 'pending',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incidents`
--

INSERT INTO `incidents` (`incident_id`, `incident_code`, `user_id`, `incident_type`, `description`, `location`, `media`, `status`, `reported_at`) VALUES
(1, NULL, 2, 'fire', 'There is a fire accident.', 'Computer Science', 'uploads/1756418599_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'pending', '2025-08-28 22:03:19'),
(2, NULL, 1, 'medical', 'A student fainted in the building', 'Computer Science', 'uploads/1756418691_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'pending', '2025-08-28 22:04:51'),
(3, NULL, 1, 'fire', 'New report', 'Computer Science', NULL, 'pending', '2025-08-28 22:48:46'),
(4, NULL, 1, 'fire', 'New incident2', 'Computer Science', 'uploads/1756423039_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'pending', '2025-08-28 23:17:19'),
(5, NULL, 1, 'security', 'New security Incident', 'Computer Science', 'uploads/1756423360_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'responding', '2025-08-28 23:22:40'),
(6, NULL, 1, 'fire', 'New incident', 'Computer Science', NULL, 'pending', '2025-08-28 23:32:56'),
(7, NULL, 1, 'fire', 'New incident fire', 'Computer Science', NULL, 'pending', '2025-08-28 23:34:38'),
(8, NULL, 1, 'medical', 'new', 'Computer Science', NULL, 'pending', '2025-08-28 23:39:43'),
(9, NULL, 1, 'fire', 'new', 'Computer Science', NULL, 'pending', '2025-08-28 23:50:38'),
(10, NULL, 1, 'fire', 'there is a fire accident in the building', 'Computer Science', NULL, 'pending', '2025-08-28 23:51:13'),
(11, NULL, 1, 'accident', 'accident', 'Computer Science', 'uploads/1756425097_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'pending', '2025-08-28 23:51:37'),
(12, NULL, 2, 'medical', 'Medical report', 'Computer Science', 'uploads/1756425462_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'pending', '2025-08-28 23:57:42'),
(13, NULL, 2, 'medical', 'New medical', 'Computer Science', NULL, 'resolved', '2025-08-29 00:10:06'),
(14, NULL, 2, 'accident', 'accident report', 'Computer Science', NULL, 'responding', '2025-08-29 04:09:34'),
(15, NULL, 3, 'security', 'new security issues', 'mass com', 'uploads/1756453790_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'resolved', '2025-08-29 07:49:50'),
(16, 'AAA-2025-001', 2, 'fire', 'new', 'Computer Science', NULL, 'pending', '2025-08-29 09:28:43'),
(17, 'INC-2025-001', 3, 'fire', 'new accident', 'Computer Science', NULL, 'pending', '2025-08-29 09:41:13'),
(18, 'INC-2025-002', 3, 'medical', 'need support', 'Computer Science', NULL, 'pending', '2025-08-29 09:42:08'),
(19, 'INC-2025-003', 2, 'fire', 'The building opposite is on fire', 'Building tech', 'uploads/1756461248_9d1f060d-6762-4d03-8e61-3e2f01dc4b2f.jpeg', 'resolved', '2025-08-29 09:54:08'),
(20, 'INC-2025-004', 2, 'medical', 'Need an ambulance right away', 'Computer Science', NULL, 'responding', '2025-08-29 09:54:41'),
(21, 'INC-2025-005', 3, 'security', 'There are group of students fighting in front of me', 'Mass com', NULL, 'pending', '2025-08-29 09:55:33'),
(22, 'INC-2025-006', 3, 'fire', 'FIRE IN LUSADA', 'Computer Science', NULL, 'pending', '2025-08-29 12:22:07'),
(23, 'INC-2025-007', 2, 'fire', 'A student was smoking very close to a gas in the department. Right now the building is on fire', 'Computer Science lecture room', 'uploads/1756563204_Ilorin-School-Fire.jpg', 'resolved', '2025-08-30 14:13:24'),
(24, 'INC-2025-008', 3, 'medical', 'We need ambulance immediately!', 'Computer Science', 'uploads/1756574175_medical.jpg', 'responding', '2025-08-30 17:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `authority_id` int(11) NOT NULL,
  `channel` enum('email','sms','in-app') DEFAULT 'email',
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('student','staff','security','admin') DEFAULT 'student',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `role`, `password`, `created_at`) VALUES
(1, 'Olawale Komolafe', 'walekomolafe17@gmail.com', '07056374443', 'staff', '$2y$10$CBkdY.iGsUndAfxueu/6f.WH7IbWRLx6RYP/.0jpaU.Cr3QGg7pWS', '2025-08-28 21:38:16'),
(2, 'Olawale Komolafe', 'komolafeolawale2020@gmail.com', '07056374443', 'student', '$2y$10$yRpeEiyXyFa/ePfpOlqp4uI9tHQxznt4bt74GdL.Z/XxGN572MyJe', '2025-08-28 21:39:21'),
(3, 'Adams James', 'adams@gmail.com', '07056374443', 'student', '$2y$10$BK4N52h0eVXTYCOwPw4B0.FbkTB1i8J4t3PBFwL0c0egB7d6S6y4u', '2025-08-29 07:48:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authorities`
--
ALTER TABLE `authorities`
  ADD PRIMARY KEY (`authority_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD UNIQUE KEY `incident_code` (`incident_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `incident_id` (`incident_id`),
  ADD KEY `authority_id` (`authority_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authorities`
--
ALTER TABLE `authorities`
  MODIFY `authority_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`authority_id`) REFERENCES `authorities` (`authority_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
