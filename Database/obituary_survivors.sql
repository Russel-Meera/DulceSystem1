-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 12:43 PM
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
-- Table structure for table `obituary_survivors`
--

CREATE TABLE `obituary_survivors` (
  `id` int(11) NOT NULL,
  `obituary_id` int(11) NOT NULL,
  `survivor_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obituary_survivors`
--

INSERT INTO `obituary_survivors` (`id`, `obituary_id`, `survivor_text`) VALUES
(1, 1, 'Husband: Ricardo Cruz'),
(2, 1, 'Children: Ana Maria Cruz-Torres, Jose Santos Cruz Jr., Elena Cruz-Mendoza'),
(3, 1, 'Grandchildren: 8 beloved grandchildren'),
(4, 1, 'Siblings: Rosa Santos-Garcia, Pedro Santos'),
(5, 2, 'Wife: Patricia Reyes'),
(6, 2, 'Children: Miguel Antonio Reyes, Sofia Reyes-Santos, Lucas Roberto Reyes'),
(7, 2, 'Grandchildren: 5 grandchildren'),
(8, 2, 'Siblings: Carmen Reyes-Lopez, Fernando Reyes'),
(9, 3, 'Husband: Carlos Hernandez'),
(10, 3, 'Children: Maria Elena Hernandez-Cruz, Carlos Jr. Hernandez, Isabel Hernandez-Diaz'),
(11, 3, 'Grandchildren: 7 grandchildren'),
(12, 3, 'Siblings: Rosa Beatriz Santos, Antonio Beatriz'),
(13, 4, 'Wife: Remedios Diaz'),
(14, 4, 'Children: 6 children'),
(15, 4, 'Grandchildren: 15 grandchildren'),
(16, 4, 'Great-grandchildren: 8 great-grandchildren'),
(17, 4, 'Siblings: 3 siblings'),
(18, 5, 'Children: Andrea Mendez, Gabriel Mendez, Sofia Mendez'),
(19, 5, 'Mother: Gloria Reyes'),
(20, 5, 'Siblings: Roberto Mendez, Maria Mendez-Cruz'),
(21, 5, 'Partner: Dr. Luis Santos'),
(22, 6, 'Wife: Isabella Garcia'),
(23, 6, 'Children: Luis Fernando Garcia, Maya Isabella Garcia'),
(24, 6, 'Parents: Luis Garcia Sr., Carmen Garcia'),
(25, 6, 'Siblings: Marco Garcia, Lucia Garcia-Mendez');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `obituary_survivors`
--
ALTER TABLE `obituary_survivors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `obituary_id` (`obituary_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `obituary_survivors`
--
ALTER TABLE `obituary_survivors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `obituary_survivors`
--
ALTER TABLE `obituary_survivors`
  ADD CONSTRAINT `obituary_survivors_ibfk_1` FOREIGN KEY (`obituary_id`) REFERENCES `obituaries` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
