-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2026 at 12:55 PM
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
-- Database: `jedisebitool`
--

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT '?',
  `color` varchar(255) NOT NULL DEFAULT '#6366f1',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `color`, `is_active`, `sort_order`, `seo_title`, `seo_description`, `seo_keywords`, `og_image`, `created_at`, `updated_at`) VALUES
(1, 'Basic Everyday Calculators', 'basic-everyday-calculators', 'Everyday math calculators for quick calculations', '🔢', '#6366f1', 1, 0, 'Basic Everyday Calculators - Free Online Tools', 'Everyday math calculators for quick calculations. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(2, 'Scientific & Math Calculators', 'scientific-math-calculators', 'Advanced scientific and mathematical tools', '📐', '#8b5cf6', 1, 1, 'Scientific & Math Calculators - Free Online Tools', 'Advanced scientific and mathematical tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(3, 'Finance Calculators', 'finance-calculators', 'Financial planning and investment calculators', '💰', '#10b981', 1, 2, 'Finance Calculators - Free Online Tools', 'Financial planning and investment calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(4, 'Health & Fitness Calculators', 'health-fitness-calculators', 'Health, fitness, and nutrition calculators', '💪', '#f59e0b', 1, 3, 'Health & Fitness Calculators - Free Online Tools', 'Health, fitness, and nutrition calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(5, 'Education Calculators', 'education-calculators', 'Study and education tools', '📚', '#3b82f6', 1, 4, 'Education Calculators - Free Online Tools', 'Study and education tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(6, 'Date & Time Calculators', 'date-time-calculators', 'Date, time, and calendar tools', '📅', '#ec4899', 1, 5, 'Date & Time Calculators - Free Online Tools', 'Date, time, and calendar tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(7, 'Unit Conversion Calculators', 'unit-conversion-calculators', 'Convert between different units of measurement', '📏', '#14b8a6', 1, 6, 'Unit Conversion Calculators - Free Online Tools', 'Convert between different units of measurement. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(8, 'Construction & Engineering', 'construction-engineering', 'Construction and engineering calculators', '🏗️', '#f97316', 1, 7, 'Construction & Engineering - Free Online Tools', 'Construction and engineering calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(9, 'Business Calculators', 'business-calculators', 'Business metrics and ROI calculators', '📊', '#0ea5e9', 1, 8, 'Business Calculators - Free Online Tools', 'Business metrics and ROI calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(10, 'Internet & Digital Calculators', 'internet-digital-calculators', 'Digital and internet-related calculators', '🌐', '#6366f1', 1, 9, 'Internet & Digital Calculators - Free Online Tools', 'Digital and internet-related calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(11, 'Lifestyle & Misc Calculators', 'lifestyle-misc-calculators', 'Miscellaneous lifestyle calculators', '🎯', '#d946ef', 1, 10, 'Lifestyle & Misc Calculators - Free Online Tools', 'Miscellaneous lifestyle calculators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(12, 'Unit Converters', 'unit-converters', 'Convert between units of length, weight, temperature and more', '🔄', '#22c55e', 1, 11, 'Unit Converters - Free Online Tools', 'Convert between units of length, weight, temperature and more. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(13, 'Expense Trackers', 'expense-trackers', 'Track your expenses and budget', '💳', '#ef4444', 1, 12, 'Expense Trackers - Free Online Tools', 'Track your expenses and budget. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(14, 'To-Do Tools', 'to-do-tools', 'Task management and to-do list tools', '✅', '#10b981', 1, 13, 'To-Do Tools - Free Online Tools', 'Task management and to-do list tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(15, 'Pomodoro & Focus Timers', 'pomodoro-focus-timers', 'Focus and productivity timers', '⏱️', '#f43f5e', 1, 14, 'Pomodoro & Focus Timers - Free Online Tools', 'Focus and productivity timers. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(16, 'QR Code Generators', 'qr-code-generators', 'Generate QR codes for any content', '📱', '#1e293b', 1, 15, 'QR Code Generators - Free Online Tools', 'Generate QR codes for any content. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(17, 'Password Generators', 'password-generators', 'Generate secure passwords', '🔐', '#dc2626', 1, 16, 'Password Generators - Free Online Tools', 'Generate secure passwords. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(18, 'Notes Tools', 'notes-tools', 'Online note-taking tools', '📝', '#fbbf24', 1, 17, 'Notes Tools - Free Online Tools', 'Online note-taking tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(19, 'File Tools', 'file-tools', 'File management and conversion tools', '📁', '#64748b', 1, 18, 'File Tools - Free Online Tools', 'File management and conversion tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(20, 'CSV & Data Tools', 'csv-data-tools', 'CSV, Excel, and data processing tools', '📋', '#0891b2', 1, 19, 'CSV & Data Tools - Free Online Tools', 'CSV, Excel, and data processing tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(21, 'PDF Tools', 'pdf-tools', 'PDF creation and manipulation tools', '📄', '#dc2626', 1, 20, 'PDF Tools - Free Online Tools', 'PDF creation and manipulation tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(22, 'Image Tools', 'image-tools', 'Image resizing, converting, and editing tools', '🖼️', '#7c3aed', 1, 21, 'Image Tools - Free Online Tools', 'Image resizing, converting, and editing tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(23, 'Text Tools', 'text-tools', 'Text processing, analysis, and formatting tools', '✍️', '#059669', 1, 22, 'Text Tools - Free Online Tools', 'Text processing, analysis, and formatting tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(24, 'Color Tools', 'color-tools', 'Color palette generators and color tools', '🎨', '#f43f5e', 1, 23, 'Color Tools - Free Online Tools', 'Color palette generators and color tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(25, 'Education & Learning', 'education-learning', 'Flashcards, quizzes, and study tools', '🎓', '#2563eb', 1, 24, 'Education & Learning - Free Online Tools', 'Flashcards, quizzes, and study tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(26, 'Developer Tools', 'developer-tools', 'Tools for developers and coders', '💻', '#1e293b', 1, 25, 'Developer Tools - Free Online Tools', 'Tools for developers and coders. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(27, 'Security Tools', 'security-tools', 'Hash generators, encryption and security tools', '🔒', '#dc2626', 1, 26, 'Security Tools - Free Online Tools', 'Hash generators, encryption and security tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(28, 'AI & Automation Tools', 'ai-automation-tools', 'AI-powered and automation tools', '🤖', '#6366f1', 1, 27, 'AI & Automation Tools - Free Online Tools', 'AI-powered and automation tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(29, 'Statistics Calculators', 'statistics-calculators', 'Statistical analysis and data visualization', '📈', '#0284c7', 1, 28, 'Statistics Calculators - Free Online Tools', 'Statistical analysis and data visualization. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(30, 'Random Generators', 'random-generators', 'Random number, name, and content generators', '🎲', '#7c3aed', 1, 29, 'Random Generators - Free Online Tools', 'Random number, name, and content generators. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(31, 'Mini Games', 'mini-games', 'Fun browser-based mini games', '🎮', '#ec4899', 1, 30, 'Mini Games - Free Online Tools', 'Fun browser-based mini games. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(32, 'System & Monitor Tools', 'system-monitor-tools', 'System monitoring and utility tools', '🖥️', '#475569', 1, 31, 'System & Monitor Tools - Free Online Tools', 'System monitoring and utility tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(33, 'Typography Tools', 'typography-tools', 'Font, typography, and ASCII tools', '🔤', '#0f172a', 1, 32, 'Typography Tools - Free Online Tools', 'Font, typography, and ASCII tools. Free, fast, and easy to use.', NULL, NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(34, 'media file converter', 'media-file-converter', NULL, '🔧', '#6366f1', 1, 0, NULL, NULL, NULL, NULL, '2026-06-09 16:34:51', '2026-06-09 16:34:51'),
(35, 'Youtube video downloader', 'youtube-video-downloader', NULL, '🔧', '#6366f1', 1, 0, NULL, NULL, NULL, NULL, '2026-06-09 22:14:07', '2026-06-09 22:14:07'),
(36, 'Instagram reels and stories downloader', 'instagram-reels-and-stories-downloader', NULL, '🔧', '#6366f1', 1, 0, NULL, NULL, NULL, NULL, '2026-06-09 22:15:00', '2026-06-09 22:15:00'),
(37, 'Facebook videos and stories downloader', 'facebook-videos-and-stories-downloader', NULL, '🔧', '#6366f1', 1, 0, NULL, NULL, NULL, NULL, '2026-06-09 22:15:37', '2026-06-09 22:15:37'),
(38, 'Movies downloader', 'movies-downloader', NULL, '🔧', '#6366f1', 1, 0, NULL, NULL, NULL, NULL, '2026-06-09 22:16:04', '2026-06-09 22:16:04');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_replied` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2024_01_01_000001_create_users_table', 1),
(2, '2024_01_01_000002_create_settings_table', 1),
(3, '2024_01_01_000003_create_categories_table', 1),
(4, '2024_01_01_000004_create_tools_table', 1),
(5, '2024_01_01_000005_create_tool_related_tables', 1),
(6, '2024_01_01_000006_create_pages_and_contacts', 1),
(7, '2026_07_12_005651_add_extended_seo_fields_to_tools_table', 2),
(8, '2026_07_12_042546_expand_seo_string_columns_on_tools_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `template` varchar(255) NOT NULL DEFAULT 'default',
  `status` enum('published','draft') NOT NULL DEFAULT 'published',
  `show_in_nav` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_footer` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('COfDA3daQXRDgJqeqAwkvZSETCMUn3kbb7TEaa6Z', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicEo1d1VtWFU5OE13RTdnakdQeUdHR0J1eENEZFo4bHg2UHZ1d3ExMyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Rvb2xzP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1784163577),
('MUuYTDurtokumUzl3J7GqLNdLgdBtLgKz69wspuc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR1Jsd1JHejhCSE9HaFdqbWc1ZVZtQ0JKUUQ1aDZ4NXR3ZklCZzJjNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Rvb2xzP3BhZ2U9NiI7fX0=', 1783837726),
('tOxyRFoSprcaeR4ubYNWswix7DylrTN0sgzAHxoh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSEdJM0o5ZEFUaWVpcHFHV09LSVZja2dMWTRhT1R2NUtzWDFDNVJ5RyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0MDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3Rvb2xzL2NyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785455340);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'JEDISEBITOOL', 'string', 'general', 'Site Name', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(2, 'site_tagline', 'Free Online Tools for Everyone', 'string', 'general', 'Tagline', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(3, 'site_description', 'JEDISEBITOOL is your all-in-one platform for free online calculators, converters, generators, and productivity tools.', 'string', 'general', 'Description', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(4, 'contact_email', 'hello@jedisebitool.com', 'string', 'general', 'Contact Email', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(5, 'footer_text', '© 2026 JEDISEBITOOL. All rights reserved. Free tools for everyone.', 'string', 'general', 'Footer Text', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(6, 'home_hero_title', 'Your All-in-One', 'string', 'general', 'Hero Title', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(7, 'home_hero_subtitle', 'Calculators, converters, generators, and productivity tools — all free, no signup required.', 'string', 'general', 'Hero Subtitle', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(8, 'enable_search', '1', 'boolean', 'general', 'Enable Search', NULL, '2026-06-06 19:07:31', '2026-07-11 03:19:37'),
(9, 'maintenance_mode', '0', 'boolean', 'general', 'Maintenance Mode', NULL, '2026-06-06 19:07:31', '2026-07-11 03:19:37'),
(10, 'tools_per_page', '24', 'integer', 'general', 'Tools Per Page', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(11, 'google_analytics', '', 'string', 'seo', 'Google Analytics ID', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(12, 'seo_title_suffix', '- JEDISEBITOOL', 'string', 'seo', 'SEO Title Suffix', NULL, '2026-06-06 19:07:31', '2026-07-11 03:19:37'),
(13, 'seo_default_description', 'Free online tools for calculations, conversions, text processing, and more. No signup required.', 'string', 'seo', 'Default Meta Description', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(14, 'currency_rates', '{\"USD\":1,\"EUR\":0.92,\"GBP\":0.79,\"JPY\":148.5,\"AUD\":1.53,\"CAD\":1.36,\"CHF\":0.88,\"CNY\":7.24,\"INR\":83.1,\"MXN\":17.05,\"BRL\":4.97,\"KRW\":1325,\"SGD\":1.34,\"HKD\":7.82,\"NOK\":10.56,\"SEK\":10.38,\"DKK\":6.88,\"NZD\":1.63,\"ZAR\":18.62,\"TRY\":30.85,\"AED\":3.67,\"SAR\":3.75,\"THB\":35.02,\"IDR\":15630,\"MYR\":4.72,\"PHP\":56.7,\"NGN\":795,\"EGP\":30.9}', 'json', 'tools', 'Currency Exchange Rates', NULL, '2026-06-06 19:07:31', '2026-06-06 19:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT '?',
  `color` varchar(255) NOT NULL DEFAULT '#6366f1',
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `tool_type` varchar(255) NOT NULL DEFAULT 'generic',
  `blade_path` varchar(255) DEFAULT NULL,
  `input_schema` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`input_schema`)),
  `output_schema` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`output_schema`)),
  `engine_class` varchar(255) DEFAULT NULL,
  `engine_method` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` text DEFAULT NULL,
  `seo_keywords` text DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `og_title` varchar(200) DEFAULT NULL,
  `og_description` text DEFAULT NULL,
  `twitter_title` varchar(200) DEFAULT NULL,
  `twitter_description` text DEFAULT NULL,
  `robots` varchar(100) DEFAULT NULL,
  `schema_markup` text DEFAULT NULL,
  `canonical_url` varchar(500) DEFAULT NULL,
  `has_custom_blade` tinyint(1) NOT NULL DEFAULT 0,
  `view_count` int(11) NOT NULL DEFAULT 0,
  `use_count` int(11) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `category_id`, `name`, `slug`, `short_description`, `long_description`, `icon`, `color`, `status`, `is_featured`, `tool_type`, `blade_path`, `input_schema`, `output_schema`, `engine_class`, `engine_method`, `seo_title`, `seo_description`, `seo_keywords`, `og_image`, `og_title`, `og_description`, `twitter_title`, `twitter_description`, `robots`, `schema_markup`, `canonical_url`, `has_custom_blade`, `view_count`, `use_count`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Percentage Calculator', 'percentage-calculator', 'Calculate percentages, percentage changes, and percentage of numbers instantly.', 'Our Percentage Calculator makes it easy to calculate any percentage quickly. Whether you need to find what percentage one number is of another, calculate a discount, or find percentage change, this tool handles it all.\n\nSimply enter a value and a percentage to get instant results including the percentage amount, the value increased by that percentage, and the value decreased by that percentage.', '📊', '#6366f1', 'active', 1, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Percentage Calculator - Calculate Percentages Online Free', 'Free online percentage calculator. Calculate percentages, percentage of a number, percentage change, and more instantly.', 'percentage calculator, calculate percentage, percent calculator, percentage of number', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3631, 620, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:00', NULL),
(2, 4, 'BMI Calculator', 'bmi-calculator', 'Calculate your Body Mass Index (BMI) with metric or imperial units.', 'BMI (Body Mass Index) is a value calculated from a person\'s weight and height. It\'s used as a screening tool to identify whether a person is underweight, normal weight, overweight, or obese.\n\nNote: BMI is a simple index and doesn\'t account for muscle mass, bone density, or fat distribution. Consult a healthcare professional for medical advice.', '⚖️', '#f59e0b', 'active', 1, 'calculator', NULL, NULL, NULL, NULL, NULL, 'BMI Calculator - Body Mass Index Calculator', 'Calculate your BMI (Body Mass Index) instantly. Enter your height and weight in metric or imperial units to find your BMI category.', 'BMI calculator, body mass index, bmi calculator metric, healthy weight calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1473, 695, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:54', NULL),
(3, 3, 'Loan Calculator', 'loan-calculator', 'Calculate monthly loan payments, total interest, and view the full amortization schedule.', 'Our Loan Calculator helps you understand the true cost of borrowing. Enter your loan amount, interest rate, and term to instantly see your monthly payment, total amount paid, and total interest cost.\n\nAn amortization schedule showing the first 12 months of payments is also provided, breaking down each payment into principal and interest components.', '🏦', '#10b981', 'active', 1, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Loan Calculator - Monthly Payment & Interest Calculator', 'Free loan calculator. Calculate monthly payments, total interest, and amortization schedule for any loan amount, rate, and term.', 'loan calculator, monthly payment calculator, mortgage calculator, amortization calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 806, 1552, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:59', NULL),
(4, 1, 'Tip Calculator', 'tip-calculator', 'Calculate how much to tip at a restaurant. Split the bill between friends.', NULL, '🍽️', '#f97316', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Tip Calculator', 'Calculate tips instantly with a free online Tip Calculator. Split bills, estimate gratuity, and determine the total amount quickly for restaurants, cafes, and services.', 'tip calculator, online tip calculator, gratuity calculator, restaurant tip calculator, bill splitter, split bill calculator, tip percentage calculator, dining tip calculator, service tip calculator, free tip calculator, total bill calculator, restaurant bill calculator, tipping calculator, meal tip calculator, bill and tip calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3736, 759, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:04', NULL),
(5, 6, 'Date Difference Calculator', 'date-difference-calculator', 'Find the number of days, weeks, months, and years between two dates.', NULL, '📅', '#ec4899', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Date Difference Calculator', 'Calculate the exact difference between two dates instantly. Find the number of days, weeks, months, and years with a fast, free, and accurate Date Difference Calculator.', 'date difference calculator, date calculator, days between dates, calculate date difference, online date calculator, date duration calculator, days calculator, months between dates, years between dates, time difference calculator, date interval calculator, date gap calculator, calendar calculator, free date calculator, date count tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1419, 329, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:57', NULL),
(6, 30, 'Random Number Generator', 'random-number-generator', 'Generate truly random numbers between any range. Generate multiple numbers at once.', NULL, '🎲', '#7c3aed', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Random Number Generator', 'Generate random numbers instantly with a free online Random Number Generator. Create single or multiple random numbers within a custom range for games, lotteries, and more.', 'random number generator, random number picker, online random number generator, random number tool, number generator, random integer generator, random picker, lottery number generator, randomizer, rng generator, free random number generator, custom random numbers, random selection tool, number randomizer, online rng', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2647, 262, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:02', NULL),
(7, 12, 'Unit Converter', 'unit-converter', 'Convert between length, weight, temperature, area, volume, speed, and data units.', NULL, '🔄', '#22c55e', 'active', 1, 'converter', NULL, NULL, NULL, NULL, NULL, 'Unit Converter - Length, Weight, Temperature & More', 'Free unit converter for length, weight, temperature, area, volume, speed, and data. Instant conversion between all measurement units.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 485, 68, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:05', NULL),
(8, 3, 'Currency Converter', 'currency-converter', 'Convert between world currencies using manually managed exchange rates.', NULL, '💱', '#10b981', 'active', 1, 'converter', NULL, NULL, NULL, NULL, NULL, 'Currency Converter - Convert Between World Currencies', 'Free currency converter supporting 28+ world currencies. Convert USD, EUR, GBP, JPY, and more instantly.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2840, 1746, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:56', NULL),
(9, 17, 'Password Generator', 'password-generator', 'Generate strong, secure passwords with customizable length and character types.', NULL, '🔐', '#dc2626', 'active', 1, 'generator', NULL, NULL, NULL, NULL, NULL, 'Password Generator - Generate Secure Passwords Online', 'Free online password generator. Create strong, secure, random passwords with letters, numbers, and symbols.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1172, 401, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:59', NULL),
(10, 16, 'QR Code Generator', 'qr-code-generator', 'Generate QR codes for URLs, text, emails, and more. Download as PNG.', NULL, '📱', '#1e293b', 'active', 1, 'generator', NULL, NULL, NULL, NULL, NULL, 'QR Code Generator - Free QR Code Creator Online', 'Create custom QR codes for URLs, text, wifi, and more. Download your QR code as PNG for free.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3817, 1874, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:01', NULL),
(11, 24, 'Color Palette Generator', 'color-palette-generator', 'Generate beautiful color palettes from a base color. Multiple harmony modes.', NULL, '🎨', '#f43f5e', 'active', 1, 'generator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2988, 1597, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:55', NULL),
(12, 26, 'JSON Formatter', 'json-formatter', 'Format, validate, and minify JSON. Instant JSON beautifier and validator.', NULL, '📋', '#0ea5e9', 'active', 1, 'text', NULL, NULL, NULL, NULL, NULL, 'JSON Formatter & Validator - Format JSON Online', 'Free online JSON formatter, validator, and minifier. Instantly format and beautify JSON code.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 438, 958, 0, '2026-06-06 19:07:31', '2026-09-01 07:37:58', NULL),
(13, 23, 'Text Summarizer', 'text-summarizer', 'Summarize long text using extractive summarization. Get key sentences automatically.', NULL, '✍️', '#059669', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Text Summarizer', 'Summarize long text instantly with a free AI-powered Text Summarizer. Extract key points from articles, essays, reports, and documents quickly and accurately.', 'text summarizer, AI text summarizer, online summarizer, summarize text, article summarizer, paragraph summarizer, document summarizer, essay summarizer, summary generator, content summarizer, automatic summarization tool, key points extractor, free text summarizer, AI summary tool, shorten text online', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 3016, 1117, 0, '2026-06-06 19:07:31', '2026-09-01 07:38:03', NULL),
(14, 14, 'To-Do List', 'todo-list', 'A clean, fast to-do list that saves locally in your browser. No account needed.', NULL, '✅', '#10b981', 'active', 1, 'productivity', 'tools.generated.todo-list', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 255, 1074, 0, '2026-06-06 19:07:31', '2026-09-01 07:34:44', NULL),
(15, 18, 'Notes App', 'notes-app', 'A minimal notes app with local storage. Create, edit, and search notes.', NULL, '📝', '#fbbf24', 'active', 0, 'productivity', 'tools.generated.notes-app', NULL, NULL, NULL, NULL, 'Notes App', 'Create, edit, and organize notes online with a free Notes App. Capture ideas, manage tasks, and keep important information accessible anytime, anywhere.', 'notes app, online notes app, note taking app, digital notebook, online notepad, free notes app, note organizer, personal notes, quick notes, sticky notes app, note manager, text notes, productivity app, online memo app, note taking tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 255, 1568, 0, '2026-06-06 19:07:31', '2026-09-01 07:34:12', NULL),
(16, 1, 'Average Calculator', 'average-calculator', 'Free online average calculator. Fast, easy, and no registration required.', NULL, '📊', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Average Calculator', 'Calculate the average of numbers instantly with a free online Average Calculator. Find the arithmetic mean quickly and accurately for math, statistics, finance, and education.', 'average calculator, mean calculator, arithmetic mean calculator, online average calculator, calculate average, number average calculator, math calculator, statistics calculator, average finder, data average calculator, score average calculator, grade average calculator, free average calculator, mean value calculator, online math tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 453, 97, 100, '2026-06-06 19:07:31', '2026-09-01 07:33:03', NULL),
(17, 1, 'Fraction Calculator', 'fraction-calculator', 'Free online fraction calculator. Fast, easy, and no registration required.', NULL, '½', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Fraction Calculator', 'Solve fraction calculations instantly with a free online Fraction Calculator. Add, subtract, multiply, divide, simplify, and convert fractions accurately in seconds.', 'fraction calculator, online fraction calculator, simplify fractions, add fractions, subtract fractions, multiply fractions, divide fractions, mixed number calculator, fraction solver, fraction simplifier, math fraction calculator, improper fraction calculator, fraction to decimal, free fraction calculator, fraction math tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 491, 13, 101, '2026-06-06 19:07:31', '2026-09-01 07:33:42', NULL),
(18, 1, 'Discount Calculator', 'discount-calculator', 'Free online discount calculator. Fast, easy, and no registration required.', NULL, '🏷️', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Discount Calculator', 'Calculate discounts instantly with a free online Discount Calculator. Find sale prices, savings, percentage discounts, and final costs quickly and accurately.', 'discount calculator, online discount calculator, sale price calculator, percentage discount calculator, discount percentage calculator, price discount calculator, savings calculator, markdown calculator, final price calculator, shopping calculator, retail discount calculator, discount finder, free discount calculator, deal calculator, price reduction calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 205, 132, 102, '2026-06-06 19:07:31', '2026-09-01 07:33:35', NULL),
(19, 2, 'Scientific Calculator', 'scientific-calculator', 'Free online scientific calculator. Fast, easy, and no registration required.', NULL, '🔬', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Scientific Calculator', 'Perform advanced mathematical calculations with a free online Scientific Calculator. Solve trigonometry, logarithms, exponents, fractions, and complex equations instantly.', 'scientific calculator, online scientific calculator, advanced calculator, math calculator, engineering calculator, trigonometry calculator, logarithm calculator, exponent calculator, free scientific calculator, calculator online, scientific math tool, algebra calculator, graphing calculator alternative, complex calculator, physics calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 200, 98, 103, '2026-06-06 19:07:31', '2026-09-01 07:34:32', NULL),
(20, 2, 'Statistics Calculator', 'statistics-calculator', 'Free online statistics calculator. Fast, easy, and no registration required.', NULL, '📈', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Statistics Calculator', 'Calculate statistical values instantly with a free online Statistics Calculator. Find mean, median, mode, variance, standard deviation, and more with accurate results.', 'statistics calculator, online statistics calculator, statistical calculator, mean calculator, median calculator, mode calculator, standard deviation calculator, variance calculator, probability calculator, data analysis tool, descriptive statistics calculator, sample statistics calculator, math statistics tool, free statistics calculator, statistical analysis tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 482, 165, 104, '2026-06-06 19:07:31', '2026-09-01 07:34:36', NULL),
(21, 2, 'Triangle Calculator', 'triangle-calculator', 'Free online triangle calculator. Fast, easy, and no registration required.', NULL, '📐', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Triangle Calculator', 'Solve triangle measurements instantly with a free online Triangle Calculator. Calculate sides, angles, area, perimeter, and other properties accurately for any triangle.', 'triangle calculator, online triangle calculator, triangle solver, right triangle calculator, triangle area calculator, triangle side calculator, triangle angle calculator, triangle perimeter calculator, geometry calculator, pythagorean calculator, law of sines calculator, law of cosines calculator, triangle measurement tool, free triangle calculator, math geometry tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 339, 16, 105, '2026-06-06 19:07:31', '2026-09-01 07:34:45', NULL),
(22, 3, 'Compound Interest Calculator', 'compound-interest-calculator', 'Free online compound interest calculator. Fast, easy, and no registration required.', NULL, '📈', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Compound Interest Calculator', 'Calculate compound interest instantly with a free online Compound Interest Calculator. Estimate investment growth, future value, returns, and accumulated savings accurately.', 'compound interest calculator, online compound interest calculator, investment calculator, savings calculator, future value calculator, interest calculator, compound growth calculator, investment return calculator, monthly compound interest, annual compound interest, financial calculator, savings growth calculator, interest rate calculator, free compound interest calculator, wealth growth calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 481, 12, 106, '2026-06-06 19:07:31', '2026-09-01 07:33:22', NULL),
(23, 3, 'Savings Calculator', 'savings-calculator', 'Free online savings calculator. Fast, easy, and no registration required.', NULL, '🏦', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Savings Calculator', 'Plan your savings with a free online Savings Calculator. Estimate future savings, interest earnings, monthly contributions, and financial growth with accurate projections.', 'savings calculator, online savings calculator, savings planner, future savings calculator, interest calculator, investment savings calculator, monthly savings calculator, financial planning tool, savings growth calculator, savings goal calculator, personal finance calculator, money saving calculator, compound savings calculator, free savings calculator, wealth planning tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 384, 119, 107, '2026-06-06 19:07:31', '2026-09-01 07:36:38', NULL),
(24, 3, 'Mortgage Calculator', 'mortgage-calculator', 'Free online mortgage calculator. Fast, easy, and no registration required.', NULL, '🏠', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Mortgage Calculator', 'Calculate mortgage payments instantly with a free online Mortgage Calculator. Estimate monthly payments, interest, loan costs, and repayment schedules accurately.', 'mortgage calculator, home loan calculator, mortgage payment calculator, online mortgage calculator, monthly mortgage calculator, loan calculator, mortgage interest calculator, house payment calculator, home financing calculator, amortization calculator, mortgage affordability calculator, property loan calculator, free mortgage calculator, mortgage repayment calculator, real estate calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 142, 44, 108, '2026-06-06 19:07:31', '2026-09-01 07:34:07', NULL),
(25, 3, 'ROI Calculator', 'roi-calculator', 'Free online roi calculator. Fast, easy, and no registration required.', NULL, '💹', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'ROI Calculator', 'Calculate your Return on Investment (ROI) instantly with a free online ROI Calculator. Measure investment performance, profitability, gains, and losses accurately.', 'roi calculator, return on investment calculator, investment calculator, roi percentage calculator, profitability calculator, investment return calculator, business roi calculator, marketing roi calculator, financial roi calculator, roi formula calculator, online roi calculator, return calculator, investment profit calculator, free roi calculator, finance calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 207, 159, 109, '2026-06-06 19:07:31', '2026-09-01 07:34:25', NULL),
(26, 3, 'Tax Calculator', 'tax-calculator', 'Free online tax calculator. Fast, easy, and no registration required.', NULL, '💸', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Tax Calculator', 'Calculate taxes instantly with a free online Tax Calculator. Estimate income tax, sales tax, VAT, and other tax amounts accurately for better financial planning.', 'tax calculator, online tax calculator, income tax calculator, sales tax calculator, VAT calculator, tax estimator, tax planning calculator, payroll tax calculator, business tax calculator, tax percentage calculator, financial calculator, tax return calculator, free tax calculator, tax computation tool, tax calculation online', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 166, 120, 110, '2026-06-06 19:07:31', '2026-09-01 07:34:38', NULL),
(27, 4, 'Calorie Calculator', 'calorie-calculator', 'Free online calorie calculator. Fast, easy, and no registration required.', NULL, '🥗', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Calorie Calculator', 'Calculate your daily calorie needs with a free online Calorie Calculator. Estimate calorie intake for weight loss, maintenance, or muscle gain based on your goals.', 'calorie calculator, daily calorie calculator, calorie intake calculator, calorie needs calculator, weight loss calculator, maintenance calories calculator, fitness calculator, nutrition calculator, BMR calculator, TDEE calculator, calorie tracker, healthy diet calculator, free calorie calculator, meal planning calculator, weight management calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 29, 68, 111, '2026-06-06 19:07:31', '2026-09-01 07:33:18', NULL),
(28, 4, 'Ideal Weight Calculator', 'ideal-weight-calculator', 'Free online ideal weight calculator. Fast, easy, and no registration required.', NULL, '⚖️', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Ideal Weight Calculator', 'Calculate your ideal body weight with a free online Ideal Weight Calculator. Estimate a healthy weight range based on height, gender, and standard health formulas.', 'ideal weight calculator, healthy weight calculator, ideal body weight calculator, body weight calculator, target weight calculator, healthy weight range, IBW calculator, weight goal calculator, ideal weight estimator, BMI and ideal weight, fitness calculator, health calculator, weight management tool, free ideal weight calculator, body health calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 396, 18, 112, '2026-06-06 19:07:31', '2026-09-01 07:33:50', NULL),
(29, 4, 'Body Fat Calculator', 'body-fat-calculator', 'Free online body fat calculator. Fast, easy, and no registration required.', NULL, '💪', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Body Fat Calculator', 'Estimate your body fat percentage with a free online Body Fat Calculator. Track fitness progress, assess body composition, and achieve your health and weight goals.', 'body fat calculator, body fat percentage calculator, body composition calculator, body fat estimator, fitness calculator, health calculator, body fat measurement, body fat analysis, lean body mass calculator, body fat tracker, fitness progress calculator, body metrics calculator, free body fat calculator, weight loss calculator, health assessment tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 86, 112, 113, '2026-06-06 19:07:31', '2026-09-01 07:33:16', NULL),
(30, 4, 'Water Intake Calculator', 'water-intake-calculator', 'Free online water intake calculator. Fast, easy, and no registration required.', NULL, '💧', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Water Intake Calculator', 'Calculate your daily water intake with a free online Water Intake Calculator. Estimate hydration needs based on weight, activity level, and lifestyle for better health.', 'water intake calculator, daily water intake calculator, hydration calculator, water consumption calculator, drink water calculator, health calculator, hydration tracker, daily hydration needs, water requirement calculator, fitness hydration calculator, water goal calculator, healthy lifestyle calculator, free water intake calculator, hydration planning tool, wellness calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 254, 63, 114, '2026-06-06 19:07:31', '2026-09-01 07:38:06', NULL),
(31, 6, 'Age Calculator', 'age-calculator', 'Free online age calculator. Fast, easy, and no registration required.', NULL, '🎂', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Age Calculator', 'Calculate your exact age instantly with a free online Age Calculator. Find your age in years, months, days, hours, and minutes from your date of birth.', 'age calculator, online age calculator, calculate age, date of birth calculator, exact age calculator, birthday calculator, age finder, years months days calculator, birth date calculator, age difference calculator, DOB calculator, free age calculator, age counter, chronological age calculator, date calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 463, 22, 115, '2026-06-06 19:07:31', '2026-09-01 07:33:00', NULL),
(32, 6, 'Time Zone Converter', 'time-zone-converter', 'Free online time zone converter. Fast, easy, and no registration required.', NULL, '🌍', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Time Zone Converter', 'Convert time between different time zones instantly with a free online Time Zone Converter. Compare global times accurately for meetings, travel, business, and remote work.', 'time zone converter, online time zone converter, world clock converter, time converter, global time converter, timezone calculator, convert time zones, international time converter, GMT converter, UTC converter, meeting time converter, world time calculator, business time converter, free time zone converter, time difference calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 154, 34, 116, '2026-06-06 19:07:31', '2026-09-01 07:34:43', NULL),
(33, 6, 'Countdown Timer', 'countdown-timer', 'Free online countdown timer. Fast, easy, and no registration required.', NULL, '⏰', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Countdown Timer', 'Set a free online Countdown Timer for tasks, events, workouts, study sessions, and reminders. Track remaining time accurately with a simple, fast, and easy-to-use timer.', 'countdown timer, online countdown timer, free countdown timer, digital countdown timer, event countdown, task timer, workout timer, study timer, reminder timer, online timer, countdown clock, time tracker, custom countdown timer, browser timer, productivity timer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 70, 119, 117, '2026-06-06 19:07:31', '2026-09-01 07:33:23', NULL),
(34, 8, 'Concrete Calculator', 'concrete-calculator', 'Free online concrete calculator. Fast, easy, and no registration required.', NULL, '🏗️', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Concrete Calculator', 'Estimate concrete volume and material requirements with a free online Concrete Calculator. Calculate concrete needed for slabs, footings, columns, driveways, and construction projects accurately.', 'concrete calculator, online concrete calculator, concrete volume calculator, cement calculator, construction calculator, slab concrete calculator, footing calculator, driveway concrete calculator, concrete estimator, building material calculator, concrete mix calculator, cubic yard calculator, cubic meter calculator, free concrete calculator, construction planning tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 88, 81, 118, '2026-06-06 19:07:31', '2026-09-01 07:33:23', NULL),
(35, 8, 'Area Calculator', 'area-calculator', 'Free online area calculator. Fast, easy, and no registration required.', NULL, '📐', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Area Calculator', 'Calculate the area of various shapes with a free online Area Calculator. Find accurate measurements for squares, rectangles, circles, triangles, polygons, and more instantly.', 'area calculator, online area calculator, geometry calculator, shape area calculator, rectangle area calculator, square area calculator, circle area calculator, triangle area calculator, polygon area calculator, land area calculator, surface area calculator, measurement calculator, area measurement tool, free area calculator, math geometry calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 448, 68, 119, '2026-06-06 19:07:31', '2026-09-01 07:33:01', NULL),
(36, 9, 'Profit Margin Calculator', 'profit-margin-calculator', 'Free online profit margin calculator. Fast, easy, and no registration required.', NULL, '💼', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Profit Margin Calculator', 'Calculate profit margin, markup, revenue, and profit instantly with a free online Profit Margin Calculator. Perfect for businesses, retailers, freelancers, and entrepreneurs.', 'profit margin calculator, margin calculator, profit calculator, markup calculator, gross profit calculator, net profit calculator, pricing calculator, business profit calculator, sales margin calculator, revenue calculator, profit percentage calculator, online margin calculator, free profit calculator, ecommerce profit calculator, business finance calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 484, 113, 120, '2026-06-06 19:07:31', '2026-09-01 07:34:20', NULL),
(37, 9, 'Break Even Calculator', 'break-even-calculator', 'Free online break even calculator. Fast, easy, and no registration required.', NULL, '📊', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Break Even Calculator', 'Calculate your break-even point instantly with a free online Break Even Calculator. Estimate sales, revenue, fixed costs, variable costs, and profitability for smarter business decisions.', 'break even calculator, break-even calculator, break even point calculator, business calculator, profit calculator, cost calculator, sales calculator, fixed cost calculator, variable cost calculator, revenue calculator, pricing calculator, profitability calculator, business finance calculator, free break even calculator, startup financial calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 481, 40, 121, '2026-06-06 19:07:31', '2026-09-01 07:33:17', NULL),
(38, 10, 'Bandwidth Calculator', 'bandwidth-calculator', 'Free online bandwidth calculator. Fast, easy, and no registration required.', NULL, '📡', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Bandwidth Calculator', 'Calculate bandwidth requirements instantly with a free online Bandwidth Calculator. Estimate data transfer, network speed, download time, and internet usage accurately.', 'bandwidth calculator, online bandwidth calculator, network bandwidth calculator, internet speed calculator, data transfer calculator, download time calculator, upload speed calculator, bandwidth estimator, network calculator, internet bandwidth tool, file transfer calculator, data usage calculator, bandwidth usage calculator, free bandwidth calculator, network planning tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 51, 61, 122, '2026-06-06 19:07:31', '2026-09-01 07:33:04', NULL),
(39, 10, 'Download Time Calculator', 'download-time-calculator', 'Free online download time calculator. Fast, easy, and no registration required.', NULL, '⬇️', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'Download Time Calculator', 'Estimate file download times instantly with a free online Download Time Calculator. Calculate download duration based on file size and internet speed accurately.', 'download time calculator, file download calculator, download speed calculator, internet speed calculator, file transfer calculator, download estimator, bandwidth calculator, network speed calculator, data transfer calculator, download duration calculator, internet bandwidth tool, online download calculator, free download time calculator, file size calculator, network planning tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 185, 176, 123, '2026-06-06 19:07:31', '2026-09-01 07:33:36', NULL),
(40, 12, 'Temperature Converter', 'temperature-converter', 'Free online temperature converter. Fast, easy, and no registration required.', NULL, '🌡️', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Temperature Converter', 'Convert temperatures instantly with a free online Temperature Converter. Easily convert between Celsius, Fahrenheit, Kelvin, Rankine, and other temperature units with accurate results.', 'temperature converter, online temperature converter, celsius to fahrenheit, fahrenheit to celsius, kelvin converter, temperature calculator, temperature conversion tool, fahrenheit converter, celsius converter, kelvin to celsius, rankine converter, unit converter, weather temperature converter, free temperature converter, temperature conversion calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 369, 148, 124, '2026-06-06 19:07:31', '2026-09-01 07:34:39', NULL),
(41, 12, 'Length Converter', 'length-converter', 'Free online length converter. Fast, easy, and no registration required.', NULL, '📏', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Length Converter', 'Convert length units instantly with a free online Length Converter. Easily convert meters, centimeters, millimeters, kilometers, inches, feet, yards, and miles with accurate results.', 'length converter, online length converter, distance converter, meter to feet converter, feet to meter converter, inch to cm converter, cm to inch converter, kilometer converter, mile converter, unit converter, measurement converter, length conversion calculator, metric converter, imperial converter, free length converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 297, 12, 125, '2026-06-06 19:07:31', '2026-09-01 07:34:01', NULL),
(42, 12, 'Weight Converter', 'weight-converter', 'Free online weight converter. Fast, easy, and no registration required.', NULL, '⚖️', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Weight Converter', 'Convert weight units instantly with a free online Weight Converter. Easily convert kilograms, grams, pounds, ounces, stones, tons, and more with accurate results.', 'weight converter, online weight converter, mass converter, kilograms to pounds, pounds to kilograms, grams to ounces, ounces to grams, stone to kg converter, ton converter, weight conversion calculator, metric weight converter, imperial weight converter, measurement converter, free weight converter, unit conversion tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 202, 17, 126, '2026-06-06 19:07:31', '2026-09-01 07:34:51', NULL),
(43, 12, 'Volume Converter', 'volume-converter', 'Free online volume converter. Fast, easy, and no registration required.', NULL, '🧪', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Volume Converter', 'Convert volume units instantly with a free online Volume Converter. Easily convert liters, milliliters, gallons, cups, pints, quarts, cubic meters, and more with accurate results.', 'volume converter, online volume converter, liters to gallons, gallons to liters, ml to liters, cubic meter converter, cubic feet converter, cups to ml, pints to liters, quarts converter, liquid volume converter, volume conversion calculator, measurement converter, free volume converter, unit conversion tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 299, 197, 127, '2026-06-06 19:07:31', '2026-09-01 07:34:49', NULL),
(44, 12, 'Speed Converter', 'speed-converter', 'Free online speed converter. Fast, easy, and no registration required.', NULL, '💨', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Speed Converter', 'Convert speed units instantly with a free online Speed Converter. Easily convert kilometers per hour, miles per hour, meters per second, knots, and more with accurate results.', 'speed converter, online speed converter, km/h to mph, mph to km/h, meters per second converter, knots converter, velocity converter, speed conversion calculator, unit converter, transportation speed converter, metric speed converter, imperial speed converter, free speed converter, speed measurement tool, speed calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 307, 161, 128, '2026-06-06 19:07:31', '2026-09-01 07:34:34', NULL),
(45, 12, 'Data Storage Converter', 'data-storage-converter', 'Free online data storage converter. Fast, easy, and no registration required.', NULL, '💾', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Data Storage Converter', 'Convert data storage units instantly with a free online Data Storage Converter. Easily convert bytes, kilobytes, megabytes, gigabytes, terabytes, petabytes, and more with accurate results.', 'data storage converter, online data storage converter, byte converter, kilobyte converter, megabyte converter, gigabyte converter, terabyte converter, petabyte converter, file size converter, storage unit converter, digital storage converter, data conversion calculator, memory size converter, free data storage converter, bytes to MB converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 431, 40, 129, '2026-06-06 19:07:31', '2026-09-01 07:33:30', NULL),
(46, 23, 'Word Counter', 'word-counter', 'Free online word counter. Fast, easy, and no registration required.', NULL, '🔢', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Word Counter', 'Count words, characters, sentences, and paragraphs instantly with a free online Word Counter. Analyze text length, reading time, and writing statistics quickly and accurately.', 'word counter, online word counter, character counter, text counter, word count tool, character count tool, sentence counter, paragraph counter, text analyzer, reading time calculator, writing statistics tool, word frequency counter, free word counter, online text analysis, word count calculator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 406, 74, 130, '2026-06-06 19:07:31', '2026-09-01 07:34:53', NULL),
(47, 23, 'Case Converter', 'case-converter', 'Free online case converter. Fast, easy, and no registration required.', NULL, '✍️', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Case Converter', 'Convert text case instantly with a free online Case Converter. Change text to uppercase, lowercase, title case, sentence case, camel case, snake case, and more with one click.', 'case converter, text case converter, uppercase converter, lowercase converter, title case converter, sentence case converter, camel case converter, snake case converter, kebab case converter, text formatter, change text case, online case converter, free case converter, text conversion tool, capitalization converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 281, 82, 131, '2026-06-06 19:07:31', '2026-09-01 07:33:19', NULL),
(48, 23, 'Lorem Ipsum Generator', 'lorem-ipsum-generator', 'Free online lorem ipsum generator. Fast, easy, and no registration required.', NULL, '📄', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Lorem Ipsum Generator', 'Generate Lorem Ipsum placeholder text instantly with a free online Lorem Ipsum Generator. Create custom paragraphs, sentences, or words for websites, designs, and mockups.', 'lorem ipsum generator, placeholder text generator, dummy text generator, lorem ipsum text, random text generator, design placeholder text, website dummy content, mockup text generator, sample text generator, filler text generator, online lorem ipsum, free lorem ipsum generator, paragraph generator, web design tool, UI design placeholder text', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 355, 64, 132, '2026-06-06 19:07:31', '2026-09-01 07:34:02', NULL),
(49, 23, 'Text Reverser', 'text-reverser', 'Free online text reverser. Fast, easy, and no registration required.', NULL, '🔄', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Text Reverser', 'Reverse text instantly with a free online Text Reverser. Flip characters, words, or entire sentences quickly for fun, coding, formatting, or text manipulation tasks.', 'text reverser, reverse text, online text reverser, word reverser, sentence reverser, string reverser, reverse words tool, reverse characters, text manipulation tool, text formatter, online reverse text, free text reverser, flip text tool, reverse string generator, text utility tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 386, 200, 133, '2026-06-06 19:07:31', '2026-09-01 07:34:40', NULL),
(50, 23, 'Duplicate Line Remover', 'duplicate-line-remover', 'Free online duplicate line remover. Fast, easy, and no registration required.', NULL, '🗑️', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Duplicate Line Remover', 'Remove duplicate lines instantly with a free online Duplicate Line Remover. Clean text, lists, logs, code, and data by deleting repeated lines quickly and accurately.', 'duplicate line remover, remove duplicate lines, online duplicate remover, text duplicate remover, remove duplicate text, unique line generator, text cleaner, list duplicate remover, code duplicate remover, log file cleaner, text processing tool, data cleanup tool, free duplicate line remover, remove repeated lines, text utility tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 327, 70, 134, '2026-06-06 19:07:31', '2026-09-01 07:33:36', NULL),
(51, 26, 'HTML Encoder/Decoder', 'html-encoder-decoder', 'Free online html encoder/decoder. Fast, easy, and no registration required.', NULL, '🌐', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'HTML Encoder/Decoder', 'Encode or decode HTML entities instantly with a free online HTML Encoder/Decoder. Convert special characters safely for websites, code, forms, and web development.', 'html encoder, html decoder, html entity encoder, html entity decoder, encode html online, decode html online, special character encoder, html escape tool, html unescape tool, web development tool, html character converter, online html encoder decoder, html entities converter, code encoding tool, free html decoder', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 223, 84, 135, '2026-06-06 19:07:31', '2026-09-01 07:33:48', NULL),
(52, 26, 'URL Encoder/Decoder', 'url-encoder-decoder', 'Free online url encoder/decoder. Fast, easy, and no registration required.', NULL, '🔗', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'URL Encoder/Decoder', 'Encode or decode URLs instantly with a free online URL Encoder/Decoder. Convert special characters into URL-safe format and decode encoded URLs accurately for web development.', 'url encoder, url decoder, url encoder decoder, encode url online, decode url online, percent encoding tool, url encoding tool, url decoding tool, URI encoder, URI decoder, web development tool, URL escape tool, URL unescape tool, free URL encoder, online URL converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 124, 130, 136, '2026-06-06 19:07:31', '2026-09-01 07:34:47', NULL),
(53, 26, 'Base64 Encoder/Decoder', 'base64-encoder-decoder', 'Free online base64 encoder/decoder. Fast, easy, and no registration required.', NULL, '🔐', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Base64 Encoder/Decoder', 'Encode or decode Base64 data instantly with a free online Base64 Encoder/Decoder. Convert text, strings, and data securely for web development, APIs, and applications.', 'base64 encoder, base64 decoder, base64 encoder decoder, encode base64 online, decode base64 online, base64 converter, text to base64, base64 to text, online base64 tool, string encoder, string decoder, web developer tool, API encoding tool, free base64 encoder, base64 utility', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 86, 156, 137, '2026-06-06 19:07:31', '2026-09-01 07:33:13', NULL),
(54, 26, 'Markdown Editor', 'markdown-editor', 'Free online markdown editor. Fast, easy, and no registration required.', NULL, '📝', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Markdown Editor', 'Write, edit, and preview Markdown online with a free Markdown Editor. Create formatted documents, README files, notes, and documentation with real-time preview.', 'markdown editor, online markdown editor, markdown preview, markdown live editor, markdown writer, markdown viewer, README editor, markdown formatter, markdown syntax editor, online text editor, markdown documentation tool, markdown note editor, free markdown editor, markdown converter, developer markdown tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 190, 83, 138, '2026-06-06 19:07:31', '2026-09-01 07:34:03', NULL),
(55, 26, 'Regex Tester', 'regex-tester', 'Free online regex tester. Fast, easy, and no registration required.', NULL, '🔍', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Regex Tester', 'Test and debug regular expressions instantly with a free online Regex Tester. Validate patterns, match text, and improve regex accuracy with real-time results.', 'regex tester, regular expression tester, online regex tester, regex validator, regex checker, regex pattern tester, regex debugger, regex tool, regex editor, regex matcher, regular expression validator, regex playground, developer regex tool, free regex tester, online regex editor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 267, 43, 139, '2026-06-06 19:07:31', '2026-09-01 07:34:24', NULL),
(56, 26, 'CSS Minifier', 'css-minifier', 'Free online css minifier. Fast, easy, and no registration required.', NULL, '🎨', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'CSS Minifier', 'Minify CSS code instantly with a free online CSS Minifier. Remove unnecessary spaces, comments, and formatting to optimize stylesheets and improve website performance.', 'css minifier, online css minifier, css compressor, css optimizer, minify css, css code minifier, css file compressor, stylesheet minifier, css cleaner, css formatter, web optimization tool, frontend developer tool, css performance optimizer, free css minifier, css code compressor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 51, 188, 140, '2026-06-06 19:07:31', '2026-09-01 07:33:25', NULL),
(57, 26, 'SQL Formatter', 'sql-formatter', 'Free online sql formatter. Fast, easy, and no registration required.', NULL, '🗄️', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'SQL Formatter', 'Format SQL queries instantly with a free online SQL Formatter. Beautify, organize, and improve the readability of SQL code for faster development and debugging.', 'sql formatter, online sql formatter, sql beautifier, sql beautify, sql code formatter, sql query formatter, sql formatter online, sql code beautifier, database query formatter, sql editor tool, sql syntax formatter, developer sql tool, free sql formatter, sql query beautifier, database development tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 418, 19, 141, '2026-06-06 19:07:31', '2026-09-01 07:34:35', NULL),
(58, 26, 'Diff Checker', 'diff-checker', 'Free online diff checker. Fast, easy, and no registration required.', NULL, '🔄', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Diff Checker', 'Compare two texts instantly with a free online Diff Checker. Find differences, highlight changes, and compare files, code, or documents quickly and accurately.', 'diff checker, text diff checker, online diff checker, compare text online, text comparison tool, file comparison tool, code diff checker, document comparison tool, compare files, text difference checker, code comparison tool, compare documents online, free diff checker, change tracker, text compare tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 226, 161, 142, '2026-06-06 19:07:31', '2026-09-01 07:33:34', NULL),
(59, 26, 'Cron Expression Generator', 'cron-expression-generator', 'Free online cron expression generator. Fast, easy, and no registration required.', NULL, '⏱️', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Cron Expression Generator', 'Generate Cron expressions instantly with a free online Cron Expression Generator. Create, validate, and customize Cron schedules for Linux, Unix, cloud services, and automation tasks.', 'cron expression generator, cron generator, cron expression builder, cron scheduler, cron syntax generator, cron job generator, cron expression editor, cron validator, cron parser, linux cron generator, unix cron scheduler, automation scheduler tool, cron schedule builder, free cron generator, developer cron tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 354, 113, 143, '2026-06-06 19:07:31', '2026-09-01 07:33:24', NULL),
(60, 27, 'MD5 Hash Generator', 'md5-hash-generator', 'Free online md5 hash generator. Fast, easy, and no registration required.', NULL, '🔒', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'MD5 Hash Generator', 'Generate MD5 hashes instantly with a free online MD5 Hash Generator. Convert text, strings, and data into MD5 checksums quickly for testing and verification.', 'md5 hash generator, md5 generator, generate md5 hash, md5 checksum generator, online md5 generator, text to md5, string to md5, md5 encoder, hash generator, checksum tool, data hash generator, file integrity checker, developer tools, free md5 generator, md5 conversion tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 493, 167, 144, '2026-06-06 19:07:31', '2026-09-01 07:34:04', NULL);
INSERT INTO `tools` (`id`, `category_id`, `name`, `slug`, `short_description`, `long_description`, `icon`, `color`, `status`, `is_featured`, `tool_type`, `blade_path`, `input_schema`, `output_schema`, `engine_class`, `engine_method`, `seo_title`, `seo_description`, `seo_keywords`, `og_image`, `og_title`, `og_description`, `twitter_title`, `twitter_description`, `robots`, `schema_markup`, `canonical_url`, `has_custom_blade`, `view_count`, `use_count`, `sort_order`, `created_at`, `updated_at`, `deleted_at`) VALUES
(61, 27, 'SHA256 Hash Generator', 'sha256-hash-generator', 'Free online sha256 hash generator. Fast, easy, and no registration required.', NULL, '🔒', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'SHA256 Hash Generator', 'Generate secure SHA256 hashes instantly online. Create cryptographic hashes for text, passwords, files, and data using a fast, free, and reliable SHA256 hash generator.', 'SHA256 hash generator, SHA256 generator, generate SHA256 hash, SHA256 online, hash generator, cryptographic hash generator, secure hash generator, SHA256 checksum, file hash generator, text hash generator, password hash generator, data hash tool, online SHA256 tool, checksum generator, encryption hash tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 28, 24, 145, '2026-06-06 19:07:32', '2026-09-01 07:34:33', NULL),
(62, 27, 'UUID Generator', 'uuid-generator', 'Free online uuid generator. Fast, easy, and no registration required.', NULL, '🆔', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'UUID Generator', 'Generate unique UUIDs instantly with a fast, secure, and free online UUID Generator. Create UUID v4 identifiers for applications, databases, APIs, and software development.', 'uuid generator, generate uuid, uuid v4 generator, unique identifier generator, guid generator, random uuid, online uuid generator, uuid creator, unique id generator, api uuid generator, database uuid, developer tools, random guid generator, uuid tool, uuid creator online', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 405, 117, 146, '2026-06-06 19:07:32', '2026-09-01 07:34:48', NULL),
(63, 27, 'Password Strength Checker', 'password-strength-checker', 'Free online password strength checker. Fast, easy, and no registration required.', NULL, '🛡️', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Password Strength Checker', 'Check your password strength instantly with a free online Password Strength Checker. Analyze password security, identify weaknesses, and create stronger, more secure passwords.', 'password strength checker, password checker, check password strength, secure password checker, password security tool, strong password tester, password analyzer, password safety checker, online password checker, password validation tool, password quality checker, cybersecurity tools, password audit tool, secure password generator, password protection tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 178, 45, 147, '2026-06-06 19:07:32', '2026-09-01 07:34:15', NULL),
(64, 22, 'Image Compressor', 'image-compressor', 'Free online image compressor. Fast, easy, and no registration required.', NULL, '🗜️', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'Image Compressor', 'Compress images online without sacrificing quality. Reduce JPG, PNG, WebP, and other image file sizes quickly using a fast, secure, and free image compressor.', 'image compressor, compress image online, reduce image size, jpg compressor, png compressor, webp compressor, photo compressor, image optimizer, online image compressor, free image compressor, image size reducer, compress photos, image optimization tool, high quality image compression, image file compressor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 277, 165, 148, '2026-06-06 19:07:32', '2026-09-01 07:33:52', NULL),
(65, 22, 'Image Resizer', 'image-resizer', 'Free online image resizer. Fast, easy, and no registration required.', NULL, '🖼️', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'Image Resizer', 'Resize images online in seconds without losing quality. Easily adjust dimensions for JPG, PNG, WebP, and more with a fast, secure, and free image resizer.', 'image resizer, resize image online, photo resizer, image size changer, resize jpg, resize png, resize webp, online image resizer, free image resizer, image dimension changer, photo resize tool, image editor, resize pictures, image scaling tool, image resize utility', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 162, 138, 149, '2026-06-06 19:07:32', '2026-09-01 07:33:54', NULL),
(66, 22, 'Image Converter', 'image-converter', 'Free online image converter. Fast, easy, and no registration required.', NULL, '🔄', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'Image Converter', 'Convert images between JPG, PNG, WebP, GIF, BMP, TIFF, SVG, and more online. Fast, secure, and free image converter with high-quality output and batch support.', 'image converter, convert image online, online image converter, jpg to png, png to jpg, webp converter, gif converter, bmp converter, tiff converter, svg converter, image format converter, photo converter, free image converter, batch image converter, high quality image converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 464, 38, 150, '2026-06-06 19:07:32', '2026-09-01 07:33:53', NULL),
(67, 22, 'Watermark Tool', 'watermark-tool', 'Free online watermark tool. Fast, easy, and no registration required.', NULL, '💧', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'Watermark Tool', 'Add custom text or image watermarks to photos and images online. Protect your content with a fast, secure, and free watermark tool that supports batch processing.', 'watermark tool, add watermark to image, image watermark, photo watermark, online watermark tool, watermark photos online, text watermark, logo watermark, image protection tool, watermark editor, batch watermark tool, free watermark tool, watermark maker, photo watermark editor, image branding tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 352, 73, 151, '2026-06-06 19:07:32', '2026-09-01 07:34:50', NULL),
(68, 22, 'Meme Generator', 'meme-generator', 'Free online meme generator. Fast, easy, and no registration required.', NULL, '😂', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Meme Generator', 'Create funny and viral memes online with a free Meme Generator. Add custom text to popular meme templates or upload your own images in seconds.', 'meme generator, online meme generator, create memes, free meme maker, meme creator, custom meme generator, funny meme maker, image meme generator, viral meme creator, meme templates, add text to image, online meme editor, meme design tool, meme creator online, social media meme maker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 477, 113, 152, '2026-06-06 19:07:32', '2026-09-01 07:34:05', NULL),
(69, 15, 'Pomodoro Timer', 'pomodoro-timer', 'Free online pomodoro timer. Fast, easy, and no registration required.', NULL, '🍅', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Pomodoro Timer', 'Boost productivity with a free online Pomodoro Timer. Stay focused, manage work and study sessions, and improve time management using the Pomodoro Technique.', 'pomodoro timer, online pomodoro timer, focus timer, productivity timer, study timer, work timer, pomodoro technique, free pomodoro timer, time management tool, countdown timer, concentration timer, task timer, productivity tool, online study timer, focus session timer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 411, 129, 153, '2026-06-06 19:07:32', '2026-09-01 07:34:20', NULL),
(70, 15, 'Study Timer', 'study-timer', 'Free online study timer. Fast, easy, and no registration required.', NULL, '📚', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Study Timer', 'Stay focused with a free online Study Timer. Track study sessions, improve concentration, manage breaks, and boost productivity with an easy-to-use timer.', 'study timer, online study timer, focus timer, learning timer, study session timer, exam study timer, productivity timer, countdown timer, student timer, study clock, concentration timer, homework timer, time management for students, free study timer, study productivity tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 130, 122, 154, '2026-06-06 19:07:32', '2026-09-01 07:34:37', NULL),
(71, 13, 'Expense Tracker', 'expense-tracker', 'Free online expense tracker. Fast, easy, and no registration required.', NULL, '💳', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Expense Tracker', 'Track your income and expenses effortlessly with a free online Expense Tracker. Manage your budget, monitor spending, and improve your personal finances.', 'expense tracker, online expense tracker, budget tracker, expense manager, personal finance tracker, money management tool, spending tracker, income and expense tracker, monthly budget planner, financial tracker, expense calculator, budget management, finance organizer, free expense tracker, money tracker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 192, 171, 155, '2026-06-06 19:07:32', '2026-09-01 07:33:37', NULL),
(72, 13, 'Budget Planner', 'budget-planner', 'Free online budget planner. Fast, easy, and no registration required.', NULL, '📊', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Budget Planner', 'Plan your budget with a free online Budget Planner. Track income, manage expenses, set savings goals, and take control of your personal finances with ease.', 'budget planner, online budget planner, budget calculator, personal budget planner, monthly budget planner, expense planner, income and expense tracker, financial planning tool, money management, savings planner, household budget planner, budget management tool, finance planner, free budget planner, budgeting tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 440, 101, 156, '2026-06-06 19:07:32', '2026-09-01 07:33:17', NULL),
(73, 25, 'Flashcard Creator', 'flashcard-creator', 'Free online flashcard creator. Fast, easy, and no registration required.', NULL, '🃏', '#6366f1', 'active', 0, 'productivity', NULL, NULL, NULL, NULL, NULL, 'Flashcard Creator', 'Create digital flashcards online for effective learning and revision. Organize study materials, memorize concepts, and improve retention with a free Flashcard Creator.', 'flashcard creator, online flashcards, flashcard maker, study flashcards, digital flashcards, learning tool, revision flashcards, exam preparation, educational flashcards, create flashcards online, study cards, memory learning tool, vocabulary flashcards, free flashcard maker, student study tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 22, 106, 157, '2026-06-06 19:07:32', '2026-09-01 07:33:39', NULL),
(74, 25, 'Quiz Generator', 'quiz-generator', 'Free online quiz generator. Fast, easy, and no registration required.', NULL, '❓', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Quiz Generator', 'Generate interactive quizzes online in seconds. Create custom quizzes for education, training, assessments, and fun with a fast, free Quiz Generator.', 'quiz generator, online quiz generator, create quizzes, quiz maker, free quiz creator, interactive quiz tool, educational quiz generator, custom quiz maker, assessment tool, exam quiz creator, trivia quiz generator, learning quiz tool, online test maker, practice quiz generator, AI quiz generator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 113, 194, 158, '2026-06-06 19:07:32', '2026-09-01 07:34:22', NULL),
(75, 25, 'Typing Speed Test', 'typing-speed-test', 'Free online typing speed test. Fast, easy, and no registration required.', NULL, '⌨️', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Typing Speed Test', 'Test your typing speed and accuracy online for free. Measure WPM, improve keyboard skills, track progress, and enhance typing performance with an interactive typing speed test.', 'typing speed test, online typing test, typing test, wpm test, typing accuracy test, keyboard typing test, free typing speed test, typing practice, typing skills test, words per minute test, online typing practice, typing trainer, typing performance, improve typing speed, typing challenge', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 497, 161, 159, '2026-06-06 19:07:32', '2026-09-01 07:34:46', NULL),
(76, 24, 'Hex to RGB Converter', 'hex-to-rgb-converter', 'Free online hex to rgb converter. Fast, easy, and no registration required.', NULL, '🎨', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'Hex to RGB Converter', 'Convert HEX color codes to RGB values instantly with a free online Hex to RGB Converter. Fast, accurate, and ideal for web design, UI, and graphic design projects.', 'hex to rgb converter, hex to rgb, convert hex to rgb, color converter, hex color converter, rgb color converter, css color converter, web color tool, hex color code, rgb color values, online color converter, web design tool, graphic design color tool, color code converter, frontend color converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 138, 138, 160, '2026-06-06 19:07:32', '2026-09-01 07:33:45', NULL),
(77, 24, 'Color Picker', 'color-picker', 'Free online color picker. Fast, easy, and no registration required.', NULL, '🖌️', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Color Picker', 'Pick colors instantly with a free online Color Picker. Explore HEX, RGB, HSL, and other color values for web design, branding, UI, and creative projects.', 'color picker, online color picker, hex color picker, rgb color picker, hsl color picker, color code picker, website color picker, image color picker, web design color tool, css color picker, color selector, color palette tool, graphic design tool, pick color online, free color picker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 218, 194, 161, '2026-06-06 19:07:32', '2026-09-01 07:33:21', NULL),
(78, 24, 'Gradient Generator', 'gradient-generator', 'Free online gradient generator. Fast, easy, and no registration required.', NULL, '🌈', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Gradient Generator', 'Create beautiful CSS gradients online with a free Gradient Generator. Design linear, radial, and custom gradients, preview changes, and copy ready-to-use CSS code instantly.', 'gradient generator, css gradient generator, online gradient generator, linear gradient generator, radial gradient generator, color gradient maker, css background generator, gradient creator, gradient tool, web design tool, color gradient editor, background gradient generator, gradient css code, ui design tool, free gradient generator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 89, 52, 162, '2026-06-06 19:07:32', '2026-09-01 07:33:43', NULL),
(79, 30, 'Name Generator', 'name-generator', 'Free online name generator. Fast, easy, and no registration required.', NULL, '👤', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Name Generator', 'Generate unique names instantly with a free online Name Generator. Find creative names for businesses, brands, usernames, characters, domains, and more.', 'name generator, random name generator, business name generator, brand name generator, username generator, company name generator, character name generator, baby name generator, domain name ideas, creative name generator, online name generator, unique name creator, fantasy name generator, nickname generator, free name generator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 73, 83, 163, '2026-06-06 19:07:32', '2026-09-01 07:34:11', NULL),
(80, 30, 'Random Word Generator', 'random-word-generator', 'Free online random word generator. Fast, easy, and no registration required.', NULL, '📖', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Random Word Generator', 'Generate random words instantly with a free online Random Word Generator. Perfect for writing, brainstorming, vocabulary practice, games, and creative inspiration.', 'random word generator, word generator, random words, online word generator, vocabulary generator, writing prompts, brainstorming tool, creative writing tool, random vocabulary, word picker, english word generator, word randomizer, free word generator, idea generator, language learning tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 500, 89, 164, '2026-06-06 19:07:32', '2026-09-01 07:34:23', NULL),
(81, 30, 'Dice Roller', 'dice-roller', 'Free online dice roller. Fast, easy, and no registration required.', NULL, '🎲', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Dice Roller', 'Roll virtual dice instantly with a free online Dice Roller. Simulate single or multiple dice for board games, tabletop RPGs, classrooms, and random decision-making.', 'dice roller, online dice roller, virtual dice, roll dice online, random dice generator, digital dice, d6 dice roller, rpg dice roller, board game dice, tabletop dice roller, free dice roller, multiple dice roller, random number generator, dice simulator, online gaming tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 307, 145, 165, '2026-06-06 19:07:32', '2026-09-01 07:33:33', NULL),
(82, 30, 'Coin Flip', 'coin-flip', 'Free online coin flip. Fast, easy, and no registration required.', NULL, '🪙', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'Coin Flip', 'Flip a virtual coin instantly with a free online Coin Flip tool. Get random Heads or Tails results for games, decisions, probability experiments, and everyday choices.', 'coin flip, online coin flip, virtual coin flip, flip a coin, heads or tails, random coin toss, digital coin flip, coin toss simulator, free coin flip, decision maker coin, probability coin flip, random decision tool, online coin toss, coin flipper, virtual coin toss', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 37, 62, 166, '2026-06-06 19:07:32', '2026-09-01 07:33:20', NULL),
(83, 30, 'Wheel of Fortune', 'wheel-of-fortune', 'Free online wheel of fortune. Fast, easy, and no registration required.', NULL, '🎡', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Wheel of Fortune', 'Spin the Wheel of Fortune online for random selections, giveaways, games, and decision-making. Create a customizable wheel with names, prizes, or options for free.', 'wheel of fortune, wheel of fortune spinner, spin the wheel, lucky wheel, random wheel spinner, prize wheel, giveaway wheel, decision wheel, wheel picker, customizable wheel spinner, online wheel of fortune, random name picker, spin wheel game, fortune wheel, free wheel spinner', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 279, 82, 167, '2026-06-06 19:07:32', '2026-09-01 07:34:51', NULL),
(84, 31, 'Tic Tac Toe', 'tic-tac-toe', 'Free online tic tac toe. Fast, easy, and no registration required.', NULL, '⭕', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Tic Tac Toe', 'Play Tic Tac Toe online for free against friends or challenge yourself. Enjoy the classic X and O game with a fast, interactive, and responsive gaming experience.', 'tic tac toe, play tic tac toe online, online tic tac toe, x and o game, tic tac toe game, free tic tac toe, multiplayer tic tac toe, classic tic tac toe, tic tac toe online game, strategy game, browser game, puzzle game, two player game, tic tac toe board, online xoxo game', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 308, 131, 168, '2026-06-06 19:07:32', '2026-09-01 07:34:42', NULL),
(85, 31, 'Memory Match Game', 'memory-match-game', 'Free online memory match game. Fast, easy, and no registration required.', NULL, '🃏', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Memory Match Game', 'Play a fun Memory Match Game online to improve concentration, memory, and cognitive skills. Match pairs, challenge yourself, and enjoy free brain-training gameplay.', 'memory match game, memory game, matching game, card matching game, memory card game, brain training game, concentration game, memory puzzle, online memory game, free memory game, cognitive skills game, educational game, pair matching game, kids memory game, brain exercise game', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 130, 173, 169, '2026-06-06 19:07:32', '2026-09-01 07:34:06', NULL),
(86, 31, 'Word Scramble', 'word-scramble', 'Free online word scramble. Fast, easy, and no registration required.', NULL, '🔤', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Word Scramble', 'Play Word Scramble online for free. Unscramble letters, improve vocabulary, sharpen spelling skills, and enjoy a fun word puzzle game for all ages.', 'word scramble, word scramble game, unscramble words, word puzzle game, letter scramble, vocabulary game, spelling game, online word scramble, free word game, word solver, word challenge, brain teaser, educational word game, word unscrambler, language learning game', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 71, 89, 170, '2026-06-06 19:07:32', '2026-09-01 07:34:54', NULL),
(87, 31, 'Number Guessing Game', 'number-guessing-game', 'Free online number guessing game. Fast, easy, and no registration required.', NULL, '🔢', '#6366f1', 'active', 0, 'game', NULL, NULL, NULL, NULL, NULL, 'Number Guessing Game', 'Play the Number Guessing Game online for free. Test your logic, improve problem-solving skills, and challenge yourself with a fun and interactive number puzzle.', 'number guessing game, guess the number, online number game, number puzzle game, guessing game, free number guessing game, logic game, brain game, interactive guessing game, math game, number challenge, online puzzle game, educational game, random number game, number prediction game', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 47, 94, 171, '2026-06-06 19:07:32', '2026-09-01 07:34:13', NULL),
(88, 33, 'ASCII Art Generator', 'ascii-art-generator', 'Free online ascii art generator. Fast, easy, and no registration required.', NULL, '🔤', '#6366f1', 'active', 0, 'generator', NULL, NULL, NULL, NULL, NULL, 'ASCII Art Generator', 'Create stunning ASCII art from text instantly with a free online ASCII Art Generator. Generate stylish text designs, banners, and terminal-friendly artwork in seconds.', 'ascii art generator, text to ascii art, ascii text generator, ascii font generator, ascii banner generator, online ascii generator, fancy text generator, terminal text art, text art creator, ascii design tool, console text generator, monospaced text art, free ascii art, developer tools, ascii converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 401, 72, 172, '2026-06-06 19:07:32', '2026-09-01 07:33:02', NULL),
(89, 33, 'Font Preview Tool', 'font-preview-tool', 'Free online font preview tool. Fast, easy, and no registration required.', NULL, '🔡', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Font Preview Tool', 'Preview fonts online before downloading or using them. Compare font styles, sizes, and formatting instantly with a fast, free, and easy-to-use Font Preview Tool.', 'font preview tool, font preview, online font preview, font tester, font comparison tool, preview fonts online, typography tool, font viewer, font style preview, text font preview, web font preview, font selection tool, font testing tool, free font preview, typography preview tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 356, 10, 173, '2026-06-06 19:07:32', '2026-09-01 07:33:40', NULL),
(90, 19, 'File Size Calculator', 'file-size-calculator', 'Free online file size calculator. Fast, easy, and no registration required.', NULL, '📁', '#6366f1', 'active', 0, 'calculator', NULL, NULL, NULL, NULL, NULL, 'File Size Calculator', 'Calculate file sizes quickly with a free online File Size Calculator. Estimate storage requirements, data transfer sizes, and file conversions with accurate results.', 'file size calculator, storage calculator, file size estimator, data size calculator, file storage calculator, online file size calculator, file transfer calculator, digital storage tool, storage space calculator, file conversion calculator, bytes calculator, mb to gb calculator, file size tool, data calculator, storage estimator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 394, 83, 174, '2026-06-06 19:07:32', '2026-09-01 07:33:38', NULL),
(91, 20, 'CSV Viewer', 'csv-viewer', 'Free online csv viewer. Fast, easy, and no registration required.', NULL, '📋', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'CSV Viewer', 'View and analyze CSV files online with a fast, secure, and free CSV Viewer. Open, sort, search, and inspect comma-separated data without installing software.', 'csv viewer, online csv viewer, view csv file, csv reader, csv file viewer, csv editor, open csv online, csv table viewer, csv data viewer, spreadsheet viewer, csv analyzer, comma separated values viewer, free csv viewer, online spreadsheet tool, csv file reader', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 302, 129, 175, '2026-06-06 19:07:32', '2026-09-01 07:33:27', NULL),
(92, 20, 'JSON to CSV Converter', 'json-to-csv-converter', 'Free online json to csv converter. Fast, easy, and no registration required.', NULL, '🔄', '#6366f1', 'active', 0, 'converter', NULL, NULL, NULL, NULL, NULL, 'JSON to CSV Converter', 'Convert JSON data to CSV format instantly with a free online JSON to CSV Converter. Fast, secure, and accurate conversion for developers, analysts, and data processing.', 'json to csv, json to csv converter, convert json to csv, online json converter, csv converter, json converter, data format converter, json file to csv, free json to csv, developer tools, data conversion tool, api data converter, structured data converter, online csv converter, json parser', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 191, 165, 176, '2026-06-06 19:07:32', '2026-09-01 07:34:00', NULL),
(93, 21, 'PDF Merger', 'pdf-merger', 'Free online pdf merger. Fast, easy, and no registration required.', NULL, '📄', '#6366f1', 'active', 0, 'file', NULL, NULL, NULL, NULL, NULL, 'PDF Merger', 'Merge multiple PDF files into one document online for free. Combine PDFs quickly, securely, and in your preferred order without installing any software.', 'pdf merger, merge pdf files, combine pdf online, online pdf merger, free pdf merger, join pdf files, merge multiple pdfs, pdf combiner, combine documents, pdf joining tool, merge documents online, secure pdf merger, reorder pdf pages, pdf management tool, online pdf tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 490, 65, 177, '2026-06-06 19:07:32', '2026-09-01 07:38:07', NULL),
(94, 28, 'Text Paraphraser', 'text-paraphraser', 'Free online text paraphraser. Fast, easy, and no registration required.', NULL, '🤖', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Text Paraphraser', 'Rewrite text instantly with a free AI-powered Text Paraphraser. Improve clarity, readability, and originality while preserving the original meaning for essays, articles, and content.', 'text paraphraser, ai paraphrasing tool, paraphrase text, rewrite text online, sentence rewriter, content rewriter, article rewriter, paraphrase tool, ai text rewriter, plagiarism-free rewriting, text improvement tool, writing assistant, online paraphraser, content paraphrasing, free paraphrasing tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 467, 26, 178, '2026-06-06 19:07:32', '2026-09-01 07:34:40', NULL),
(95, 28, 'Grammar Checker', 'grammar-checker', 'Free online grammar checker. Fast, easy, and no registration required.', NULL, '✅', '#6366f1', 'active', 0, 'text', NULL, NULL, NULL, NULL, NULL, 'Grammar Checker', 'Check grammar, spelling, and punctuation instantly with a free AI-powered Grammar Checker. Improve writing accuracy, clarity, and readability for professional content.', 'grammar checker, grammar checker online, ai grammar checker, spelling checker, punctuation checker, grammar correction tool, writing assistant, english grammar checker, proofreading tool, grammar editor, online grammar tool, grammar and spell check, sentence checker, writing correction tool, free grammar checker', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 242, 56, 179, '2026-06-06 19:07:32', '2026-09-01 07:37:03', NULL),
(96, 34, 'mp4 to mp3', 'mp4-to-mp3', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.mp4-to-mp3', NULL, NULL, NULL, NULL, 'MP4 to MP3 Converter', 'Convert MP4 videos to high-quality MP3 audio online. Extract audio quickly with a fast, secure, and free MP4 to MP3 converter that works on all devices.', 'mp4 to mp3, mp4 to mp3 converter, convert mp4 to mp3, video to mp3, audio extractor, extract audio from video, free mp4 to mp3, online mp4 converter, mp3 converter, video audio converter, mp4 audio extractor, online audio converter, high quality mp3 converter, media converter, video to audio tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 29, 0, 0, '2026-06-09 16:36:15', '2026-09-01 07:34:09', NULL),
(97, 21, 'pdf to jpg', 'pdf-to-jpg', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.pdf-to-jpg', NULL, NULL, NULL, NULL, 'PDF to JPG Converter', 'Convert PDF files to high-quality JPG images online. Fast, secure, and free PDF to JPG converter with accurate page rendering and quick file processing.', 'pdf to jpg, pdf to jpg converter, convert pdf to jpg, pdf to jpeg, pdf to image, online pdf converter, free pdf to jpg, pdf image converter, convert pdf pages to jpg, document to image converter, pdf file converter, high quality pdf converter, online document converter, pdf to picture, pdf to image tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, 0, 0, '2026-06-09 19:19:37', '2026-09-01 07:34:18', NULL),
(98, 22, 'Jpg to png', 'jpg-to-png', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.jpg-to-png', NULL, NULL, NULL, NULL, 'JPG to PNG Converter', 'Convert JPG images to PNG online in seconds. Enjoy fast, secure, and free image conversion with high-quality output and support for transparent backgrounds', 'jpg to png, jpg to png converter, convert jpg to png, jpeg to png, online image converter, free jpg to png, image format converter, jpg image converter, png converter, convert jpeg to png, photo converter, online jpg converter, high quality image converter, image conversion tool, jpg to transparent png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 0, 0, '2026-06-09 19:31:52', '2026-09-01 07:33:58', NULL),
(99, 19, 'word to jpg', 'word-to-jpg', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.word-to-jpg', NULL, NULL, NULL, NULL, 'Word to JPG Converter', 'Convert Word documents to high-quality JPG images online. Fast, secure, and free Word to JPG converter with accurate formatting and quick file processing.', 'word to jpg, word to jpg converter, convert word to jpg, doc to jpg, docx to jpg, word document to image, word to image converter, online word converter, free word to jpg, document to jpg, docx converter, word file converter, image converter, online document converter, word to jpeg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 10, 0, 0, '2026-06-09 19:38:52', '2026-09-01 07:34:55', NULL),
(100, 34, 'video compressor', 'video-compressor', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.video-compressor', NULL, NULL, NULL, NULL, 'Video Compressor', 'Compress videos online without losing quality. Reduce MP4 and other video file sizes quickly using a fast, secure, and free video compression tool.', 'video compressor, compress video online, reduce video size, free video compressor, mp4 compressor, online video compressor, video file compressor, compress mp4, video optimization tool, video size reducer, high quality video compressor, video compression tool, online video optimizer, media compressor, video file reducer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 13, 0, 0, '2026-06-09 19:54:17', '2026-09-01 07:34:49', NULL),
(101, 34, 'mp4 to gif', 'mp4-to-gif', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.mp4-to-gif', NULL, NULL, NULL, NULL, 'MP4 to GIF Converter', 'Convert MP4 videos to high-quality GIFs online in seconds. Trim clips, adjust size, and create smooth animated GIFs with a fast, free, and easy converter.', 'mp4 to gif, mp4 to gif converter, convert mp4 to gif, video to gif, online gif converter, free mp4 to gif, animated gif maker, create gif from video, video gif converter, mp4 gif maker, online video converter, trim video to gif, high quality gif converter, gif creation tool, convert video clips to gif', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, 0, 0, '2026-06-09 20:28:27', '2026-09-01 07:34:09', NULL),
(102, 22, 'svg to png', 'svg-to-png', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.svg-to-png', NULL, NULL, NULL, NULL, 'SVG to PNG Converter', 'Convert SVG files to PNG online in seconds. Fast, free, and secure SVG to PNG converter with high-quality output and support for transparent backgrounds.', 'svg to png, svg to png converter, convert svg to png, online svg converter, free svg to png, svg image converter, png converter, vector to png, svg file converter, image format converter, convert vector image, svg to raster, transparent png converter, online image converter, svg conversion tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, 0, 0, '2026-06-09 20:42:56', '2026-09-01 07:34:38', NULL),
(103, 31, 'Decision maker wheel', 'decision-maker-wheel', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'game', 'tools.generated.decision-maker-wheel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 0, 0, '2026-06-09 22:04:11', '2026-06-10 02:42:38', '2026-06-10 02:42:38'),
(104, 31, 'decision maker wheel game', 'decision-maker-wheel-game', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'game', 'tools.generated.decision-maker-wheel-game', NULL, NULL, NULL, NULL, 'Decision Maker Wheel Game', 'Spin the Decision Maker Wheel to make random choices instantly. A free, fun, and interactive wheel spinner for games, giveaways, classrooms, teams, and everyday decisions.', 'decision maker wheel, spin the wheel, wheel spinner, random wheel, decision wheel, picker wheel, random choice generator, lucky wheel, wheel of names, spin wheel game, random decision maker, prize wheel spinner, online wheel spinner, choice picker, decision spinner tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 15, 0, 0, '2026-06-09 22:10:27', '2026-09-01 07:33:32', NULL),
(105, 22, 'Image photoshop editor', 'image-photoshop-editor', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.image-photoshop-editor', NULL, NULL, NULL, NULL, 'Image Photoshop Editor', 'Edit images online with a powerful Photoshop-style editor. Crop, resize, retouch, add effects, and enhance photos using a fast, free, and easy-to-use editing tool.', 'image photoshop editor, online photo editor, photoshop online, image editor, photo editing tool, edit images online, free photo editor, image retouching, ai photo editor, crop image, resize image, add photo effects, online photoshop alternative, image enhancement, photo editing software', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 5, 0, 0, '2026-06-09 22:24:17', '2026-09-01 07:33:53', NULL),
(106, 22, 'Object Remover', 'object-remover', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.object-remover', NULL, NULL, NULL, NULL, 'Object Remover', 'Remove unwanted objects, people, text, and blemishes from photos instantly with AI. Edit images online for free while maintaining high-quality, natural-looking results.', 'object remover, ai object remover, remove objects from photos, photo object remover, remove unwanted objects, image cleanup tool, remove people from photos, remove text from image, ai photo editor, photo retouch tool, image object eraser, online object remover, remove blemishes from photos, image editing tool, photo cleanup AI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, 0, 0, '2026-06-09 22:44:40', '2026-09-01 07:34:13', NULL),
(107, 22, 'Image background remover', 'image-background-remover', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.image-background-remover', NULL, NULL, NULL, NULL, 'Image Background Remover', 'Remove image backgrounds instantly with AI. Create transparent backgrounds, edit photos, and download high-quality results using a fast, free online background remover.', 'image background remover, remove background from image, ai background remover, background remover online, transparent background maker, photo background remover, remove image background, free background remover, image editor, ai photo editor, online background remover, png background remover, automatic background remover, image cutout tool, background eraser', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 16, 0, 0, '2026-06-09 23:15:43', '2026-09-01 07:33:51', NULL),
(108, 34, 'mp4 video editor', 'mp4-video-editor', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.mp4-video-editor', NULL, NULL, NULL, NULL, 'MP4 Video Editor', 'Edit MP4 videos online with ease. Trim, cut, merge, crop, add effects, subtitles, and more using a fast, secure, and free MP4 video editor.', 'mp4 video editor, edit mp4 online, free mp4 editor, online video editor, mp4 cutter, mp4 trimmer, merge mp4 videos, crop mp4 video, video editing tool, add subtitles to mp4, edit video online, mp4 editor free, video trimmer, online video editing, mp4 editing software', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 12, 0, 0, '2026-06-09 23:41:14', '2026-09-01 07:34:10', NULL),
(109, 26, 'html javascipt css compiler', 'html-javascipt-css-compiler', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.html-javascipt-css-compiler', NULL, NULL, NULL, NULL, 'HTML, CSS & JavaScript Compiler', 'Write, compile, and preview HTML, CSS, and JavaScript code online. Build, test, and debug responsive web pages instantly with a free online compiler.', 'html compiler, css compiler, javascript compiler, online html editor, online css editor, online javascript editor, html css javascript compiler, web code editor, frontend compiler, online code compiler, html css js playground, web development tool, run html online, javascript code editor, responsive web editor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, 0, 0, '2026-06-10 00:05:35', '2026-09-01 07:33:49', NULL),
(110, 26, 'Python compiler', 'python-compiler', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.python-compiler', NULL, NULL, NULL, NULL, 'Python Compiler', 'Compile and run Python code online with a fast, secure, and free Python compiler. Test, debug, and execute Python programs instantly without installation.', 'python compiler, online python compiler, python interpreter, run python online, python code editor, execute python code, free python compiler, python ide online, python programming, python coding tool, compile python code, python debugger, online code compiler, learn python online, python development tool', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 8, 10, 0, '2026-06-10 01:49:32', '2026-09-01 07:37:04', NULL),
(111, 22, 'Image upscaler', 'image-upscaler', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.image-upscaler', NULL, NULL, NULL, NULL, 'Image Upscaler', 'Upscale images online without losing quality. Enhance image resolution, sharpen details, and improve clarity instantly with a fast, AI-powered image upscaler.', 'image upscaler, ai image upscaler, upscale image online, increase image resolution, image enhancer, photo upscaler, image quality enhancer, ai photo enhancer, hd image upscaler, enlarge image without losing quality, online image upscaler, image resolution enhancer, photo quality improver, image sharpener, free image upscaler', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 8, 0, 0, '2026-06-10 02:31:29', '2026-09-01 07:33:57', NULL),
(112, 22, 'screenshot to code', 'screenshot-to-code', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'generator', 'tools.generated.screenshot-to-code', NULL, NULL, NULL, NULL, 'Screenshot to Code', 'Convert screenshots into clean, responsive code instantly. Generate HTML, CSS, React, or other frontend code from UI screenshots using AI-powered technology.', 'screenshot to code, image to code, ui to code, ai code generator, screenshot to html, screenshot to react, html css generator, frontend code generator, ui screenshot converter, design to code, image to html, ai web development, responsive code generator, website code generator, screenshot converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 7, 0, 0, '2026-06-10 02:47:51', '2026-09-01 07:34:33', NULL),
(113, 34, 'mp3 editor', 'mp3-editor', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'productivity', 'tools.generated.mp3-editor', NULL, NULL, NULL, NULL, 'MP3 Editor', 'Edit MP3 files online with ease. Trim, cut, merge, split, adjust audio, and enhance sound quality using a fast, secure, and free MP3 editor.', 'mp3 editor, edit mp3 online, free mp3 editor, online audio editor, mp3 cutter, mp3 trimmer, merge mp3 files, split mp3, audio editing tool, mp3 joiner, audio editor online, edit audio files, mp3 converter, sound editor, music editor online', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, 0, 0, '2026-06-10 04:56:24', '2026-09-01 07:34:08', NULL),
(119, 21, 'pdf editor', 'pdf-editor', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'file', 'tools.generated.pdf-editor', NULL, NULL, NULL, NULL, 'PDF Editor', 'Edit PDF files online with ease. Add text, images, annotations, signatures, and more using a fast, secure, and free PDF editor compatible with all devices.', 'pdf editor, edit pdf online, free pdf editor, online pdf editor, modify pdf, pdf editing tool, edit pdf documents, add text to pdf, pdf annotator, sign pdf online, pdf editor free, edit pdf files, pdf document editor, secure pdf editor, online document editor', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 30, 0, 0, '2026-06-20 19:44:48', '2026-09-01 07:34:15', NULL),
(120, 19, 'Text to pdf Converter', 'text-to-pdf-converter', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.text-to-pdf-converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 4, 0, 0, '2026-07-15 23:03:47', '2026-09-01 07:34:42', NULL),
(121, 19, 'Pdf to text Converter', 'pdf-to-text-converter', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.pdf-to-text-converter', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 11, 0, 0, '2026-07-15 23:19:24', '2026-09-01 07:34:18', NULL),
(122, 19, 'Pdf to images', 'pdf-to-images', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.pdf-to-images', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, 0, 0, '2026-07-15 23:30:16', '2026-09-01 07:34:17', NULL),
(123, 19, 'Image to pdf', 'image-to-pdf', NULL, NULL, '🔧', '#6366f1', 'active', 0, 'converter', 'tools.generated.image-to-pdf', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 9, 0, 0, '2026-07-15 23:50:13', '2026-09-01 07:33:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tool_contents`
--

CREATE TABLE `tool_contents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tool_id` bigint(20) UNSIGNED NOT NULL,
  `section_key` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tool_faqs`
--

CREATE TABLE `tool_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tool_id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_visible` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_faqs`
--

INSERT INTO `tool_faqs` (`id`, `tool_id`, `question`, `answer`, `sort_order`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 1, 'How do I calculate a percentage?', 'To calculate a percentage, divide the part by the whole and multiply by 100. For example, 50/200 × 100 = 25%.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(2, 1, 'How do I calculate a percentage discount?', 'Multiply the original price by the discount percentage and divide by 100. Then subtract from the original price.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(3, 1, 'What is percentage increase?', 'Percentage increase is the change in value expressed as a percentage of the original value: ((new - old) / old) × 100.', 2, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(4, 2, 'What is a healthy BMI?', 'A BMI between 18.5 and 24.9 is considered healthy for adults. Below 18.5 is underweight, 25-29.9 is overweight, and 30 or above is obese.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(5, 2, 'Is BMI accurate?', 'BMI is a useful screening tool but has limitations. It doesn\'t account for muscle mass, age, sex, or fat distribution. Athletes may have a high BMI due to muscle, not fat.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(6, 3, 'How is the monthly payment calculated?', 'Monthly payment = P × r × (1+r)^n / ((1+r)^n - 1), where P is principal, r is monthly interest rate, and n is number of months.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(7, 3, 'What is an amortization schedule?', 'An amortization schedule shows how each payment is split between paying down the principal and paying interest over the life of the loan.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(9, 7, 'What units are supported?', 'We support length (m, km, ft, mi, yd, in, cm, mm), weight (kg, g, lb, oz, ton), temperature (C, F, K), area (m², ft², km², acre, ha), volume (L, ml, gal, qt, cup), speed (m/s, km/h, mph, knot), and data (B, KB, MB, GB, TB).', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(10, 8, 'Are the exchange rates live?', 'Exchange rates are manually managed by the admin and should be updated regularly. They are not connected to a live feed.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(11, 9, 'How long should my password be?', 'For strong security, use at least 12-16 characters with a mix of uppercase, lowercase, numbers, and symbols.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(12, 9, 'Is this generator secure?', 'Yes, passwords are generated in your browser using cryptographically secure random functions. They are never sent to our servers.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(13, 10, 'What content can I encode in a QR code?', 'URLs, plain text, email addresses, phone numbers, SMS messages, WiFi credentials, vCards, and more.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(14, 10, 'How do I scan a QR code?', 'Use your phone\'s camera app (on most modern phones) or a free QR code scanner app.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(15, 11, 'What are color harmonies?', 'Color harmonies are combinations of colors that are pleasing to the eye. Analogous uses adjacent colors, complementary uses opposite colors, and triadic uses three evenly spaced colors.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(16, 12, 'What is JSON?', 'JSON (JavaScript Object Notation) is a lightweight data interchange format. It\'s easy for humans to read and write, and easy for machines to parse and generate.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(17, 12, 'What is JSON minification?', 'Minification removes all unnecessary whitespace and formatting from JSON, reducing file size for transmission.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(19, 14, 'Is my data saved?', 'Yes, your tasks are saved in your browser\'s local storage. They persist across page refreshes but are specific to your device and browser.', 0, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(20, 14, 'Can I sync across devices?', 'Currently tasks are stored locally in your browser. They won\'t sync across different devices.', 1, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(23, 4, 'How much should I tip?', 'In the US, 15-20% is standard for restaurants. 10% for OK service, 20% for great service, 25%+ for excellent.', 0, 1, '2026-07-12 04:34:07', '2026-07-12 04:34:07'),
(24, 13, 'How does the summarizer work?', 'We use extractive summarization — analyzing word frequency and sentence position to identify the most important sentences in your text.', 0, 1, '2026-07-12 04:41:03', '2026-07-12 04:41:03'),
(25, 15, 'Where are my notes stored?', 'Notes are saved in your browser\'s local storage. They\'re private and only accessible on your device.', 0, 1, '2026-07-12 04:43:24', '2026-07-12 04:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `tool_inputs`
--

CREATE TABLE `tool_inputs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tool_id` bigint(20) UNSIGNED NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_label` varchar(255) NOT NULL,
  `field_type` varchar(255) NOT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `default_value` text DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `validation` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`validation`)),
  `help_text` text DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tool_inputs`
--

INSERT INTO `tool_inputs` (`id`, `tool_id`, `field_name`, `field_label`, `field_type`, `placeholder`, `default_value`, `required`, `options`, `validation`, `help_text`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'value', 'Value', 'number', 'e.g. 200', NULL, 1, NULL, NULL, 'Enter the base number', 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(2, 1, 'percent', 'Percentage (%)', 'number', 'e.g. 15', NULL, 1, NULL, NULL, 'Enter the percentage', 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(3, 2, 'unit', 'Unit System', 'select', NULL, NULL, 1, '\"[{\\\"value\\\":\\\"metric\\\",\\\"label\\\":\\\"Metric (kg\\\\\\/cm)\\\"},{\\\"value\\\":\\\"imperial\\\",\\\"label\\\":\\\"Imperial (lbs\\\\\\/in)\\\"}]\"', NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(4, 2, 'weight', 'Weight', 'number', 'e.g. 70', NULL, 1, NULL, NULL, 'Weight in kg (metric) or lbs (imperial)', 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(5, 2, 'height', 'Height', 'number', 'e.g. 175', NULL, 1, NULL, NULL, 'Height in cm (metric) or inches (imperial)', 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(6, 3, 'principal', 'Loan Amount ($)', 'number', 'e.g. 10000', NULL, 1, NULL, '\"{\\\"min\\\":1}\"', NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(7, 3, 'annual_rate', 'Annual Interest Rate (%)', 'number', 'e.g. 5.5', NULL, 1, NULL, '\"{\\\"min\\\":0,\\\"step\\\":\\\"0.1\\\"}\"', NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(8, 3, 'term_months', 'Loan Term (months)', 'number', 'e.g. 60', NULL, 1, NULL, NULL, '12 = 1 year, 60 = 5 years, 360 = 30 years', 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(17, 7, 'value', 'Value', 'number', 'e.g. 100', NULL, 1, NULL, NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(18, 7, 'type', 'Unit Type', 'select', NULL, NULL, 1, '\"[{\\\"value\\\":\\\"length\\\",\\\"label\\\":\\\"Length (m, km, ft, mi...)\\\"},{\\\"value\\\":\\\"weight\\\",\\\"label\\\":\\\"Weight (kg, lb, oz...)\\\"},{\\\"value\\\":\\\"temperature\\\",\\\"label\\\":\\\"Temperature (C, F, K)\\\"},{\\\"value\\\":\\\"area\\\",\\\"label\\\":\\\"Area (m\\\\u00b2, ft\\\\u00b2, acre...)\\\"},{\\\"value\\\":\\\"volume\\\",\\\"label\\\":\\\"Volume (L, gal, ml...)\\\"},{\\\"value\\\":\\\"speed\\\",\\\"label\\\":\\\"Speed (km\\\\\\/h, mph...)\\\"},{\\\"value\\\":\\\"data\\\",\\\"label\\\":\\\"Data (B, KB, MB, GB...)\\\"}]\"', NULL, NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(19, 7, 'from', 'From Unit', 'text', 'e.g. km', NULL, 1, NULL, NULL, 'Enter unit: m, km, ft, mi, kg, lb, c, f, k, l, gal, mps, kph, mph, b, kb, mb, gb...', 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(20, 7, 'to', 'To Unit', 'text', 'e.g. mi', NULL, 1, NULL, NULL, NULL, 3, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(21, 8, 'amount', 'Amount', 'number', 'e.g. 100', '1', 1, NULL, NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(22, 8, 'from', 'From Currency', 'select', NULL, NULL, 1, '\"[{\\\"value\\\":\\\"USD\\\",\\\"label\\\":\\\"USD - US Dollar\\\"},{\\\"value\\\":\\\"EUR\\\",\\\"label\\\":\\\"EUR - Euro\\\"},{\\\"value\\\":\\\"GBP\\\",\\\"label\\\":\\\"GBP - British Pound\\\"},{\\\"value\\\":\\\"JPY\\\",\\\"label\\\":\\\"JPY - Japanese Yen\\\"},{\\\"value\\\":\\\"AUD\\\",\\\"label\\\":\\\"AUD - Australian Dollar\\\"},{\\\"value\\\":\\\"CAD\\\",\\\"label\\\":\\\"CAD - Canadian Dollar\\\"},{\\\"value\\\":\\\"CHF\\\",\\\"label\\\":\\\"CHF - Swiss Franc\\\"},{\\\"value\\\":\\\"CNY\\\",\\\"label\\\":\\\"CNY - Chinese Yuan\\\"},{\\\"value\\\":\\\"INR\\\",\\\"label\\\":\\\"INR - Indian Rupee\\\"},{\\\"value\\\":\\\"MXN\\\",\\\"label\\\":\\\"MXN - Mexican Peso\\\"},{\\\"value\\\":\\\"BRL\\\",\\\"label\\\":\\\"BRL - Brazilian Real\\\"},{\\\"value\\\":\\\"KRW\\\",\\\"label\\\":\\\"KRW - South Korean Won\\\"},{\\\"value\\\":\\\"SGD\\\",\\\"label\\\":\\\"SGD - Singapore Dollar\\\"},{\\\"value\\\":\\\"AED\\\",\\\"label\\\":\\\"AED - UAE Dirham\\\"},{\\\"value\\\":\\\"NGN\\\",\\\"label\\\":\\\"NGN - Nigerian Naira\\\"}]\"', NULL, NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(23, 8, 'to', 'To Currency', 'select', NULL, NULL, 1, '\"[{\\\"value\\\":\\\"EUR\\\",\\\"label\\\":\\\"EUR - Euro\\\"},{\\\"value\\\":\\\"USD\\\",\\\"label\\\":\\\"USD - US Dollar\\\"},{\\\"value\\\":\\\"GBP\\\",\\\"label\\\":\\\"GBP - British Pound\\\"},{\\\"value\\\":\\\"JPY\\\",\\\"label\\\":\\\"JPY - Japanese Yen\\\"},{\\\"value\\\":\\\"AUD\\\",\\\"label\\\":\\\"AUD - Australian Dollar\\\"},{\\\"value\\\":\\\"CAD\\\",\\\"label\\\":\\\"CAD - Canadian Dollar\\\"},{\\\"value\\\":\\\"CHF\\\",\\\"label\\\":\\\"CHF - Swiss Franc\\\"},{\\\"value\\\":\\\"CNY\\\",\\\"label\\\":\\\"CNY - Chinese Yuan\\\"},{\\\"value\\\":\\\"INR\\\",\\\"label\\\":\\\"INR - Indian Rupee\\\"},{\\\"value\\\":\\\"MXN\\\",\\\"label\\\":\\\"MXN - Mexican Peso\\\"},{\\\"value\\\":\\\"BRL\\\",\\\"label\\\":\\\"BRL - Brazilian Real\\\"},{\\\"value\\\":\\\"NGN\\\",\\\"label\\\":\\\"NGN - Nigerian Naira\\\"},{\\\"value\\\":\\\"AED\\\",\\\"label\\\":\\\"AED - UAE Dirham\\\"},{\\\"value\\\":\\\"ZAR\\\",\\\"label\\\":\\\"ZAR - South African Rand\\\"}]\"', NULL, NULL, 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(24, 9, 'length', 'Password Length', 'range', NULL, '16', 0, NULL, '\"{\\\"min\\\":4,\\\"max\\\":64}\"', NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(25, 9, 'count', 'Number of Passwords', 'number', NULL, '1', 0, NULL, '\"{\\\"min\\\":1,\\\"max\\\":10}\"', NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(26, 9, 'uppercase', 'Include Uppercase (A-Z)', 'checkbox', NULL, '1', 0, NULL, NULL, NULL, 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(27, 9, 'lowercase', 'Include Lowercase (a-z)', 'checkbox', NULL, '1', 0, NULL, NULL, NULL, 3, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(28, 9, 'numbers', 'Include Numbers (0-9)', 'checkbox', NULL, '1', 0, NULL, NULL, NULL, 4, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(29, 9, 'symbols', 'Include Symbols (!@#$)', 'checkbox', NULL, '1', 0, NULL, NULL, NULL, 5, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(30, 10, 'text', 'Content (URL, Text, etc.)', 'textarea', 'https://example.com or any text...', NULL, 1, NULL, NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(31, 10, 'size', 'Size (px)', 'range', NULL, '300', 0, NULL, '\"{\\\"min\\\":100,\\\"max\\\":500,\\\"step\\\":50}\"', NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(32, 10, 'color', 'QR Color', 'color', NULL, '#000000', 0, NULL, NULL, NULL, 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(33, 10, 'background', 'Background Color', 'color', NULL, '#ffffff', 0, NULL, NULL, NULL, 3, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(34, 11, 'base_color', 'Base Color', 'color', NULL, '#6366f1', 1, NULL, NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(35, 11, 'mode', 'Color Harmony', 'select', NULL, NULL, 0, '\"[{\\\"value\\\":\\\"analogous\\\",\\\"label\\\":\\\"Analogous\\\"},{\\\"value\\\":\\\"complementary\\\",\\\"label\\\":\\\"Complementary\\\"},{\\\"value\\\":\\\"triadic\\\",\\\"label\\\":\\\"Triadic\\\"},{\\\"value\\\":\\\"monochromatic\\\",\\\"label\\\":\\\"Monochromatic\\\"},{\\\"value\\\":\\\"split-complementary\\\",\\\"label\\\":\\\"Split Complementary\\\"}]\"', NULL, NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(36, 11, 'count', 'Number of Colors', 'range', NULL, '5', 0, NULL, '\"{\\\"min\\\":3,\\\"max\\\":8}\"', NULL, 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(37, 12, 'json', 'JSON Input', 'textarea', '{\"key\": \"value\", \"array\": [1, 2, 3]}', NULL, 1, NULL, NULL, NULL, 0, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(38, 12, 'action', 'Action', 'select', NULL, NULL, 0, '\"[{\\\"value\\\":\\\"format\\\",\\\"label\\\":\\\"Format & Beautify\\\"},{\\\"value\\\":\\\"minify\\\",\\\"label\\\":\\\"Minify\\\"},{\\\"value\\\":\\\"validate\\\",\\\"label\\\":\\\"Validate Only\\\"}]\"', NULL, NULL, 1, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(39, 12, 'indent', 'Indent Size', 'select', NULL, '4', 0, '\"[{\\\"value\\\":\\\"2\\\",\\\"label\\\":\\\"2 spaces\\\"},{\\\"value\\\":\\\"4\\\",\\\"label\\\":\\\"4 spaces\\\"},{\\\"value\\\":\\\"8\\\",\\\"label\\\":\\\"8 spaces\\\"}]\"', NULL, NULL, 2, '2026-06-06 19:07:31', '2026-06-06 19:07:31'),
(42, 4, 'bill', 'Bill Amount ($)', 'number', 'e.g. 45.50', '', 1, NULL, NULL, '', 0, '2026-07-12 04:34:07', '2026-07-12 04:34:07'),
(43, 4, 'tip_percent', 'Tip Percentage (%)', 'number', 'e.g. 18', '18', 1, NULL, NULL, '', 1, '2026-07-12 04:34:07', '2026-07-12 04:34:07'),
(44, 4, 'people', 'Number of People', 'number', 'e.g. 4', '1', 1, NULL, NULL, '', 2, '2026-07-12 04:34:07', '2026-07-12 04:34:07'),
(45, 5, 'date1', 'Start Date', 'date', '', '', 1, NULL, NULL, '', 0, '2026-07-12 04:35:41', '2026-07-12 04:35:41'),
(46, 5, 'date2', 'End Date', 'date', '', '', 1, NULL, NULL, '', 1, '2026-07-12 04:35:41', '2026-07-12 04:35:41'),
(47, 6, 'min', 'Minimum', 'number', '', '1', 1, NULL, NULL, '', 0, '2026-07-12 04:36:43', '2026-07-12 04:36:43'),
(48, 6, 'max', 'Maximum', 'number', '', '100', 1, NULL, NULL, '', 1, '2026-07-12 04:36:43', '2026-07-12 04:36:43'),
(49, 6, 'count', 'How Many Numbers?', 'number', '', '1', 1, NULL, NULL, '', 2, '2026-07-12 04:36:43', '2026-07-12 04:36:43'),
(50, 13, 'text', 'Text to Summarize', 'textarea', 'Paste your text here (minimum 50 characters)...', '', 1, NULL, NULL, '', 0, '2026-07-12 04:41:03', '2026-07-12 04:41:03'),
(51, 13, 'ratio', 'Summary Length', 'select', '', '0.3', 0, NULL, NULL, '', 1, '2026-07-12 04:41:03', '2026-07-12 04:41:03');

-- --------------------------------------------------------

--
-- Table structure for table `tool_templates`
--

CREATE TABLE `tool_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `tool_type` varchar(255) NOT NULL,
  `blade_content` longtext NOT NULL,
  `description` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `is_active`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@jedisebitool.com', '2026-06-06 19:07:31', '$2y$12$0GjoWb1Nuy9KlVvQ782PLOjBHiQGlXjrlIeDYipUgmUxuY.Bq56/6', 'admin', 1, NULL, 'rnYfthjnxp5yYeEJJlvppiQ8H9ol1xfz5038JRMXZEon1FpBF81MXWj4ynUS', '2026-06-06 19:07:31', '2026-06-06 19:07:31');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_is_active_sort_order_index` (`is_active`,`sort_order`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tools_slug_unique` (`slug`),
  ADD KEY `tools_status_is_featured_sort_order_index` (`status`,`is_featured`,`sort_order`),
  ADD KEY `tools_category_id_status_index` (`category_id`,`status`);

--
-- Indexes for table `tool_contents`
--
ALTER TABLE `tool_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_contents_tool_id_section_key_index` (`tool_id`,`section_key`);

--
-- Indexes for table `tool_faqs`
--
ALTER TABLE `tool_faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_faqs_tool_id_sort_order_index` (`tool_id`,`sort_order`);

--
-- Indexes for table `tool_inputs`
--
ALTER TABLE `tool_inputs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_inputs_tool_id_sort_order_index` (`tool_id`,`sort_order`);

--
-- Indexes for table `tool_templates`
--
ALTER TABLE `tool_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tool_templates_slug_unique` (`slug`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tools`
--
ALTER TABLE `tools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `tool_contents`
--
ALTER TABLE `tool_contents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tool_faqs`
--
ALTER TABLE `tool_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tool_inputs`
--
ALTER TABLE `tool_inputs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tool_templates`
--
ALTER TABLE `tool_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_contents`
--
ALTER TABLE `tool_contents`
  ADD CONSTRAINT `tool_contents_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_faqs`
--
ALTER TABLE `tool_faqs`
  ADD CONSTRAINT `tool_faqs_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tool_inputs`
--
ALTER TABLE `tool_inputs`
  ADD CONSTRAINT `tool_inputs_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
