-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 13, 2022 at 07:00 AM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rsa_blowfish_e_exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `classid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `name`, `classid`, `reg_date`) VALUES
(2, 'SSS 3', 'c4ca4238a0b923820dcc509a6f75849b', '2022-11-13 19:45:27'),
(1, 'SSS 2', 'cfcd208495d565ef66e7dff9f98764da', '2022-11-13 19:45:26');

-- --------------------------------------------------------

--
-- Table structure for table `examinations`
--

CREATE TABLE `examinations` (
  `id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `subjectid` varchar(255) NOT NULL,
  `classid` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `examname` varchar(255) DEFAULT NULL,
  `current_term` varchar(20) NOT NULL,
  `examinationpin` varchar(255) DEFAULT NULL,
  `startdate` datetime NOT NULL,
  `enddate` datetime NOT NULL,
  `visibility` varchar(10) NOT NULL,
  `camera_status` varchar(200) DEFAULT NULL,
  `examinationid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `duration` varchar(20) NOT NULL,
  `display` varchar(255) NOT NULL,
  `rsa_public` varchar(255) DEFAULT NULL,
  `rsa_private` varbinary(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `examinations`
--

INSERT INTO `examinations` (`id`, `status`, `subjectid`, `classid`, `description`, `examname`, `current_term`, `examinationpin`, `startdate`, `enddate`, `visibility`, `camera_status`, `examinationid`, `reg_date`, `duration`, `display`, `rsa_public`, `rsa_private`) VALUES
(5, 'available', 'cfcd208495d565ef66e7dff9f98764da', 'c4ca4238a0b923820dcc509a6f75849b', 'Math for encryption', 'Encryption recording', 'First term', '098', '2022-12-12 08:55:12', '2022-12-12 23:59:59', 'yes', NULL, 'a87ff679a2f3e71d9181a67b7542122c', '2022-12-12 07:23:31', '23', 'random', '4kDM', 0x4d446b34),
(4, 'available', 'cfcd208495d565ef66e7dff9f98764da', 'c4ca4238a0b923820dcc509a6f75849b', 'hellow', 'world', 'First term', '1234', '2022-12-11 08:13:08', '2022-12-11 23:59:59', 'yes', NULL, 'c4ca4238a0b923820dcc509a6f75849b', '2022-12-11 13:16:48', '12', 'random', '==ANzITM', 0x4d54497a4e413d3d),
(1, 'unavailable', 'cfcd208495d565ef66e7dff9f98764da', 'c4ca4238a0b923820dcc509a6f75849b', 'helo word', 'Math test', 'First term', '67', '2022-11-13 20:55:47', '2022-11-13 23:59:59', 'yes', NULL, 'cfcd208495d565ef66e7dff9f98764da', '2022-11-13 19:47:55', '5', 'random', NULL, NULL),
(6, 'available', 'cfcd208495d565ef66e7dff9f98764da', 'c4ca4238a0b923820dcc509a6f75849b', 'Math for recording the encryption', 'RSA MATH', 'First term', '8790', '2022-12-12 08:40:43', '2022-12-12 23:59:59', 'yes', NULL, 'e4da3b7fbbce2345d7772b0674a318d5', '2022-12-12 07:53:37', '23', 'random', '==AM5cDO', 0x4f4463354d413d3d);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `examinationid` varchar(255) NOT NULL,
  `questiontype` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `option_a` text NOT NULL,
  `option_b` text NOT NULL,
  `option_c` text NOT NULL,
  `option_d` text NOT NULL,
  `option_e` text NOT NULL,
  `mark` varchar(255) NOT NULL,
  `correct` varchar(255) NOT NULL,
  `questionid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `examinationid`, `questiontype`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `option_e`, `mark`, `correct`, `questionid`, `reg_date`) VALUES
