-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 11, 2025 at 06:01 AM
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
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `subject` text,
  `body` text,
  `variables` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `user_id`, `title`, `slug`, `subject`, `body`, `variables`) VALUES
(3, 0, 'Verification', 'verification', 'Email verification', '<p>Hello {{user_name}},</p>\r\n\r\n<p>Welcome to {{site_name}}. Your verification code is: {{verify_code}}</p>\r\n', '{{site_name}},{{user_name}}, {{verify_code}}'),
(4, 0, 'Forgot Password', 'forgot-password', 'Recover password', '<p>Hello {{user_name}},</p>\r\n\r\n<p>We have reset your password, Please use this  {{recovery_password}}  code to login your account</p>\r\n', '{{user_name}}, {{recovery_password}}'),
(5, 0, 'Appointment Booking Confirmation Company', 'appointment-booking-confirmation-company', 'Appointment Booking confirmation', '<p>{{customer_name}} recently booked an appointment  on  {{appointment_date}}at {{appointment_date}} {{appointment_time}} </p><p>{{booking_number}}</p>\r\n', '{{customer_name}}, {{appointment_date}},{{appointment_time}}, {{booking_number}}'),
(6, 0, 'Appointment Booking Confirmation Customer', 'appointment-booking-confirmation-customer', 'Appointment Booking confirmation', '<p>Appopintment booking in {{business_name}} - of {{service_name}} is confirmed at {{appointment_date}} {{appointment_time}} on </p><p>{{location_name}}   {{location_address}}</p><p>{{zoom_link}}</p><p>{{meet_link}}</p><p> {{booking_number}}</p>\r\n\r\n<p> </p>\r\n\r\n<p> </p>\r\n', '{{business_name}}, {{service_name}},{{appointment_date}}, {{appointment_time}},{{location_name}}, {{location_address}},{{zoom_link}}, {{meet_link}},{{booking_number}}'),
(7, 0, 'Appointment Reminder Customer', 'appointment-reminder-customer', 'Appoitment Reminder notification', '<p>Hello {{customer_name}} ,</p><p> You have an appointment   {{business_name}} - {{service_name}} {{appointment_date}}  {{appointment_time}} </p><p>{{booking_number}}</p><p><br></p>\r\n', '{{customer_name}},{{business_name}}, {{service_name}},{{appointment_date}}, {{appointment_time}}, {{booking_number}}'),
(8, 0, 'Appointment Booking Confirmation Staff', 'appointment-booking-confirmation-staff', 'Appointment Confirmation', '<p>{{customer_name}} recently booked an appointment  on  {{appointment_date}}at {{appointment_date}} {{appointment_time}} </p><p>{{booking_number}}</p>\r\n', '{{customer_name}}, {{appointment_date}},{{appointment_time}}, {{booking_number}}'),
(9, 0, 'Appointment Update Customer', 'appointment-update-customer', 'Appointment Update', '<p>Dear {{customer_name}} ,</p><p>Thank you for your booking at our   {{business_name}} , {{service_name}}  on {{appointment_date}} at {{appointment_time}} ({{customer_timezone}}) is {{status}}</p><p>{{booking_number}}</p><p><br></p><p><br></p><p><br></p>\r\n', '{{customer_name}}, {{business_name}},{{service_name}}, {{appointment_date}}, {{appointment_time}},{{customer_timezone}},{{status}}, {{booking_number}}'),
(10, 0, 'Appointment Update Company & Staff', 'appointment-update-company-staff', 'Appointment Update', '<p>Appointment {{service_name}}  on  {{appointment_date}}at {{appointment_date}} at {{appointment_time}} is {{status}}</p><p>{{booking_number}}</p>', '{{service_name}}, {{appointment_date}},{{appointment_time}}, {{status}}, {{booking_number}}'),
(12, 0, 'Event Booking Company', 'event-booking-company', 'Event Booking confirmation', '<p>{{customer_name}}  recently booked an event  - {{event_name}} at {{event_date}}</p>', '{{customer_name}},{{event_name}}, {{event_date}}'),
(13, 0, 'Event Booking Customer', 'event-booking-customer', 'Event Booking confirmation', '<p>Hello {{customer_name}} ,</p><p>here is the status of your appointment</p><p>Confirmed event : {{event_name}}</p><p>Date : {{event_date}}</p><p>Time : {{event_time}}</p><p>If  you have any questions we are here to help</p><p>{{business_name}} team.</p><p><br></p>', '{{customer_name}}, {{event_name}},{{event_date}}, {{event_time}}, {{business_name}}'),
(14, 0, 'Event Booking Update Company', 'event-booking-update-company', 'Event Booking update confirmation', '<p>Booking of {{event_name}}  on  {{event_date}}at {{event_time}}  is {{status}}</p>', '{{event_name}},{{event_date}}, {{event_time}}, {{status}}'),
(15, 0, 'Event Booking Update Customer', 'event-booking-update-customer', 'Event Booking update confirmation', '<p>Dear {{customer_name}} ,</p><p>Thank you for your booking at our   {{business_name}} , {{event_name}}  on {{event_date}} at {{event_time}}  is {{status}}</p>', '{{customer_name}}, {{business_name}},{{event_name}}, {{event_date}}, {{event_time}},{{status}}'),
(18, 0, 'Contact Submit Company', 'contact-submit-company', 'Contact Message ', '<p>{{message}}</p><p>Sender Email : {{sender_email}}</p>', '{{message}},{{sender_email}}'),
(19, 0, 'Contact Submit Admin', 'contact-submit-admin', 'Contact Message ', '<p>{{message}}</p><p>Sender Email : {{sender_email}}</p>', '{{message}},{{sender_email}}'),
(20, 0, 'Payout Request Admin', 'payout-request-admin', 'Payout Request ', '<p>{{user_name}}  make a payout request of {{amount}} via {{payment_method}}</p>', '{{user_name}},{{amount}},{{payment_method}}'),
(21, 0, 'Payout Confirm Admin', 'payout-confirm-admin', 'Payout Request Confirm', '<p>Admin accept and completed your payout request of  {{<span xss=removed>amount</span>}} via {{payment_method}}</p>', '{{amount}},{{payment_method}}');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
