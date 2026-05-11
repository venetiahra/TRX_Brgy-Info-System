-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 05:49 AM
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
  `province` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangay_officials`
--

INSERT INTO `barangay_officials` (`id`, `captain_name`, `secretary_name`, `treasurer_name`, `barangay_name`, `municipality`, `province`) VALUES
(2, 'Hon. Trixe Maclin', 'Ayesha Artates', 'Yzabelle Santos', 'Barangay TRX', 'TRX Municipality', 'TRX Province');

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
(4, 2, 'Certificate of Indigency', 'School Scholarship', 'TRX-2026-0004', NULL, '2026-04-22', 'Client Portal', 'Submitted via client portal', '2026-04-22 03:14:35');

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
(6, 'TRX-2026-004', 'Beatrice Eulamae', NULL, 'Ferrer', NULL, 'Female', 'Single', '2000-01-01', 26, 'Imus, Cavite', '+63 917 100 2222', 'Student', 'Filipino', 25, 'Registered', 'Active', '2026-04-22 03:06:40');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_requests`
--
ALTER TABLE `certificate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
