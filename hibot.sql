-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2018 at 03:53 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hibot`
--

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`id`, `label`) VALUES
(1, 'Muscle Cramps'),
(2, 'Flu'),
(3, 'Faintness'),
(4, 'Poisoning');

-- --------------------------------------------------------

--
-- Table structure for table `disease_symptoms`
--

CREATE TABLE `disease_symptoms` (
  `id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `symptom_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `disease_symptoms`
--

INSERT INTO `disease_symptoms` (`id`, `disease_id`, `symptom_id`) VALUES
(1, 2, 1),
(2, 2, 2),
(3, 1, 1),
(4, 1, 2),
(5, 3, 2),
(6, 3, 5),
(7, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`id`, `name`) VALUES
(1, 'Chest Pain'),
(2, 'Dizziness'),
(4, 'Breathing Difficulty'),
(5, 'Sweating');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(3) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'admin1', 'admin1'),
(3, 'admin2', 'admin2'),
(4, 'kazim', '123');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE `user_meta` (
  `id` int(11) NOT NULL,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_meta`
--

INSERT INTO `user_meta` (`id`, `meta_key`, `meta_value`, `user_id`) VALUES
(1, 'reg-name', 'kazim', 1),
(2, 'reg-dob', '08/09/10', 1),
(3, 'reg-address', '                               adasdadasad``', 1),
(4, 'reg-city', 'sad', 1),
(5, 'reg-country', 'dsa', 1),
(6, 'reg-phone', '6115', 1),
(7, 'reg-age', '55', 1),
(8, 'reg-bloodgroup', 'asd', 1),
(9, 'physicians', '[\"asda\",\"asdasd`d`\",\"ASDF\"]', 1),
(10, 'reg-injuryreason', 'ASASASDSASDADADAADSDAAASDADAD', 1),
(11, 'diseases', '[\"ASDAS\",\"123\",\"412312\",\"QQQQQQQ\"]', 1),
(12, 'medicines', '[\"VRFV\",\"CDSR\",\"3EDC\"]', 1),
(13, 'reg-medicinereason', 'DADAD3RE', 1),
(14, 'chest', '{\"discomfort\":\"\",\"experience\":\"\",\"occur\":\"onceamonth\",\"activities\":\"\",\"relieve\":\"\"}', 1),
(15, 'heart', '{\"notice\":\"\",\"bring1\":\"\",\"symtoms\":[\"chestpain\",\"sweaty\",\"discomfort\",\"bodypain\",\"lowenergy\",\"lowbp\"]}', 1),
(16, 'kidney', '{\"kindeysymptomdiagnosed\":[\"bloodtest\",\"protein\"],\"kidneydieases\":\"\",\"symptoms\":[\"hospitalized\",\"stones\"],\"kidneydetails\":\"\"}', 1),
(17, 'smoker', '{\"smoked\":\"\",\"started\":\"\",\"stopped\":\"\",\"length\":\"\",\"use\":\"\",\"modify\":[\"modify1\",\"modify2\",\"modify3\",\"modify5\"]}', 1),
(18, 'sugar', '{\"diabetes\":\"\",\"list\":\"\",\"injection\":\"\",\"type\":\"\",\"complications\":\"\",\"felt\":[\"stressed\"],\"goals\":\"\",\"doctordetails\":\"\"}', 1),
(19, 'reg-healthy', '5', 1),
(20, 'reg-gender', 'male', 1),
(21, 'reg-disease', 'yes', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_symptoms`
--

CREATE TABLE `user_symptoms` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `symptom_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_symptoms`
--

INSERT INTO `user_symptoms` (`id`, `form_id`, `symptom_id`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_form`
--

CREATE TABLE `weekly_form` (
  `id` int(11) NOT NULL,
  `feeling` varchar(500) NOT NULL,
  `is_weird_health` tinyint(1) NOT NULL,
  `medicines` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `submitted_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weekly_form`
--

INSERT INTO `weekly_form` (`id`, `feeling`, `is_weird_health`, `medicines`, `image`, `user_id`, `submitted_date`) VALUES
(1, 'Test', 1, 'Test,,', '', 1, '2018-04-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disease_id` (`disease_id`),
  ADD KEY `disease_id_2` (`disease_id`),
  ADD KEY `fk_symptom_id` (`symptom_id`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UNIQUE` (`username`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`),
  ADD KEY `symptom_id` (`symptom_id`);

--
-- Indexes for table `weekly_form`
--
ALTER TABLE `weekly_form`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `weekly_form`
--
ALTER TABLE `weekly_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  ADD CONSTRAINT `fk_disease_id` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`id`),
  ADD CONSTRAINT `fk_symptom_id` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`);

--
-- Constraints for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  ADD CONSTRAINT `user_symptoms_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `weekly_form` (`id`),
  ADD CONSTRAINT `user_symptoms_ibfk_2` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
