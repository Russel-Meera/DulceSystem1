git -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 02:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_dulce`
--

-- --------------------------------------------------------

--
-- Table structure for table `funeral_packages`
--

CREATE TABLE `funeral_packages` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Basic','Standard','Premium','Deluxe') NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `description` text NOT NULL,
  `details` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `funeral_packages`
--

INSERT INTO `funeral_packages` (`id`, `name`, `type`, `price`, `description`, `details`, `image`, `created_at`, `updated_at`) VALUES
(1, 'awda', 'Basic', 2131.00, 'adaw', '21312', '1769836692_697d90948c0e3.jpg', '2026-01-31 05:18:12', '2026-01-31 05:18:12'),
(2, 'Russel', '', 123123.00, 'awdawd', '', '1769841957_697da5252a4cc.jpg', '2026-01-31 06:45:57', '2026-01-31 06:45:57'),
(3, 'adaw', 'Basic', 123123.00, 'awdawd', 'awdaw', '1769842248_697da6482ea38.jpg', '2026-01-31 06:50:48', '2026-01-31 06:50:48'),
(4, 'Russel', 'Basic', 123.00, 'Kawihdawdj', '[\"awdaw\",\"awdawd\",\"1231\"]', '1769844305_697dae51d2843.jpg', '2026-01-31 07:25:05', '2026-01-31 07:25:05'),
(5, 'Tite', 'Standard', 1000000.00, 'Tanga', '[\"Maangas\",\"Mabangis\"]', '1769845872_697db4709472c.jpg', '2026-01-31 07:51:12', '2026-01-31 07:51:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `funeral_packages`
--
ALTER TABLE `funeral_packages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `funeral_packages`
--
ALTER TABLE `funeral_packages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
