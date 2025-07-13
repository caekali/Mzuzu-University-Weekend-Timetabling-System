-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 13, 2025 at 12:40 PM
-- Server version: 10.11.11-MariaDB-0+deb12u1
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mzuzu_university_weekend_timetabling_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `allocation_programme`
--

CREATE TABLE `allocation_programme` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `allocation_id` bigint(20) UNSIGNED NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `allocation_programme`
--

INSERT INTO `allocation_programme` (`id`, `allocation_id`, `programme_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(6, 6, 1),
(7, 1, 4),
(8, 2, 4),
(9, 7, 1),
(10, 8, 1),
(11, 9, 1),
(12, 10, 1),
(13, 11, 2),
(14, 12, 1),
(15, 13, 4),
(16, 14, 4),
(17, 15, 4),
(19, 17, 4),
(20, 18, 4),
(21, 19, 4),
(22, 20, 1),
(23, 21, 2),
(25, 22, 4),
(26, 23, 1),
(27, 23, 2),
(28, 24, 2),
(29, 25, 2),
(30, 26, 2),
(31, 1, 2),
(32, 27, 2),
(33, 11, 4),
(34, 2, 2),
(35, 13, 2),
(36, 16, 4),
(37, 28, 2),
(38, 28, 1),
(39, 29, 1),
(40, 30, 1),
(41, 31, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `constraints`
--

CREATE TABLE `constraints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_hard` tinyint(1) NOT NULL,
  `constraintable_id` bigint(20) UNSIGNED NOT NULL,
  `constraintable_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `constraints`
--

INSERT INTO `constraints` (`id`, `type`, `day`, `start_time`, `end_time`, `is_hard`, `constraintable_id`, `constraintable_type`, `created_at`, `updated_at`) VALUES
(1, 'unavailable', 'Saturday', '07:00:00', '18:30:00', 1, 7, 'App\\Models\\Lecturer', '2025-07-10 14:38:48', '2025-07-10 14:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lecture_hours` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `code`, `name`, `lecture_hours`, `level`, `semester`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'COMM1101', 'Communication Studies I', '3', 1, 1, 3, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(2, 'STAT2301', 'Introduction to Statistical Analysis', '3', 1, 2, 2, '2025-07-10 08:13:16', '2025-07-10 09:54:52'),
(3, 'STAT4705', 'Big Data Analytics', '3', 4, 7, 2, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(4, 'MATH3505', 'Optimization', '3', 3, 5, 2, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(5, 'STAT3505', 'Stocastic Modelling ', '3', 3, 5, 2, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(6, 'MATH2306', 'Linear Algebra', '3', 2, 3, 2, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(7, 'MATH1101', 'Pre-calculus', '3', 1, 1, 2, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(8, 'BICT1101', 'End User Computing', '3', 1, 1, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(9, 'BICT1102', 'Introduction To Programming With C', '3', 1, 1, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(10, 'BICT3503', 'Algorthms and Data Structures With Java', '3', 3, 5, 1, '2025-07-10 08:13:16', '2025-07-10 15:09:12'),
(11, 'BICT2302', 'Programming In Java', '3', 2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(12, 'BICT2306', 'Data Wrangling and Exploratory Data Analysis', '3', 2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(13, 'BICT2303', 'Computer Networks I', '3', 2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 09:36:51'),
(14, 'BICT2307', 'Introduction to Cloud Computing', '3', 2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(15, 'BICT3505', 'Web Programming', '3', 3, 5, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(16, 'BICT3502', 'Research Methods', '3', 3, 5, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(17, 'BICT4702', 'Modelling and Simulation', '3', 4, 7, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(18, 'BICT4703', 'Network Administration and Information Security', '3', 4, 7, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(19, 'BICT4704', 'Enterpreneurship', '3', 4, 7, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(20, 'BICT2401', 'Operating System', '3', 2, 4, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(21, 'BICT2304', 'Web Design', '3', 2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 15:08:08'),
(22, 'BICT3501', 'Computer networks II', '3', 3, 5, 1, '2025-07-10 08:13:16', '2025-07-10 09:38:04'),
(23, 'BICT3504', 'Mobile Telecommunication', '3', 3, 5, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(24, 'BICT4701', 'Software Engineering', '3', 4, 7, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(25, 'BICT1103', 'Computer and Communication Technology', '3', 1, 1, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(26, 'COMM1201', 'Communication Studies II', '3', 1, 2, 3, '2025-07-10 09:25:09', '2025-07-10 09:25:09'),
(27, 'MATH1201', 'Calculus', '3', 1, 2, 2, '2025-07-10 09:28:41', '2025-07-10 09:28:41'),
(28, 'BICT2402', 'Human Computer Interaction', '3', 2, 4, 1, '2025-07-10 09:30:45', '2025-07-10 09:30:45'),
(29, 'BICT1207', 'Introduction to Data Science', '3', 1, 2, 1, '2025-07-10 09:30:48', '2025-07-10 09:30:48'),
(30, 'BICT1206', 'Programming with Python', '3', 1, 2, 1, '2025-07-10 09:31:54', '2025-07-10 09:31:54'),
(31, 'BICT2404', 'Databases', '3', 2, 4, 1, '2025-07-10 09:33:02', '2025-07-10 09:41:49'),
(32, 'STAT2405', 'Non-Parametric Inference', '3', 2, 4, 2, '2025-07-10 09:37:39', '2025-07-10 09:43:28'),
(33, 'MATH2405', 'Discrete Mathematics', '3', 2, 4, 2, '2025-07-10 09:38:26', '2025-07-10 09:41:16'),
(34, 'BICT2403', 'Systems Analysis and Design', '3', 2, 4, 1, '2025-07-10 09:38:51', '2025-07-10 09:38:51'),
(35, 'BICT 2405', 'Machine Learning', '3', 2, 4, 1, '2025-07-10 09:38:59', '2025-07-10 09:42:32'),
(36, 'BICT2406', 'Mobile App Development', '3', 2, 4, 1, '2025-07-10 09:40:51', '2025-07-10 09:40:51'),
(37, 'MATH2401', 'Multivariate Calculus', '3', 2, 4, 2, '2025-07-10 09:42:11', '2025-07-10 09:42:11'),
(38, 'STAT3606', 'Applied Regression Analysis', '3', 3, 6, 2, '2025-07-10 09:45:38', '2025-07-10 09:45:38'),
(39, 'BICT3607', 'Data Warehousing & Mining', '3', 3, 6, 1, '2025-07-10 09:46:32', '2025-07-10 09:46:32'),
(40, 'BICT3601', 'Object Oriented Analysis and Design', '3', 3, 6, 1, '2025-07-10 09:46:40', '2025-07-10 09:46:40'),
(41, 'BICT3602', 'Object Oriented Programming with C++', '3', 3, 6, 1, '2025-07-10 09:47:13', '2025-07-10 09:47:13'),
(42, 'BICT3608', 'Data Visualisation & Virtualisation', '3', 3, 6, 1, '2025-07-10 09:47:18', '2025-07-10 09:47:18'),
(43, 'BICT3603', 'Distributed Systems', '3', 3, 6, 1, '2025-07-10 09:47:43', '2025-07-10 09:47:43'),
(45, 'BICT3604', 'Group Projects', '3', 3, 6, 1, '2025-07-10 09:48:23', '2025-07-10 09:48:23'),
(46, 'BICT 3604', 'Group Project / Min Project', '3', 3, 6, 1, '2025-07-10 09:48:41', '2025-07-10 09:48:41'),
(47, 'BICT3605', 'Project Management', '3', 3, 6, 1, '2025-07-10 09:48:57', '2025-07-10 09:48:57'),
(48, 'BICT4806', 'Emerging & Contemporary Issues in AI&ML', '3', 4, 8, 1, '2025-07-10 09:49:33', '2025-07-10 09:49:33'),
(49, 'BICT4801', 'Artificial Intelligence', '3', 4, 8, 1, '2025-07-10 09:49:53', '2025-07-10 10:00:46'),
(50, 'BICT4807', 'Introduction to Knowledge Engineering', '3', 4, 8, 1, '2025-07-10 09:50:18', '2025-07-10 09:50:18'),
(51, 'BICT4802', 'Electronic Commerce', '3', 4, 8, 1, '2025-07-10 09:50:28', '2025-07-10 09:50:28'),
(52, 'BICT4804', 'Systems Project', '3', 4, 8, 1, '2025-07-10 09:51:06', '2025-07-10 09:51:06'),
(53, 'BICT4803', 'Business Management', '3', 4, 8, 1, '2025-07-10 09:51:09', '2025-07-10 09:51:09'),
(55, 'STAT4806', 'Introduction to Bayesian Decision Theory', '3', 4, 8, 2, '2025-07-10 09:52:37', '2025-07-10 09:52:37'),
(56, 'BICT4805', 'Industrial Attachment', '3', 4, 8, 1, '2025-07-10 09:52:45', '2025-07-10 09:52:45'),
(57, 'BICT1201', 'PC Management and Maintenance', '3', 1, 2, 1, '2025-07-10 09:54:02', '2025-07-10 09:54:02'),
(58, 'BICT1202', 'Computer Architecture and Organisation', '3', 1, 2, 1, '2025-07-10 09:54:34', '2025-07-10 09:54:34'),
(59, 'BICT1203', 'Multimedia', '3', 1, 2, 1, '2025-07-10 09:55:07', '2025-07-10 09:55:07'),
(60, 'BICT4705', 'Information Systems Audit', '3', 4, 7, 1, '2025-07-10 15:11:03', '2025-07-10 15:11:03'),
(61, 'BICT4706', 'Fundamentals of Deep Learning', '3', 4, 7, 1, '2025-07-10 15:13:44', '2025-07-10 15:14:10');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
(1, 'ICT', 'Information And Communication Technology', '2025-07-10 08:13:15', '2025-07-10 08:13:15'),
(2, 'MATH', 'Mathematics', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(3, 'COMM', 'Communication', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(4, 'TOUR', 'Tourism', '2025-07-10 10:25:23', '2025-07-10 10:25:23');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`id`, `user_id`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(2, 3, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(3, 4, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(4, 5, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(5, 6, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(6, 7, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(7, 8, 1, '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(8, 9, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(9, 10, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(10, 11, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(11, 12, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(12, 13, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(13, 14, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(14, 15, 1, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(15, 16, 2, '2025-07-10 10:09:39', '2025-07-10 10:09:39'),
(16, 17, 2, '2025-07-10 10:11:57', '2025-07-10 10:11:57'),
(17, 18, 2, '2025-07-10 10:12:40', '2025-07-10 10:12:40'),
(18, 19, 2, '2025-07-10 10:13:27', '2025-07-10 10:13:27'),
(19, 20, 2, '2025-07-10 10:14:00', '2025-07-10 10:14:00'),
(20, 21, 2, '2025-07-10 10:15:13', '2025-07-10 10:15:13'),
(21, 22, 1, '2025-07-10 10:18:14', '2025-07-10 10:18:14'),
(22, 23, 1, '2025-07-10 10:21:07', '2025-07-10 10:21:07'),
(23, 24, 1, '2025-07-10 10:25:00', '2025-07-10 10:25:00'),
(24, 25, 4, '2025-07-10 10:30:14', '2025-07-10 10:30:14'),
(25, 26, 3, '2025-07-10 10:37:41', '2025-07-10 10:37:41'),
(26, 27, 2, '2025-07-10 10:55:29', '2025-07-10 10:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer_course_allocations`
--

CREATE TABLE `lecturer_course_allocations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `lecturer_id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lecturer_course_allocations`
--

INSERT INTO `lecturer_course_allocations` (`id`, `course_id`, `lecturer_id`, `level`, `created_at`, `updated_at`) VALUES
(1, 31, 13, 2, '2025-07-10 10:03:12', '2025-07-10 10:03:12'),
(2, 34, 6, 2, '2025-07-10 10:05:19', '2025-07-10 10:05:19'),
(3, 35, 5, 2, '2025-07-10 10:06:50', '2025-07-10 10:06:50'),
(4, 32, 17, 2, '2025-07-10 10:14:36', '2025-07-10 10:14:36'),
(5, 33, 16, 2, '2025-07-10 10:15:08', '2025-07-10 10:15:08'),
(6, 38, 18, 3, '2025-07-10 10:15:37', '2025-07-10 10:15:37'),
(7, 39, 9, 3, '2025-07-10 10:16:15', '2025-07-10 10:16:15'),
(8, 42, 11, 3, '2025-07-10 10:16:44', '2025-07-10 10:16:44'),
(9, 49, 6, 3, '2025-07-10 10:17:16', '2025-07-10 10:17:16'),
(10, 55, 19, 4, '2025-07-10 10:19:41', '2025-07-10 10:19:41'),
(11, 28, 21, 2, '2025-07-10 10:19:56', '2025-07-10 10:19:56'),
(12, 48, 22, 4, '2025-07-10 10:21:35', '2025-07-10 10:21:35'),
(13, 36, 8, 2, '2025-07-10 10:21:37', '2025-07-10 10:21:37'),
(14, 40, 14, 3, '2025-07-10 10:22:24', '2025-07-10 10:22:24'),
(15, 41, 11, 3, '2025-07-10 10:22:47', '2025-07-10 10:22:47'),
(16, 43, 1, 3, '2025-07-10 10:23:23', '2025-07-10 10:23:23'),
(17, 49, 6, 4, '2025-07-10 10:28:06', '2025-07-10 10:28:06'),
(18, 51, 12, 4, '2025-07-10 10:29:31', '2025-07-10 10:29:31'),
(19, 53, 24, 4, '2025-07-10 10:31:01', '2025-07-10 10:31:01'),
(20, 50, 9, 4, '2025-07-10 10:32:37', '2025-07-10 10:32:37'),
(21, 27, 15, 1, '2025-07-10 10:34:51', '2025-07-10 10:34:51'),
(22, 26, 25, 2, '2025-07-10 10:38:49', '2025-07-10 10:38:49'),
(23, 26, 25, 1, '2025-07-10 10:40:20', '2025-07-10 10:40:20'),
(24, 57, 13, 1, '2025-07-10 10:41:07', '2025-07-10 10:41:07'),
(25, 58, 12, 1, '2025-07-10 10:41:45', '2025-07-10 10:41:45'),
(26, 59, 1, 1, '2025-07-10 10:42:15', '2025-07-10 10:42:15'),
(27, 37, 20, 2, '2025-07-10 10:44:48', '2025-07-10 10:44:48'),
(28, 27, 15, 1, '2025-07-10 10:53:15', '2025-07-10 10:53:15'),
(29, 30, 3, 1, '2025-07-10 10:54:10', '2025-07-10 10:54:10'),
(30, 2, 26, 1, '2025-07-10 10:55:54', '2025-07-10 10:55:54'),
(31, 29, 5, 1, '2025-07-10 10:56:25', '2025-07-10 10:56:25');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_23_065816_create_roles_table', 1),
(5, '2025_05_23_072446_user_roles', 1),
(6, '2025_05_25_120825_create_departments_table', 1),
(7, '2025_05_25_121019_create_programmes_table', 1),
(8, '2025_05_25_121252_create_lecturers_table', 1),
(9, '2025_05_25_121519_create_students_table', 1),
(10, '2025_05_25_121840_create_courses_table', 1),
(11, '2025_05_25_121941_create_lecturer_course_allocations_table', 1),
(12, '2025_05_25_122332_create_venues_table', 1),
(13, '2025_05_25_122333_create_schedule_entries_table', 1),
(14, '2025_05_25_122833_create_constraints_table', 1),
(16, '2025_06_17_205002_allocation_programme', 1),
(17, '2025_06_18_070226_create_settings_table', 1),
(18, '2025_06_18_121718_create_schedule_days_table', 1),
(19, '2025_06_21_181733_create_schedule_versions_table', 1),
(20, '2025_06_21_181909_add_schedule_version_id_to_schedule_entries', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number_of_students` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `code`, `name`, `number_of_students`, `department_id`, `created_at`, `updated_at`) VALUES
(1, 'BSDS', 'BSc Data Science', 20, 1, '2025-07-10 08:13:16', '2025-07-11 09:15:22'),
(2, 'DICT', 'Diploma in ICT', 20, 1, '2025-07-10 08:13:16', '2025-07-11 09:15:30'),
(4, 'BICTU', 'Bsc. ICT Upgrading', 20, 1, '2025-07-10 09:20:54', '2025-07-11 09:15:16');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2025-07-11 08:34:53', '2025-07-11 08:34:53'),
(2, 'Student', '2025-07-11 08:34:53', '2025-07-11 08:34:53'),
(3, 'Lecturer', '2025-07-11 08:34:53', '2025-07-11 08:34:53'),
(4, 'HOD', '2025-07-11 08:34:53', '2025-07-11 08:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_days`
--

CREATE TABLE `schedule_days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 0,
  `start_time` time NOT NULL DEFAULT '07:00:00',
  `end_time` time NOT NULL DEFAULT '18:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_days`
--

INSERT INTO `schedule_days` (`id`, `name`, `enabled`, `start_time`, `end_time`) VALUES
(1, 'Sunday', 1, '07:00:00', '13:30:00'),
(2, 'Monday', 0, '07:00:00', '18:00:00'),
(3, 'Tuesday', 0, '07:00:00', '18:00:00'),
(4, 'Wednesday', 0, '07:00:00', '18:00:00'),
(5, 'Thursday', 0, '07:00:00', '18:00:00'),
(6, 'Friday', 1, '15:30:00', '18:30:00'),
(7, 'Saturday', 1, '07:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_entries`
--

CREATE TABLE `schedule_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL,
  `lecturer_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schedule_version_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_entries`
--

INSERT INTO `schedule_entries` (`id`, `day`, `level`, `start_time`, `end_time`, `programme_id`, `lecturer_id`, `course_id`, `venue_id`, `created_at`, `updated_at`, `schedule_version_id`) VALUES
(250, 'Saturday', 2, '10:30:00', '11:00:00', 1, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(251, 'Saturday', 2, '10:30:00', '11:00:00', 2, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(252, 'Saturday', 2, '10:30:00', '11:00:00', 4, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(253, 'Saturday', 2, '11:00:00', '12:00:00', 1, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(254, 'Saturday', 2, '11:00:00', '12:00:00', 2, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(255, 'Saturday', 2, '11:00:00', '12:00:00', 4, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(256, 'Saturday', 2, '12:00:00', '13:00:00', 1, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(257, 'Saturday', 2, '12:00:00', '13:00:00', 2, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(258, 'Saturday', 2, '12:00:00', '13:00:00', 4, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(259, 'Saturday', 2, '13:00:00', '13:30:00', 1, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(260, 'Saturday', 2, '13:00:00', '13:30:00', 2, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(261, 'Saturday', 2, '13:00:00', '13:30:00', 4, 13, 31, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(262, 'Sunday', 2, '07:00:00', '08:00:00', 1, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(263, 'Sunday', 2, '07:00:00', '08:00:00', 2, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(264, 'Sunday', 2, '07:00:00', '08:00:00', 4, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(265, 'Sunday', 2, '08:00:00', '09:00:00', 1, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(266, 'Sunday', 2, '08:00:00', '09:00:00', 2, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(267, 'Sunday', 2, '08:00:00', '09:00:00', 4, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(268, 'Sunday', 2, '09:00:00', '10:00:00', 1, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(269, 'Sunday', 2, '09:00:00', '10:00:00', 2, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(270, 'Sunday', 2, '09:00:00', '10:00:00', 4, 6, 34, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(271, 'Saturday', 2, '14:00:00', '15:00:00', 1, 5, 35, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(272, 'Saturday', 2, '15:00:00', '16:00:00', 1, 5, 35, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(273, 'Saturday', 2, '16:00:00', '17:00:00', 1, 5, 35, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(274, 'Saturday', 2, '07:00:00', '08:00:00', 1, 17, 32, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(275, 'Saturday', 2, '08:00:00', '09:00:00', 1, 17, 32, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(276, 'Saturday', 2, '09:00:00', '10:00:00', 1, 17, 32, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(277, 'Sunday', 2, '10:30:00', '11:00:00', 1, 16, 33, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(278, 'Sunday', 2, '11:00:00', '12:00:00', 1, 16, 33, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(279, 'Sunday', 2, '12:00:00', '13:00:00', 1, 16, 33, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(280, 'Sunday', 2, '13:00:00', '13:30:00', 1, 16, 33, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(281, 'Saturday', 3, '14:00:00', '15:00:00', 1, 18, 38, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(282, 'Saturday', 3, '15:00:00', '16:00:00', 1, 18, 38, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(283, 'Saturday', 3, '16:00:00', '17:00:00', 1, 18, 38, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(284, 'Saturday', 3, '10:30:00', '11:00:00', 1, 9, 39, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(285, 'Saturday', 3, '11:00:00', '12:00:00', 1, 9, 39, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(286, 'Saturday', 3, '12:00:00', '13:00:00', 1, 9, 39, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(287, 'Saturday', 3, '13:00:00', '13:30:00', 1, 9, 39, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(288, 'Saturday', 3, '07:00:00', '08:00:00', 1, 11, 42, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(289, 'Saturday', 3, '08:00:00', '09:00:00', 1, 11, 42, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(290, 'Saturday', 3, '09:00:00', '10:00:00', 1, 11, 42, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(291, 'Sunday', 4, '10:30:00', '11:00:00', 4, 6, 49, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(292, 'Sunday', 4, '11:00:00', '12:00:00', 4, 6, 49, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(293, 'Sunday', 4, '12:00:00', '13:00:00', 4, 6, 49, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(294, 'Sunday', 4, '13:00:00', '13:30:00', 4, 6, 49, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(295, 'Friday', 4, '15:30:00', '16:00:00', 1, 19, 55, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(296, 'Friday', 4, '16:00:00', '17:00:00', 1, 19, 55, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(297, 'Friday', 4, '17:00:00', '18:00:00', 1, 19, 55, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(298, 'Friday', 4, '18:00:00', '18:30:00', 1, 19, 55, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(299, 'Sunday', 2, '10:30:00', '11:00:00', 2, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(300, 'Sunday', 2, '10:30:00', '11:00:00', 4, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(301, 'Sunday', 2, '11:00:00', '12:00:00', 2, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(302, 'Sunday', 2, '11:00:00', '12:00:00', 4, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(303, 'Sunday', 2, '12:00:00', '13:00:00', 2, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(304, 'Sunday', 2, '12:00:00', '13:00:00', 4, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(305, 'Sunday', 2, '13:00:00', '13:30:00', 2, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(306, 'Sunday', 2, '13:00:00', '13:30:00', 4, 21, 28, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(307, 'Sunday', 4, '10:30:00', '11:00:00', 1, 22, 48, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(308, 'Sunday', 4, '11:00:00', '12:00:00', 1, 22, 48, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(309, 'Sunday', 4, '12:00:00', '13:00:00', 1, 22, 48, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(310, 'Sunday', 4, '13:00:00', '13:30:00', 1, 22, 48, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(311, 'Saturday', 2, '14:00:00', '15:00:00', 2, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(312, 'Saturday', 2, '14:00:00', '15:00:00', 4, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(313, 'Saturday', 2, '15:00:00', '16:00:00', 2, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(314, 'Saturday', 2, '15:00:00', '16:00:00', 4, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(315, 'Saturday', 2, '16:00:00', '17:00:00', 2, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(316, 'Saturday', 2, '16:00:00', '17:00:00', 4, 8, 36, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(317, 'Sunday', 3, '07:00:00', '08:00:00', 4, 14, 40, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(318, 'Sunday', 3, '08:00:00', '09:00:00', 4, 14, 40, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(319, 'Sunday', 3, '09:00:00', '10:00:00', 4, 14, 40, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(320, 'Saturday', 3, '14:00:00', '15:00:00', 4, 11, 41, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(321, 'Saturday', 3, '15:00:00', '16:00:00', 4, 11, 41, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(322, 'Saturday', 3, '16:00:00', '17:00:00', 4, 11, 41, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(323, 'Sunday', 3, '10:30:00', '11:00:00', 4, 1, 43, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(324, 'Sunday', 3, '11:00:00', '12:00:00', 4, 1, 43, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(325, 'Sunday', 3, '12:00:00', '13:00:00', 4, 1, 43, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(326, 'Sunday', 3, '13:00:00', '13:30:00', 4, 1, 43, 4, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(327, 'Friday', 4, '15:30:00', '16:00:00', 4, 12, 51, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(328, 'Friday', 4, '16:00:00', '17:00:00', 4, 12, 51, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(329, 'Friday', 4, '17:00:00', '18:00:00', 4, 12, 51, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(330, 'Friday', 4, '18:00:00', '18:30:00', 4, 12, 51, 7, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(331, 'Saturday', 4, '07:00:00', '08:00:00', 4, 24, 53, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(332, 'Saturday', 4, '08:00:00', '09:00:00', 4, 24, 53, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(333, 'Saturday', 4, '09:00:00', '10:00:00', 4, 24, 53, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(334, 'Sunday', 4, '07:00:00', '08:00:00', 1, 9, 50, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(335, 'Sunday', 4, '08:00:00', '09:00:00', 1, 9, 50, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(336, 'Sunday', 4, '09:00:00', '10:00:00', 1, 9, 50, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(337, 'Saturday', 1, '07:00:00', '08:00:00', 1, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(338, 'Saturday', 1, '07:00:00', '08:00:00', 2, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(339, 'Saturday', 1, '08:00:00', '09:00:00', 1, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(340, 'Saturday', 1, '08:00:00', '09:00:00', 2, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(341, 'Saturday', 1, '09:00:00', '10:00:00', 1, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(342, 'Saturday', 1, '09:00:00', '10:00:00', 2, 15, 27, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(343, 'Saturday', 1, '14:00:00', '15:00:00', 1, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(344, 'Saturday', 1, '14:00:00', '15:00:00', 2, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(345, 'Saturday', 1, '15:00:00', '16:00:00', 1, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(346, 'Saturday', 1, '15:00:00', '16:00:00', 2, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(347, 'Saturday', 1, '16:00:00', '17:00:00', 1, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(348, 'Saturday', 1, '16:00:00', '17:00:00', 2, 25, 26, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(349, 'Friday', 1, '15:30:00', '16:00:00', 2, 13, 57, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(350, 'Friday', 1, '16:00:00', '17:00:00', 2, 13, 57, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(351, 'Friday', 1, '17:00:00', '18:00:00', 2, 13, 57, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(352, 'Friday', 1, '18:00:00', '18:30:00', 2, 13, 57, 6, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(353, 'Sunday', 1, '07:00:00', '08:00:00', 2, 12, 58, 3, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(354, 'Sunday', 1, '08:00:00', '09:00:00', 2, 12, 58, 3, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(355, 'Sunday', 1, '09:00:00', '10:00:00', 2, 12, 58, 3, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(356, 'Saturday', 1, '10:30:00', '11:00:00', 2, 1, 59, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(357, 'Saturday', 1, '11:00:00', '12:00:00', 2, 1, 59, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(358, 'Saturday', 1, '12:00:00', '13:00:00', 2, 1, 59, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(359, 'Saturday', 1, '13:00:00', '13:30:00', 2, 1, 59, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(360, 'Friday', 2, '15:30:00', '16:00:00', 2, 20, 37, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(361, 'Friday', 2, '16:00:00', '17:00:00', 2, 20, 37, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(362, 'Friday', 2, '17:00:00', '18:00:00', 2, 20, 37, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(363, 'Friday', 2, '18:00:00', '18:30:00', 2, 20, 37, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(364, 'Saturday', 1, '10:30:00', '11:00:00', 1, 3, 30, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(365, 'Saturday', 1, '11:00:00', '12:00:00', 1, 3, 30, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(366, 'Saturday', 1, '12:00:00', '13:00:00', 1, 3, 30, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(367, 'Saturday', 1, '13:00:00', '13:30:00', 1, 3, 30, 5, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(368, 'Sunday', 1, '07:00:00', '08:00:00', 1, 26, 2, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(369, 'Sunday', 1, '08:00:00', '09:00:00', 1, 26, 2, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(370, 'Sunday', 1, '09:00:00', '10:00:00', 1, 26, 2, 1, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(371, 'Friday', 1, '15:30:00', '16:00:00', 1, 5, 29, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(372, 'Friday', 1, '16:00:00', '17:00:00', 1, 5, 29, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(373, 'Friday', 1, '17:00:00', '18:00:00', 1, 5, 29, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3),
(374, 'Friday', 1, '18:00:00', '18:30:00', 1, 5, 29, 2, '2025-07-13 08:28:09', '2025-07-13 08:28:09', 3);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_versions`
--

CREATE TABLE `schedule_versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `generated_at` timestamp NOT NULL,
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedule_versions`
--

INSERT INTO `schedule_versions` (`id`, `label`, `is_published`, `generated_at`, `published_at`) VALUES
(3, 'Semester 2 2024/2025 Academic Year', 1, '2025-07-13 08:28:09', '2025-07-13 08:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PYOSEaAcbKUAJEcT4aO9oyBnsKLPn0YzDN6s66UN', 1, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTFlXSzNmYVZvT3F4TUNrYkVRNndpSEw4U0pSa1hVdkpSazFFS1VBcSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvZnVsbC10aW1ldGFibGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTI6ImN1cnJlbnRfcm9sZSI7czo1OiJBZG1pbiI7fQ==', 1752410403);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'slot_duration', '60', '2025-06-29 08:52:52', '2025-06-29 15:06:12'),
(2, 'break_duration', '30', '2025-06-29 08:52:52', '2025-06-30 08:28:22'),
(5, 'population_size', '100', '2025-07-13 07:46:28', '2025-07-13 10:29:01'),
(6, 'number_of_generations', '500', '2025-07-13 07:46:28', '2025-07-13 10:29:01'),
(7, 'tournament_size', '5', '2025-07-13 07:46:28', '2025-07-13 08:18:22'),
(8, 'mutation_rate', '0.02', '2025-07-13 07:46:28', '2025-07-13 10:29:01'),
(9, 'crossover_rate', '0.8', '2025-07-13 07:46:28', '2025-07-13 08:18:38'),
(10, 'elite_schedules', '1', '2025-07-13 07:46:28', '2025-07-13 07:46:28'),
(11, 'ga_last_updated', '2025-07-13 12:29:01', '2025-07-13 07:47:36', '2025-07-13 10:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL,
  `programme_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `level`, `programme_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, '2025-07-10 12:52:34', '2025-07-12 16:36:25'),
(2, 1, 2, 28, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(3, 1, 1, 29, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(4, 4, 1, 30, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(5, 1, 2, 31, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(6, 1, 2, 32, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(7, 4, 1, 33, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(8, 3, 1, 34, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(9, 1, 1, 35, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(10, 3, 2, 36, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(11, 2, 2, 37, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(12, 4, 2, 38, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(13, 2, 2, 39, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(14, 4, 1, 40, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(15, 3, 1, 41, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(16, 3, 1, 42, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(17, 2, 2, 43, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(18, 1, 2, 44, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(19, 2, 2, 45, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(20, 4, 2, 46, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(21, 1, 2, 47, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(22, 1, 1, 48, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(23, 1, 1, 49, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(24, 2, 2, 50, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(25, 2, 1, 51, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(26, 3, 1, 52, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(27, 3, 2, 53, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(28, 1, 1, 54, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(29, 3, 1, 55, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(30, 1, 1, 56, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(31, 1, 2, 57, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(32, 2, 1, 58, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(33, 1, 2, 59, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(34, 4, 2, 60, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(35, 2, 1, 61, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(36, 3, 1, 62, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(37, 4, 1, 63, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(38, 2, 2, 64, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(39, 3, 2, 65, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(40, 1, 1, 66, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(41, 3, 1, 67, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(42, 2, 1, 68, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(43, 1, 2, 69, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(44, 3, 2, 70, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(45, 1, 2, 71, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(46, 3, 1, 72, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(47, 2, 2, 73, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(48, 2, 1, 74, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(49, 1, 2, 75, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(50, 1, 2, 76, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(51, 3, 1, 77, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(52, 3, 2, 78, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(53, 4, 2, 79, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(54, 1, 1, 80, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(55, 3, 1, 81, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(56, 3, 2, 82, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(57, 1, 1, 83, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(58, 3, 1, 84, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(59, 4, 1, 85, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(60, 1, 1, 86, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(61, 1, 1, 87, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(62, 1, 2, 88, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(63, 3, 1, 89, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(64, 4, 2, 90, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(65, 2, 2, 91, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(66, 2, 2, 92, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(67, 1, 2, 93, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(68, 1, 1, 94, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(69, 1, 4, 95, '2025-07-11 09:55:12', '2025-07-11 09:55:12'),
(70, 3, 4, 96, '2025-07-11 09:56:14', '2025-07-11 09:56:14'),
(71, 3, 4, 97, '2025-07-11 09:57:01', '2025-07-11 09:57:01'),
(72, 3, 4, 98, '2025-07-11 09:57:51', '2025-07-11 09:57:51'),
(73, 3, 1, 99, '2025-07-11 09:58:44', '2025-07-11 09:58:44'),
(74, 3, 4, 100, '2025-07-11 09:59:28', '2025-07-11 09:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `email_verified_at`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System', 'Admin', 'admin@my.mzuni.ac.mw', '$2y$12$LRUFvG.kI25fZxCugzvsoezscgmlSuZ9HColH896TNCSsaQlq0vpS', NULL, 1, NULL, '2025-07-11 08:34:54', '2025-07-11 08:34:54'),
(2, 'Ezekiel', 'Namacha', 'ezekiel.namacha@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, '0Qq59Cpy3Y', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(3, 'Lome', 'Longwe', 'lome.longwe@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, 'QvvyaWLZFK', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(4, 'Chimango', 'Nyasulu', 'chimango.nyasulu@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, 'QM2XOgxo2G', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(5, 'Seyani', 'Nayeja', 'seyani.nayeja@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, 'x4Gej1yChu', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(6, 'Precious', 'Msonda', 'precious.msonda@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, 'auw3lNlxHe', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(7, 'Blessings', 'Ngwira', 'blessings.ngwira@my.mzuni.ac.mw', '$2y$12$9NPqJeDnqDLaWvuN9sO53uCChzc6.RnFoDxyFXrqfGUMrZst8wPOe', '2025-07-10 08:13:16', 1, 'iyzK3KW3yT', '2025-07-10 08:13:16', '2025-07-12 16:55:32'),
(8, 'Enock', 'Tung\'ande', 'enock.tungande@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:16', 0, '4itWanLosk', '2025-07-10 08:13:16', '2025-07-10 08:13:16'),
(9, 'Vision', 'Thondoya', 'vision.thondoya@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'zKYn50bjy1', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(10, 'Josephy', 'Kumwenda', 'josephy.kumwenda@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'hjht84pAQR', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(11, 'Mr.', 'Nalivata', 'mr.nalivata@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'bWZT007VMa', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(12, 'Stanley', 'Ndebvu', 'stanley.ndebvu@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'mYhMCDJOWE', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(13, 'Mwekela', '', 'mwekela@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'WoDuxn8pKL', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(14, 'Prince', 'Goba', 'prince.goba@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'Ww12NFrcj9', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(15, 'Donald', 'Phiri', 'donald.phiri@my.mzuni.ac.mw', NULL, '2025-07-10 08:13:17', 0, 'W62p10oi0n', '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(16, 'Math1201', 'Math1201', 'math1201@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:09:39', '2025-07-10 10:09:39'),
(17, 'Math2405', 'Math2405', 'math24@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:11:56', '2025-07-10 10:11:56'),
(18, 'Stat2405', 'Stat2405', 'stat2405@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:12:40', '2025-07-10 10:12:40'),
(19, 'Stat3606', 'Stat3606', 'stat3606@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:13:26', '2025-07-10 10:13:26'),
(20, 'Stat4806', 'Stat4806', 'stat4806@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:14:00', '2025-07-10 10:14:00'),
(21, 'Math2401', 'math2401', 'math2401@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:15:13', '2025-07-10 10:15:13'),
(22, 'Emmanuel', 'Ngalande', 'emmanuel.ngalande@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:18:14', '2025-07-10 10:18:14'),
(23, 'Reuben', 'Moyo', 'reuen.moyo@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:21:07', '2025-07-10 10:21:07'),
(24, 'Ufulu', 'Nalivata', 'ufulu.nalivata@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:25:00', '2025-07-10 10:25:00'),
(25, 'Wilson', 'Banda', 'wilson.banda@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:30:14', '2025-07-10 10:30:14'),
(26, 'Comm1201', 'Comm1201', 'comm1201@my.mzuni.ac.am', NULL, NULL, 0, NULL, '2025-07-10 10:37:41', '2025-07-10 10:37:41'),
(27, 'Stat2301', 'Stat2301', 'stat2301@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 10:55:29', '2025-07-10 10:55:29'),
(28, 'Thokozani', 'Moyo', 'thokozani.moyo4@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(29, 'Tiyamike', 'Tembo', 'tiyamike.tembo5@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(30, 'Lumbani', 'Zimba', 'lumbani.zimba6@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(31, 'Tiyese', 'Nyirenda', 'tiyese.nyirenda9@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(32, 'Tadala', 'Mhone', 'tadala.mhone11@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(33, 'Nchimunya', 'Chilemba', 'nchimunya.chilemba12@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(34, 'Tiyese', 'Chipeta', 'tiyese.chipeta15@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(35, 'Thokozani', 'Chikafa', 'thokozani.chikafa17@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(36, 'Limbani', 'Zimba', 'limbani.zimba18@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(37, 'Tadala', 'Moyo', 'tadala.moyo20@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(38, 'Fatsani', 'Gondwe', 'fatsani.gondwe21@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(39, 'Blessings', 'Mwalwanda', 'blessings.mwalwanda22@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:22', '2025-07-10 14:00:22'),
(40, 'Mwayi', 'Chipeta', 'mwayi.chipeta23@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(41, 'Chikondi', 'Kachere', 'chikondi.kachere24@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(42, 'Tadala', 'Nyirenda', 'tadala.nyirenda25@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(43, 'Fatsani', 'Kachere', 'fatsani.kachere28@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(44, 'Limbani', 'Moyo', 'limbani.moyo29@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(45, 'Blessings', 'Ngwira', 'blessings.ngwira30@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(46, 'Mwayi', 'Moyo', 'mwayi.moyo31@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(47, 'Limbani', 'Chilemba', 'limbani.chilemba32@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(48, 'Tiyamike', 'Kachere', 'tiyamike.kachere33@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(49, 'Yamikani', 'Nyirenda', 'yamikani.nyirenda35@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(50, 'Lumbani', 'Phiri', 'lumbani.phiri36@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(51, 'Zione', 'Chipeta', 'zione.chipeta37@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(52, 'Tamandani', 'Gondwe', 'tamandani.gondwe38@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(53, 'Zione', 'Mhone', 'zione.mhone39@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:23', '2025-07-10 14:00:23'),
(54, 'Mwayi', 'Kumwenda', 'mwayi.kumwenda40@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(55, 'Tadala', 'Mwanza', 'tadala.mwanza41@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(56, 'Yamikani', 'Kumwenda', 'yamikani.kumwenda42@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(57, 'Zione', 'Kumwenda', 'zione.kumwenda44@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(58, 'Dalitso', 'Mwanza', 'dalitso.mwanza46@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(59, 'Yamikani', 'Banda', 'yamikani.banda47@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(60, 'Nchimunya', 'Luhanga', 'nchimunya.luhanga48@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(61, 'Blessings', 'Banda', 'blessings.banda49@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(62, 'Kondwani', 'Phiri', 'kondwani.phiri50@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(63, 'Fatsani', 'Chirwa', 'fatsani.chirwa54@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(64, 'Tadala', 'Kalua', 'tadala.kalua55@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(65, 'Tiwonge', 'Mwanza', 'tiwonge.mwanza56@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:24', '2025-07-10 14:00:24'),
(66, 'Tiyamike', 'Mkandawire', 'tiyamike.mkandawire59@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(67, 'Limbani', 'Nyirenda', 'limbani.nyirenda60@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(68, 'Chikondi', 'Zimba', 'chikondi.zimba61@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(69, 'Limbani', 'Nyirenda', 'limbani.nyirenda62@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(70, 'Fatsani', 'Moyo', 'fatsani.moyo63@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(71, 'Tiwonge', 'Kachere', 'tiwonge.kachere66@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(72, 'Zione', 'Moyo', 'zione.moyo67@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(73, 'Tiyese', 'Moyo', 'tiyese.moyo68@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(74, 'Yamikani', 'Chipeta', 'yamikani.chipeta69@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(75, 'Mwayi', 'Kumwenda', 'mwayi.kumwenda70@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(76, 'Tiyamike', 'Chikafa', 'tiyamike.chikafa73@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(77, 'Thokozani', 'Zimba', 'thokozani.zimba75@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:25', '2025-07-10 14:00:25'),
(78, 'Thokozani', 'Kumwenda', 'thokozani.kumwenda76@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(79, 'Blessings', 'Chipeta', 'blessings.chipeta77@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(80, 'Tiwonge', 'Luhanga', 'tiwonge.luhanga78@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(81, 'Tiyamike', 'Mkandawire', 'tiyamike.mkandawire79@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(82, 'Chisomo', 'Mwanza', 'chisomo.mwanza80@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(83, 'Fatsani', 'Phiri', 'fatsani.phiri81@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(84, 'Thokozani', 'Nyirenda', 'thokozani.nyirenda82@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(85, 'Chikondi', 'Chirwa', 'chikondi.chirwa83@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(86, 'Tadala', 'Gondwe', 'tadala.gondwe84@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(87, 'Blessings', 'Phiri', 'blessings.phiri87@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(88, 'Nchimunya', 'Moyo', 'nchimunya.moyo88@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:26', '2025-07-10 14:00:26'),
(89, 'Lumbani', 'Kachere', 'lumbani.kachere89@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(90, 'Limbani', 'Mkandawire', 'limbani.mkandawire90@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(91, 'Tiyamike', 'Moyo', 'tiyamike.moyo91@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(92, 'Tadala', 'Kalua', 'tadala.kalua93@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(93, 'Limbani', 'Chikafa', 'limbani.chikafa94@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(94, 'Blessings', 'Chikafa', 'blessings.chikafa96@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-10 14:00:27', '2025-07-10 14:00:27'),
(95, 'Regina', 'Nyambalo', 'bict2821@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-11 09:55:12', '2025-07-11 09:55:12'),
(96, 'Gloria', 'Mtungama', 'bict2022@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-11 09:56:14', '2025-07-11 09:56:14'),
(97, 'Bester', 'Gondwe', 'bict0622@my.mzuni.ac.mw', NULL, NULL, 0, NULL, '2025-07-11 09:57:01', '2025-07-11 09:57:01'),
(98, 'Asante', 'Kolowiko', 'bict1222@my.mzuni.ac.mw', '$2y$12$irTWSWEdSqBNuDTzIy8gz.iVn7ciVZNDSoCcacPjOaohvhVlQYjGK', NULL, 1, NULL, '2025-07-11 09:57:51', '2025-07-11 11:19:16'),
(99, 'Joshua', 'Ndeule', 'bict2221@my.mzuni.ac.mw', '$2y$12$m0YEGn3GMiqMXhg3IRz3VOmKDRWKPKlGUVFRInkD1djX4ryLH8Ge.', NULL, 1, NULL, '2025-07-11 09:58:44', '2025-07-11 11:06:14'),
(100, 'Caeser', 'Kalikunde', 'bict0822@my.mzuni.ac.mw', '$2y$12$WCriZUa7wd5WxWLHJY5PrOy/.o8YiMJxSpznnpEM8EWXfjklOYgKi', NULL, 1, 'd4a90v2Sp0JhayjXSId4HpSt0op1LGLOn2n95HxUTmr0GHEM5x0aQ3M2BkJI', '2025-07-11 09:59:28', '2025-07-11 10:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3),
(7, 3),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 3),
(18, 3),
(19, 3),
(20, 3),
(21, 3),
(22, 3),
(23, 3),
(24, 3),
(25, 3),
(26, 3),
(27, 3),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(43, 2),
(44, 2),
(45, 2),
(46, 2),
(47, 2),
(48, 2),
(49, 2),
(50, 2),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(56, 2),
(57, 2),
(58, 2),
(59, 2),
(60, 2),
(61, 2),
(62, 2),
(63, 2),
(64, 2),
(65, 2),
(66, 2),
(67, 2),
(68, 2),
(69, 2),
(70, 2),
(71, 2),
(72, 2),
(73, 2),
(74, 2),
(75, 2),
(76, 2),
(77, 2),
(78, 2),
(79, 2),
(80, 2),
(81, 2),
(82, 2),
(83, 2),
(84, 2),
(85, 2),
(86, 2),
(87, 2),
(88, 2),
(89, 2),
(90, 2),
(91, 2),
(92, 2),
(93, 2),
(94, 2),
(1, 4),
(95, 2),
(96, 2),
(97, 2),
(98, 2),
(99, 2),
(100, 2),
(99, 1),
(97, 1),
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `is_lab` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`id`, `name`, `capacity`, `is_lab`, `created_at`, `updated_at`) VALUES
(1, 'English Lecture Room', 60, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(2, 'Main Lecture Room', 50, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(3, 'ICT LAB 1', 70, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(4, 'ICT LAB 2', 60, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(5, 'Geography Lecture Room', 80, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(6, 'ODEL ROOM A', 70, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17'),
(7, 'ODEL ROOM B', 70, 0, '2025-07-10 08:13:17', '2025-07-10 08:13:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allocation_programme`
--
ALTER TABLE `allocation_programme`
  ADD PRIMARY KEY (`id`),
  ADD KEY `allocation_programme_allocation_id_foreign` (`allocation_id`),
  ADD KEY `allocation_programme_programme_id_foreign` (`programme_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `constraints`
--
ALTER TABLE `constraints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `constraints_constraintable_type_constraintable_id_index` (`constraintable_type`,`constraintable_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_department_id_foreign` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturers_user_id_foreign` (`user_id`),
  ADD KEY `lecturers_department_id_foreign` (`department_id`);

--
-- Indexes for table `lecturer_course_allocations`
--
ALTER TABLE `lecturer_course_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturer_course_allocations_course_id_foreign` (`course_id`),
  ADD KEY `lecturer_course_allocations_lecturer_id_foreign` (`lecturer_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programmes_department_id_foreign` (`department_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule_days`
--
ALTER TABLE `schedule_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schedule_days_name_unique` (`name`);

--
-- Indexes for table `schedule_entries`
--
ALTER TABLE `schedule_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_entries_programme_id_foreign` (`programme_id`),
  ADD KEY `schedule_entries_lecturer_id_foreign` (`lecturer_id`),
  ADD KEY `schedule_entries_course_id_foreign` (`course_id`),
  ADD KEY `schedule_entries_venue_id_foreign` (`venue_id`),
  ADD KEY `schedule_entries_schedule_version_id_foreign` (`schedule_version_id`);

--
-- Indexes for table `schedule_versions`
--
ALTER TABLE `schedule_versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_user_id_unique` (`user_id`),
  ADD KEY `students_programme_id_foreign` (`programme_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD KEY `user_roles_user_id_foreign` (`user_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allocation_programme`
--
ALTER TABLE `allocation_programme`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `constraints`
--
ALTER TABLE `constraints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `lecturer_course_allocations`
--
ALTER TABLE `lecturer_course_allocations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `schedule_days`
--
ALTER TABLE `schedule_days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `schedule_entries`
--
ALTER TABLE `schedule_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1382;

--
-- AUTO_INCREMENT for table `schedule_versions`
--
ALTER TABLE `schedule_versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `venues`
--
ALTER TABLE `venues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `allocation_programme`
--
ALTER TABLE `allocation_programme`
  ADD CONSTRAINT `allocation_programme_allocation_id_foreign` FOREIGN KEY (`allocation_id`) REFERENCES `lecturer_course_allocations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `allocation_programme_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lecturers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lecturer_course_allocations`
--
ALTER TABLE `lecturer_course_allocations`
  ADD CONSTRAINT `lecturer_course_allocations_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lecturer_course_allocations_lecturer_id_foreign` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programmes`
--
ALTER TABLE `programmes`
  ADD CONSTRAINT `programmes_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule_entries`
--
ALTER TABLE `schedule_entries`
  ADD CONSTRAINT `schedule_entries_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_entries_lecturer_id_foreign` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_entries_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_entries_schedule_version_id_foreign` FOREIGN KEY (`schedule_version_id`) REFERENCES `schedule_versions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_entries_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_programme_id_foreign` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `students_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
