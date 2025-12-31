-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2024 at 02:22 PM
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
-- Database: `abtcdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointmentid` int(11) NOT NULL,
  `patientid` int(11) NOT NULL,
  `appointment_day` date NOT NULL,
  `appointment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `findings`
--

CREATE TABLE `findings` (
  `findingid` int(11) NOT NULL,
  `patientid` int(11) NOT NULL,
  `animal_type` enum('Dog','Cat','Others') NOT NULL,
  `category` enum('1','2','3') NOT NULL,
  `vaccine_type` enum('Verorab','Rabipur') NOT NULL,
  `wound_type` enum('Bite','Scratch') NOT NULL,
  `sob` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `pob` varchar(50) NOT NULL,
  `wound_wash` enum('Yes','No') NOT NULL,
  `tandok` enum('Yes','No') NOT NULL,
  `animal_class` enum('Pet','Stray') NOT NULL,
  `pcec` varchar(50) NOT NULL,
  `pvrv` varchar(50) NOT NULL,
  `erig` varchar(50) NOT NULL,
  `d1` date NOT NULL,
  `d3` date NOT NULL,
  `d7` date NOT NULL,
  `d20/30` date NOT NULL,
  `weight` varchar(20) NOT NULL,
  `bp` varchar(20) NOT NULL,
  `pr` varchar(20) NOT NULL,
  `rr` varchar(20) NOT NULL,
  `temp` varchar(20) NOT NULL,
  `ats` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patientid` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `midname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `sufix` varchar(50) NOT NULL,
  `date_of_birth` date NOT NULL,
  `age` varchar(50) NOT NULL,
  `place_of_birth` varchar(100) NOT NULL,
  `gender` enum('male','female','others') NOT NULL,
  `cpnumber` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `guardian_name` varchar(100) NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `religion` varchar(50) NOT NULL,
  `occupation` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `municipal` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `midname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointmentid`),
  ADD KEY `patientid` (`patientid`);

--
-- Indexes for table `findings`
--
ALTER TABLE `findings`
  ADD PRIMARY KEY (`findingid`),
  ADD KEY `patientid` (`patientid`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patientid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointmentid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `findings`
--
ALTER TABLE `findings`
  MODIFY `findingid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patientid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`patientid`) REFERENCES `patients` (`patientid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `findings`
--
ALTER TABLE `findings`
  ADD CONSTRAINT `findings_ibfk_1` FOREIGN KEY (`patientid`) REFERENCES `patients` (`patientid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
