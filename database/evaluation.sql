-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2025 at 08:50 AM
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
(9, 'Nelson Nellas', 'nelson', 'nelson@gmail.com', '$2y$10$RhrrQQ7BpdD4zC6U3MEQY..o429Lf2HNb1hIQKWqSOn2llVOUYRli', 'upload/pics/67a4347e30608.png', '2025-02-06 04:03:10'),
(10, 'Catriona Dawg', 'admin_cat', 'catriona@gmail.com', '$2y$10$mCrbmTsUSwthDbnldivqa.y4UqWYIrz.a5TbT4cJ.xLmPYUvMLOgy', 'upload/pics/67a95bbca62ed.png', '2025-02-10 01:51:56');

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

--
-- Dumping data for table `tblanswer`
--

INSERT INTO `tblanswer` (`answer_id`, `evaluate_id`, `criteria_id`, `ratings`, `comment`) VALUES
(1, 1, 1, '4', '0'),
(2, 1, 2, '4', '0'),
(3, 1, 3, '3', '0'),
(4, 1, 4, '3', '0'),
(5, 1, 5, '4', '0'),
(6, 1, 6, '3', '0'),
(7, 1, 7, '4', '0'),
(8, 1, 8, '3', '0'),
(9, 1, 9, '4', '0'),
(10, 1, 10, '4', '0'),
(11, 1, 11, '4', '0'),
(12, 1, 12, '3', '0'),
(13, 1, 13, '3', '0'),
(14, 2, 1, '1', 'Thank you lord'),
(15, 2, 2, '4', '0'),
(16, 2, 3, '4', '0'),
(17, 2, 4, '4', '0'),
(18, 2, 5, '3', '0'),
(19, 2, 6, '4', '0'),
(20, 2, 7, '4', '0'),
(21, 2, 8, '3', '0'),
(22, 2, 9, '4', '0'),
(23, 2, 10, '4', '0'),
(24, 2, 11, '4', '0'),
(25, 2, 12, '3', '0'),
(26, 2, 13, '4', '0'),
(27, 3, 1, '4', 'Thank you lord'),
(28, 3, 2, '4', '0'),
(29, 3, 3, '4', '0'),
(30, 3, 4, '4', '0'),
(31, 3, 5, '4', '0'),
(32, 3, 6, '4', '0'),
(33, 3, 7, '4', '0'),
(34, 3, 8, '4', '0'),
(35, 3, 9, '4', '0'),
(36, 3, 10, '4', '0'),
(37, 3, 11, '4', '0'),
(38, 3, 12, '3', '0'),
(39, 3, 13, '1', '0'),
(40, 4, 1, '4', 'I like the way he teach us'),
(41, 4, 2, '3', '0'),
(42, 4, 3, '4', '0'),
(43, 4, 4, '3', '0'),
(44, 4, 5, '3', '0'),
(45, 4, 6, '3', '0'),
(46, 4, 7, '4', '0'),
(47, 4, 8, '4', '0'),
(48, 4, 9, '4', '0'),
(49, 4, 10, '3', '0'),
(50, 4, 11, '4', '0'),
(51, 4, 12, '4', '0'),
(52, 4, 13, '3', '0'),
(53, 5, 1, '4', 'Goods'),
(54, 5, 2, '2', '0'),
(55, 5, 3, '3', '0'),
(56, 5, 4, '3', '0'),
(57, 5, 5, '3', '0'),
(58, 5, 6, '3', '0'),
(59, 5, 7, '3', '0'),
(60, 5, 8, '3', '0'),
(61, 5, 9, '3', '0'),
(62, 5, 10, '3', '0'),
(63, 5, 11, '3', '0'),
(64, 5, 12, '4', '0'),
(65, 5, 13, '3', '0'),
(66, 6, 1, '4', '0'),
(67, 6, 2, '4', 'All of her subject is good'),
(68, 6, 3, '3', '0'),
(69, 6, 4, '4', '0'),
(70, 6, 5, '4', '0'),
(71, 6, 6, '3', '0'),
(72, 6, 7, '4', '0'),
(73, 6, 8, '4', '0'),
(74, 6, 9, '4', '0'),
(75, 6, 10, '4', '0'),
(76, 6, 11, '3', '0'),
(77, 6, 12, '4', '0'),
(78, 6, 13, '3', '0'),
(79, 7, 1, '4', 'I understand why law is important thank you hehe'),
(80, 7, 2, '4', '0'),
(81, 7, 3, '4', '0'),
(82, 7, 4, '3', '0'),
(83, 7, 5, '3', '0'),
(84, 7, 6, '4', '0'),
(85, 7, 7, '3', '0'),
(86, 7, 8, '4', '0'),
(87, 7, 9, '3', '0'),
(88, 7, 10, '3', '0'),
(89, 7, 11, '3', '0'),
(90, 7, 12, '4', '0'),
(91, 7, 13, '3', '0'),
(92, 8, 1, '4', 'nays'),
(93, 8, 2, '4', '0'),
(94, 8, 3, '2', '0'),
(95, 8, 4, '3', '0'),
(96, 8, 5, '3', '0'),
(97, 8, 6, '3', '0'),
(98, 8, 7, '4', '0'),
(99, 8, 8, '3', '0'),
(100, 8, 9, '3', '0'),
(101, 8, 10, '3', '0'),
(102, 8, 11, '4', '0'),
(103, 8, 12, '3', '0'),
(104, 8, 13, '4', '0'),
(105, 9, 1, '3', '0'),
(106, 9, 2, '3', 'Strict sha sa class but makat\'on rasad'),
(107, 9, 3, '3', '0'),
(108, 9, 4, '4', '0'),
(109, 9, 5, '4', '0'),
(110, 9, 6, '3', '0'),
(111, 9, 7, '3', '0'),
(112, 9, 8, '4', '0'),
(113, 9, 9, '4', '0'),
(114, 9, 10, '3', '0'),
(115, 9, 11, '4', '0'),
(116, 9, 12, '4', '0'),
(117, 9, 13, '3', '0'),
(118, 10, 1, '4', '0'),
(119, 10, 2, '3', '0'),
(120, 10, 3, '4', 'All goods ra iyang management and lesson take care'),
(121, 10, 4, '4', '0'),
(122, 10, 5, '4', '0'),
(123, 10, 6, '3', '0'),
(124, 10, 7, '3', '0'),
(125, 10, 8, '4', '0'),
(126, 10, 9, '3', '0'),
(127, 10, 10, '4', '0'),
(128, 10, 11, '4', '0'),
(129, 10, 12, '4', '0'),
(130, 10, 13, '4', '0'),
(131, 11, 1, '1', '0'),
(132, 11, 2, '1', '0'),
(133, 11, 3, '1', '0'),
(134, 11, 4, '1', '0'),
(135, 11, 5, '1', '0'),
(136, 11, 6, '1', '0'),
(137, 11, 7, '1', '0'),
(138, 11, 8, '1', '0'),
(139, 11, 9, '1', '0'),
(140, 11, 10, '1', '0'),
(141, 11, 11, '1', '0'),
(142, 11, 12, '1', '0'),
(143, 11, 13, '1', '0');

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

