-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2025 at 06:05 PM
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
  `id` int(11) NOT NULL,
  `Booking` enum('Available','Closed') DEFAULT 'Available',
  `doctor_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `Booking`, `doctor_id`, `time`, `updated_at`, `created_at`) VALUES
(1, 'Closed', 20, '2025-05-07 22:49:00', '2025-05-07 04:24:35', '2025-05-06 22:50:39'),
(2, 'Closed', 20, '2025-05-09 13:54:00', '2025-05-07 04:27:09', '2025-05-06 22:54:22'),
(3, 'Closed', 19, '2025-05-15 22:54:00', '2025-05-11 01:08:59', '2025-05-06 22:55:27'),
(4, 'Available', 19, '2025-05-06 23:03:00', '2025-05-07 00:01:46', '2025-05-06 23:01:43'),
(5, 'Available', 20, '2025-05-29 23:02:00', '2025-05-07 04:24:16', '2025-05-06 23:02:23'),
(6, 'Available', 20, '2025-05-25 23:06:00', '2025-05-06 23:06:03', '2025-05-06 23:06:03'),
(7, 'Available', 19, '2025-05-21 23:09:00', '2025-05-07 00:01:28', '2025-05-06 23:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `type` enum('clinic','online') NOT NULL DEFAULT 'clinic',
  `created_at` datetime DEFAULT current_timestamp(),
  `doctor_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `type`, `created_at`, `doctor_id`, `patient_id`, `appointment_id`) VALUES
(1, 'clinic', '2025-04-29 18:44:40', 20, 29, 1),
(14, 'clinic', '2025-05-07 04:27:09', NULL, 29, 2),
(15, 'clinic', '2025-05-11 01:08:59', NULL, 30, 3);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `icon` varchar(255) DEFAULT 'img/department.png',
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `icon`, `description`, `created_at`, `updated_at`) VALUES
(1, 'cardiologist', 'img/icons8-cardiology-48.png', 'All cardiologists in this branch of medicine study heart disorders, but the study of heart disorders in adults and children requires different training paths. Therefore, adult cardiologists (often called \"cardiologists\") lack adequate training to care for children, and pediatric cardiologists are not trained to treat heart conditions in adults. Surgical aspects, other than pacemaker implantation, are not included in cardiology and fall under the scope of cardiothoracic surgery. For example, coronary artery bypass grafting (CABG), heart-lung bypass, and valve replacement are performed by surgeons, not cardiologists. A cardiologist typically first identifies who needs heart surgery and refers them to a cardiac surgeon for the procedure.', '2025-05-06 00:34:58', '2025-05-06 00:34:58'),
(2, 'surgeon', 'img/1747093700_Surgery.png', 'surgeon description', '2025-05-13 02:48:20', '2025-05-13 02:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `specialization` varchar(50) DEFAULT 'General',
  `phone` varchar(11) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'img/doctor.png',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `education` text DEFAULT NULL,
  `experience_years` varchar(20) DEFAULT NULL,
  `working_hours` varchar(100) DEFAULT NULL,
  `languages` varchar(255) DEFAULT 'Arabic',
  `birthday` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `clinic_price` int(11) DEFAULT 150,
  `online_price` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `phone`, `email`, `password`, `hospital_id`, `image`, `created_at`, `updated_at`, `education`, `experience_years`, `working_hours`, `languages`, `birthday`, `department_id`, `clinic_price`, `online_price`) VALUES
