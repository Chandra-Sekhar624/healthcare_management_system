-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 09:41 AM
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
-- Database: `healthconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `reason` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:17:21'),
(2, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:23:36'),
(3, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:23:51'),
(4, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:28:42'),
(5, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:29:51'),
(6, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:29:55'),
(7, 'chandrasekhar Bera', 'chandra95@gmail.com', 'gdfgdfgdfgdf', 'fdgdfgdfgdfgdfgdfgdfgdf', '2025-05-24 01:30:11'),
(8, 'hjghj', 'aabc@gmail.com', 'yrty', 'rtyrtyrt', '2025-05-24 07:09:35'),
(9, 'subhas', 'chandra95@gmail.com', 'dasdgasdhgashj', 'asdasdasdas', '2025-05-24 12:37:16'),
(10, 'chandra', 'bera@gmail.com', 'gdfg', 'dfgdf', '2025-06-09 04:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `license_number` varchar(50) NOT NULL,
  `experience` int(3) NOT NULL,
  `bio` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `availability` text DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `name`, `specialty`, `license_number`, `experience`, `bio`, `education`, `availability`, `consultation_fee`, `rating`, `created_at`, `updated_at`, `approval_status`) VALUES
(1, 2, '', 'Cardiology', 'MED12345', 10, 'Dr. John Doe is a cardiologist with over 10 years of experience in treating heart-related conditions.', NULL, NULL, NULL, 0.00, '2025-05-23 00:53:41', '2025-05-26 09:45:15', 'approved'),
(3, 22, '', 'Endocrinology', 'ml256325', 5, 'yhgfhgf', NULL, NULL, NULL, 0.00, '2025-05-26 03:34:04', '2025-05-26 09:33:32', 'approved'),
(5, 25, '', 'Endocrinology', 'cvbcv', 5, 'cvbcvb', NULL, NULL, NULL, 0.00, '2025-05-26 09:41:58', '2025-05-26 09:45:08', 'approved'),
(6, 27, '', 'Dermatology', 'hjghjghj', 5, '', NULL, NULL, NULL, 0.00, '2025-05-26 10:05:59', '2025-05-27 07:17:27', 'rejected'),
(9, 30, '', 'Neurology', 'ml256325546456', 10, 'ghgfhgfh', NULL, NULL, NULL, 0.00, '2025-05-27 07:03:28', '2025-05-27 07:11:39', 'rejected'),
(10, 32, '', 'Endocrinology', 'hghj564', 5, 'hgjghj', NULL, NULL, NULL, 0.00, '2025-05-27 07:18:51', '2025-05-27 07:23:01', 'approved'),
(11, 38, 'ggg gg', 'Obstetrics and Gynecology', 'jghjghjghj', 5, 'ghjghj', NULL, NULL, NULL, 0.00, '2025-05-27 09:35:14', '2025-05-27 09:36:03', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedule`
--

