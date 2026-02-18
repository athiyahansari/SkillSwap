-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 12:13 PM
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
-- Database: `skillswap`
--

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `availability_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `day_of_week` enum('Mon','Tue','Wed','Thu','Fri','Sat','Sun') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`availability_id`, `profile_id`, `day_of_week`, `start_time`, `end_time`) VALUES
(1, 1, 'Mon', '10:00:00', '00:00:00'),
(2, 3, 'Sat', '09:00:00', '10:00:00'),
(3, 3, 'Sat', '10:00:00', '11:00:00'),
(4, 3, 'Sat', '11:00:00', '12:00:00'),
(5, 3, 'Sat', '12:00:00', '13:00:00'),
(6, 3, 'Mon', '05:30:00', '06:30:00'),
(7, 4, 'Sat', '08:00:00', '09:00:00'),
(8, 4, 'Sat', '09:00:00', '10:00:00'),
(9, 4, 'Sat', '10:00:00', '11:00:00'),
(10, 4, 'Sat', '11:00:00', '12:00:00'),
(11, 4, 'Wed', '14:00:00', '15:00:00'),
(12, 4, 'Wed', '15:00:00', '16:00:00'),
(13, 4, 'Fri', '14:00:00', '15:00:00'),
(14, 4, 'Fri', '15:00:00', '16:00:00'),
(15, 4, 'Mon', '16:00:00', '17:00:00'),
(16, 4, 'Mon', '17:00:00', '18:00:00'),
(17, 4, 'Fri', '16:00:00', '17:00:00'),
(18, 4, 'Fri', '17:00:00', '18:00:00'),
(19, 4, 'Tue', '18:00:00', '19:00:00'),
(20, 4, 'Tue', '19:00:00', '20:00:00'),
(21, 5, 'Sat', '08:00:00', '09:00:00'),
(22, 5, 'Sat', '09:00:00', '10:00:00'),
(23, 5, 'Wed', '10:00:00', '11:00:00'),
(24, 5, 'Wed', '11:00:00', '12:00:00'),
(25, 5, 'Fri', '10:00:00', '11:00:00'),
(26, 5, 'Fri', '11:00:00', '12:00:00'),
(27, 5, 'Sun', '10:00:00', '11:00:00'),
(28, 5, 'Sun', '11:00:00', '12:00:00'),
(29, 5, 'Mon', '14:00:00', '15:00:00'),
(30, 5, 'Mon', '15:00:00', '16:00:00'),
(31, 5, 'Fri', '16:00:00', '17:00:00'),
(32, 5, 'Fri', '17:00:00', '18:00:00'),
(33, 5, 'Tue', '18:00:00', '19:00:00'),
(34, 5, 'Tue', '19:00:00', '20:00:00'),
(35, 6, 'Thu', '08:00:00', '09:00:00'),
(36, 6, 'Thu', '09:00:00', '10:00:00'),
(37, 6, 'Mon', '10:00:00', '11:00:00'),
(38, 6, 'Mon', '11:00:00', '12:00:00'),
(39, 6, 'Fri', '10:00:00', '11:00:00'),
(40, 6, 'Fri', '11:00:00', '12:00:00'),
(41, 6, 'Sun', '10:00:00', '11:00:00'),
(42, 6, 'Sun', '11:00:00', '12:00:00'),
(43, 6, 'Tue', '14:00:00', '15:00:00'),
(44, 6, 'Tue', '15:00:00', '16:00:00'),
(45, 6, 'Wed', '14:00:00', '15:00:00'),
(46, 6, 'Wed', '15:00:00', '16:00:00'),
(47, 6, 'Sat', '14:00:00', '15:00:00'),
(48, 6, 'Sat', '15:00:00', '16:00:00'),
(49, 6, 'Sun', '16:00:00', '17:00:00'),
(50, 6, 'Sun', '17:00:00', '18:00:00'),
(51, 6, 'Thu', '18:00:00', '19:00:00'),
(52, 6, 'Thu', '19:00:00', '20:00:00'),
(53, 7, 'Wed', '08:00:00', '09:00:00'),
(54, 7, 'Wed', '09:00:00', '10:00:00'),
(55, 7, 'Fri', '08:00:00', '09:00:00'),
(56, 7, 'Fri', '09:00:00', '10:00:00'),
(57, 7, 'Tue', '10:00:00', '11:00:00'),
(58, 7, 'Tue', '11:00:00', '12:00:00'),
(59, 7, 'Sat', '10:00:00', '11:00:00'),
(60, 7, 'Sat', '11:00:00', '12:00:00'),
(61, 7, 'Mon', '12:00:00', '13:00:00'),
(62, 7, 'Mon', '13:00:00', '14:00:00'),
(63, 7, 'Sun', '12:00:00', '13:00:00'),
(64, 7, 'Sun', '13:00:00', '14:00:00'),
(65, 7, 'Tue', '14:00:00', '15:00:00'),
(66, 7, 'Tue', '15:00:00', '16:00:00'),
(67, 7, 'Sat', '14:00:00', '15:00:00'),
(68, 7, 'Sat', '15:00:00', '16:00:00'),
(69, 7, 'Wed', '16:00:00', '17:00:00'),
(70, 7, 'Wed', '17:00:00', '18:00:00'),
(71, 7, 'Fri', '16:00:00', '17:00:00'),
(72, 7, 'Fri', '17:00:00', '18:00:00'),
(73, 7, 'Thu', '18:00:00', '19:00:00'),
(74, 7, 'Thu', '19:00:00', '20:00:00'),
(75, 8, 'Mon', '10:00:00', '11:00:00'),
(76, 8, 'Mon', '11:00:00', '12:00:00'),
(77, 8, 'Sun', '10:00:00', '11:00:00'),
(78, 8, 'Sun', '11:00:00', '12:00:00'),
(79, 8, 'Sat', '12:00:00', '13:00:00'),
(80, 8, 'Sat', '13:00:00', '14:00:00'),
(81, 8, 'Tue', '14:00:00', '15:00:00'),
(82, 8, 'Tue', '15:00:00', '16:00:00'),
(83, 8, 'Thu', '14:00:00', '15:00:00'),
(84, 8, 'Thu', '15:00:00', '16:00:00'),
(85, 8, 'Sat', '16:00:00', '17:00:00'),
(86, 8, 'Sat', '17:00:00', '18:00:00'),
(87, 8, 'Wed', '18:00:00', '19:00:00'),
(88, 8, 'Wed', '19:00:00', '20:00:00'),
(89, 8, 'Fri', '18:00:00', '19:00:00'),
(90, 8, 'Fri', '19:00:00', '20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `tutor_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('pending','confirmed','cancelled','blocked') DEFAULT 'pending',
  `meeting_link` varchar(255) DEFAULT NULL,
  `cancelled_by_id` int(11) DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `student_id`, `tutor_id`, `subject_id`, `start_time`, `end_time`, `status`, `meeting_link`, `cancelled_by_id`, `cancellation_reason`) VALUES
