-- phpMyAdmin SQL Dump
-- version 4.7.1
-- https://www.phpmyadmin.net/
--
-- Host: sql12.freemysqlhosting.net:3306
-- Generation Time: Feb 24, 2021 at 12:09 PM
-- Server version: 5.5.62-0ubuntu0.14.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sql12394568`
--

-- --------------------------------------------------------

--
-- Table structure for table `Answers`
--

CREATE TABLE `Answers` (
  `ansid` int(10) NOT NULL,
  `qnsid` int(10) NOT NULL,
  `userid` int(10) NOT NULL,
  `ans` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `CheckboxQns`
--

CREATE TABLE `CheckboxQns` (
  `cbqid` int(10) NOT NULL,
  `qns` varchar(500) NOT NULL,
  `options` varchar(500) NOT NULL,
  `correctans` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `MCQ`
--

CREATE TABLE `MCQ` (
  `mid` int(10) NOT NULL,
  `qns` varchar(500) NOT NULL,
  `options` varchar(500) NOT NULL,
  `correctans` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Questions`
--

CREATE TABLE `Questions` (
  `qnsid` int(10) NOT NULL,
  `type` int(1) NOT NULL,
  `xid` int(10) NOT NULL,
  `marks` int(5) NOT NULL,
  `qid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Quiz`
--

CREATE TABLE `Quiz` (
  `qid` int(10) NOT NULL,
  `title` varchar(60) NOT NULL,
  `description` varchar(300) NOT NULL,
  `fromdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `todate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shuffle` tinyint(1) NOT NULL,
  `quizkey` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `QuizAttempt`
--

CREATE TABLE `QuizAttempt` (
  `qaid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `qid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `QuizHost`
--

CREATE TABLE `QuizHost` (
  `qhid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `qid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TextQns`
--

CREATE TABLE `TextQns` (
  `tqid` int(10) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `qns` varchar(500) NOT NULL,
  `ans` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `uid` int(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(60) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `otp` int(6) NOT NULL,
  `isverified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`uid`, `name`, `password`, `email`, `mobile`, `otp`, `isverified`) VALUES
(44, 'vaibhav arvindbhai sorathiya', 'b5eac4d9a5cd53b782a7d852ab2fc281', 'vaibhavpatel1921@gmail.com', '1234567', 321438, 1),
(45, 'yash', 'c296539f3286a899d8b3f6632fd62274', '49yash@gmail.com', '123', 707541, 1),
(46, 'yash9xm', '202cb962ac59075b964b07152d234b70', 'yash9xm@gmail.com', '1223345', 342531, 1),
(47, 'deep', '1538d3eb9e345703ccb48f2bc56d9098', 'menparadeep@gmail.com', '2342342344', 996268, 1),
(48, 'vaibhav arvindbhai sorathiya', '202cb962ac59075b964b07152d234b70', 'vaibhavpatel1921@gmail.com', '1234567', 707479, 0),
(49, 'vaibhav arvindbhai sorathiya', '202cb962ac59075b964b07152d234b70', 'vaibhavpatel1921@gmail.com', '1234567', 811195, 1),
(50, 'vaibhav arvindbhai sorathiya', '81dc9bdb52d04dc20036dbd8313ed055', 'vaibhavpatel1921@gmail.com', '123', 343363, 0),
(51, 'vaibhav arvindbhai sorathiya', '202cb962ac59075b964b07152d234b70', 'vaibhavpatel1921@gmail.com', '123', 327762, 0),
(52, 'vaibhav arvindbhai sorathiya', '202cb962ac59075b964b07152d234b70', 'vaibhavpatel1921@gmail.com', '12', 316240, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Answers`
--
ALTER TABLE `Answers`
  ADD PRIMARY KEY (`ansid`),
  ADD KEY `qnsid` (`qnsid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `CheckboxQns`
--
ALTER TABLE `CheckboxQns`
  ADD PRIMARY KEY (`cbqid`);

--
-- Indexes for table `MCQ`
--
ALTER TABLE `MCQ`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`qnsid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `Quiz`
--
ALTER TABLE `Quiz`
  ADD PRIMARY KEY (`qid`);

--
-- Indexes for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  ADD PRIMARY KEY (`qaid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `QuizHost`
--
ALTER TABLE `QuizHost`
  ADD PRIMARY KEY (`qhid`),
  ADD KEY `uid` (`uid`),
  ADD KEY `qid` (`qid`);

--
-- Indexes for table `TextQns`
--
ALTER TABLE `TextQns`
  ADD PRIMARY KEY (`tqid`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Answers`
--
ALTER TABLE `Answers`
  MODIFY `ansid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `CheckboxQns`
--
ALTER TABLE `CheckboxQns`
  MODIFY `cbqid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `MCQ`
--
ALTER TABLE `MCQ`
  MODIFY `mid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Questions`
--
ALTER TABLE `Questions`
  MODIFY `qnsid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Quiz`
--
ALTER TABLE `Quiz`
  MODIFY `qid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  MODIFY `qaid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `QuizHost`
--
ALTER TABLE `QuizHost`
  MODIFY `qhid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `TextQns`
--
ALTER TABLE `TextQns`
  MODIFY `tqid` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `uid` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `Answers`
--
ALTER TABLE `Answers`
  ADD CONSTRAINT `Answers_ibfk_1` FOREIGN KEY (`qnsid`) REFERENCES `Questions` (`qnsid`),
  ADD CONSTRAINT `Answers_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `Users` (`uid`);

--
-- Constraints for table `Questions`
--
ALTER TABLE `Questions`
  ADD CONSTRAINT `Questions_ibfk_1` FOREIGN KEY (`qid`) REFERENCES `Quiz` (`qid`);

--
-- Constraints for table `QuizAttempt`
--
ALTER TABLE `QuizAttempt`
  ADD CONSTRAINT `QuizAttempt_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `Users` (`uid`),
  ADD CONSTRAINT `QuizAttempt_ibfk_2` FOREIGN KEY (`qid`) REFERENCES `Quiz` (`qid`);

--
-- Constraints for table `QuizHost`
--
ALTER TABLE `QuizHost`
  ADD CONSTRAINT `QuizHost_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `Users` (`uid`),
  ADD CONSTRAINT `QuizHost_ibfk_2` FOREIGN KEY (`qid`) REFERENCES `Quiz` (`qid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
