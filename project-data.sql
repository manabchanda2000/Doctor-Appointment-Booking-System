-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2025 at 07:58 AM
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
-- Database: `project-data`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook_link` varchar(255) DEFAULT NULL,
  `youtube_link` varchar(255) DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `linkedin_link` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_reviews` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `email`, `password`, `phone`, `address`, `facebook_link`, `youtube_link`, `instagram_link`, `linkedin_link`, `name`, `logo`, `rating`, `total_reviews`, `created_at`, `updated_at`) VALUES
(1, 'panditpritam399@gmail.com', '$2y$10$KTQL0BD.orHrLji9rI3csu70KMT2qta/Xz0YEbkZvYCmj3A8dhay6', '7719332010', 'Krishnagar', 'https://www.facebook.com/', 'https://www.youtube.com/', 'https://www.instagram.cpm', 'https://linkedin.com', 'Apna Health', 'admin_1745758718.jpg', 0.00, 0, '2025-04-27 11:23:47', '2025-04-27 13:08:17');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `posted_by` enum('doctor','admin') DEFAULT NULL,
  `posted_for` enum('Patient','doctor','all') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`announcement_id`, `admin_id`, `doctor_id`, `title`, `description`, `category`, `image`, `file`, `posted_by`, `posted_for`, `created_at`, `updated_at`) VALUES
