-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 12:44 PM
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
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `category` enum('general','service','holiday','important') NOT NULL,
  `content` text NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `views` int(11) NOT NULL DEFAULT 0,
  `announcement_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `category`, `content`, `is_pinned`, `views`, `announcement_date`, `created_at`) VALUES
(2, 'New Online Payment Options Available', 'service', '<p>We’re excited to announce new convenient payment options for our services. Clients can now pay through GCash, PayMaya, bank transfer, and credit/debit cards through our secure online payment system.</p>', 1, 192, '2025-01-22', '2026-02-03 10:34:01'),
(3, 'Virtual Memorial Services Now Available', 'general', '<p><strong>In response to client needs, we now offer live streaming services for memorial ceremonies. Family and friends who cannot attend in person can participate remotely through our secure online platform.</strong></p>', 1, 160, '2025-01-20', '2026-02-03 10:34:01'),
(4, 'Schedule Updates for Upcoming Holidays', 'holiday', 'Please be informed of our adjusted operating schedules for the upcoming national holidays. Emergency services remain available 24/7. Regular services will resume on the specified dates.', 0, 313, '2025-01-15', '2026-02-03 10:34:01'),
(5, 'New Chapel Renovation Completed', 'service', 'We’re proud to announce the completion of our Harmony Hall renovation. The chapel now features enhanced audio-visual equipment, improved seating, and upgraded air conditioning for your comfort.', 0, 278, '2025-01-10', '2026-02-03 10:34:01'),
(6, 'Community Outreach Program', 'general', 'DULCE Funeral Services is launching a community outreach program to provide grief counseling and support services to families in need. More details will be shared soon.', 0, 198, '2025-01-05', '2026-02-03 10:34:01'),
(7, 'Happy New Year from DULCE', 'general', 'As we welcome the new year, DULCE Funeral Services extends warm wishes to all our clients and their families. Thank you for trusting us with your needs. We remain committed to serving with compassion.', 0, 421, '2025-01-01', '2026-02-03 10:34:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
