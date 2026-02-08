-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 12:42 PM
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
-- Table structure for table `obituaries`
--

CREATE TABLE `obituaries` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `birth_date` date NOT NULL,
  `death_date` date NOT NULL,
  `age` int(11) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `full_biography` text DEFAULT NULL,
  `wake_schedule` varchar(100) DEFAULT NULL,
  `chapel` varchar(100) DEFAULT NULL,
  `viewing_hours` varchar(100) DEFAULT NULL,
  `interment` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obituaries`
--

INSERT INTO `obituaries` (`id`, `client_id`, `booking_id`, `name`, `birth_date`, `death_date`, `age`, `image_url`, `excerpt`, `full_biography`, `wake_schedule`, `chapel`, `viewing_hours`, `interment`, `created_at`) VALUES
(1, 1, 1, 'Maria Santos Cruz', '1945-03-15', '2025-01-18', 79, 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=400&h=400&fit=crop', 'Beloved mother, grandmother, and friend. Maria dedicated her life to her family and community, touching countless lives with her warmth and kindness.', 'Maria Santos Cruz, 79, passed away peacefully on January 18, 2025, surrounded by her loving family. Born on March 15, 1945, in Alaminos, Laguna, Maria was a pillar of strength and compassion in her community.', 'January 20-22, 2025', 'Serenity Chapel', '9:00 AM - 9:00 PM', 'January 23, 2025, 10:00 AM at Alaminos Memorial Park', '2026-02-03 10:00:58'),
(2, 1, 2, 'Roberto Miguel Reyes', '1960-07-08', '2025-01-16', 64, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=400&fit=crop', 'Loving husband, devoted father, and respected educator. Roberto\'s passion for teaching and dedication to his students made a lasting impact on the community.', 'Roberto Miguel Reyes, 64, beloved educator and family man, passed away on January 16, 2025. Born on July 8, 1960, Roberto dedicated over 40 years to education, shaping the minds and hearts of countless students.', 'January 18-20, 2025', 'Harmony Hall', '8:00 AM - 10:00 PM', 'January 21, 2025, 9:00 AM at Holy Cross Cemetery', '2026-02-03 10:00:58'),
(3, 1, 3, 'Elena Beatriz Hernandez', '1952-11-22', '2025-01-13', 72, 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&h=400&fit=crop', 'Cherished wife, mother of three, and devoted grandmother. Elena\'s love for cooking and hospitality brought joy to everyone who knew her.', 'Elena Beatriz Hernandez, 72, passed away peacefully on January 13, 2025. Born on November 22, 1952, Elena was known throughout the community for her incredible cooking and warm hospitality.', 'January 15-17, 2025', 'Grace Chapel', '10:00 AM - 8:00 PM', 'January 18, 2025, 2:00 PM at St. Mary\'s Cemetery', '2026-02-03 10:00:58'),
(4, 1, 4, 'Jose Antonio Diaz', '1938-04-03', '2025-01-10', 86, 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&h=400&fit=crop', 'Beloved patriarch, war veteran, and community leader. Jose\'s wisdom, strength, and generosity inspired generations of family and friends.', 'Jose Antonio Diaz, 86, patriarch of the Diaz family, passed away on January 10, 2025. Born on April 3, 1938, Jose was a man of honor, courage, and unwavering integrity who served his country and community with distinction.', 'January 12-15, 2025', 'Tranquility Suite', '24 Hours', 'January 16, 2025, 10:00 AM at Veterans Memorial Cemetery', '2026-02-03 10:00:58'),
(5, 1, 5, 'Carmen Gloria Mendez', '1968-09-12', '2025-01-06', 56, 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=400&h=400&fit=crop', 'Compassionate nurse, loving mother, and devoted friend. Carmen\'s caring spirit and selfless dedication to helping others will be deeply missed.', 'Carmen Gloria Mendez, 56, a compassionate nurse and loving mother, passed away unexpectedly on January 6, 2025. Born on September 12, 1968, Carmen dedicated her life to caring for others, both professionally and personally.', 'January 8-10, 2025', 'Serenity Chapel', '9:00 AM - 9:00 PM', 'January 11, 2025, 3:00 PM at Garden of Peace Memorial Park', '2026-02-03 10:00:58'),
(6, 1, 6, 'Fernando Luis Garcia', '1975-01-28', '2025-01-03', 49, 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=400&fit=crop', 'Talented musician, devoted husband, and proud father. Fernando\'s love for music and passion for life touched everyone who had the privilege of knowing him.', 'Fernando Luis Garcia, 49, talented musician and beloved family man, passed away tragically on January 3, 2025. Born on January 28, 1975, Fernando lived a life filled with music, love, and joy that he generously shared with everyone around him.', 'January 5-7, 2025', 'Harmony Hall', '8:00 AM - 10:00 PM', 'January 8, 2025, 11:00 AM at Eternal Gardens', '2026-02-03 10:00:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `obituaries`
--
ALTER TABLE `obituaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_obituaries_client_id` (`client_id`),
  ADD KEY `idx_obituaries_booking_id` (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `obituaries`
--
ALTER TABLE `obituaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for table `obituaries`
--
ALTER TABLE `obituaries`
  ADD CONSTRAINT `fk_obituaries_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_obituaries_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