(1, 'a87ff679a2f3e71d9181a67b7542122c', '', 'CSEeqjI/VNrQAc2e5PIJ/1w2SkDQ96EZhN41522ISn7p+oDn1tvCa3/xRwXde7mnaofzgvlWou1Qj/cnZTrvOrXNfa2O072Dv1aCjcbuzu4=', 'fT2Yi1H+j4OcPrizVdnXnPwECT4YZLtcD1aFxP4K4Ic6UrgWHZDn1HAgq1T2GVCu', 'zoQRTSpD6TLQtqsfmppOHIZzI/SA5DFXboct004Rf+1ckIvkCoqGSKtt9esMFnft', 'No/k31azoBWKecmPt8u8uY6lSXwKhfv6nmkDEt1cnjD7TTEBQLpGFPH6i6m4pYQH', 'QlhS/1oJNMHj9tudxG6sPo0zNfMiFOQSNSWiWeAUqHw=', 'KglobCA+4sXswRDR4RO8IA==', 'rdtm5fPR0lYcSpRMVXYSZA==', '5SPWPXvwr61JOj2f+ubnIA==', 'cfcd208495d565ef66e7dff9f98764da', '2022-12-13 05:58:32');

-- --------------------------------------------------------

--
-- Table structure for table `school_information`
--

CREATE TABLE `school_information` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `current_term` varchar(11) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `hostsystem` text DEFAULT NULL,
  `about` text DEFAULT NULL,
  `school_id` varchar(255) NOT NULL,
  `activation_key` varchar(255) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `theme` varchar(255) NOT NULL DEFAULT 'navbar-dark'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `school_information`
--

INSERT INTO `school_information` (`id`, `name`, `logo`, `address`, `current_term`, `city`, `state`, `hostsystem`, `about`, `school_id`, `activation_key`, `last_update`, `theme`) VALUES
(3, 'RSA BLOWFISH E-EXAM', '../images/oic.png', 'Department of Cyber Security Lautech', 'First term', 'Ogbomoso', 'Oyo', 'MCAN', NULL, 'c81e728d9d4c2f636f067f89cc14862c', '==QO2kzN3IjMwcTM', '2022-12-11 07:00:29', 'sidebar-dark-danger'),
(4, 'AES BLOWFISH E-EXAM', 'images/oic.png', 'Department Of Cyber Security Lautech', 'First Term', 'Ogbomoso', 'Oyo', 'MCAN', NULL, 'eccbc87e4b5ce2fe28308fd9f2a7baf3', '==QOxMzM1UTO2YTM', '2022-11-13 12:48:39', 'navbar-dark');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `class` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `studentid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id`, `class`, `userid`, `reg_date`, `studentid`) VALUES
(2, 'c4ca4238a0b923820dcc509a6f75849b', '7f1de29e6da19d22b51c68001e7e0e54', '2022-12-11 13:15:09', 'c4ca4238a0b923820dcc509a6f75849b'),
(3, 'c4ca4238a0b923820dcc509a6f75849b', '42a0e188f5033bc65bf8d78622277c4e', '2022-12-11 13:15:11', 'c81e728d9d4c2f636f067f89cc14862c'),
(1, 'c4ca4238a0b923820dcc509a6f75849b', '02522a2b2726fb0a03bb19f2d8d9524d', '2022-12-11 13:15:05', 'cfcd208495d565ef66e7dff9f98764da'),
(4, 'c4ca4238a0b923820dcc509a6f75849b', '3988c7f88ebcb58c6ce932b957b6f332', '2022-12-11 13:15:18', 'eccbc87e4b5ce2fe28308fd9f2a7baf3');

-- --------------------------------------------------------

--
-- Table structure for table `studentexmination`
--

