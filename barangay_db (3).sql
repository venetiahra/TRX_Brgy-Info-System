-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 08:27 AM
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
-- Database: `barangay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangay_officials`
--

CREATE TABLE `barangay_officials` (
  `id` int(11) NOT NULL,
  `captain_name` varchar(100) NOT NULL,
  `secretary_name` varchar(100) NOT NULL,
  `treasurer_name` varchar(100) DEFAULT NULL,
  `barangay_name` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `captain_bio` text DEFAULT NULL,
  `secretary_bio` text DEFAULT NULL,
  `treasurer_bio` text DEFAULT NULL,
  `captain_photo` varchar(255) DEFAULT NULL,
  `secretary_photo` varchar(255) DEFAULT NULL,
  `treasurer_photo` varchar(255) DEFAULT NULL,
  `official_seal` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `captain_name`, `secretary_name`, `treasurer_name`, `barangay_name`, `municipality`, `province`, `captain_bio`, `secretary_bio`, `treasurer_bio`, `captain_photo`, `secretary_photo`, `treasurer_photo`, `official_seal`) VALUES
(2, 'Hon. Trixe Maclin', 'Ayesha Artates', 'Yzabelle Santos', 'Barangay TRX', 'TRX Municipality', 'TRX Province', '', '', '', 'uploads/officials/captain_photo_upload_1776831076_94a044dc.png', 'uploads/officials/secretary_photo_upload_1776831076_b14d7124.png', 'uploads/officials/treasurer_photo_upload_1776831059_7708ddd0.png', 'uploads/officials/official_seal.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `blotter_reports`
--

CREATE TABLE `blotter_reports` (
  `id` int(11) NOT NULL,
  `control_no` varchar(50) NOT NULL,
  `complainant_name` varchar(150) NOT NULL,
  `respondent_name` varchar(150) NOT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
  `incident_location` varchar(255) NOT NULL,
  `complaint_details` text NOT NULL,
  `submitted_via` varchar(30) DEFAULT 'Client Portal',
  `status` varchar(30) DEFAULT 'Pending Review',
  `schedule_date` datetime DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blotter_reports`
--

INSERT INTO `blotter_reports` (`id`, `control_no`, `complainant_name`, `respondent_name`, `contact_number`, `incident_date`, `incident_time`, `incident_location`, `complaint_details`, `submitted_via`, `status`, `schedule_date`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'BLT-2026-0001', 'Beatrice Eulamae Ferrer', 'John Michael Santos', '+63 917 100 2222', '2026-04-22', '13:18:00', 'Barangay Talon Uno, Las Piñas City', 'The complainant reported that the respondent, identified as John Michael Santos, borrowed money amounting to ₱5,000.00 on March 15, 2026, with a promise to pay on or before April 15, 2026. However, despite repeated follow-ups and reminders, the respondent failed to settle the said amount. On April 22, 2026, at around 3:15 PM, the complainant attempted to personally collect the payment, but the respondent refused to pay and allegedly avoided further communication. The complainant is seeking assistance to formally document the incident and to request mediation for the settlement of the unpaid debt.', 'Client Portal', 'Pending Review', NULL, NULL, '2026-04-22 05:15:25', '2026-04-22 05:16:17'),
(2, 'BLT-2026-0002', 'Beatrice Eulamae Ferrer', 'Beatrice Eulamae Ferrer', '+63 917 100 2222', '2026-04-22', '07:30:00', 'Barangay Talon Uno, Las Piñas City', 'The complainant reported that the respondent, identified as Kevin Louis Ramirez, has continuously failed to respond to text messages and online chats despite multiple attempts to communicate. The complainant stated that messages were sent regarding important matters, but the respondent allegedly ignored them without providing any explanation. This lack of response caused frustration and emotional distress to the complainant. The complainant is requesting that this incident be recorded for documentation purposes and is seeking assistance in encouraging the respondent to respond accordingly.', 'Client Portal', 'Complete', NULL, NULL, '2026-04-22 05:17:18', '2026-04-22 05:58:13'),
(3, 'BLT-2026-0003', 'Beatrice Eulamae Ferrer', 'Christian Paolo Reyes', '+63 917 100 2222', '2026-04-22', '08:04:00', 'Barangay Talon Uno, Las Piñas City', 'The complainant reported that the respondent, identified as Christian Paolo Reyes, failed to reply to multiple text messages and online chats sent throughout the day. The complainant stated that despite the respondent being active on social media, no response was received, which raised concern and disappointment. The complainant further expressed that the respondent’s actions appeared to be intentional and caused emotional distress. Efforts to reach the respondent were unsuccessful. The complainant is requesting that this matter be recorded and hopes for proper communication to be restored.', 'Client Portal', 'Pending Review', NULL, NULL, '2026-04-22 06:00:48', '2026-04-22 06:00:48'),
(4, 'BLT-2026-0004', 'Beatrice Eulamae Ferrer', 'Ralph Joshua Mendoza', '+63 917 100 2222', '2026-04-22', '08:04:00', 'Barangay Talon Uno, Las Piñas City', 'The complainant reported that the respondent, identified as Ralph Joshua Mendoza, was creating excessive noise late at night by playing loud music and engaging in shouting along with several companions. The disturbance reportedly started past midnight and affected the complainant’s ability to rest. The complainant stated that prior requests to lower the volume were ignored. Due to the continued disturbance, the complainant sought assistance to formally document the incident and request appropriate action to prevent further disruption.', 'Client Portal', 'Complete', NULL, NULL, '2026-04-22 06:04:18', '2026-04-22 06:06:50');

-- --------------------------------------------------------

--
-- Table structure for table `certificate_requests`
--

CREATE TABLE `certificate_requests` (
  `id` int(11) NOT NULL,
  `resident_id` int(11) NOT NULL,
  `certificate_type` varchar(100) NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `control_no` varchar(50) DEFAULT NULL,
  `or_no` varchar(50) DEFAULT NULL,
  `date_issued` date NOT NULL,
  `issued_by` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificate_requests`
--

INSERT INTO `certificate_requests` (`id`, `resident_id`, `certificate_type`, `purpose`, `control_no`, `or_no`, `date_issued`, `issued_by`, `remarks`, `created_at`) VALUES
(1, 1, 'Barangay Clearance', 'First Time Jobseeker', 'TRX-2026-0001', 'OR-1001', '2026-04-21', 'Barangay TRX Administrator', 'Paid', '2026-04-21 06:28:59'),
(2, 2, 'Certificate of Solo Parent', 'Legal Purpose', 'TRX-2026-0002', NULL, '2026-04-21', 'Barangay TRX Administrator', NULL, '2026-04-21 06:52:09'),
(3, 6, 'Barangay Clearance', 'Legal Purposes', 'TRX-2026-0003', NULL, '2026-04-22', 'Barangay TRX Administrator', NULL, '2026-04-22 03:07:10'),
(4, 2, 'Certificate of Indigency', 'School Scholarship', 'TRX-2026-0004', NULL, '2026-04-22', 'Client Portal', 'Submitted via client portal', '2026-04-22 03:14:35'),
(5, 2, 'Certificate of Low Income', '4P\'s Program', 'TRX-2026-0005', NULL, '2026-04-22', 'Client Portal', 'Submitted via client portal', '2026-04-22 05:59:11');

-- --------------------------------------------------------

--
-- Table structure for table `residents`
--

CREATE TABLE `residents` (
  `id` int(11) NOT NULL,
  `resident_no` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `sex` varchar(10) NOT NULL,
  `civil_status` varchar(20) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `age` int(11) DEFAULT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `citizenship` varchar(50) DEFAULT NULL,
  `years_of_residency` int(11) DEFAULT 0,
  `voter_status` varchar(20) DEFAULT NULL,
  `resident_status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `residents`
--

INSERT INTO `residents` (`id`, `resident_no`, `first_name`, `middle_name`, `last_name`, `suffix`, `sex`, `civil_status`, `birth_date`, `age`, `address`, `contact_number`, `occupation`, `citizenship`, `years_of_residency`, `voter_status`, `resident_status`, `created_at`) VALUES
(1, 'TRX-2026-001', 'Hershie', 'Hers', 'Forbes', NULL, 'Female', 'Single', '2005-03-03', 21, 'Bacoor Ewan, Barangay TRX', '09123456788', 'Student', 'Filipino', 8, 'Registered', 'Active', '2026-04-21 06:28:59'),
(2, 'TRX-2026-002', 'Maria', 'Lopez', 'Villanueva', '', 'Female', 'Married', '1992-08-22', 33, 'Purok 2, Barangay TRX', '09179876543', 'Store Owner', 'Filipino', 12, 'Registered', 'Active', '2026-04-21 06:28:59'),
(3, 'TRX-2026-003', 'Marj', 'Marjorieeee', 'Nayga', NULL, 'Female', 'Separated', '2004-10-14', 21, 'Purok 3, Barangay TRX', '09123456789', 'Jobseeker', 'Filipino', 9, 'Not Registered', 'Transferred', '2026-04-21 06:28:59'),
(6, 'TRX-2026-004', 'Beatrice Eulamae', NULL, 'Ferrer', NULL, 'Female', 'Single', '2001-01-01', 25, 'Imus, Cavite', '+63 917 100 2222', 'Student', 'Filipino', 25, 'Registered', 'Active', '2026-04-22 03:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` varchar(50) DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fullname`, `role`, `created_at`) VALUES
(2, 'admin', '$2y$10$a4eVFqJxz0ouszd4asuST.sP.TkIkDXjWgsQ3.eBkKD9x63vimR.O', 'Barangay TRX Administrator', 'admin', '2026-04-21 06:28:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blotter_reports`
--
ALTER TABLE `blotter_reports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `control_no` (`control_no`);

--
-- Indexes for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `resident_id` (`resident_id`);

--
-- Indexes for table `residents`
--
ALTER TABLE `residents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `resident_no` (`resident_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangay_officials`
--
ALTER TABLE `barangay_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blotter_reports`
--
ALTER TABLE `blotter_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `residents`
--
ALTER TABLE `residents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  ADD CONSTRAINT `certificate_requests_ibfk_1` FOREIGN KEY (`resident_id`) REFERENCES `residents` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
