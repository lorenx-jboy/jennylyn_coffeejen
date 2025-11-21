-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2025 at 09:45 AM
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
-- Database: `brewstack_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--



CREATE TABLE `users` (
  `id_number` varchar(20) NOT NULL PRIMARY KEY,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `extension_name` varchar(10) DEFAULT NULL,
  `extension_other` varchar(10) DEFAULT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `a1_question` varchar(100) NOT NULL,
  `a1_answer` varchar(100) NOT NULL,
  `a2_question` varchar(100) NOT NULL,
  `a2_answer` varchar(100) NOT NULL,
  `a3_question` varchar(100) NOT NULL,
  `a3_answer` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_number`, `first_name`, `middle_name`, `last_name`, `extension_name`, `extension_other`, `sex`, `birthdate`, `age`, `purok`, `barangay`, `city`, `province`, `country`, `zip_code`, `email`, `username`, `password_hash`, `a1_question`, `a1_answer`, `a2_question`, `a2_answer`, `a3_question`, `a3_answer`, `created_at`) VALUES
(2, '2025-0001', 'Rabbi Lorenz Jay', 'Cagampang', 'Cuartero', 'Sr', '', 'Male', '2113-09-22', 0, 'P9', 'Marcelina ', 'Bayugan City', 'Agusan Del Sur', 'Philippines', '8502', 'rabbilorenzjay.cuartero@csucc.edu.ph', 'student1', '$2y$10$6jh9JOX2nDBPu/yT6bDa3.mFszWGQJuPLK9JVm6UU0.FJ8YpIA2xa', 'favorite_teacher_highschool', '$2y$10$u23Estg/RDQLDn8M7XjK1uwfYqxjFjKk6d4JLkaLD3BoHBoXAiZX6', 'hobby_childhood', '$2y$10$g98XDhVXpRj/FCrKYy5iEO5wU5g.LCTzPHdKG9.E5WNiYY.ssbhR2', 'dream_job', '$2y$10$Hby/yajXm6Um7JQULEzSruta2AQzl4qTpQhlm7qO04BH8dcZqXf0O', '2025-11-18 06:01:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
