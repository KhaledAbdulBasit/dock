-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 06:47 PM
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
-- Database: `dock`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointments_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `Booking` enum('Available','Closed') DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `Service` enum('Consultation','Examination','X-ray','Blood test','Comprehensive examination','Follow-up','Operation') DEFAULT NULL,
  `department` enum('Cardiology Department','Pediatrics Department','Orthopedic Department','Department of Internal Medicine','Department of Surgery','Department of Obstetrics and Gynecology','Neurology Department','Department of Urology','Department of Ear, Nose and Throat','Ophthalmology Department') DEFAULT NULL,
  `Hospital_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `consultation_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone_num` int(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `specialty` varchar(25) DEFAULT NULL,
  `consultation_type` varchar(25) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `doctor_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`consultation_id`, `name`, `phone_num`, `email`, `specialty`, `consultation_type`, `date`, `created_at`, `doctor_id`) VALUES
(1, 'Mostafa kamal kamal', 1021493365, '01203728710mm@gmail.com', 'cardiology', 'Online consultation', '2025-04-24 00:00:00', '2025-04-29 18:44:40', NULL),
(2, 'Mostafa kamal kamal', 1021493365, '01203728710mm@gmail.com', 'cardiology', 'Online consultation', '2025-04-24 00:00:00', '2025-04-29 18:57:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `Specialization` varchar(50) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Hospital_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Education` text DEFAULT NULL,
  `ExperienceYears` varchar(20) DEFAULT NULL,
  `working hours` varchar(100) DEFAULT NULL,
  `Languages` text DEFAULT NULL,
  `Hospital_name` varchar(100) DEFAULT NULL,
  `Birthday` date DEFAULT NULL,
  `Account Type` varchar(100) DEFAULT NULL,
  `Confirm Password` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `name`, `Specialization`, `phone`, `email`, `password`, `Hospital_id`, `image`, `created_at`, `updated_at`, `Education`, `ExperienceYears`, `working hours`, `Languages`, `Hospital_name`, `Birthday`, `Account Type`, `Confirm Password`, `department_id`) VALUES
