-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 11:56 AM
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
-- Database: `telemedicine`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login`
--

CREATE TABLE `admin_login` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login`
--

INSERT INTO `admin_login` (`id`, `name`, `email`, `password`) VALUES
(1, 'Raihan', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `qualification` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `course_id` int(100) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `resume` varchar(255) NOT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `schedule` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `name`, `email`, `password`, `qualification`, `experience`, `course_id`, `picture`, `address`, `mobile`, `fee`, `resume`, `specialty`, `availability`, `schedule`) VALUES
(7, 'DR Usman', 'usman@gmail.com', '123', 'MBBS', '5 years', 0, '02c4c5b718c7e55c276e11d019b4a06c.jpg', 'Mian Multan road, Lahore', '031344465734', 1500.00, '', 'Dermatologist', 'Monday-Friday', '09:00 AM to 05:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--

CREATE TABLE `enroll` (
  `eid` int(11) NOT NULL,
  `sid` int(255) NOT NULL,
  `doctorid` int(255) NOT NULL,
  `status` varchar(20) DEFAULT 'approved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enroll`
--

INSERT INTO `enroll` (`eid`, `sid`, `doctorid`, `status`, `created_at`) VALUES
(23, 3, 7, 'approved', '2026-03-25 10:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `fid` int(11) NOT NULL,
  `doctorid` int(11) NOT NULL,
  `patientid` int(11) NOT NULL,
  `rating` int(11) DEFAULT 5,
  `message` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`fid`, `doctorid`, `patientid`, `rating`, `message`, `created_at`, `updated_at`) VALUES
(3, 7, 3, 5, 'good doctor', '2026-03-25 10:52:21', '2026-03-25 10:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`) VALUES
(46, 7, 3, 'hi', '2026-03-25 10:50:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `nid` int(11) NOT NULL,
  `doctorid` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `patient_email` varchar(255) DEFAULT NULL,
  `patient_phone` varchar(20) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`nid`, `doctorid`, `eid`, `patient_name`, `patient_email`, `patient_phone`, `message`, `type`, `is_read`, `created_at`) VALUES
(7, 7, 23, 'aahil', 'aahil@gmail.com', '03134556566', 'New appointment from patient aahil', 'new_appointment', 0, '2026-03-25 10:11:54');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `sid` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`sid`, `name`, `email`, `password`, `phone`, `age`, `gender`, `contact`, `medical_history`) VALUES
(3, 'aahil', 'aahil@gmail.com', '123', '03134556566', 24, 'Male', 'Lahore', 'allergy');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `pid` int(11) NOT NULL,
  `doctorid` int(11) NOT NULL,
  `patientid` int(11) NOT NULL,
  `medicine` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `frequency` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescriptions`
--

INSERT INTO `prescriptions` (`pid`, `doctorid`, `patientid`, `medicine`, `dosage`, `duration`, `frequency`, `instructions`, `created_at`, `updated_at`) VALUES
(4, 7, 3, 'panadol', '500 mg', '7 days', 'Once daily', 'take with meel', '2026-03-25 10:50:29', '2026-03-25 10:50:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_login`
--
ALTER TABLE `admin_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enroll`
--
ALTER TABLE `enroll`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `idx_doctor` (`doctorid`),
  ADD KEY `idx_patient` (`patientid`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `eid` (`eid`),
  ADD KEY `idx_doctor` (`doctorid`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `idx_doctor_patient` (`doctorid`,`patientid`),
  ADD KEY `idx_patient` (`patientid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_login`
--
ALTER TABLE `admin_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enroll`
--
ALTER TABLE `enroll`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `nid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`patientid`) REFERENCES `patient` (`sid`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`eid`) REFERENCES `enroll` (`eid`) ON DELETE CASCADE;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_ibfk_1` FOREIGN KEY (`doctorid`) REFERENCES `doctor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_ibfk_2` FOREIGN KEY (`patientid`) REFERENCES `patient` (`sid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
