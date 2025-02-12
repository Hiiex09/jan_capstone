-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 01:56 AM
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
-- Database: `evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_teacher_subject`
--

CREATE TABLE `tblstudent_teacher_subject` (
  `sts_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_teacher_subject`
--

INSERT INTO `tblstudent_teacher_subject` (`sts_id`, `student_id`, `teacher_id`, `subject_id`) VALUES
(14, 9, 1, 1),
(15, 9, 1, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  ADD PRIMARY KEY (`sts_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  MODIFY `sts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `tblteacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `tblsubject` (`subject_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