(13, 'مصطفى كمال كامل سيد ', NULL, NULL, '01203728710mmxxrr@gmail.com', '$2y$10$EAqKbCoNM5aiHVaaGqAoI.W7J./tlR3F4nhLefck3g9JLCedbgQhu', NULL, NULL, '2025-04-29 18:08:00', '2025-04-29 18:08:00', NULL, NULL, NULL, NULL, NULL, '2025-04-02', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `Hospital_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `business_ hours` varchar(100) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `image` varchar(255) DEFAULT "img/host.jpg",
  `Confirm Password` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`Hospital_id`, `name`, `email`, `birthday`, `password`, `location`, `created_at`, `updated_at`, `business_ hours`, `services`, `image`, `Confirm Password`) VALUES
(1, 'ghjhgjhj', 'ym179@gmail.com', '2025-05-09', '$2y$10$mTmfEBkf0q6mzUXIteP6Dueh2oT3KoMv0324.9Mo/kze9JN0xPNt.', '', '2025-04-25 21:01:24', '2025-04-25 21:01:24', '', '', '', 0),
(2, 'ghjhgjhj', 'ym17978@gmail.com', '2025-04-26', '$2y$10$mVt463rhvufENxx4rkdw1OjhOIa6pBPYjuZyc4adSm.ehtGw9XD5G', '', '2025-04-25 21:16:03', '2025-04-25 21:16:03', '', '', '', 0),
(3, 'ghjkjk', 'ym1799400@gmail.com', '2025-04-11', '$2y$10$encu0.eLhPbt2igPzwwxz.BVDlNyIRiksEh//fB9sR6pEORlgB/Oy', '', '2025-04-25 23:50:47', '2025-04-25 23:50:47', '', '', '', 0),
(5, 'مصطفى كمال كامل سيد ', '01203728710mmxx@gmail.com', '2025-04-02', '$2y$10$.AGCq32oabPMfH9ExR1iceSK7HscBXVwzEY8MOJJeXiNzAqmLFPwW', NULL, '2025-04-29 18:07:15', '2025-04-29 18:07:15', NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `medical _id` int(11) NOT NULL,
  `visit_date` datetime DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `treatment` text DEFAULT NULL,
  `doctor_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `SSN` int(11) DEFAULT NULL,
  `Gender` enum('Male','Female','Other') DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `Address` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `Medical` varchar(50) DEFAULT NULL,
  `History` varchar(50) DEFAULT NULL,
  `Doctor_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `blood_type` varchar(10) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `Confirm Password` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `name`, `SSN`, `Gender`, `phone`, `Email`, `birthday`, `Address`, `password`, `Medical`, `History`, `Doctor_id`, `image`, `blood_type`, `height`, `weight`, `created_at`, `updated_at`, `Confirm Password`) VALUES
(7, 'ghjhgjhj', 0, 'Male', 0, 'ym17978@gmail.com', '2025-04-26', '', '0', '', '', NULL, '', '', 0, 0, '2025-04-25 21:21:21', NULL, 0),
(8, 'ghjhgjhj', 0, 'Male', 0, 'ym17978@gmail.com', '2025-04-26', '', '0', '', '', NULL, '', '', 0, 0, '2025-04-25 21:22:32', NULL, 0),
(9, 'sond', 0, 'Male', 0, 'ym1790@gmail.com', '2025-05-09', '', '0', '', '', NULL, '', '', 0, 0, '2025-04-25 21:42:16', NULL, 0),
(10, 'sond', 0, 'Male', 0, 'ym179900@gmail.com', '2025-04-10', '', '0', '', '', NULL, '', '', 0, 0, '2025-04-25 23:42:25', NULL, 0),
(11, 'ghjkjk', 0, 'Male', 0, 'ym1799400@gmail.com', '2025-04-11', '', '0', '', '', NULL, '', '', 0, 0, '2025-04-25 23:50:26', NULL, 0),
(14, 'مصطفى كمال كامل سيد ', NULL, NULL, NULL, '01203728710mm@gmail.com', '2025-04-03', NULL, '$2y$10$P6axOth2edjgMApXLkDwF.vSTEzGLuI6k3lRi3qs9IhgiKatrRHpe', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 18:01:31', NULL, 0),
(15, 'مصطفى كمال كامل سيد ', NULL, NULL, NULL, 'mostafax@www.www', '2025-04-30', NULL, '$2y$10$Nb6JxICfPGBUj5tJDbgCEuCUQmzw1F4ldXwY.cqDoFMPVZUhLo2gO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:42:22', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `paymentmethods`
--

CREATE TABLE `paymentmethods` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `expiry_date` varchar(10) DEFAULT NULL,
  `cvv` int(11) DEFAULT NULL,
  `payment date` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `amount paid` decimal(10,2) DEFAULT NULL,
  `expiry_date_yy` int(11) DEFAULT NULL,
  `go` int(255) DEFAULT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `method_id` int(11) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `card_number`, `expiry_date`, `cvv`, `payment date`, `amount paid`, `expiry_date_yy`, `go`, `consultation_id`, `method_id`, `mobile_number`, `updated_at`) VALUES
(2, '3333 3333 3333 3333', '02/33', 444, '2025-04-29 16:23:50', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:23:50'),
(3, '3333 3333 3333 3333', '02/33', 444, '2025-04-29 16:24:39', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:24:39'),
(4, '3333 3333', '02/33', 0, '2025-04-29 16:25:01', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:25:01'),
(5, '3333 3333', '02/33', 5555, '2025-04-29 16:25:54', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:25:54'),
(6, '3333 3333', '02/33', 5555, '2025-04-29 16:26:07', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:26:07'),
(7, '3333 3333', '02/33', 5555, '2025-04-29 16:27:01', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-29 19:27:01');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `doctor_name` varchar(20) DEFAULT NULL,
  `content` varchar(225) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `url_image` varchar(225) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request a new consultation`
--

CREATE TABLE `request a new consultation` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `Required Specialization` enum('Cardiology','Pediatrics','Orthopedics','Dermatology','Neurology','Psychology','Gynecology','Dentistry') DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `preferred date` date DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `consultation_type` enum('Available','Closed') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointments_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `Hospital_id` (`Hospital_id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`consultation_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `Hospital_id` (`Hospital_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`Hospital_id`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`medical _id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD UNIQUE KEY `patient_id` (`patient_id`),
  ADD KEY `patients_ibfk_1` (`Doctor_id`);

--
-- Indexes for table `paymentmethods`
--
ALTER TABLE `paymentmethods`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `consultation_id` (`consultation_id`),
  ADD KEY `doctor_id` (`go`),
  ADD KEY `method_id` (`method_id`),
  ADD KEY `patient_id` (`expiry_date_yy`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `request a new consultation`
--
ALTER TABLE `request a new consultation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointments_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `consultation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `Hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `medical _id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request a new consultation`
--
ALTER TABLE `request a new consultation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`Hospital_id`) REFERENCES `hospitals` (`Hospital_id`);

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`Hospital_id`) REFERENCES `hospitals` (`Hospital_id`),
  ADD CONSTRAINT `doctor_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`),
  ADD CONSTRAINT `medical_records_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`Doctor_id`) REFERENCES `doctor` (`doctor_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`consultation_id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`go`) REFERENCES `doctor` (`doctor_id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`method_id`) REFERENCES `paymentmethods` (`method_id`),
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`expiry_date_yy`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `request a new consultation`
--
ALTER TABLE `request a new consultation`
  ADD CONSTRAINT `request a new consultation_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`Hospital_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
