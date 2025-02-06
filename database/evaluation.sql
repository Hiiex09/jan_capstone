-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 05:44 AM
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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `email`, `password`, `image`, `date_created`) VALUES
(6, 'Henri Mendoza ', 'devon', 'jeanhenrimendoza@gmail.com', '$2y$10$zCVH4dEzoVJQBWtHWO127ezOj4cqeIQ3m4PgfGFDzjpBmOqWZ1uLO', 'Student.png', '2025-02-05 10:00:01'),
(8, 'Hazami Nagashi', 'hazami', 'hazami@gmail.com', '$2y$10$6BTnyHorr98Lt2hkzLv2Le0X8BvwJRvvGO4lFxM4qu2flpxpSl01y', 'upload/pics/67a43442dc6cb.png', '2025-02-06 04:02:10'),
(9, 'Nelson Nellas', 'nelson', 'nelson@gmail.com', '$2y$10$RhrrQQ7BpdD4zC6U3MEQY..o429Lf2HNb1hIQKWqSOn2llVOUYRli', 'upload/pics/67a4347e30608.png', '2025-02-06 04:03:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblanswer`
--

CREATE TABLE `tblanswer` (
  `answer_id` int(11) NOT NULL,
  `evaluate_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `ratings` enum('1','2','3','4') NOT NULL,
  `comment` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblcriteria`
--

CREATE TABLE `tblcriteria` (
  `criteria_id` int(11) NOT NULL,
  `criteria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcriteria`
--

INSERT INTO `tblcriteria` (`criteria_id`, `criteria`) VALUES
(1, 'Preparation of the lessons'),
(2, 'Routine activities (checking of  attendance, etc.)'),
(3, 'Lesson motivation'),
(4, 'Mastery of the subject matter'),
(5, 'Teaching  techniques and strategies'),
(6, 'Classroom management / Class disicpline'),
(7, 'Clarity of Explanation'),
(8, 'Command of language of instruction'),
(9, 'Voice of modulation and diction'),
(10, 'Class participation in the discussion'),
(11, 'Grooming / Personality'),
(12, ' Prompt in coming to the class and never been absent'),
(13, 'Time consciousness (arrival / departure)');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

CREATE TABLE `tbldepartment` (
  `department_id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`department_id`, `department_name`) VALUES
(1, 'Information Technology'),
(2, 'BS Educacation (Math)'),
(3, 'BS Educacation (English)'),
(4, 'Basic Education'),
(5, 'BS Hospitality Management'),
(6, 'BS Tourism Management'),
(7, 'BS Criminology'),
(8, 'BS Devcom'),
(9, 'JHS'),
(10, 'SHS');

-- --------------------------------------------------------

--
-- Table structure for table `tblevaluate`
--

CREATE TABLE `tblevaluate` (
  `evaluate_id` int(11) NOT NULL,
  `schoolyear_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `is_evaluated` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblschoolyear`
--

CREATE TABLE `tblschoolyear` (
  `schoolyear_id` int(11) NOT NULL,
  `school_year` varchar(255) NOT NULL,
  `semester` enum('1','2','3','') DEFAULT NULL,
  `is_default` enum('No','Yes') DEFAULT 'No',
  `is_status` enum('Not Yet Started','Started','Closed') DEFAULT 'Not Yet Started'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblschoolyear`
--

INSERT INTO `tblschoolyear` (`schoolyear_id`, `school_year`, `semester`, `is_default`, `is_status`) VALUES
(1, '2025 - 2026', '1', 'Yes', 'Started');

-- --------------------------------------------------------

--
-- Table structure for table `tblsection`
--

CREATE TABLE `tblsection` (
  `section_id` int(11) NOT NULL,
  `section_name` varchar(255) NOT NULL,
  `year_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblsection_teacher_subject`
--

CREATE TABLE `tblsection_teacher_subject` (
  `section_teacher_subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent`
--

CREATE TABLE `tblstudent` (
  `student_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `year_level` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent`
--

INSERT INTO `tblstudent` (`student_id`, `school_id`, `name`, `email`, `department_id`, `password`, `year_level`, `image`) VALUES
(1, 1111111, '1111111 1111111', '1111111@gmail.com', 1, '1111111', 1, '67a437768cb92.png'),
(2, 2222222, '2222222 2222222', '2222222@f.f', 1, '2222222', 4, '67a438039fbec.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblstudent_section`
--

CREATE TABLE `tblstudent_section` (
  `student_section_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `is_regular` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstudent_section`
--

INSERT INTO `tblstudent_section` (`student_section_id`, `student_id`, `section_id`, `is_regular`) VALUES
(1, 1, 0, 0),
(2, 2, 0, 0);

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
(1, 1, 1, 3),
(2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tblsubject`
--

CREATE TABLE `tblsubject` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `is_minor` tinyint(1) NOT NULL DEFAULT 0,
  `subject_type` enum('Major','Minor') NOT NULL DEFAULT 'Minor',
  `department_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblsubject`
--

INSERT INTO `tblsubject` (`subject_id`, `subject_name`, `is_minor`, `subject_type`, `department_id`) VALUES
(1, 'sad', 0, 'Minor', 0),
(2, 'das', 0, 'Minor', 0),
(3, 'asdasd', 0, 'Minor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher`
--

CREATE TABLE `tblteacher` (
  `teacher_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblteacher`
--

INSERT INTO `tblteacher` (`teacher_id`, `school_id`, `name`, `department_id`, `image`) VALUES
(1, 3123123, '3123123 3123123', 1, '67a4382047376.png'),
(2, 5676867, '5676867 5676867', 2, '67a4382eeeb93.png');

-- --------------------------------------------------------

--
-- Table structure for table `tblteacher_section`
--

CREATE TABLE `tblteacher_section` (
  `teacher_section_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblanswer`
--
ALTER TABLE `tblanswer`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `evaluate_id` (`evaluate_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `tblcriteria`
--
ALTER TABLE `tblcriteria`
  ADD PRIMARY KEY (`criteria_id`);

--
-- Indexes for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `tblevaluate`
--
ALTER TABLE `tblevaluate`
  ADD PRIMARY KEY (`evaluate_id`),
  ADD KEY `schoolyear_id` (`schoolyear_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `tblschoolyear`
--
ALTER TABLE `tblschoolyear`
  ADD PRIMARY KEY (`schoolyear_id`);

--
-- Indexes for table `tblsection`
--
ALTER TABLE `tblsection`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `tblsection_teacher_subject`
--
ALTER TABLE `tblsection_teacher_subject`
  ADD PRIMARY KEY (`section_teacher_subject_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tblstudent_section`
--
ALTER TABLE `tblstudent_section`
  ADD PRIMARY KEY (`student_section_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  ADD PRIMARY KEY (`sts_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `tblsubject`
--
ALTER TABLE `tblsubject`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_name` (`subject_name`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD PRIMARY KEY (`teacher_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `tblteacher_section`
--
ALTER TABLE `tblteacher_section`
  ADD PRIMARY KEY (`teacher_section_id`),
  ADD KEY ` teacher_id` (`teacher_id`),
  ADD KEY `section_id` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblanswer`
--
ALTER TABLE `tblanswer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcriteria`
--
ALTER TABLE `tblcriteria`
  MODIFY `criteria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  MODIFY `department_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblevaluate`
--
ALTER TABLE `tblevaluate`
  MODIFY `evaluate_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblschoolyear`
--
ALTER TABLE `tblschoolyear`
  MODIFY `schoolyear_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblsection`
--
ALTER TABLE `tblsection`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblsection_teacher_subject`
--
ALTER TABLE `tblsection_teacher_subject`
  MODIFY `section_teacher_subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstudent_section`
--
ALTER TABLE `tblstudent_section`
  MODIFY `student_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  MODIFY `sts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblsubject`
--
ALTER TABLE `tblsubject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblteacher_section`
--
ALTER TABLE `tblteacher_section`
  MODIFY `teacher_section_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblanswer`
--
ALTER TABLE `tblanswer`
  ADD CONSTRAINT `tblanswer_ibfk_1` FOREIGN KEY (`evaluate_id`) REFERENCES `tblevaluate` (`evaluate_id`),
  ADD CONSTRAINT `tblanswer_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `tblcriteria` (`criteria_id`);

--
-- Constraints for table `tblstudent`
--
ALTER TABLE `tblstudent`
  ADD CONSTRAINT `tblstudent_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbldepartment` (`department_id`);

--
-- Constraints for table `tblstudent_section`
--
ALTER TABLE `tblstudent_section`
  ADD CONSTRAINT `tblstudent_section_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`);

--
-- Constraints for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tblstudent` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `tblteacher` (`teacher_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblstudent_teacher_subject_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `tblsubject` (`subject_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblteacher`
--
ALTER TABLE `tblteacher`
  ADD CONSTRAINT `tblteacher_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `tbldepartment` (`department_id`);

--
-- Constraints for table `tblteacher_section`
--
ALTER TABLE `tblteacher_section`
  ADD CONSTRAINT `tblteacher_section_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `tblteacher` (`teacher_id`),
  ADD CONSTRAINT `tblteacher_section_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `tblsection` (`section_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