(19, 'Abdulbasit salem khaled ', 'Urological Care Hospital', '01061550335', 'Abdulbasit@gmail.com', '$2y$10$97tdPj08rVE87vDWtvpGYe6qS42nWnoCPll5VaTfherobJcRhGNxC', 13, 'img/doctor.png', '2025-05-05 01:52:59', '2025-05-07 04:29:19', 'Doctor of Dental Surgery (DDS) – Cairo University |\nAdvanced Training in Cosmetic Dentistry – Harvard University |\nBachelor’s Degree in Dental Surgery – Cairo University |\nMaster’s in Cosmetic Dentistry – Ain Shams University |\nFellow of the International Congress of Oral Implantologists |(FICOI)', '20', 'Mon-Fri: 9:00 AM - 5:00 PM\nSat: 10:00 AM - 2:00 PM', 'English|Spanish| French', '2000-05-01', 1, 150, 100),
(20, 'salem khaled ', 'General', NULL, 'salem@gmail.com', '$2y$10$97tdPj08rVE87vDWtvpGYe6qS42nWnoCPll5VaTfherobJcRhGNxC', NULL, 'img/doctor.png', '2025-05-06 21:39:15', '2025-05-07 04:29:15', NULL, '10', NULL, 'Arabic', NULL, 1, 150, 100),
(21, 'taha abdulbasit salem', 'General', '01124283519', 'taha@gmail.com', '$2y$10$.ypR0Oz6qyk7e6DZZG22JOE5sgocnxau/ci2amgyvkLr9ofPTOqOK', NULL, 'img/doc_6820f09af24c7.jpg', '2025-05-11 01:03:34', '2025-05-11 21:46:50', 'Doctor of Dental Surgery (DDS) – Cairo University |\r\nAdvanced Training in Cosmetic Dentistry – Harvard University |\r\nBachelor’s Degree in Dental Surgery – Cairo University |\r\nMaster’s in Cosmetic Dentist', NULL, 'Mon-Fri: 9:00 AM - 5:00 PM\r\nSat: 10:00 AM - 2:00 PM', 'Arabic', '2025-05-01', 1, 150, 100);

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `business_ hours` varchar(100) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `image` varchar(255) DEFAULT 'img/host.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `email`, `password`, `location`, `created_at`, `updated_at`, `business_ hours`, `services`, `image`) VALUES
(12, 'AL Noor Specialized Hospital', 'alnoor@gmail.com', '$2y$10$y2KUKWde1zkitjftY8HQ9uRdSjtSfkJXs.TsbbrPZoWNQIa8TNCEi', NULL, '2025-05-03 01:15:47', '2025-05-07 05:05:58', NULL, 'A leading hospital in providing integrated health services with the highest quality standards. We pride ourselves on an outstanding medical team and state-of-the-art medical equipment to provide the best patient care. More than 20 years of experience in various medical specialties.\r\n', 'img/host.jpg'),
(13, 'EL Noor Specialized Hospital', 'elnoor@gmail.com', '$2y$10$y2KUKWde1zkitjftY8HQ9uRdSjtSfkJXs.TsbbrPZoWNQIa8TNCEi', NULL, '2025-05-03 01:19:26', '2025-05-07 05:05:56', NULL, 'A leading hospital in providing integrated health services with the highest quality standards. We pride ourselves on an outstanding medical team and state-of-the-art medical equipment to provide the best patient care. More than 25 years of experience in various medical specialties.\r\n', 'img/1746224366_host.jpg'),
(14, 'Al Salam International Hospital', 'salam@gmail.com', '$2y$10$y2KUKWde1zkitjftY8HQ9uRdSjtSfkJXs.TsbbrPZoWNQIa8TNCEi', NULL, '2025-05-07 04:54:34', '2025-05-07 05:46:08', NULL, 'We are an integrated medical center offering comprehensive medical services of the highest quality and care. We aim to improve the health of the community through distinguished medical services', 'img/host.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `medical_records`
--

CREATE TABLE `medical_records` (
  `id` int(11) NOT NULL,
  `consultation_id` int(11) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `treatment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_records`
--

INSERT INTO `medical_records` (`id`, `consultation_id`, `diagnosis`, `patient_id`, `doctor_id`, `updated_at`, `treatment`, `created_at`) VALUES
(1, 1, 'Mild Hypertension', 29, 20, '2025-05-07 01:45:54', 'Prescribed ACE inhibitors and lifestyle changes', '2025-05-07 01:45:54'),
(2, 2, 'Knee Osteoarthritis', 29, 21, '2025-05-12 23:32:04', 'Physical therapy and anti-inflammatory medication', '2025-05-12 23:30:06'),
(3, NULL, 'Mild Hypertension', 29, 21, '2025-05-12 23:49:39', 'Prescribed ACE inhibitors and lifestyle changes', '2025-05-12 23:35:13'),
(4, NULL, 'Knee Osteoarthritis', 29, 21, '2025-05-12 23:50:18', 'Physical therapy and anti-inflammatory medication', '2025-05-12 23:46:40');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `ssn` varchar(14) DEFAULT NULL,
  `gender` enum('male','female','Other') DEFAULT NULL,
  `phone` varchar(12) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'img/patient.png',
  `blood_type` varchar(10) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `ssn`, `gender`, `phone`, `email`, `birthday`, `address`, `password`, `image`, `blood_type`, `height`, `weight`, `created_at`, `updated_at`) VALUES
(29, 'khaled abdulbasit salem', '29801011401891', 'male', '01061550335', 'khaled11@gmail.com', '2025-05-04', '15 may', '$2y$10$97tdPj08rVE87vDWtvpGYe6qS42nWnoCPll5VaTfherobJcRhGNxC', 'img/patient_6817e9212d91f.png', 'A+', 170, 80, '2025-05-04 20:02:56', '2025-05-13 03:09:06'),
(30, 'yossef abdulbasit salem', '29801011401892', NULL, NULL, 'yossef@gmail.com', NULL, NULL, '$2y$10$7IBwNSOK6pZW4zDXuqS7yu7gZZDWxlNU.REq44ARRPmTXgtxyWuhe', 'img/patient.png', NULL, NULL, NULL, '2025-05-11 01:04:12', '2025-05-13 03:09:28');

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
(24, '', '', 0, '2025-05-07 00:49:03', NULL, NULL, NULL, NULL, NULL, '01010101010', '2025-05-07 03:49:03'),
(25, '', '', 0, '2025-05-07 01:04:05', NULL, NULL, NULL, NULL, NULL, '01010101010', '2025-05-07 04:04:05'),
(26, '', '', 0, '2025-05-07 01:08:52', NULL, NULL, NULL, NULL, NULL, '01010101010', '2025-05-07 04:08:52'),
(27, '', '', 0, '2025-05-07 01:09:32', NULL, NULL, NULL, NULL, NULL, '01010101010', '2025-05-07 04:09:32'),
(28, '', '', 0, '2025-05-07 01:18:40', NULL, NULL, NULL, NULL, NULL, '01061550335', '2025-05-07 04:18:40'),
(29, '', '', 0, '2025-05-07 01:21:06', NULL, NULL, NULL, NULL, NULL, '01061550335', '2025-05-07 04:21:06'),
(30, '', '', 0, '2025-05-07 01:27:09', NULL, NULL, NULL, NULL, NULL, '01010101010', '2025-05-07 04:27:09'),
(31, '', '', 0, '2025-05-10 22:08:59', NULL, NULL, NULL, NULL, NULL, '', '2025-05-11 01:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `method_id` int(11) NOT NULL,
  `method_name` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`method_id`, `method_name`, `created_at`, `updated_at`) VALUES
(1, 'visa', '2025-05-07 03:23:26', '2025-05-07 03:23:26'),
(2, 'vodafone-cash', '2025-05-07 03:24:21', '2025-05-07 03:24:21');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `image` varchar(225) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `doctor_id`, `image`, `updated_at`, `created_at`) VALUES
(1, 'Importance of Drinking Water', 'This review attempts to provide some sense of our current knowledge of water including overall patterns of intake and some factors linked with intake, the complex mechanisms behind water homeostasis, the effects of variation in water intake on health and energy intake, weight, and human performance and functioning. Water represents a critical nutrient whose absence will be lethal within days. Water’s importance for prevention of nutrition-related noncommunicable diseases has emerged more recently because of the shift toward large proportions of fluids coming from caloric beverages. Nevertheless, there are major gaps in knowledge related to measurement of total fluid intake, hydration status at the population level, and few longer-term systematic interventions and no published random-controlled longer-term trials. We suggest some ways to examine water requirements as a means to encouraging more dialogue on this important topic.\n\nKeywords: water, hydration, water intake, water measurement, recommended daily intake, water adequacy', 19, 'img/post_68180c4759407.png', '2025-05-06 03:25:18', '2025-05-05 03:50:01'),
(3, 'Flu Prevention', 'Wash your hands thoroughly, avoid crowded places, and take vitamins to strengthen your immunity.', 19, 'img/post_68180c4759407.png', '2025-05-05 03:54:31', '2025-05-05 03:54:31'),
(4, 'Healthy Sleep', 'Sleep 7–8 hours daily and avoid electronic devices before bedtime to improve your sleep quality.', 19, 'img/post_68181036001fe.jpg', '2025-05-05 04:11:18', '2025-05-05 04:11:18'),
(9, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 20, 'img/post_681a62b469663.jpg', '2025-05-06 22:27:48', '2025-05-06 22:27:48');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `rating` int(1) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `rating`, `text`, `hospital_id`, `patient_id`, `doctor_id`, `updated_at`, `created_at`) VALUES
(1, 5, 'g', 12, 29, 19, '2025-05-06 03:06:06', '2025-05-06 03:06:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`name`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_doctors_email` (`email`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `Hospital_id` (`hospital_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_hospitals_email` (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD UNIQUE KEY `patient_id` (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ssn` (`ssn`);

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
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`method_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `medical_records`
--
ALTER TABLE `medical_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `method_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `consultations_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `consultations_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `doctors_ibfk_3` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`);

--
-- Constraints for table `medical_records`
--
ALTER TABLE `medical_records`
  ADD CONSTRAINT `medical_records_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `medical_records_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`),
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`go`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`method_id`) REFERENCES `payment_methods` (`method_id`),
  ADD CONSTRAINT `payments_ibfk_4` FOREIGN KEY (`expiry_date_yy`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `payments_ibfk_5` FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