(1, NULL, 2, 'COVID 19', 'Stay safe and secure', 'Emergency Alerts', NULL, NULL, 'doctor', 'all', '2025-04-26 16:38:06', '2025-04-26 16:38:06'),
(3, NULL, 2, 'Heatly tips', 'Stay Hydrated', 'Healthcare Updates', NULL, NULL, 'doctor', 'Patient', '2025-04-26 17:04:44', '2025-04-26 17:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

CREATE TABLE `answer` (
  `answer_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `answer_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`answer_id`, `question_id`, `doctor_id`, `answer`, `answer_time`) VALUES
(1, 3, 2, 'To improve your teeth, brush twice daily with fluoride toothpaste, floss daily, visit your dentist every six months, limit sugary and acidic foods, eat calcium-rich foods and crunchy fruits or vegetables, drink plenty of water, and consider whitening or orthodontic treatments if needed.', '2025-04-25 06:46:11'),
(2, 3, 2, 'To keep your teeth in great shape, brush and floss daily, eat a balanced diet low in sugar, stay hydrated, visit your dentist twice a year, and explore professional treatments if needed for whitening or alignment.', '2025-04-25 06:47:38'),
(3, 1, 1, 'To achieve glowing skin, maintain a daily skincare routine with cleansing, moisturizing, and sun protection, eat a balanced diet rich in fruits, vegetables, and water, get enough sleep, avoid stress, and consider exfoliating or using face masks weekly for added care.', '2025-04-25 06:50:37'),
(4, 4, 2, 'To strengthen your mindset, focus on building self-discipline, practice positive thinking, set clear and achievable goals, face challenges with resilience, learn from failures, surround yourself with supportive and inspiring people, and make time for mindfulness or meditation to develop mental clarity.', '2025-04-25 07:22:22');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `clinic_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `payment_status` enum('Pending','Paid','Refunded') DEFAULT 'Pending',
  `payment_mode` enum('Online','Offline') NOT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `doctor_confirmation` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `mode_of_appointment` enum('Physical','Video Call') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `patient_id`, `doctor_id`, `clinic_id`, `appointment_date`, `appointment_time`, `status`, `payment_status`, `payment_mode`, `cancellation_reason`, `doctor_confirmation`, `mode_of_appointment`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 8, '2025-04-24', '11:00:00', 'Completed', 'Paid', 'Offline', '', 'Approved', 'Physical', '2025-04-23 08:21:42', '2025-04-24 15:05:18'),
(16, 2, 1, 9, '2025-04-25', '10:30:00', 'Cancelled', 'Pending', 'Offline', 'Other personal reasons', 'Rejected', 'Physical', '2025-04-23 16:58:52', '2025-04-24 08:24:46'),
(17, 2, 2, 7, '2025-05-25', '00:00:00', 'Completed', 'Paid', 'Online', NULL, 'Approved', 'Physical', '2025-04-24 07:52:27', '2025-04-24 08:08:12'),
(18, 2, 1, 9, '2025-04-29', '23:00:00', 'Cancelled', 'Pending', 'Online', 'Changed my mind', 'Rejected', 'Physical', '2025-04-24 12:49:22', '2025-04-24 12:53:54'),
(19, 2, 2, 7, '2025-04-30', '10:00:00', 'Completed', 'Paid', 'Offline', NULL, 'Approved', 'Physical', '2025-04-26 19:00:36', '2025-04-27 07:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctor_id` int(11) NOT NULL,
  `UID` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `qualification` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `availability` enum('Active','Inactive','Blocked') DEFAULT 'Active',
  `address` varchar(512) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `fees` decimal(10,2) DEFAULT NULL,
  `emergency` enum('Yes','No') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctor_id`, `UID`, `name`, `email`, `phone`, `password`, `specialization`, `experience`, `qualification`, `bio`, `profile_img`, `rating`, `total_reviews`, `availability`, `address`, `gender`, `dob`, `fees`, `emergency`, `created_at`, `updated_at`) VALUES
(1, '123456789012', 'Dr. Pritam Pandit', 'pritambosss2@gmail.com', '7719332510', '$2y$10$KTQL0BD.orHrLji9rI3csu70KMT2qta/Xz0YEbkZvYCmj3A8dhay6', 'Neurology', 11, 'MBBS, MS', 'Hello I am a doctor', 'doctor_1745328167.png', 0.00, 0, 'Active', 'Karimpur Laxmipara', 'Male', '2004-09-19', 500.00, 'Yes', '2025-03-30 06:39:09', '2025-04-22 18:05:59'),
(2, '123456789025', 'Dr. Tanisha Chakraborty', 'tanishachakraborty309@gmail.com', '9883793987', '$2y$10$KTQL0BD.orHrLji9rI3csu70KMT2qta/Xz0YEbkZvYCmj3A8dhay6', 'Cardiology', 5, 'MBBS', 'I am a doctor', 'doctor_1745651768.png', 4.50, 2, 'Active', 'Dherapara', 'Female', '2004-12-26', 500.00, 'Yes', '2025-04-21 17:04:11', '2025-04-26 07:53:48');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_clinic`
--

CREATE TABLE `doctor_clinic` (
  `clinic_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `location_link` varchar(500) DEFAULT NULL,
  `clinic_img` varchar(255) DEFAULT NULL,
  `days_available` varchar(64) DEFAULT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `fees` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Active','Inactive','Blocked') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_clinic`
--

INSERT INTO `doctor_clinic` (`clinic_id`, `doctor_id`, `clinic_name`, `area`, `city`, `state`, `pincode`, `latitude`, `longitude`, `location_link`, `clinic_img`, `days_available`, `opening_time`, `closing_time`, `fees`, `created_at`, `updated_at`, `phone`, `email`, `status`) VALUES
(6, 2, 'ChomChom', 'Hashkhali, Gobindapur', 'Hashkhali', 'West Bengal', '741505', 22.57059840, 88.42117120, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d655.4029394276071!2d88.60471971736827!3d23.360137652215577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f8d864ffffffff%3A0xc11403c27ec01cd4!2sHanskhali%20High%20School%20(H.S.)!5e0!3m2!1sen!2sin!4v1745328827625!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade', 'clinic_1745331143.png', 'Mon, Sun', '18:44:00', '16:54:00', 500.00, '2025-04-22 14:12:23', '2025-04-22 14:24:46', '7719332510', 'panditpritam399@gmail.com', 'Active'),
(7, 2, 'Mua clinic', 'nabadwip', 'nabadwip', 'West Bengal', '741105', 25.40354728, 88.36718383, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8674.870449976615!2d88.48610413659691!3d23.382480523401725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f9200f17b8ae6f%3A0xa4131e1c1da6a6cc!2sGlobal%20Institute%20of%20Management%20%26%20Technology%20(GIMT)!5e0!3m2!1sen!2sin!4v1745309978598!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade', 'clinic_1745340473.png', 'Mon, Sun', '14:00:00', '16:00:00', 150.00, '2025-04-22 16:47:53', '2025-04-22 18:50:19', '7719332510', 'roumikdas684@gmail.com', 'Active'),
(8, 2, 'pui pui', 'Ghurni', 'Krishnagar', 'West Bengal', '741101', 23.38998153, 88.49751729, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d655.4029394276071!2d88.60471971736827!3d23.360137652215577!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f8d864ffffffff%3A0xc11403c27ec01cd4!2sHanskhali%20High%20School%20(H.S.)!5e0!3m2!1sen!2sin!4v1745328827625!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade', 'clinic_1745340875.png', 'Mon, Sun', '10:00:00', '17:00:00', 200.00, '2025-04-22 16:54:35', '2025-04-22 17:17:18', '7719332510', 'roumikdas684@gmail.com', 'Inactive'),
(9, 1, 'mumi clinic', 'Palpara More', 'Krishnagar', 'West Bengal', '741102', 23.39336892, 86.50571412, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8674.870449976615!2d88.48610413659691!3d23.382480523401725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f9200f17b8ae6f%3A0xa4131e1c1da6a6cc!2sGlobal%20Institute%20of%20Management%20%26%20Technology%20(GIMT)!5e0!3m2!1sen!2sin!4v1745309978598!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade', 'clinic_1745341003.png', 'Mon, Sun', '13:00:00', '18:00:00', 1000.00, '2025-04-22 16:56:43', '2025-04-22 18:50:32', '7719332510', 'roumikdas684@gmail.com', 'Active'),
(10, 1, 'peka pig', 'karimpure laxmipara', 'Karimpur', 'West Bengal', '741152', 23.36332288, 88.60029412, 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d8674.870449976615!2d88.48610413659691!3d23.382480523401725!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f9200f17b8ae6f%3A0xa4131e1c1da6a6cc!2sGlobal%20Institute%20of%20Management%20%26%20Technology%20(GIMT)!5e0!3m2!1sen!2sin!4v1745309978598!5m2!1sen!2sin\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade', 'clinic_1745342132.png', 'Mon, Sun', '16:00:00', '18:00:00', 500.00, '2025-04-22 17:15:32', '2025-04-22 17:19:09', '7719332510', 'roumikdas684@gmail.com', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_request`
--

CREATE TABLE `doctor_request` (
  `request_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `UID` varchar(50) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_request`
--

INSERT INTO `doctor_request` (`request_id`, `name`, `email`, `phone`, `gender`, `UID`, `status`, `requested_at`, `approved_at`) VALUES
(1, 'Pritam Pandit', 'pritambosss2@gmail.com', '9749769885', 'Male', '123456789012', 'Pending', '2025-03-29 04:27:17', NULL),
(2, 'Pritam Pandit', 'panditpritam399@gmail.com', '9749769885', 'Male', '123456789013', 'Pending', '2025-03-29 04:28:36', NULL),
(3, 'Tanisha Chakraborty', 'tanishachakraborty309@gmail.com', '9874587421', 'Female', '123456789201', 'Pending', '2025-04-27 15:04:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_booking`
--

CREATE TABLE `emergency_booking` (
  `booking_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `emergency_type` varchar(255) NOT NULL,
  `patient_location` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `contact_person_name` varchar(255) DEFAULT NULL,
  `contact_person_number` varchar(15) DEFAULT NULL,
  `status` enum('Pending','Accepted','Completed','Cancelled') DEFAULT 'Pending',
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_tips`
--

CREATE TABLE `health_tips` (
  `tip_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `health_tips`
--

INSERT INTO `health_tips` (`tip_id`, `doctor_id`, `title`, `description`, `category`, `image`, `likes`, `created_at`, `updated_at`) VALUES
(1, 2, 'How to improve your Mental Health?', '1. **Stay Active**: Engage in regular physical activities, such as walking, jogging, yoga, or any exercise you enjoy.\r\n2. **Practice Self-Care**: Take time for yourself, pamper yourself, and do things that make you happy and relaxed.\r\n3. **Maintain Connections**: Stay in touch with friends and family; talking to loved ones can be a great mood booster.\r\n4. **Eat Healthy**: Follow a balanced diet rich in nutrients to fuel your body and mind.\r\n5. **Sleep Well**: Aim for 7-9 hours of good-quality sleep to keep your mind refreshed and focused.\r\n6. **Manage Stress**: Use techniques like meditation, deep breathing, or journaling to cope with stress effectively.\r\n7. **Seek Professional Help**: If needed, don\'t hesitate to consult a therapist or counselor for support.\r\n8. **Set Goals**: Achieve a sense of purpose by setting small, realistic goals and celebrating your progress.\r\n9. **Limit Negativity**: Avoid toxic environments and reduce exposure to social media if it affects your mood.\r\n10. **Stay Positive**: Focus on gratitude, reflect on happy moments, and work on building a positive outlook.', 'Mental Health', 'tips_1745567306_IMG.png', 0, '2025-04-25 07:48:26', '2025-04-25 14:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `log_info`
--

CREATE TABLE `log_info` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `logout_time` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` enum('Success','Failed') NOT NULL,
  `log_type` enum('patient','doctor','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_info`
--

INSERT INTO `log_info` (`log_id`, `admin_id`, `doctor_id`, `patient_id`, `login_time`, `logout_time`, `ip_address`, `device_info`, `location`, `status`, `log_type`) VALUES
(1, NULL, 1, NULL, '2025-04-02 06:28:34', '2025-04-02 06:28:47', '2409:40e1:1d:49e:adc5:e637:a977:46b6', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(2, NULL, 1, NULL, '2025-04-02 06:28:55', '2025-04-02 06:48:06', '2409:40e1:1d:49e:adc5:e637:a977:46b6', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(64, NULL, 1, NULL, '2025-04-02 06:48:21', NULL, '2409:40e1:1d:49e:adc5:e637:a977:46b6', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(65, NULL, 1, NULL, '2025-04-02 06:48:24', NULL, '2409:40e1:1d:49e:adc5:e637:a977:46b6', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(66, NULL, 1, NULL, '2025-04-03 07:48:27', '2025-04-03 08:04:47', '2409:40e1:107a:d47a:ed39:3a2:d507:d960', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(67, NULL, NULL, NULL, '2025-04-08 07:11:25', NULL, '103.192.116.82', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(68, NULL, NULL, NULL, '2025-04-08 07:11:30', NULL, '136.232.95.98', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(69, NULL, NULL, NULL, '2025-04-08 07:11:30', NULL, '103.192.116.82', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(70, NULL, NULL, NULL, '2025-04-08 07:11:33', NULL, '103.192.116.82', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(71, NULL, 1, NULL, '2025-04-08 07:13:58', '2025-04-08 07:16:24', '103.192.116.82', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(72, NULL, 1, NULL, '2025-04-08 07:16:55', '2025-04-08 07:19:00', '103.192.116.82', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(73, NULL, 1, NULL, '2025-04-08 07:19:18', NULL, '2401:4900:775f:a76e:d0ab:9a6b:a53:3a1c', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(74, NULL, NULL, NULL, '2025-04-20 01:34:47', '2025-04-20 02:11:42', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(75, NULL, NULL, NULL, '2025-04-20 02:12:00', '2025-04-20 02:54:04', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(76, NULL, NULL, NULL, '2025-04-20 03:05:39', '2025-04-20 03:31:06', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(77, NULL, NULL, NULL, '2025-04-20 03:36:05', '2025-04-20 03:36:29', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(78, NULL, NULL, NULL, '2025-04-20 03:37:24', '2025-04-20 03:37:31', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(79, NULL, NULL, NULL, '2025-04-20 03:39:19', '2025-04-20 03:39:23', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(80, NULL, NULL, 1, '2025-04-20 03:39:54', '2025-04-20 05:52:02', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(81, NULL, NULL, NULL, '2025-04-20 05:52:18', '2025-04-20 05:55:14', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(82, NULL, NULL, NULL, '2025-04-20 05:55:26', NULL, '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(83, NULL, NULL, NULL, '2025-04-20 05:56:12', NULL, '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(84, NULL, 1, NULL, '2025-04-20 06:08:39', '2025-04-20 06:25:24', '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'doctor'),
(85, NULL, 1, NULL, '2025-04-20 06:25:42', NULL, '2401:4900:b1bb:d5db:8497:6360:25a6:6bb6', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'doctor'),
(87, NULL, NULL, NULL, '2025-04-20 14:12:14', '2025-04-20 15:14:10', '2401:4900:b1bb:d5db:7d47:cbc7:3d47:a8be', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(88, NULL, NULL, NULL, '2025-04-20 15:14:23', '2025-04-20 15:20:33', '2401:4900:b1bb:d5db:7d47:cbc7:3d47:a8be', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(89, NULL, NULL, NULL, '2025-04-20 15:20:52', '2025-04-20 16:02:59', '2401:4900:b1bb:d5db:7d47:cbc7:3d47:a8be', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(90, NULL, NULL, 1, '2025-04-20 16:03:19', NULL, '2401:4900:b1bb:d5db:7d47:cbc7:3d47:a8be', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(91, NULL, NULL, 1, '2025-04-21 05:02:53', '2025-04-21 08:09:46', '2409:40e1:4001:e5c5:85f2:7697:cbac:8f53', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(92, NULL, NULL, NULL, '2025-04-21 08:09:57', NULL, '2409:40e1:4006:e6e1:4836:5a2:449b:ba34', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(93, NULL, NULL, 1, '2025-04-21 14:40:28', NULL, '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(94, NULL, 1, NULL, '2025-04-21 15:41:43', '2025-04-21 17:01:50', '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(95, NULL, 1, NULL, '2025-04-21 17:02:01', '2025-04-21 17:10:39', '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(96, NULL, 2, NULL, '2025-04-21 17:10:51', NULL, '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(97, NULL, 1, NULL, '2025-04-21 17:20:28', '2025-04-21 17:20:48', '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(98, NULL, 2, NULL, '2025-04-21 17:21:04', NULL, '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(99, NULL, NULL, 1, '2025-04-21 17:51:16', NULL, '2401:4900:3bdb:5105:c8c5:3e93:9597:f3de', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(100, NULL, 2, NULL, '2025-04-22 04:46:14', NULL, '2409:40e1:4004:1afe:98b2:6893:4345:a4d7', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(101, NULL, 2, NULL, '2025-04-22 06:09:44', '2025-04-22 06:51:41', '2409:40e1:4004:1afe:98b2:6893:4345:a4d7', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(102, NULL, 1, NULL, '2025-04-22 06:51:52', '2025-04-22 06:52:10', '2409:40e1:4004:1afe:98b2:6893:4345:a4d7', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(103, NULL, 2, NULL, '2025-04-22 06:52:21', NULL, '2409:40e1:4004:1afe:98b2:6893:4345:a4d7', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(104, NULL, NULL, 2, '2025-04-22 13:12:31', NULL, '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(105, NULL, 1, NULL, '2025-04-22 13:13:43', '2025-04-22 13:15:02', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(106, NULL, NULL, 1, '2025-04-22 13:15:38', '2025-04-22 13:15:55', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(107, NULL, NULL, 2, '2025-04-22 13:16:06', '2025-04-22 13:19:37', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(108, NULL, 1, NULL, '2025-04-22 13:20:57', '2025-04-22 14:11:40', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(109, NULL, 2, NULL, '2025-04-22 14:11:51', '2025-04-22 16:55:03', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(110, NULL, NULL, 2, '2025-04-22 14:23:00', '2025-04-22 16:45:33', '2401:4900:3be2:e587:4031:b45b:8c85:47e5', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'patient'),
(111, NULL, NULL, 2, '2025-04-22 16:45:45', '2025-04-22 18:37:23', '2401:4900:7447:28e3:4c8f:1efd:4157:b365', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'patient'),
(112, NULL, 1, NULL, '2025-04-22 16:55:23', NULL, '2401:4900:7447:28e3:4c8f:1efd:4157:b365', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(113, NULL, NULL, 1, '2025-04-22 18:37:36', NULL, '2401:4900:775e:9584:4ee:830a:1bb9:520d', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'patient'),
(114, NULL, NULL, 2, '2025-04-22 18:54:49', '2025-04-22 19:22:49', '2401:4900:775e:9584:4ee:830a:1bb9:520d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(115, NULL, NULL, 2, '2025-04-22 19:23:00', '2025-04-22 19:25:15', '2401:4900:775e:9584:4ee:830a:1bb9:520d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(116, NULL, NULL, 2, '2025-04-22 19:25:25', NULL, '2401:4900:775e:9584:4ee:830a:1bb9:520d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(117, NULL, NULL, 2, '2025-04-22 19:50:04', '2025-04-22 20:22:58', '2401:4900:775e:9584:4ee:830a:1bb9:520d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(118, NULL, NULL, 2, '2025-04-22 20:23:11', '2025-04-22 20:51:52', '2401:4900:72ab:64ea:1146:d39d:3c76:2bca', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(119, NULL, NULL, 2, '2025-04-22 20:52:02', NULL, '2401:4900:72ab:64ea:1146:d39d:3c76:2bca', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(120, NULL, NULL, 2, '2025-04-23 05:17:35', '2025-04-23 08:25:10', '2401:4900:3a05:6694:bced:44ff:5ead:aead', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(121, NULL, NULL, 2, '2025-04-23 08:25:20', NULL, '2401:4900:3a05:6694:bced:44ff:5ead:aead', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(122, NULL, NULL, 2, '2025-04-23 12:40:12', NULL, '2401:4900:3a1a:bf1c:cc8b:c274:56e2:6301', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(123, NULL, 2, NULL, '2025-04-23 15:25:41', NULL, '2401:4900:3a1a:bf1c:cc8b:c274:56e2:6301', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(124, NULL, 1, NULL, '2025-04-23 16:26:08', NULL, '2401:4900:3a1a:bf1c:cc8b:c274:56e2:6301', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(125, NULL, NULL, 2, '2025-04-24 05:01:49', NULL, '2409:40e1:4011:e823:4984:28eb:3be7:81b7', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(126, NULL, 2, NULL, '2025-04-24 05:08:56', NULL, '2409:40e1:4011:e823:4984:28eb:3be7:81b7', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(127, NULL, 1, NULL, '2025-04-24 08:26:10', NULL, '2409:40e1:400c:5fbb:5185:5bc7:7214:a250', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(128, NULL, 1, NULL, '2025-04-24 12:42:37', '2025-04-24 13:34:26', '2401:4900:3a0f:235d:757a:e869:bb23:9dc3', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(129, NULL, NULL, 1, '2025-04-24 12:43:15', '2025-04-24 12:44:31', '2401:4900:3a0f:235d:757a:e869:bb23:9dc3', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(130, NULL, NULL, 2, '2025-04-24 12:44:40', '2025-04-24 19:08:37', '2401:4900:3a0f:235d:757a:e869:bb23:9dc3', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(131, NULL, 2, NULL, '2025-04-24 13:34:36', '2025-04-24 19:08:40', '2401:4900:3a0f:235d:757a:e869:bb23:9dc3', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(132, NULL, 1, NULL, '2025-04-24 14:47:22', NULL, '2401:4900:3a0f:235d:757a:e869:bb23:9dc3', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(133, NULL, NULL, 2, '2025-04-25 06:00:12', NULL, '2409:40e1:4050:7335:4592:a656:1805:625', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(134, NULL, 2, NULL, '2025-04-25 06:01:08', NULL, '2409:40e1:4050:7335:4592:a656:1805:625', 'Win32 - Microsoft Edge', 'Kolkata, India', 'Success', 'doctor'),
(135, NULL, 1, NULL, '2025-04-25 06:49:06', NULL, '2401:4900:3a2e:6fc2:d03c:26d5:347:1dbe', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'doctor'),
(136, NULL, NULL, 2, '2025-04-25 13:09:32', '2025-04-25 13:39:59', 'UNKNOWN', 'Win32 - Google Chrome', 'Unknown', 'Success', 'patient'),
(137, NULL, 2, NULL, '2025-04-25 13:09:43', '2025-04-25 18:19:31', 'UNKNOWN', 'Win32 - Microsoft Edge', 'Unknown', 'Success', 'doctor'),
(138, NULL, NULL, 2, '2025-04-25 13:40:10', NULL, '2401:4900:3a16:337a:90e3:374c:753f:735d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(139, NULL, NULL, 2, '2025-04-25 13:52:42', NULL, '2401:4900:3a16:337a:90e3:374c:753f:735d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(140, NULL, NULL, 2, '2025-04-25 13:53:06', '2025-04-25 18:19:26', '2401:4900:3a16:337a:90e3:374c:753f:735d', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'patient'),
(141, NULL, NULL, 2, '2025-04-26 05:24:14', '2025-04-26 09:06:03', '2401:4900:b188:f34d:6da4:1019:9c02:5f2e', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(142, NULL, 2, NULL, '2025-04-26 05:24:29', '2025-04-26 09:06:08', '2401:4900:b188:f34d:6da4:1019:9c02:5f2e', 'Win32 - Microsoft Edge', 'Patna, India', 'Success', 'doctor'),
(143, NULL, 2, NULL, '2025-04-26 15:06:43', NULL, '2401:4900:b188:f34d:fda1:ae77:bb31:981f', 'Win32 - Microsoft Edge', 'Patna, India', 'Success', 'doctor'),
(144, NULL, NULL, 2, '2025-04-26 15:06:54', NULL, '2401:4900:b188:f34d:fda1:ae77:bb31:981f', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(145, NULL, 1, NULL, '2025-04-26 17:08:57', NULL, '223.184.141.156', 'Win32 - Google Chrome', 'Mumbai, India', 'Success', 'doctor'),
(146, NULL, NULL, 2, '2025-04-27 05:45:23', '2025-04-27 08:52:27', '2401:4900:b148:fb53:6588:7a8d:b781:b5ed', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'patient'),
(147, NULL, 2, NULL, '2025-04-27 05:45:29', '2025-04-27 08:21:37', '2401:4900:b148:fb53:6588:7a8d:b781:b5ed', 'Win32 - Microsoft Edge', 'Patna, India', 'Success', 'doctor'),
(148, NULL, 2, NULL, '2025-04-27 08:44:01', NULL, '223.184.137.177', 'Win32 - Google Chrome', 'Mumbai, India', 'Success', 'doctor'),
(149, 1, NULL, NULL, '2025-04-27 11:24:59', '2025-04-27 17:26:26', '2401:4900:b148:fb53:6588:7a8d:b781:b5ed', 'Win32 - Google Chrome', 'Patna, India', 'Success', 'admin'),
(150, NULL, 2, NULL, '2025-04-27 11:32:47', '2025-04-27 17:26:34', '2401:4900:b148:fb53:6588:7a8d:b781:b5ed', 'Win32 - Microsoft Edge', 'Patna, India', 'Success', 'doctor'),
(151, 1, NULL, NULL, '2025-04-28 05:52:27', NULL, '2409:40e1:4053:9106:b474:6842:4d41:2fc0', 'Win32 - Google Chrome', 'Kolkata, India', 'Success', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `our_team`
--

CREATE TABLE `our_team` (
  `team_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `github` varchar(255) DEFAULT NULL,
  `portfolio` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `patient_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `emergency_contact` varchar(16) DEFAULT NULL,
  `area` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `pincode` varchar(10) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL,
  `availability` enum('Active','Inactive','Blocked') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`patient_id`, `name`, `email`, `password`, `phone`, `gender`, `dob`, `blood_group`, `emergency_contact`, `area`, `city`, `state`, `pincode`, `profile_img`, `availability`, `created_at`, `updated_at`) VALUES
(1, 'Pritam Pandit', 'panditpritam399@gmail.com', '$2y$10$ch23dPp25QTqzYN1xjbhE.0aeY2KFQO4xgaCUk/esczkhKCpZ3xq2', '7719332510', 'Male', '2004-09-19', 'A-', '9883793987', 'karimpure laxmipara', 'Karimpur', 'WB', '741152', 'patient_1745222897.png', 'Active', '2025-03-28 13:01:59', '2025-04-21 18:29:22'),
(2, 'Tanisha Chakraborty', 'tanishachakraborty309@gmail.com', '$2y$10$ch23dPp25QTqzYN1xjbhE.0aeY2KFQO4xgaCUk/esczkhKCpZ3xq2', '9883793987', 'Female', '2004-12-26', 'B+', '7719332510', 'Dhera Para', 'Nabadwip', 'WB', '741105', 'patient_1745651741.png', 'Active', '2025-04-21 17:06:32', '2025-04-26 07:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `prescription_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `prescription` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`prescription_id`, `appointment_id`, `patient_id`, `doctor_id`, `prescription`, `created_at`, `updated_at`) VALUES
(4, 1, 2, 2, 'prescription_1745742039_8491.pdf', '2025-04-27 08:20:39', '2025-04-27 08:20:39');

-- --------------------------------------------------------

--
-- Table structure for table `query`
--

CREATE TABLE `query` (
  `query_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `query`
--

INSERT INTO `query` (`query_id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Pritam Pandit', 'pritambosss2@gmail.com', '9749769885', 'hello', '2025-03-29 07:42:25'),
(2, 'Pritam Pandit', 'pritambosss2@gmail.com', '9749769885', 'hello', '2025-03-29 07:45:15');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE `question` (
  `question_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `description` text DEFAULT NULL,
  `question_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Answered') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`question_id`, `patient_id`, `specialty`, `question`, `description`, `question_time`, `status`) VALUES
(1, 2, 'Skin', 'How to glow my skin', 'it is a big problem to me.', '2025-04-25 06:50:37', 'Answered'),
(3, 2, 'Dental', 'How to improve my teeth', 'there is a big problem', '2025-04-25 06:46:11', 'Answered'),
(4, 2, 'Mental Health', 'How to strong my mindset', 'it is a big problem to my study', '2025-04-25 07:21:33', 'Answered');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `review_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `review_for` enum('doctor','website') NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`review_id`, `patient_id`, `doctor_id`, `review_for`, `rating`, `review_text`, `review_date`, `likes_count`) VALUES
(3, 2, 2, 'doctor', 5, 'Good doctor', '2025-04-26 07:52:56', 0),
(4, 2, 2, 'doctor', 4, 'Beautiful doctor❤️🫣', '2025-04-26 07:53:48', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_mode` enum('Online','Offline') NOT NULL,
  `payment_status` enum('Pending','Paid','Failed') DEFAULT 'Pending',
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `reference_id` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `appointment_id`, `patient_id`, `doctor_id`, `amount`, `payment_mode`, `payment_status`, `transaction_date`, `reference_id`, `remarks`) VALUES
(1, 1, 2, 2, 200.00, 'Offline', 'Paid', '2025-04-23 17:56:10', NULL, NULL),
(5, 17, 2, 2, 150.00, 'Online', 'Paid', '2025-04-24 08:08:12', NULL, NULL),
(6, 19, 2, 2, 150.00, 'Offline', 'Paid', '2025-04-27 07:11:51', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `clinic_id` (`clinic_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `UID` (`UID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `doctor_clinic`
--
ALTER TABLE `doctor_clinic`
  ADD PRIMARY KEY (`clinic_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `doctor_request`
--
ALTER TABLE `doctor_request`
  ADD PRIMARY KEY (`request_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `UID` (`UID`);

--
-- Indexes for table `emergency_booking`
--
ALTER TABLE `emergency_booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `health_tips`
--
ALTER TABLE `health_tips`
  ADD PRIMARY KEY (`tip_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `log_info`
--
ALTER TABLE `log_info`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `our_team`
--
ALTER TABLE `our_team`
  ADD PRIMARY KEY (`team_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `query`
--
ALTER TABLE `query`
  ADD PRIMARY KEY (`query_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `answer`
--
ALTER TABLE `answer`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctor_clinic`
--
ALTER TABLE `doctor_clinic`
  MODIFY `clinic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `doctor_request`
--
ALTER TABLE `doctor_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `emergency_booking`
--
ALTER TABLE `emergency_booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_tips`
--
ALTER TABLE `health_tips`
  MODIFY `tip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_info`
--
ALTER TABLE `log_info`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `our_team`
--
ALTER TABLE `our_team`
  MODIFY `team_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `query`
--
ALTER TABLE `query`
  MODIFY `query_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `answer_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `question` (`question_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `answer_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_ibfk_3` FOREIGN KEY (`clinic_id`) REFERENCES `doctor_clinic` (`clinic_id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_clinic`
--
ALTER TABLE `doctor_clinic`
  ADD CONSTRAINT `doctor_clinic_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_booking`
--
ALTER TABLE `emergency_booking`
  ADD CONSTRAINT `emergency_booking_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emergency_booking_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `health_tips`
--
ALTER TABLE `health_tips`
  ADD CONSTRAINT `health_tips_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `log_info`
--
ALTER TABLE `log_info`
  ADD CONSTRAINT `log_info_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `log_info_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `log_info_ibfk_3` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE SET NULL;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `question_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointment` (`appointment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_ibfk_3` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`doctor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