CREATE TABLE `doctor_schedule` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `prescription` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `record_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('appointment','reminder','system') NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `related_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `related_id`, `created_at`) VALUES
(1, 1, 'New Doctor Registration', 'doc tor has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 03:34:04'),
(2, 21, 'New Doctor Registration', 'doc tor has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 03:34:04'),
(4, 1, 'New Doctor Registration', 'tyrtyrty rtyrtyrytr has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 03:35:38'),
(5, 21, 'New Doctor Registration', 'tyrtyrty rtyrtyrytr has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 03:35:38'),
(6, 1, 'New Doctor Registration', 'bbbbb dddd has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 09:41:58'),
(7, 21, 'New Doctor Registration', 'bbbbb dddd has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 09:41:58'),
(9, 1, 'New Doctor Registration', 'cccc cccccc has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 10:05:59'),
(10, 21, 'New Doctor Registration', 'cccc cccccc has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 10:05:59'),
(11, 1, 'New Doctor Registration', 'dddddddddd ddddddddd has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 12:27:35'),
(12, 21, 'New Doctor Registration', 'dddddddddd ddddddddd has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-26 12:27:35'),
(13, 1, 'New Doctor Registration', 'ggg ggg has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 07:03:28'),
(14, 21, 'New Doctor Registration', 'ggg ggg has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 07:03:28'),
(16, 1, 'New Doctor Registration', 'chandra hh has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 07:18:51'),
(17, 21, 'New Doctor Registration', 'chandra hh has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 07:18:51'),
(18, 1, 'New Doctor Registration', 'ggg gg has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 09:35:14'),
(19, 21, 'New Doctor Registration', 'ggg gg has registered as a doctor and is awaiting approval', 'system', 0, NULL, '2025-05-27 09:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `height` decimal(5,2) DEFAULT NULL COMMENT 'Height in centimeters',
  `weight` decimal(5,2) DEFAULT NULL COMMENT 'Weight in kilograms',
  `status` enum('active','inactive') DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `name`, `address`, `date_of_birth`, `gender`, `blood_group`, `allergies`, `medical_conditions`, `emergency_contact_name`, `emergency_contact_phone`, `created_at`, `updated_at`, `height`, `weight`, `status`, `medical_history`) VALUES
