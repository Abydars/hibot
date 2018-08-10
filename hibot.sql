-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2018 at 12:31 PM
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
(4, 'Poisoning'),
(5, 'Anxiety Disorder'),
(6, 'Shallow Breathing'),
(7, 'Viral Infection'),
(8, 'Heart Burn'),
(9, 'HyperVentilation'),
(10, 'Bacterial Infection'),
(11, 'Collapsed Lungs Feeling'),
(12, 'Asthma'),
(13, 'Migraine'),
(14, 'Sickness'),
(15, 'influenza Flu'),
(16, 'Eye Infection'),
(17, 'Seasonal Depression'),
(18, 'Biopolar Disorder'),
(19, 'Motion'),
(20, 'High Fever'),
(21, 'Cough Problem'),
(22, 'Viral Pneumonia'),
(23, 'Common Cold'),
(24, 'Strep Throat'),
(25, 'Viral Hypertenion'),
(26, 'Lung Infection'),
(29, 'ftftf');

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
(1, 5, 1),
(2, 6, 1),
(3, 5, 3),
(4, 6, 3),
(5, 3, 2),
(6, 4, 2),
(7, 7, 2),
(8, 8, 2),
(9, 9, 2),
(10, 10, 2),
(11, 3, 3),
(12, 4, 3),
(13, 7, 3),
(14, 8, 3),
(15, 9, 3),
(16, 10, 3),
(19, 11, 3),
(20, 12, 3),
(21, 13, 3),
(22, 14, 3),
(23, 11, 4),
(24, 12, 4),
(25, 13, 4),
(26, 14, 4),
(27, 2, 4),
(28, 7, 4),
(30, 2, 5),
(31, 7, 5),
(32, 13, 5),
(33, 16, 5),
(34, 16, 6),
(35, 13, 6),
(36, 17, 6),
(37, 18, 6),
(39, 17, 7),
(40, 18, 7),
(42, 19, 7),
(43, 15, 8),
(44, 5, 8),
(45, 17, 8),
(46, 3, 8),
(47, 18, 9),
(49, 7, 9),
(50, 18, 10),
(52, 7, 10),
(53, 23, 11),
(54, 3, 11),
(55, 14, 11),
(56, 6, 11),
(57, 8, 12),
(58, 26, 12),
(59, 10, 12),
(60, 3, 12),
(61, 20, 13),
(62, 10, 13),
(63, 14, 13),
(64, 26, 13),
(65, 15, 13),
(66, 11, 14),
(67, 23, 14),
(68, 2, 14),
(69, 10, 14),
(71, 14, 14),
(75, 1, 1),
(76, 1, 2),
(77, 1, 9),
(83, 21, 2),
(84, 21, 3),
(85, 29, 2),
(86, 29, 3),
(87, 29, 4);

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
(3, 'Sweating'),
(4, 'Breathing Difficulty'),
(5, 'Eye Discomfort'),
(6, 'Headache'),
(7, 'Sick Stomach'),
(8, 'Insomnia'),
(9, 'Body Pain'),
(10, 'Energy Loss'),
(11, 'Low Blood Pressure'),
(12, 'High Blood Pressure'),
(13, 'Fever'),
(14, 'Cough Problem'),
(15, 'Testing');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(3) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `name`, `phone`, `role`) VALUES
(1, 'admin', 'admin', 'Kazim', NULL, 'admin'),
(2, 'admin1', 'admin1', NULL, NULL, 'user'),
(3, 'admin2', 'admin2', NULL, NULL, 'user'),
(4, 'kazim', '123', NULL, NULL, 'user'),
(5, 'mub', '123', NULL, NULL, 'user'),
(6, 'tester', 'tester', 'Test', '49280394', 'user'),
(7, 'tester123', 'tester', 'tester', '429038', 'user'),
(8, 'kk', 'admin', 'kirmani', '03322514962', 'user'),
(9, 'kirmani', '123', 'kirmani', '03322514962', 'user'),
(10, 'ali khan', '123', 'ali', '123456789', 'user'),
(11, 'po', '1', 'po', '12', 'user');

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
(3, 'reg-address', '                                   adasdadasad``', 1),
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
(14, 'chest', '{\"discomfort\":\"test\",\"experience\":\"\",\"occur\":\"onceamonth\",\"activities\":\"\",\"relieve\":\"\"}', 1),
(15, 'heart', '{\"notice\":\"\",\"bring1\":\"\",\"symtoms\":[\"chestpain\",\"sweaty\",\"discomfort\",\"bodypain\",\"lowenergy\",\"lowbp\"]}', 1),
(16, 'kidney', '{\"kindeysymptomdiagnosed\":[\"bloodtest\",\"protein\"],\"kidneydieases\":\"\",\"symptoms\":[\"hospitalized\",\"stones\"],\"kidneydetails\":\"\"}', 1),
(17, 'smoker', '{\"smoked\":\"\",\"started\":\"\",\"stopped\":\"\",\"length\":\"\",\"use\":\"\",\"modify\":[\"modify1\",\"modify2\",\"modify3\",\"modify5\"]}', 1),
(18, 'sugar', '{\"diabetes\":\"\",\"list\":\"\",\"injection\":\"\",\"type\":\"\",\"complications\":\"\",\"felt\":[\"stressed\"],\"goals\":\"\",\"doctordetails\":\"\"}', 1),
(19, 'reg-healthy', '5', 1),
(20, 'reg-gender', 'male', 1),
(21, 'reg-disease', 'yes', 1),
(22, 'reg-name', '', 7),
(23, 'reg-dob', '', 7),
(24, 'reg-address', '  ', 7),
(25, 'reg-city', '', 7),
(26, 'reg-country', '', 7),
(27, 'reg-phone', '', 7),
(28, 'reg-age', '', 7),
(29, 'reg-bloodgroup', '', 7),
(30, 'physicians', '[\"\",\"\",\"\"]', 7),
(31, 'reg-injuryreason', '', 7),
(32, 'diseases', '[\"\",\"\",\"\",\"\"]', 7),
(33, 'medicines', '[\"\",\"\",\"\"]', 7),
(34, 'reg-medicinereason', '', 7),
(35, 'chest', '{\"discomfort\":\"\",\"experience\":\"\",\"activities\":\"\",\"relieve\":\"\"}', 7),
(36, 'heart', '{\"notice\":\"\",\"bring1\":\"\"}', 7),
(37, 'kidney', '{\"kidneydieases\":\"\",\"kidneydetails\":\"\"}', 7),
(38, 'smoker', '{\"smoked\":\"\",\"started\":\"\",\"stopped\":\"\",\"length\":\"\",\"use\":\"\"}', 7),
(39, 'sugar', '{\"diabetes\":\"\",\"list\":\"\",\"injection\":\"\",\"type\":\"\",\"complications\":\"\",\"goals\":\"\",\"doctordetails\":\"\"}', 7),
(40, 'image', 'field.jpg', 7),
(41, 'filename', 'uploads/field.jpg', 7),
(42, 'reg-name', 'ali khan', 10),
(43, 'reg-dob', '', 10),
(44, 'reg-address', '  ', 10),
(45, 'reg-city', '', 10),
(46, 'reg-country', '', 10),
(47, 'reg-phone', '', 10),
(48, 'reg-age', '', 10),
(49, 'reg-bloodgroup', '', 10),
(50, 'physicians', '[\"\",\"\",\"\"]', 10),
(51, 'reg-injuryreason', '', 10),
(52, 'diseases', '[\"\",\"\",\"\",\"\"]', 10),
(53, 'medicines', '[\"\",\"\",\"\"]', 10),
(54, 'reg-medicinereason', '', 10),
(55, 'chest', '{\"discomfort\":\"\",\"experience\":\"\",\"activities\":\"\",\"relieve\":\"\"}', 10),
(56, 'heart', '{\"notice\":\"\",\"bring1\":\"\"}', 10),
(57, 'kidney', '{\"kidneydieases\":\"\",\"kidneydetails\":\"\"}', 10),
(58, 'smoker', '{\"smoked\":\"\",\"started\":\"\",\"stopped\":\"\",\"length\":\"\",\"use\":\"\"}', 10),
(59, 'sugar', '{\"diabetes\":\"\",\"list\":\"\",\"injection\":\"\",\"type\":\"\",\"complications\":\"\",\"goals\":\"\",\"doctordetails\":\"\"}', 10);

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
(5, 3, 5),
(6, 4, 1),
(8, 6, 3),
(9, 6, 8),
(10, 6, 9),
(11, 7, 1),
(12, 7, 10),
(13, 7, 14),
(14, 8, 1),
(15, 8, 2),
(16, 9, 1),
(17, 9, 2),
(18, 9, 8),
(19, 9, 13),
(20, 10, 1),
(21, 10, 2),
(22, 11, 1),
(23, 12, 2),
(24, 12, 3),
(25, 13, 4),
(26, 13, 10),
(27, 13, 11),
(28, 14, 2),
(29, 14, 4),
(30, 14, 7),
(31, 14, 9),
(32, 14, 10),
(33, 15, 4),
(34, 15, 5),
(35, 15, 6),
(36, 15, 7),
(37, 15, 11),
(38, 15, 12),
(39, 15, 13),
(40, 15, 14),
(41, 16, 1),
(42, 16, 4),
(43, 16, 9),
(44, 17, 7),
(45, 17, 13),
(46, 17, 14),
(47, 18, 1),
(48, 18, 9),
(49, 18, 11),
(50, 19, 1),
(51, 19, 4),
(52, 19, 11),
(53, 20, 5),
(54, 20, 7),
(55, 20, 12),
(56, 21, 5),
(57, 21, 7),
(58, 21, 12),
(59, 22, 6),
(60, 22, 7),
(61, 22, 13),
(62, 23, 1),
(63, 23, 10),
(64, 23, 14),
(65, 24, 13),
(66, 24, 14);

