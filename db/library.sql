-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 17, 2025 at 01:15 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `library_books`
--

CREATE TABLE `library_books` (
  `id` int NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `publication_year` int DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `total_copies` int DEFAULT NULL,
  `available_copies` int DEFAULT NULL,
  `added_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_loans`
--

CREATE TABLE `library_loans` (
  `id` int NOT NULL,
  `reservation_id` int DEFAULT NULL,
  `loan_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `returned` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_reservations`
--

CREATE TABLE `library_reservations` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `reservation_date` datetime DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','picked_up','returned') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_reviews`
--

CREATE TABLE `library_reviews` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `review_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `library_users`
--

CREATE TABLE `library_users` (
  `id` int NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','librarian','student') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `library_books`
--
ALTER TABLE `library_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `library_loans`
--
ALTER TABLE `library_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `library_reservations`
--
ALTER TABLE `library_reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `library_reviews`
--
ALTER TABLE `library_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `library_users`
--
ALTER TABLE `library_users`
  ADD PRIMARY KEY (`id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `library_loans`
--
ALTER TABLE `library_loans`
  ADD CONSTRAINT `library_loans_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `library_reservations` (`id`);

--
-- Constraints for table `library_reservations`
--
ALTER TABLE `library_reservations`
  ADD CONSTRAINT `library_reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `library_users` (`id`),
  ADD CONSTRAINT `library_reservations_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`);

--
-- Constraints for table `library_reviews`
--
ALTER TABLE `library_reviews`
  ADD CONSTRAINT `library_reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `library_users` (`id`),
  ADD CONSTRAINT `library_reviews_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