--
-- Dumping data for table `tblevaluate`
--

INSERT INTO `tblevaluate` (`evaluate_id`, `schoolyear_id`, `student_id`, `teacher_id`, `is_evaluated`) VALUES
(1, 1, 1, 1, 0),
(2, 1, 2, 1, 0),
(3, 1, 3, 1, 0),
(4, 1, 4, 3, 0),
(5, 1, 4, 2, 0),
(6, 1, 4, 1, 0),
(7, 1, 5, 1, 0),
(8, 1, 6, 1, 0),
(9, 1, 6, 2, 0),
(10, 1, 6, 3, 0),
(11, 1, 7, 4, 0);

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

--
-- Dumping data for table `tblsection`
--

INSERT INTO `tblsection` (`section_id`, `section_name`, `year_level`) VALUES
(1, 'c4-1', 1),
(2, 'Kamunggay', 2);

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

--
-- Dumping data for table `tblsection_teacher_subject`
--

INSERT INTO `tblsection_teacher_subject` (`section_teacher_subject_id`, `section_id`, `teacher_id`, `subject_id`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 2, 1, 3),
(4, 1, 3, 4),
(5, 1, 2, 3),
(6, 2, 2, 2),
(7, 2, 3, 4),
(8, 1, 1, 5),
(9, 1, 4, 6),
(10, 2, 4, 6);

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
(1, 1234567, 'Devon Barangan', 'sample@gmail.com', 1, '1234567', 1, '67a48bf800365.png'),
(2, 9876543, 'Juan Dela Cruz', 'juan@gmail.com', 3, '9876543', 2, '67a48c3cddc9f.png'),
(3, 6246262, 'Maria Juana', '', 3, '6246262', 1, 'Teacher 7.png'),
(4, 1215135, 'Fish Cracker', 'fish@gmail.com', 1, '1215135', 1, '67ab0fe939545.png'),
(5, 6312413, 'Dionela C. Kasper', 'dionela@gmail.com', 7, '6312413', 4, '67ab125950b03.png'),
(6, 6436347, 'Cutie  Pie', 'cutie@gmail.com', 1, '6436347', 4, '67abe23fbc0d4.png'),
(7, 5231241, 'Cristine Bughaw', 'cristine@gmail.com', 6, '5231241', 4, '67abe545c5904.png'),
(8, 9573801, 'Zayn Ma\'lick', 'zayn@gmail.com', 1, '9573801', 2, '67abef9a73672.png'),
(9, 6826141, 'Curly Tops', 'curly@gmail.com', 3, '6826141', 1, '67abefb7bd7f4.png'),
(10, 1513653, 'Peter Pan', 'peter@gmail.com', 1, '1513653', 1, '67ac52a3aaf1d.png');

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
(1, 1, 1, 1),
(2, 2, 0, 0),
(3, 3, 2, 1),
(4, 4, 1, 1),
(5, 5, 0, 0),
(6, 6, 2, 1),
(7, 7, 0, 0),
(8, 8, 2, 1),
(9, 9, 0, 0),
(10, 10, 2, 1);

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
(1, 'ITDB', 0, 'Minor', 0),
(2, 'ITSPI', 0, 'Minor', 0),
(3, 'ETHICS', 0, 'Minor', 0),
(4, 'Oral Communication', 0, 'Minor', 0),
(5, 'Political Studies', 0, 'Minor', 0),
(6, 'Tourism & Spot', 0, 'Minor', 0);

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
(1, 8979879, 'Mary Jane', 1, '67a48c5c41afc.png'),
(2, 7531515, 'Jane De Leon', 3, '67a48c720c6c7.png'),
(3, 1245556, 'Jhayvot G. Marilag', 4, '67ab100e514dd.png'),
(4, 5313151, 'Zae Phymer', 6, 'Teacher 3.png');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblanswer`
--
ALTER TABLE `tblanswer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

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
  MODIFY `evaluate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tblschoolyear`
--
ALTER TABLE `tblschoolyear`
  MODIFY `schoolyear_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblsection`
--
ALTER TABLE `tblsection`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblsection_teacher_subject`
--
ALTER TABLE `tblsection_teacher_subject`
  MODIFY `section_teacher_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblstudent`
--
ALTER TABLE `tblstudent`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblstudent_section`
--
ALTER TABLE `tblstudent_section`
  MODIFY `student_section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblstudent_teacher_subject`
--
ALTER TABLE `tblstudent_teacher_subject`
  MODIFY `sts_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblsubject`
--
ALTER TABLE `tblsubject`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblteacher`
--
ALTER TABLE `tblteacher`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
