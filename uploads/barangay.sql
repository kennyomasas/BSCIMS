-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2025 at 04:09 PM
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
-- Database: `barangay`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('Super Admin','Admin') DEFAULT 'Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'Kenny', 'Omas-as', 'kenomasas@gmail.com', 'Admin', '2025-03-05 17:08:21', '2025-03-05 17:08:21'),
(2, 'SandySandy', '', '', '', '', '', '2025-03-06 04:17:36', '2025-03-14 03:19:28'),
(3, 'ken', 'ken', 'SANDY', 'OMASAS', 'gela@gmail.com', '', '2025-03-06 04:18:21', '2025-03-06 04:18:21'),
(4, 'harold', 'harold', 'harold', 'tulod', 'dodongmwaa@gmail.com', '', '2025-03-06 04:22:30', '2025-03-06 04:22:30'),
(5, 'admin11', 'admin11', 'haroldes', 'Tulodss', 'dodongemwaa@gmail.com', 'Admin', '2025-03-06 04:23:52', '2025-03-06 04:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `announcements_events`
--

CREATE TABLE `announcements_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements_events`
--

INSERT INTO `announcements_events` (`id`, `title`, `description`, `start_date`, `end_date`, `created_at`) VALUES
(1, 'Fiesta', 'Birhen del Rosario', '2025-03-13', '2025-03-14', '2025-03-12 07:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_name` varchar(255) NOT NULL,
  `date_issued` date NOT NULL,
  `actions` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `certificate_name`, `date_issued`, `actions`, `created_at`, `updated_at`) VALUES
(1, 'Barangay Clearance', '2025-03-05', NULL, '2025-03-05 14:37:03', '2025-03-05 14:37:03'),
(2, 'Indigency Certificate', '2025-03-05', NULL, '2025-03-05 14:37:18', '2025-03-05 14:37:18'),
(3, 'Residency Certificate', '2025-03-05', NULL, '2025-03-05 14:37:31', '2025-03-05 14:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `certificates_type`
--

CREATE TABLE `certificates_type` (
  `id` int(11) NOT NULL,
  `certificate_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_requests`
--

CREATE TABLE `document_requests` (
  `id` int(11) NOT NULL,
  `or_no` varchar(50) NOT NULL,
  `ctc_no` varchar(50) NOT NULL,
  `issued_to` varchar(255) NOT NULL,
  `document_type` enum('Barangay Clearance','Indigency Certificate','Residency Certificate') NOT NULL,
  `purpose` text NOT NULL,
  `issue_date` date NOT NULL,
  `signatory` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_requests`
--

INSERT INTO `document_requests` (`id`, `or_no`, `ctc_no`, `issued_to`, `document_type`, `purpose`, `issue_date`, `signatory`, `created_at`, `updated_at`) VALUES
(1, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-02', 'Hon. Christian Jay Malunis', '2025-03-02 13:19:41', '2025-03-02 13:19:41'),
(2, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-03', 'Hon. Marilou Q. Erazo', '2025-03-03 02:20:43', '2025-03-03 02:20:43'),
(3, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-03', 'Hon. Christian Jay Malunis', '2025-03-03 07:06:10', '2025-03-03 07:06:10'),
(4, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-11', 'Hon. Rodel Faye L. Solinap', '2025-03-04 06:02:33', '2025-03-04 06:02:33'),
(5, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-04', 'Hon. Geralyn C. Baliling', '2025-03-04 06:18:21', '2025-03-04 06:18:21'),
(6, '', '', '', 'Indigency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Marilou Q. Erazo', '2025-03-04 06:33:53', '2025-03-04 06:33:53'),
(7, '', '', 'Chaidemaezia Guimba Dongogan', 'Indigency Certificate', 'VACCINATION REQUIREMENTS', '2025-03-08', 'Hon. Junnie P. Gabucan', '2025-03-04 06:34:36', '2025-03-04 06:34:36'),
(8, '', '', 'Harold Gamboa Tulod', 'Barangay Clearance', 'BUSINESS REGISTRATION', '2025-03-03', 'Hon. Rodel Faye L. Solinap', '2025-03-04 07:27:55', '2025-03-04 07:27:55'),
(9, '', '', 'Harold Gamboa Tulod', 'Residency Certificate', 'BUSINESS REGISTRATION', '2025-03-03', 'Hon. Rodel Faye L. Solinap', '2025-03-04 07:37:50', '2025-03-04 07:37:50'),
(10, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Scholarship Requirements (UNIFAST)', '2025-03-03', 'Hon. Rodel Faye L. Solinap', '2025-03-04 07:38:24', '2025-03-04 07:38:24'),
(11, '', '', 'Harold Gamboa Tulod', 'Barangay Clearance', 'Scholarship Requirements (UNIFAST)', '2025-03-03', 'Hon. Rodel Faye L. Solinap', '2025-03-04 07:39:13', '2025-03-04 07:39:13'),
(12, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Scholarship Requirements (UNIFAST)', '2025-03-03', 'Hon. Rodel Faye L. Solinap', '2025-03-04 07:42:58', '2025-03-04 07:42:58'),
(13, '', '', 'Chaidemaezia Guimba Dongogan', 'Indigency Certificate', 'Educational Assistance', '2025-03-04', 'Hon. Dizon P. Tagupa', '2025-03-04 13:11:21', '2025-03-04 13:11:21'),
(14, '', '', 'Chaidemaezia Guimba Dongogan', 'Indigency Certificate', 'Educational Assistance', '2025-03-04', 'Hon. Christian Jay Malunis', '2025-03-04 13:20:59', '2025-03-04 13:20:59'),
(15, '', '', 'Chaidemaezia Guimba Dongogan', 'Indigency Certificate', 'Educational Assistance', '2025-03-08', 'Hon. Christian Jay Malunis', '2025-03-04 14:42:49', '2025-03-04 14:42:49'),
(16, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-06', 'Hon. Christian Jay Malunis', '2025-03-05 01:39:41', '2025-03-05 01:39:41'),
(17, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Dizon P. Tagupa', '2025-03-05 02:45:30', '2025-03-05 02:45:30'),
(18, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Barry C. Denuyo', '2025-03-05 04:31:00', '2025-03-05 04:31:00'),
(19, '', '', 'angela pagaling caldozass', 'Indigency Certificate', 'Vehicle Assistance', '2025-03-05', 'Hon. Geralyn C. Baliling', '2025-03-05 07:30:47', '2025-03-05 07:30:47'),
(20, '', '', 'Harold Gamboa Tulod', 'Residency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Geralyn C. Baliling', '2025-03-05 10:08:24', '2025-03-05 10:08:24'),
(21, '', '', 'Chaidemaezia Guimba Dongogan', 'Residency Certificate', 'Vehicle Assistance', '2025-03-05', 'Hon. Geralyn C. Baliling', '2025-03-05 10:14:37', '2025-03-05 10:14:37'),
(22, '', '', '', 'Indigency Certificate', 'Vehicle Assistance', '2025-03-06', 'Hon. Geralyn C. Baliling', '2025-03-05 10:15:11', '2025-03-05 10:15:11'),
(23, '', '', 'kenssss odhap har', 'Residency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Christian Jay Malunis', '2025-03-05 10:15:41', '2025-03-05 10:15:41'),
(24, '', '', 'Harold Gamboa Tulod', 'Residency Certificate', 'Vehicle Assistance', '2025-03-11', 'Hon. Dizon P. Tagupa', '2025-03-05 10:26:52', '2025-03-05 10:26:52'),
(25, '', '', '', 'Barangay Clearance', 'Educational Assistance', '2025-03-06', 'Hon. Christian Jay Malunis', '2025-03-05 10:33:17', '2025-03-05 10:33:17'),
(26, '', '', '', 'Indigency Certificate', 'Vehicle Assistance', '2025-03-05', 'Hon. Geralyn C. Baliling', '2025-03-05 10:39:31', '2025-03-05 10:39:31'),
(27, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Christian Jay Malunis', '2025-03-05 10:44:45', '2025-03-05 10:44:45'),
(28, '', '', '', 'Indigency Certificate', 'Educational Assistance', '2025-03-05', 'Hon. Dizon P. Tagupa', '2025-03-05 14:19:59', '2025-03-05 14:19:59'),
(29, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-06', 'Hon. Lydia O. Devila', '2025-03-06 14:45:05', '2025-03-06 14:45:05'),
(30, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-06', 'Hon. Lydia O. Devila', '2025-03-06 14:46:22', '2025-03-06 14:46:22'),
(31, '', '', '', 'Barangay Clearance', 'PAG-IBIG REQUIREMENTS', '2025-03-05', 'Hon. Rodel Faye L. Solinap', '2025-03-06 15:15:03', '2025-03-06 15:15:03'),
(32, '', '', '', 'Barangay Clearance', 'Vehicle Assistance', '2025-03-06', 'Hon. Barry C. Denuyo', '2025-03-06 15:35:00', '2025-03-06 15:35:00'),
(33, '', '', '', 'Barangay Clearance', 'Vehicle Assistance', '2025-03-06', 'Hon. Barry C. Denuyo', '2025-03-06 15:40:02', '2025-03-06 15:40:02'),
(34, '', '', '', 'Barangay Clearance', 'VACCINATION REQUIREMENTS', '2025-03-14', 'Hon. Rodel Faye L. Solinap', '2025-03-06 15:40:44', '2025-03-06 15:40:44'),
(35, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Educational Assistance', '2025-03-07', 'Hon. Barry C. Denuyo', '2025-03-07 05:03:27', '2025-03-07 05:03:27'),
(36, '', '', 'Chaidemaezia Guimba Dongogan', 'Residency Certificate', 'Burial Assistance', '2025-04-10', 'Hon. Christian Jay Malunis', '2025-03-07 15:27:50', '2025-03-07 15:27:50'),
(37, '', '', '', 'Indigency Certificate', 'BUSINESS REGISTRATION', '2025-03-10', 'Hon. Lydia O. Devila', '2025-03-10 05:36:50', '2025-03-10 05:36:50'),
(38, '', '', 'kenssss odhap har', 'Indigency Certificate', 'PHILIPPINE I.D SYSTEM REQUIREMENTS', '2025-02-13', 'Hon. Geralyn C. Baliling', '2025-03-10 09:53:54', '2025-03-10 09:53:54'),
(39, '', '', '', 'Barangay Clearance', 'PAG-IBIG REQUIREMENTS', '2025-03-20', 'Hon. Geralyn C. Baliling', '2025-03-10 10:19:12', '2025-03-10 10:19:12'),
(40, '', '', '', 'Barangay Clearance', 'PAG-IBIG REQUIREMENTS', '2025-03-20', 'Hon. Geralyn C. Baliling', '2025-03-10 10:27:00', '2025-03-10 10:27:00'),
(41, '', '', '', 'Indigency Certificate', 'PAG-IBIG REQUIREMENTS', '2025-03-20', 'Hon. Geralyn C. Baliling', '2025-03-10 10:27:10', '2025-03-10 10:27:10'),
(42, '', '', '', 'Barangay Clearance', 'PAG-IBIG REQUIREMENTS', '2025-03-20', 'Hon. Geralyn C. Baliling', '2025-03-10 10:30:26', '2025-03-10 10:30:26'),
(43, '', '', 'Harold Gamboa Tulod', 'Residency Certificate', 'Educational Assistance', '2025-03-10', 'Hon. Geralyn C. Baliling', '2025-03-10 10:31:46', '2025-03-10 10:31:46'),
(44, '', '', '', 'Barangay Clearance', 'BUSINESS REGISTRATION', '2025-01-07', 'Hon. Geralyn C. Baliling', '2025-03-10 10:35:27', '2025-03-10 10:35:27'),
(45, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Medical Assistance', '2025-03-10', 'Hon. Geralyn C. Baliling', '2025-03-10 11:57:04', '2025-03-10 11:57:04'),
(46, '', '', '', 'Indigency Certificate', 'Educational Assistance', '2025-03-13', 'Hon. Geralyn C. Baliling', '2025-03-10 12:01:02', '2025-03-10 12:01:02'),
(47, '', '', 'stephen omasas Cerada', 'Residency Certificate', 'Educational Assistance', '2025-03-11', 'Hon. Geralyn C. Baliling', '2025-03-10 15:13:29', '2025-03-10 15:13:29'),
(48, '', '', 'Harold Gamboa Tulod', 'Indigency Certificate', 'Vehicle Assistance', '2025-03-12', 'Hon. Christian Jay Malunis', '2025-03-11 04:56:34', '2025-03-11 04:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `officials`
--

CREATE TABLE `officials` (
  `id` int(11) NOT NULL,
  `complete_name` varchar(255) NOT NULL,
  `mobile_number` varchar(50) NOT NULL,
  `position` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `officials`
--

INSERT INTO `officials` (`id`, `complete_name`, `mobile_number`, `position`, `photo`) VALUES
(1, 'Mariza Teofilo Labe', 'Punong Barangay', 'Punong Barangay', 'punong barangay.png'),
(2, 'Junnie Pasco Gabucan', 'Peace & order', 'Sangguniang Barangay Member', 'peace&order.png'),
(3, 'Ceasar Ayan Arevalo Sibog', 'Approration badac tourism', 'Sangguniang Barangay Member', 'approration badac tourism.png'),
(4, 'Jacqueline Oliverio Canete', 'Infrastracture', 'Sangguniang Barangay Member', 'infratracture.png'),
(5, 'Lydia Obenita Devilla', 'Social services vawc and bcp', 'Sangguniang Barangay Member', 'social services vawc and bcp.png'),
(6, 'Marilou Quintanes Erazo', 'Health nutrition and environnment', 'Sangguniang Barangay Member', 'health nutrition and environnment.png'),
(7, 'Barry Cortez Denuyo', 'Bdrrmc good governancey', 'Sangguniang Barangay Member', 'bdrrmc good governance.png'),
(8, 'Dizon Patena Tagupa', 'Education and agriculture', 'Sangguniang Barangay Member', 'education and agriculture.png'),
(9, 'Geralyn Cotejo Baliling', 'Secretary', 'Barangay Secretary', 'secretary.png'),
(10, 'Rodel Faye L. Solinap', 'Tresurer', 'Barangay Treasurer', 'tresurer.png'),
(11, 'Christian Jay Sotis Malunes', 'Sk Chairman', 'SK Chairman', 'sk.png');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `birthplace` varchar(100) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `marital_status` enum('Single','Married') NOT NULL,
  `religion` varchar(50) NOT NULL,
  `sector` varchar(50) NOT NULL,
  `education` varchar(50) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `sitio` varchar(50) NOT NULL,
  `house_number` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `since_year` varchar(4) NOT NULL,
  `household_number` varchar(50) NOT NULL,
  `house_owner` enum('Yes','No') NOT NULL,
  `shelter_type` enum('Owned','Rented') NOT NULL,
  `house_material` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `profile_image`, `last_name`, `first_name`, `middle_name`, `nickname`, `birthdate`, `birthplace`, `citizenship`, `gender`, `mobile_number`, `email`, `marital_status`, `religion`, `sector`, `education`, `height`, `weight`, `sitio`, `house_number`, `purok`, `since_year`, `household_number`, `house_owner`, `shelter_type`, `house_material`, `created_at`) VALUES
(15, 'uploads/IMG_20250213_223453.jpg', 'Tulod', 'Harold', 'Gamboa', '', '2004-03-23', '', 'Filipino', 'Male', '997438209', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 0.00, '', '', 'Purok 2', '', '', 'Yes', 'Owned', 'Wood', '2025-02-13 14:37:14'),
(16, 'uploads/20250131_095203.jpg', 'Dongogan', 'Chaidemaezia', 'Guimba', 'Chaii', '2004-01-19', 'Kibalagon', 'Other', 'Female', '09662219584', 'chaidemaziad@gmail.com', 'Married', 'Christian', 'Student', 'College Level', 6.00, 80.00, 'Sitio 1', '', 'Purok 2', '2004', '', 'Yes', 'Owned', 'Concrete', '2025-02-15 04:23:35'),
(20, 'uploads/WIN_20241014_23_03_18_Pro.jpg', 'omasas', 'ken', 'odhap', '', '2025-02-20', '', 'Filipino', 'Male', '988738283', 'jdnaces1145@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 0.00, 'Sitio 1', '', 'Purok 2', '', '', 'Yes', 'Owned', 'Concrete', '2025-02-24 16:15:56'),
(22, 'uploads/WIN_20241015_17_37_17_Pro.jpg', 'har', 'kenssss', 'odhap', '', '2025-02-21', '', 'Filipino', 'Male', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Employed', 'College Graduate', 0.00, 0.00, 'Sitio 1', '', 'Purok 2', '', '', 'Yes', 'Rented', 'Concrete', '2025-02-25 10:40:36'),
(23, 'uploads/WIN_20241015_17_37_17_Pro.jpg', 'Cerada', 'kevin', 'odhap', '', '2025-02-19', '', 'Other', 'Male', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Business Owner', 'Elementary Level', 0.00, 0.00, '', '', 'Purok 2', '', '', 'No', 'Owned', 'Concrete', '2025-02-25 11:00:29'),
(24, 'uploads/WIN_20241014_23_03_18_Pro.jpg', 'Cerada', 'stephen', 'omasas', '', '2025-02-21', '', 'Filipino', 'Male', '988738283', 'stepmwaa@gmail.com', 'Single', 'Christian', 'Student', 'Elementary Level', 0.00, 0.00, 'Sitio 1', '', 'Purok 2', '', '', 'No', 'Owned', 'Concrete', '2025-02-27 13:22:46'),
(26, '', 'caldoza', 'angela', 'pagaling', 'lalai', '2004-03-20', 'malaybalay city', 'Other', 'Female', '09609012716', 'gela@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 7', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 05:09:48'),
(27, 'uploads/20250131_094427.jpg', 'caldozass', 'angela', 'pagaling', 'lalai', '2025-03-07', 'malaybalay city', 'Other', 'Female', '09609012716', 'gela@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 1', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 05:53:58'),
(28, 'uploads/20250131_094421.jpg', 'caldozassss', 'kenssss', 'odhap', 'lalai', '2025-03-03', ' malaybalay city', 'Other', 'Female', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 3', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 05:56:16'),
(29, '', 'caldozasssswww', 'kenssss', 'odhap', 'lalai', '2025-02-26', 'malaybalay city', 'Other', 'Female', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 4', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 05:56:34'),
(30, '', 'batanf', 'kenssss', 'odhap', 'lalai', '2025-03-06', 'malaybalay city', 'Other', 'Female', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 11', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 05:59:09'),
(31, '', 'batang', 'kenssss', 'odhap', 'lalai', '2025-03-05', 'malaybalay city', 'Other', 'Female', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 9', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 06:01:27'),
(32, '', 'batangss', 'kenssss', 'odhap', 'lalai', '2025-03-06', 'malaybalay city', 'Other', 'Female', '988738283', 'dodongmwaa@gmail.com', 'Single', 'Christian', 'Student', 'College Level', 0.00, 36.00, '', '1', 'Purok 8', '2019', 'sd', 'Yes', 'Owned', 'Concrete', '2025-03-05 06:04:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcements_events`
--
ALTER TABLE `announcements_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates_type`
--
ALTER TABLE `certificates_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document_requests`
--
ALTER TABLE `document_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `officials`
--
ALTER TABLE `officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `announcements_events`
--
ALTER TABLE `announcements_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `certificates_type`
--
ALTER TABLE `certificates_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_requests`
--
ALTER TABLE `document_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `officials`
--
ALTER TABLE `officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