-- --------------------------------------------------------

--
-- Table structure for table `user_uploads`
--

CREATE TABLE `user_uploads` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_uploads`
--

INSERT INTO `user_uploads` (`id`, `filename`, `form_id`) VALUES
(1, 'uploads/Fall-wallpaper.jpg', 11),
(2, 'uploads/Application Form.jpg', 13);

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
(3, 'Test', 1, 'Test,test,test', '', 1, '2018-04-21'),
(4, 'Test', 1, 'test,,', '', 1, '2018-04-21'),
(6, 'kazim', 1, 'ad,ad,ad', '', 1, '2018-05-06'),
(7, 'mubashir', 1, 'lll,ll,ll', '', 5, '2018-05-06'),
(8, 'Test', 1, ',,', '', 1, '2018-05-13'),
(9, 'Test', 1, ',,', '', 1, '2018-05-13'),
(10, 'Test', 1, 'test,,', '', 1, '2018-05-13'),
(11, 'test', 1, ',,', '', 1, '2018-05-13'),
(12, 'test', 1, 'test,,', '', 7, '2018-05-13'),
(13, 'kazim kirmanikazim kirmanikazim kirmanikazim kirmanikazim kirmanikazim kirmanikazim kirmanikazim kirmani', 1, 'panadol,panadol,panadol', '', 1, '2018-05-14'),
(14, '2e2e2e2e2e2e2e', 1, 'e2e,2e2,2e2', '', 1, '2018-05-14'),
(15, 'KIRMANIKIRMANIKIRMANIKIRMANIKIRMANIKIRMANIKIRMANIKIRMANIKIRMANI', 1, 'asasdasd,asdasdasd,adadadada', '', 8, '2018-05-14'),
(16, 'LeOLeOLeOLeOLeOLeOLeO', 1, '', '', 1, '2018-06-05'),
(17, 'LeoLeoLeoLeoLeoLeoLeoLeoLeo', 1, '', '', 1, '2018-06-05'),
(18, 'i was feeling low and i had a headache', 1, 'xyz,,', '', 10, '2018-06-19'),
(19, 'adsdsdsdsdsddssssssssssssssssssss', 1, '', '', 1, '2018-06-21'),
(20, 'i am feeling bad', 1, '', '', 1, '2018-06-21'),
(21, 'i am feeling bad', 1, '', '', 1, '2018-06-21'),
(22, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 1, '', '', 1, '2018-06-21'),
(23, 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 1, '', '', 11, '2018-06-23'),
(24, 'test', 1, '', '', 11, '2018-06-25');

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
-- Indexes for table `user_uploads`
--
ALTER TABLE `user_uploads`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `disease_symptoms`
--
ALTER TABLE `disease_symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `user_uploads`
--
ALTER TABLE `user_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `weekly_form`
--
ALTER TABLE `weekly_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
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