(15, 24, '', 'doctor1@example.com', '2016-06-01', 'male', NULL, NULL, NULL, NULL, NULL, '2025-05-26 09:41:07', '2025-05-27 14:39:23', NULL, NULL, 'active', NULL),
(16, 34, '', '546546', '2025-05-29', 'male', NULL, NULL, NULL, NULL, NULL, '2025-05-27 07:27:04', '2025-05-27 14:39:34', NULL, NULL, 'active', NULL),
(18, 39, '', 'dfdf', '2025-05-08', 'male', NULL, NULL, NULL, NULL, NULL, '2025-05-27 12:25:27', '2025-05-27 14:39:44', NULL, NULL, 'active', NULL),
(19, 40, '', 'fdsfdsf', '2025-05-15', 'female', NULL, NULL, NULL, NULL, NULL, '2025-05-27 12:26:10', '2025-05-27 14:39:52', NULL, NULL, 'active', NULL),
(20, 41, '', 'sads', '2025-05-29', 'male', NULL, NULL, NULL, NULL, NULL, '2025-05-27 12:26:59', '2025-05-27 14:40:03', NULL, NULL, 'active', NULL),
(21, 42, '', 'gdfg', '2025-06-12', 'male', NULL, NULL, NULL, NULL, NULL, '2025-06-09 07:13:19', '2025-06-09 07:13:19', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL,
  `medical_record_id` int(11) NOT NULL,
  `medication_name` varchar(100) NOT NULL,
  `dosage` varchar(50) NOT NULL,
  `frequency` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_type` enum('admin','doctor','patient') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `profile_image`, `email`, `password`, `phone`, `user_type`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Admin', 'User', '', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567890', 'admin', '2025-05-23 00:53:41', '2025-05-23 00:53:41', 'active'),
(2, 'John', 'Doe', '', 'doctor@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1234567891', 'doctor', '2025-05-23 00:53:41', '2025-05-23 00:53:41', 'active'),
(21, 'Admin', 'User', '', 'admin@healthconnect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 'admin', '2025-05-26 03:30:07', '2025-05-26 03:30:07', 'active'),
(22, 'doc', 'tor', '', 'doctor1@example.com', '$2y$10$h1woT.f1rWIXhmoShakC1ekmS0ecQCfwbB2CMbbBHfNfromBdn27q', '959356879', 'doctor', '2025-05-26 03:34:04', '2025-05-26 03:34:04', 'active'),
(24, 'aaaaaaaaaa', 'aaaaaaaaaaaa', '', 'aa@gmail.com', '$2y$10$iV4M9q3d2itF3eu3g3nR1.2awWfckXhRvOLJtB3e7B1.j61NyjJRO', '959356879', 'patient', '2025-05-26 09:41:07', '2025-05-26 09:41:07', 'active'),
(25, 'bbbbb', 'dddd', '', 'bbbbb@gmail.com', '$2y$10$brV3tPdW6spj10BJ.GcZW.fju6nJ1dNXEHSrK2vuYWaVNAmFbyAca', '95931678855', 'doctor', '2025-05-26 09:41:58', '2025-05-26 09:41:58', 'active'),
(27, 'cccc', 'cccccc', '', 'c@gmail.com', '$2y$10$bSrk05Envl/siUcCF2tA5uCsm1zSS8UrEwcJ1etqIABOZygHprvRO', '6456546', 'doctor', '2025-05-26 10:05:59', '2025-05-26 10:05:59', 'active'),
(30, 'ggg', 'ggg', '', 'ggg@gmail.com', '$2y$10$xK/fcqg9Pr7P.aYYRHuZPegCD5.fL.3/0kLG3DWC.wJrQmSIuLybW', '7362953659', 'doctor', '2025-05-27 07:03:28', '2025-05-27 07:03:28', 'active'),
(32, 'chandra', 'hh', '', 'cbdr@gmail.com', '$2y$10$RxZDqQcmkqqPqciyAf7OdepAVTsp38fD.xypQiGjs7qP0i0o6hCDa', '7362958654', 'doctor', '2025-05-27 07:18:51', '2025-05-27 07:18:51', 'active'),
(34, 'aaaa', 'aaaa', '', 'aaaaaa@gmail.com', '$2y$10$Bt6T2M8MUTObF9YmgFT.5.Mo0NpMmVBjWCCqH6f6ClLOtDx91/7hC', '5645654645', 'patient', '2025-05-27 07:27:04', '2025-05-27 07:27:04', 'active'),
(38, 'ggg', 'gg', '', 'gg@gmail.com', '$2y$10$yqfv7oKZ3lXX0IO3Z/YNBOV00UkNwifXhUNaZ0NYvRtddoivzQY9e', '9856321456', 'doctor', '2025-05-27 09:35:14', '2025-05-27 09:35:14', 'active'),
(39, 'ph', 'ph', '', 'ph@gmail.com', '$2y$10$EptfyMMTBwayGyUoYD7/EeYgjh2KML5PKdtVQNMRi1cYyudoicIEC', '54545', 'patient', '2025-05-27 12:25:27', '2025-05-27 12:25:27', 'active'),
(40, 'pi', 'pi', '', 'pi@gmail.com', '$2y$10$x7lXHugvQO.I8SuO1oTbRe2Pd/Rxq.VCkr9tMo73mUQj35N6rEv8m', '900000000', 'patient', '2025-05-27 12:26:10', '2025-05-27 12:26:10', 'active'),
(41, 'pj', 'pj', '', 'pj@gmail.com', '$2y$10$vFRKkkQFwQ/EA/Cj45vIPueWFMsSTpp7WPZZJEX/Y1xps3Nyb81sC', '9000000', 'patient', '2025-05-27 12:26:59', '2025-05-27 12:26:59', 'active'),
(42, 'patient', '.', 'profile_42_1749454797.png', 'patient@example.com', '$2y$10$Y8o31eE1HnvOPbp.cN8FtOiTq1sUmCH.rVVOdhTC1tXG51UolIkM2', '959312526', 'patient', '2025-06-09 07:13:18', '2025-06-09 07:39:57', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_number` (`license_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medical_record_id` (`medical_record_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointment_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctor_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedule`
--
ALTER TABLE `doctor_schedule`
  ADD CONSTRAINT `doctor_schedule_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `record_appointment_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `record_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `record_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notification_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patient_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescription_record_fk` FOREIGN KEY (`medical_record_id`) REFERENCES `medical_records` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `review_appointment_fk` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `review_doctor_fk` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_patient_fk` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