(1, 2, 1, NULL, '2026-01-27 17:00:00', '2026-01-27 18:00:00', 'pending', NULL, NULL, NULL),
(2, 2, 1, NULL, '2026-01-26 10:00:00', '2026-01-26 11:00:00', 'pending', NULL, NULL, NULL),
(3, 2, 4, NULL, '2026-01-31 09:00:00', '2026-01-31 10:00:00', '', NULL, NULL, NULL),
(4, 6, 5, NULL, '2026-01-30 16:00:00', '2026-01-30 17:00:00', 'cancelled', NULL, NULL, NULL),
(5, 2, 1, NULL, '2026-02-02 10:00:00', '2026-02-02 11:00:00', 'confirmed', NULL, NULL, NULL),
(6, 12, 3, NULL, '2026-02-07 11:00:00', '2026-02-07 12:00:00', 'confirmed', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(10,2) NOT NULL COMMENT '10% Commission',
  `tutor_earnings` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `total_amount`, `platform_fee`, `tutor_earnings`, `payment_status`) VALUES
(1, 6, 15.00, 1.50, 13.50, ''),
(4, 5, 80.00, 8.00, 72.00, ''),
(5, 3, 60.00, 6.00, 54.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `booking_id`, `rating`, `comment`) VALUES
(1, 3, 4, 'it was funn');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tutorprofiles`
--

CREATE TABLE `tutorprofiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `bio` text DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `university_name` varchar(255) DEFAULT NULL,
  `payout_method` varchar(50) DEFAULT NULL,
  `payout_details` text DEFAULT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `transcript_provided` tinyint(4) DEFAULT 0,
  `subjects` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tutorprofiles`
--

INSERT INTO `tutorprofiles` (`profile_id`, `user_id`, `hourly_rate`, `bio`, `qualification`, `university_name`, `payout_method`, `payout_details`, `status`, `transcript_provided`, `subjects`) VALUES
(1, 3, 15.00, '', 'Bachelors', 'Stanford University', 'bank', 'bank name\r\nbank account\r\ncode', 'pending', 0, 'Calculus,Physics,Database'),
(3, 5, 29.59, 'i love teaching', 'PhD', 'Stanford University', 'paypal', 'jane3@gmail.com', 'verified', 1, 'Calculus,Database,computer science'),
(4, 7, 25.22, 'hi i am a teacher', 'Masters', 'Stanford University', 'paypal', 'mail', 'verified', 1, 'Calculus,Physics,Database'),
(5, 8, 55.30, 'im a teacherrrr!!!', 'Bachelors', 'Stanford University', 'bank', 'bank name\r\nbank number', 'rejected', 0, 'Calculus,Physics,Database'),
(6, 9, 30.90, 'IM A TUTORR', 'Bachelors', 'Staffordshire University', 'paypal', 'jkwknjwkd', 'pending', 0, 'Calculus,Physics,Database'),
(7, 13, 66.66, '', 'Masters', 'Stanford University', 'bank', 'yoohooo', 'pending', 1, 'Calculus,Physics,Database'),
(8, 14, 35.99, '', 'Bachelors', 'Stanford University', 'paypal', 'jane7', 'pending', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tutorsubjects`
--