CREATE TABLE `studentexmination` (
  `id` int(11) NOT NULL,
  `questionid` varchar(255) NOT NULL,
  `studentid` varchar(255) NOT NULL,
  `examinationid` varchar(255) NOT NULL,
  `answerstatus` varchar(50) NOT NULL,
  `answerid` varchar(50) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `selectedoption` varchar(50) DEFAULT NULL,
  `correctness` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `studentstartexamination`
--

CREATE TABLE `studentstartexamination` (
  `id` int(11) NOT NULL,
  `studentid` varchar(255) NOT NULL,
  `examinationid` varchar(255) NOT NULL,
  `classid` varchar(255) NOT NULL,
  `examinationtype` varchar(255) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `active_status` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `grade` varchar(255) DEFAULT NULL,
  `totalquestionanswered` int(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `totalquestions` int(11) DEFAULT NULL,
  `scores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`scores`)),
  `score` float NOT NULL DEFAULT 0,
  `photo` varchar(255) DEFAULT NULL,
  `capture_status` int(11) DEFAULT NULL,
  `captured_photo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`captured_photo`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subjectname` varchar(255) NOT NULL,
  `classid` varchar(255) NOT NULL,
  `subjectid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subjectname`, `classid`, `subjectid`, `reg_date`) VALUES
(1, 'Math', 'c4ca4238a0b923820dcc509a6f75849b', 'cfcd208495d565ef66e7dff9f98764da', '2022-11-13 19:46:48');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `teacherid` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `classid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `userid`, `teacherid`, `reg_date`, `classid`) VALUES
(2, '9fc3d7152ba9336a670e36d0ed79bc43', 'c4ca4238a0b923820dcc509a6f75849b', '2022-11-13 19:45:27', 'c4ca4238a0b923820dcc509a6f75849b'),
(1, '65ded5353c5ee48d0b7d48c591b8f430', 'cfcd208495d565ef66e7dff9f98764da', '2022-11-13 19:45:27', 'cfcd208495d565ef66e7dff9f98764da');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `userid` varchar(255) NOT NULL,
  `active_status` int(11) DEFAULT NULL,
  `role` varchar(255) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `schoolid` varchar(255) DEFAULT NULL,
  `dob` varchar(255) DEFAULT NULL,
  `avatar` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `surname`, `firstname`, `lastname`, `username`, `password`, `userid`, `active_status`, `role`, `reg_date`, `phone`, `email`, `schoolid`, `dob`, `avatar`) VALUES
(135, 'Viillabe', 'Boy', 'vboy', 'vbvboy', '1a699ad5e06aa8a6db3bcf9cfb2f00f2', '02522a2b2726fb0a03bb19f2d8d9524d', 0, 'student', '2022-12-11 13:28:39', NULL, NULL, 'c81e728d9d4c2f636f067f89cc14862c', NULL, '../students_avatar/viillabeboyvboy_470_1670764505.jpg'),
(132, 'Moyo', 'Sore', 'Aduke', 'moyo', '7f3d09296993260d900daef15524963f', '1afa34a7f984eeabdbb0a7d494132ee5', 1, 'admin', '2022-11-13 12:50:59', NULL, NULL, 'c81e728d9d4c2f636f067f89cc14862c', NULL, NULL),
(138, 'Viillabe', 'Boy', 'vboy', 'vbvboy2', '1a699ad5e06aa8a6db3bcf9cfb2f00f2', '3988c7f88ebcb58c6ce932b957b6f332', 0, 'student', '2022-12-12 07:28:20', NULL, NULL, NULL, NULL, '../students_avatar/viillabeboyvboy_446_1670764518.jpg'),
(137, 'Viillabe', 'Boy', 'vboy', 'vbvboy1', '1a699ad5e06aa8a6db3bcf9cfb2f00f2', '42a0e188f5033bc65bf8d78622277c4e', 0, 'student', '2022-12-13 05:35:19', NULL, NULL, NULL, NULL, '../students_avatar/viillabeboyvboy_199_1670764511.jpg'),
(133, 'Simeon', 'Xfinder', 'Adewale', 'sxadewale', 'a8f31ef55249ef19cbdeb288097a622a', '65ded5353c5ee48d0b7d48c591b8f430', NULL, 'teacher', '2022-11-13 19:45:27', NULL, NULL, NULL, NULL, 'staffs_avatar/default.png'),
(136, 'Viillabe', 'Boy', 'vboy', 'vbvboy0', '1a699ad5e06aa8a6db3bcf9cfb2f00f2', '7f1de29e6da19d22b51c68001e7e0e54', NULL, 'student', '2022-12-11 13:15:09', NULL, NULL, NULL, NULL, '../students_avatar/viillabeboyvboy_560_1670764508.jpg'),
(134, 'ODUBANJO', 'IBUKUN', 'Adesina', 'oiadesina', 'af3a0f60e9f6f2f43df87b30bc600268', '9fc3d7152ba9336a670e36d0ed79bc43', NULL, 'teacher', '2022-11-13 19:45:27', NULL, NULL, NULL, NULL, 'staffs_avatar/default.png'),
(1, 'admins', 'kabiru', 'adesina', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 1, 'admin', '2022-12-13 05:35:22', NULL, NULL, 'c81e728d9d4c2f636f067f89cc14862c', NULL, NULL),
(129, 'olokun', 'kabiru', 'adesina', 'admin3', '21232f297a57a5a743894a0e4a801fc3', 'c4ca4238a0b923820dcc509a6f75849b', NULL, 'admin', '2022-10-05 21:14:05', NULL, NULL, 'cfcd208495d565ef66e7dff9f98764da', NULL, NULL),
(131, 'Sunday', 'annonymus', 'anousmousy 2', 'admin1', '21232f297a57a5a743894a0e4a801fc3', 'd1f491a404d6854880943e5c3cd9ca25', NULL, 'admin', '2022-10-31 14:05:28', NULL, NULL, 'c81e728d9d4c2f636f067f89cc14862c', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`classid`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `examinations`
--
ALTER TABLE `examinations`
  ADD PRIMARY KEY (`examinationid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `classid` (`classid`),
  ADD KEY `subjectid` (`subjectid`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`questionid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `examinationid` (`examinationid`);

--
-- Indexes for table `school_information`
--
ALTER TABLE `school_information`
  ADD PRIMARY KEY (`school_id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`studentid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `class` (`class`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `studentexmination`
--
ALTER TABLE `studentexmination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examinationid` (`examinationid`),
  ADD KEY `questionid` (`questionid`),
  ADD KEY `studentid` (`studentid`);

--
-- Indexes for table `studentstartexamination`
--
ALTER TABLE `studentstartexamination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `classid` (`classid`),
  ADD KEY `examinationid` (`examinationid`),
  ADD KEY `studentstartexamination_ibfk_2` (`studentid`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjectid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `classid` (`classid`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacherid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `classid` (`classid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `examinations`
--
ALTER TABLE `examinations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `school_information`
--
ALTER TABLE `school_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studentexmination`
--
ALTER TABLE `studentexmination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `studentstartexamination`
--
ALTER TABLE `studentstartexamination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `examinations`
--
ALTER TABLE `examinations`
  ADD CONSTRAINT `examinations_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  ADD CONSTRAINT `examinations_ibfk_2` FOREIGN KEY (`subjectid`) REFERENCES `subjects` (`subjectid`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`examinationid`) REFERENCES `examinations` (`examinationid`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`class`) REFERENCES `class` (`classid`),
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);

--
-- Constraints for table `studentexmination`
--
ALTER TABLE `studentexmination`
  ADD CONSTRAINT `studentexmination_ibfk_1` FOREIGN KEY (`examinationid`) REFERENCES `examinations` (`examinationid`),
  ADD CONSTRAINT `studentexmination_ibfk_2` FOREIGN KEY (`questionid`) REFERENCES `questions` (`questionid`),
  ADD CONSTRAINT `studentexmination_ibfk_3` FOREIGN KEY (`studentid`) REFERENCES `student` (`studentid`);

--
-- Constraints for table `studentstartexamination`
--
ALTER TABLE `studentstartexamination`
  ADD CONSTRAINT `studentstartexamination_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  ADD CONSTRAINT `studentstartexamination_ibfk_2` FOREIGN KEY (`studentid`) REFERENCES `student` (`studentid`),
  ADD CONSTRAINT `studentstartexamination_ibfk_3` FOREIGN KEY (`examinationid`) REFERENCES `examinations` (`examinationid`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`);

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_ibfk_1` FOREIGN KEY (`classid`) REFERENCES `class` (`classid`),
  ADD CONSTRAINT `teachers_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
