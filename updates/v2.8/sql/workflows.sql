-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 10, 2024 at 07:49 AM
-- Server version: 5.7.39
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aoxio_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `workflows`
--

CREATE TABLE `workflows` (
  `id` int(11) NOT NULL,
  `lang_id` int(11) DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `details` text,
  `image` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `workflows`
--

INSERT INTO `workflows` (`id`, `lang_id`, `title`, `details`, `image`, `thumb`, `status`) VALUES
(1, 1, 'Create Account and Set Up Business', 'Sign up, add services, set availability, and customize your booking page to match your brand for a seamless customer experience.', 'assets/images/workflow1.png', 'assets/images/workflow1.png', 1),
(2, 1, 'Accept Bookings and Manage Schedule', 'Share your booking link to let clients book easily. Manage, reschedule, and send reminders from one platform.', 'assets/images/workflow2.png', 'assets/images/workflow2.png', 1),
(3, 1, 'Automate Payments & Grow Your Business', 'Accept payments securely, track transactions, auto-send invoices, and use analytics to optimize and expand your business.', 'assets/images/workflow3.png', 'assets/images/workflow3.png', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `workflows`
--
ALTER TABLE `workflows`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `workflows`
--
ALTER TABLE `workflows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