CREATE TABLE `tutorsubjects` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `education_level` varchar(100) DEFAULT NULL,
  `subjects_interest` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `role` enum('learner','tutor','admin') NOT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `otp_code` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `phone`, `timezone`, `education_level`, `subjects_interest`, `password`, `reset_token`, `role`, `is_verified`, `otp_code`) VALUES
(1, '', 'admin@skillswap.com', NULL, NULL, NULL, NULL, '$2y$10$uEIRHchD/U5f1zJernzpteekOkNoMoNWYfFPyzvQQnRVhXkyw3z6W', NULL, 'admin', 1, NULL),
(2, 'Alex Johnson', 'alex@gmail.com', '+1 000 000 000', 'America/New_York', 'secondary', 'Mathematics,Physics,music', '$2y$10$j7ERGNMKqygHCRs6p3zjX.HdZGwDVAgsjQnW7nHl37HAe6xnGueG2', NULL, 'learner', 1, NULL),
(3, 'Jane Doe', 'jane@gmail.com', '', 'America/Indiana/Vincennes', NULL, NULL, '$2y$10$ZOo8LeQWAd.B2wghrXSeY.JRJU2MqS3xSFmlehrr5C4ErBpodnUV6', NULL, 'tutor', 1, NULL),
(4, 'Jane2 Doe', 'jane2@gmail.com', '', 'America/Vancouver', NULL, NULL, '$2y$10$VXDGa3s8CkuPq0er1BsBde55kn5ECcv9tqz.tJ.ug6urhQTqs/m5m', NULL, 'tutor', 1, NULL),
(5, 'Jane3 Doe', 'jane3@gmail.com', '', 'Asia/Seoul', NULL, NULL, '$2y$10$VTFkcQiTyMqo81/QxEDo8eCsfcDNCtROyOoq/PJFR9VK4DDpvW3qe', NULL, 'tutor', 1, NULL),
(6, 'Alex2 Johnson', 'alex2@gmail.com', '+1 000 000 000', 'Asia/Baku', 'high_school', 'Mathematics,Physics,art', '$2y$10$HkrCvkYvAR9mrhAY1CgaWOY5MsyoQELrnJbN3SNftemYA4XTWz1.y', NULL, 'learner', 1, NULL),
(7, 'Jane4 Doe', 'jane4@gmail.com', '', 'Asia/Jakarta', NULL, NULL, '$2y$10$YssLd1euQO/VZtnLE3sMhemz4wrT6Px1b/g/DIlNBP/cpruLrDPnW', NULL, 'tutor', 1, NULL),
(8, 'Jane5 Doe', 'jane5@gmail.com', '', 'Europe/Berlin', NULL, NULL, '$2y$10$Pv9u4N8EOVVRfeP.5EPuf..jXCcSQlK/tBJJJhGXhxMdhOfl72lie', NULL, 'tutor', 1, NULL),
(9, 'Sumaiya Ansari', 'sumaiya@gmail.com', '', 'America/Resolute', NULL, NULL, '$2y$10$o9YOzr9Eux8Eg/fFISL4aODnhhYwMWoYJty/rcAox4fB1PSVwTNcm', NULL, 'tutor', 1, NULL),
(12, 'Alex3 Johnson', 'alex3@gmail.com', '+1 000 000 000', 'Atlantic/Faroe', 'secondary', 'Mathematics,documenting', '$2y$10$LUuKtBqmmXXbLFHeqxPChunixMQInmRCr4VzZAil2lNKp0w1/6BjW', NULL, 'learner', 1, NULL),
(13, 'Jane6 Doe', 'jane6@gmail.com', '', 'Antarctica/DumontDUrville', NULL, NULL, '$2y$10$l8RnqEZdGuRuLQEUFaqIweASSm5ALKjT008uNohCzK5tMWUTZJRMu', NULL, 'tutor', 1, NULL),
(14, 'Jane7 Doe', 'jane7@gmail.com', '', '', NULL, NULL, '$2y$10$Y3ouuAar7VwcaGGF8qv58.0UbfWwFi9qGrlcTgx9BxACwH2f6G3nG', NULL, 'tutor', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `profile_id` (`profile_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `tutor_id` (`tutor_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `cancelled_by_id` (`cancelled_by_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_name` (`subject_name`);

--
-- Indexes for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `tutorsubjects`
--
ALTER TABLE `tutorsubjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `profile_id` (`profile_id`,`subject_id`),
  ADD KEY `subject_id` (`subject_id`);

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
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `availability_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tutorsubjects`
--
ALTER TABLE `tutorsubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availability`
--
ALTER TABLE `availability`
  ADD CONSTRAINT `availability_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `tutorprofiles` (`profile_id`) ON DELETE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`tutor_id`) REFERENCES `tutorprofiles` (`profile_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`cancelled_by_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `tutorprofiles`
--
ALTER TABLE `tutorprofiles`
  ADD CONSTRAINT `tutorprofiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tutorsubjects`
--
ALTER TABLE `tutorsubjects`
  ADD CONSTRAINT `tutorsubjects_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `tutorprofiles` (`profile_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tutorsubjects_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
