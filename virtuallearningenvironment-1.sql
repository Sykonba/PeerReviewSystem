-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2015 at 10:25 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `virtuallearningenvironment`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
`adminID` int(10) unsigned NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(16) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`adminID`, `firstName`, `lastName`, `email`, `password`, `salt`) VALUES
(2, 'Admin', 'Admin', 'admin@admin.com', '7de964eb96587a04854e12b10feb82d912f8b3d89d41460a7985699b627b8e34', '459896f642703592');

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE IF NOT EXISTS `assessment` (
`id` int(10) unsigned NOT NULL,
  `grade` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL,
  `comments` text NOT NULL,
  `dateOfComment` date NOT NULL,
  `reportID` int(10) unsigned NOT NULL,
  `studentID` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`groupID` int(10) unsigned NOT NULL,
  `groupName` varchar(32) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`groupID`, `groupName`) VALUES
(0, 'NO GROUP'),
(2, 'Group 22'),
(4, 'Group 4'),
(5, 'Group 1'),
(6, 'Group 3'),
(7, 'BE'),
(8, 'MBBS'),
(9, 'UCL Group'),
(10, 'Group 33'),
(12, 'NOT ASSIGNED');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
`reportID` int(10) unsigned NOT NULL,
  `reportName` varchar(50) NOT NULL,
  `dateOfSubmission` date NOT NULL,
  `fileName` text NOT NULL,
  `studentID` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE IF NOT EXISTS `students` (
`studentID` int(11) unsigned NOT NULL,
  `firstName` varchar(32) NOT NULL,
  `lastName` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(15) NOT NULL,
  `salt` char(16) NOT NULL,
  `groupID` int(10) unsigned NOT NULL,
  `dateOfBirth` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentID`, `firstName`, `lastName`, `email`, `password`, `salt`, `groupID`, `dateOfBirth`) VALUES
(5, 'Andrew', 'Dahal', 'andrew@test.com', '7de964eb96587a0', '459896f642703592', 6, '1985-03-03'),
(6, 'Barnan', 'Lamichhane', 'barnan@test.com', '7de964eb96587a0', '459896f642703592', 2, '1988-02-02'),
(7, 'David ', 'Jones', 'david@test.com', '7de964eb96587a0', '459896f642703592', 8, '1990-10-07'),
(8, 'James', 'Holmes', 'james@test.com', '7de964eb96587a0', '459896f642703592', 8, '1985-03-10'),
(9, 'Dave ', 'Watson', 'dave@test.com', '7de964eb96587a0', '459896f642703592', 8, '1992-02-01'),
(10, 'Tom', 'Coles', 'tom@test.com', '7de964eb96587a0', '459896f642703592', 6, '1991-01-12'),
(11, 'Robin', 'Friedman', 'marty@test.com', '7de964eb96587a0', '459896f642703592', 2, '1995-02-02');

-- --------------------------------------------------------

--
-- Table structure for table `visibility`
--

CREATE TABLE IF NOT EXISTS `visibility` (
  `groupID` int(10) unsigned NOT NULL,
  `reportID` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
 ADD PRIMARY KEY (`adminID`);

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
 ADD PRIMARY KEY (`id`), ADD KEY `reportID` (`reportID`), ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`groupID`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
 ADD PRIMARY KEY (`reportID`), ADD KEY `studentID` (`studentID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
 ADD PRIMARY KEY (`studentID`), ADD KEY `groupID` (`groupID`);

--
-- Indexes for table `visibility`
--
ALTER TABLE `visibility`
 ADD KEY `groupID` (`groupID`), ADD KEY `reportID` (`reportID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
MODIFY `adminID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
MODIFY `groupID` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
MODIFY `reportID` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
MODIFY `studentID` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
ADD CONSTRAINT `assessment_ibfk_1` FOREIGN KEY (`reportID`) REFERENCES `reports` (`reportID`),
ADD CONSTRAINT `assessment_ibfk_2` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`);

--
-- Constraints for table `visibility`
--
ALTER TABLE `visibility`
ADD CONSTRAINT `visibility_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `groups` (`groupID`),
ADD CONSTRAINT `visibility_ibfk_2` FOREIGN KEY (`reportID`) REFERENCES `reports` (`reportID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
