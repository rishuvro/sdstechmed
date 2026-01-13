-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 02:24 AM
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
-- Database: `sdstechmed`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `created_at`) VALUES
(1, 'SDS Admin', 'admin@sdstechmed.com', '$2y$10$ru5YXKPPKnZeSDt/EPW8U.3u5HDmK5CokGbQs3n4k6lqBSAdbsCIi', '2025-12-22 08:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(160) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `seo_h1` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `created_at`, `image`, `seo_h1`, `meta_title`, `meta_description`, `featured`) VALUES
(1, 'Hair Removal Machines', 'hair-removal-machines', 'Professional hair removal systems for clinics and distributors.', 1, '2025-12-22 09:05:57', 'be9ea3f407ce549eb40fc98bc40e65da.webp', 'China Hair Removal Machines manufacturer', 'Hair Removal Machines | SDS Techmed', 'Explore SDS Techmed hair removal machines for professional clinics, salons, and distributors.', 1),
(2, 'Tattoo Removal Machines', 'tattoo-removal-machines', 'Reliable tattoo removal solutions with clinical-grade performance.', 2, '2025-12-22 09:05:57', '2.png', 'China Tattoo Removal Machines manufacturer', 'Tattoo Removal Machines | SDS Techmed', 'Discover SDS Techmed tattoo removal machines designed for safe, effective results.', 1),
(3, 'Weight Loss Machine', 'weight-loss-machine', 'Body contouring and slimming devices for aesthetic practices.', 3, '2025-12-22 09:05:57', '3.png', 'China Weight loss machine manufacturer', 'Weight Loss Machine | SDS Techmed', 'Browse SDS Techmed weight loss and body contouring machines for professional use.', 1),
(4, 'IPL Hair Removal And Skin Rejuvenation Machine', 'ipl-hair-removal-skin-rejuvenation', 'IPL solutions for hair removal and skin rejuvenation treatments.', 4, '2025-12-22 09:05:57', '4.png', 'China IPL Hair Removal & Skin Rejuvenation Machine manufacturer', 'IPL Hair Removal & Skin Rejuvenation | SDS Techmed', 'Explore IPL machines for hair removal and skin rejuvenation from SDS Techmed.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `whatsapp` varchar(80) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `whatsapp`, `message`, `created_at`) VALUES
(1, 'Rakibul', 'rakibulislamshuvro@gmail.com', '01727688491', 'sdsdsdsdsd', '2025-12-27 08:25:58'),
(2, 'Rakibul', 'rakibulislamshuvro@gmail.com', '01727688491', 'dsdsd', '2025-12-27 08:27:10'),
(3, 'Sharirik', NULL, '01727688491', 'h', '2025-12-27 14:02:47'),
(4, 'Sharirik Protibondhi Surokkha Trust, Maitri Shilpa', NULL, '+880...', 'sdsdsada', '2025-12-27 15:24:28'),
(5, 'Rakibul Islam', 'rakibulislamshuvro@gmail.com', '01727688491', 'dsd', '2025-12-27 15:26:15'),
(6, 'Sharirik Protibondhi Surokkha Trust, Maitri Shilpa', 'rakibulislamshuvro@gmail.com', '01727688491', 'sdsadsad', '2025-12-27 15:35:20'),
(7, 'S Ahmed', 'sdsds@gmail.com', '124206546', 'Hair removal machinge', '2025-12-27 16:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(220) NOT NULL,
  `slug` varchar(240) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `cover_image`, `published_at`, `created_at`) VALUES
(1, 'March beauty good time! Exfu\'s full range of beauty instruments is your choice!', 'march-beauty-good-time-exfu-s-full-range-of-beauty-instruments-is-your-choice', NULL, 'March beauty good time! Exfu\'s full range of beauty instruments is your choice!\r\n\r\n$800 off coupons are being issued!\r\n\r\nWelcome to inquire! 7X24 hours online service!\r\nAnd evreything.', '3de8763fb647a044a4b7540d46094334.webp', '2025-12-27 13:51:00', '2025-12-27 07:52:10');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `slug`, `title`, `content`, `updated_at`) VALUES
(1, 'about', 'About SDS Techmed', '<p>Write your About content here.</p>', '2025-12-22 08:05:36'),
(2, 'faq', 'FAQs', '<p>Add FAQs here.</p>', '2025-12-22 08:05:36'),
(3, 'privacy-policy', 'Privacy Policy', '<p>Add policy here.</p>', '2025-12-22 08:05:36'),
(4, 'terms', 'Terms of Service', '<p>Add terms here.</p>', '2025-12-22 08:05:36'),
(5, 'home', 'Home Sections', '<p>Home content blocks can be stored here.</p>', '2025-12-22 08:05:36'),
(6, 'service-privacy', 'Service & Privacy', '<h3>Pre-Sales Service</h3><p>…</p><h3>After-Sales Service</h3><p>…</p>', '2025-12-22 08:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `specs` longtext DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `main_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `short_description`, `description`, `specs`, `featured`, `main_image`, `status`, `created_at`, `meta_title`, `meta_description`) VALUES
(1, 1, 'Portable Yag Laser / Q Switched Nd Yag Laser / Nd Yag Laser Machine', 'portable-yag-laser-q-switched-nd-yag-laser-nd-yag-laser-machine', NULL, 'APPLICATION：\r\n1)tattoo removal 2)eyebrow removal\r\n3)eyeline removal 4) lipline removal\r\n5)pigment treatment  6)birth mark removal\r\n7)nevus of ota removal\r\n8)skin whitening   9)skin rejuvenation\r\nWavelength：1064&532&1320', 'Frequency：1-10HZ', 1, '135467970713c68aa1b0df526018b389.png', 'active', '2025-12-27 07:45:33', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(200) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `whatsapp` varchar(80) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `hero_title` varchar(255) DEFAULT NULL,
  `hero_subtitle` text DEFAULT NULL,
  `hero_button_text` varchar(80) DEFAULT NULL,
  `hero_button_url` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `topbar_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `header_tagline` varchar(120) DEFAULT NULL,
  `header_cta_text` varchar(60) DEFAULT NULL,
  `header_cta_url` varchar(255) DEFAULT NULL,
  `header_logo` varchar(255) DEFAULT NULL,
  `header_style` varchar(20) NOT NULL DEFAULT 'v1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `email`, `phone`, `whatsapp`, `address`, `footer_text`, `updated_at`, `hero_title`, `hero_subtitle`, `hero_button_text`, `hero_button_url`, `hero_image`, `topbar_enabled`, `header_tagline`, `header_cta_text`, `header_cta_url`, `header_logo`, `header_style`) VALUES
(1, 'SDS Techmed', 'info@sdstechmed.com', '+880...', '+880...', 'Dhaka, Bangladesh', '© SDS Techmed. All rights reserved.', '2025-12-27 14:51:04', 'Medical Aesthetic Devices & Laser Solutions', 'SDS Techmed supplies advanced technology for clinics, salons, and distributors worldwide.', 'Explore Products', '/sdstechmed/public/products', '692090728d69ff77f62f6405185de93f.webp', 1, NULL, NULL, NULL, '3eaa517d30b7220c76032fabaa974f85.jpeg', 'v1');

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `id` int(11) NOT NULL,
  `entity_type` enum('page','category','product','news','settings') NOT NULL,
  `entity_id` int(11) NOT NULL,
  `lang` varchar(10) NOT NULL,
  `field` varchar(50) NOT NULL,
  `value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq` (`entity_type`,`entity_id`,`lang`,`field`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
