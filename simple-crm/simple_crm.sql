-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 09:19 PM
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
-- Database: `simple_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `activityable_type` varchar(255) NOT NULL,
  `activityable_id` bigint(20) UNSIGNED NOT NULL,
  `outcome` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `is_all_day` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `customer_id`, `type`, `subject`, `description`, `start_date`, `end_date`, `due_date`, `completed_at`, `status`, `assigned_to`, `created_by`, `activityable_type`, `activityable_id`, `outcome`, `location`, `is_all_day`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'meeting', 'Parth sir', 'Project Review', '2025-04-24 00:00:00', '2025-04-24 23:59:59', '2025-04-24 23:59:59', NULL, 'pending', 1, 1, 'App\\Models\\User', 1, NULL, NULL, 0, '2025-04-23 13:38:30', '2025-04-23 13:38:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `customer_id`, `first_name`, `last_name`, `email`, `phone`, `position`, `department`, `is_primary`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Gohel', 'Darshan', 'goheldarshan987@gmail.com', '07069288848', NULL, NULL, 1, NULL, '2025-04-23 13:36:00', '2025-04-23 13:36:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `website`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Darshan', 'goheldarshan987@gmail.com', '07069288848', 'Keshod', 'Junagadh', 'Gujarat', 'India', '362001', 'https://stackdot.in/', NULL, 'active', '2025-04-23 13:35:37', '2025-04-23 13:35:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_group_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`options`)),
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_filterable` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `validation_rules` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_groups`
--

CREATE TABLE `custom_field_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `custom_field_id` bigint(20) UNSIGNED NOT NULL,
  `entity_type` varchar(255) NOT NULL,
  `entity_id` bigint(20) UNSIGNED NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deals`
--

CREATE TABLE `deals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) NOT NULL DEFAULT 'USD',
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `expected_close_date` date DEFAULT NULL,
  `pipeline_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deals`
--

INSERT INTO `deals` (`id`, `name`, `description`, `amount`, `currency`, `status`, `expected_close_date`, `pipeline_id`, `customer_id`, `contact_id`, `owner_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'StackDot', NULL, 20000.00, 'USD', 'won', '2026-06-20', NULL, 1, NULL, 1, '2025-04-23 13:39:31', '2025-04-23 13:46:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deal_history`
--

CREATE TABLE `deal_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deal_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_tracking`
--

CREATE TABLE `email_tracking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `emailable_type` varchar(255) NOT NULL,
  `emailable_id` bigint(20) UNSIGNED NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `sent_at` datetime NOT NULL,
  `opened_at` datetime DEFAULT NULL,
  `open_count` int(11) NOT NULL DEFAULT 0,
  `clicked_at` datetime DEFAULT NULL,
  `click_count` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2024_03_19_create_pipelines_table', 1),
(3, '2024_03_20_000001_create_customers_table', 1),
(4, '2024_03_20_create_activities_and_custom_fields_tables', 1),
(5, '2024_03_20_create_deals_table', 1),
(6, '2024_04_20_add_dates_to_activities_table', 1),
(7, '2025_04_19_145811_create_contacts_table', 1),
(8, '2025_04_19_145821_create_opportunities_table', 1),
(9, '2025_04_19_145834_create_notes_table', 1),
(10, '2025_04_19_153151_create_personal_access_tokens_table', 1),
(11, '2025_04_19_171808_add_customer_id_to_activities_table', 1),
(12, '2025_04_23_185104_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pipelines`
--

CREATE TABLE `pipelines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pipeline_stages`
--

CREATE TABLE `pipeline_stages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pipeline_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `probability` int(11) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `color` varchar(255) NOT NULL DEFAULT '#4f46e5',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
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
('8OPd0Owuqxu6sNjsODeGmBVEOBMtF4BhDfnDfIiY', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM3RpQllleWZYREVSektIbnQySDZoeUJNRjUyTGljUldLYkI3ZE8wQiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYWxlbmRhciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1745435792);

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Darshan', 'goheldarshan987@gmail.com', NULL, '$2y$12$GeZDXbWp45oEuyygMGPsqe8oSsKvU4i2W1Gq1MyYsaqOnRrAQyP/y', NULL, '2025-04-23 13:21:54', '2025-04-23 13:21:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activities_assigned_to_foreign` (`assigned_to`),
  ADD KEY `activities_created_by_foreign` (`created_by`),
  ADD KEY `activities_activityable_type_activityable_id_index` (`activityable_type`,`activityable_id`),
  ADD KEY `activities_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contacts_email_unique` (`email`),
  ADD KEY `contacts_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `custom_fields_key_unique` (`key`),
  ADD KEY `custom_fields_field_group_id_foreign` (`field_group_id`);

--
-- Indexes for table `custom_field_groups`
--
ALTER TABLE `custom_field_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `custom_field_values_custom_field_id_foreign` (`custom_field_id`),
  ADD KEY `custom_field_values_entity_type_entity_id_index` (`entity_type`,`entity_id`);

--
-- Indexes for table `deals`
--
ALTER TABLE `deals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deals_pipeline_id_foreign` (`pipeline_id`),
  ADD KEY `deals_customer_id_foreign` (`customer_id`),
  ADD KEY `deals_owner_id_foreign` (`owner_id`);

--
-- Indexes for table `deal_history`
--
ALTER TABLE `deal_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deal_history_deal_id_foreign` (`deal_id`),
  ADD KEY `deal_history_user_id_foreign` (`user_id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_templates_created_by_foreign` (`created_by`);

--
-- Indexes for table `email_tracking`
--
ALTER TABLE `email_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_tracking_user_id_foreign` (`user_id`),
  ADD KEY `email_tracking_emailable_type_emailable_id_index` (`emailable_type`,`emailable_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notes_customer_id_foreign` (`customer_id`),
  ADD KEY `notes_created_by_foreign` (`created_by`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `pipelines`
--
ALTER TABLE `pipelines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pipelines_created_by_foreign` (`created_by`);

--
-- Indexes for table `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pipeline_stages_pipeline_id_foreign` (`pipeline_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_groups`
--
ALTER TABLE `custom_field_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deals`
--
ALTER TABLE `deals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deal_history`
--
ALTER TABLE `deal_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_tracking`
--
ALTER TABLE `email_tracking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pipelines`
--
ALTER TABLE `pipelines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
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
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activities_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `activities_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD CONSTRAINT `custom_fields_field_group_id_foreign` FOREIGN KEY (`field_group_id`) REFERENCES `custom_field_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD CONSTRAINT `custom_field_values_custom_field_id_foreign` FOREIGN KEY (`custom_field_id`) REFERENCES `custom_fields` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deals`
--
ALTER TABLE `deals`
  ADD CONSTRAINT `deals_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `deals_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `deals_pipeline_id_foreign` FOREIGN KEY (`pipeline_id`) REFERENCES `pipelines` (`id`);

--
-- Constraints for table `deal_history`
--
ALTER TABLE `deal_history`
  ADD CONSTRAINT `deal_history_deal_id_foreign` FOREIGN KEY (`deal_id`) REFERENCES `deals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `deal_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD CONSTRAINT `email_templates_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `email_tracking`
--
ALTER TABLE `email_tracking`
  ADD CONSTRAINT `email_tracking_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notes_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pipelines`
--
ALTER TABLE `pipelines`
  ADD CONSTRAINT `pipelines_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `pipeline_stages`
--
ALTER TABLE `pipeline_stages`
  ADD CONSTRAINT `pipeline_stages_pipeline_id_foreign` FOREIGN KEY (`pipeline_id`) REFERENCES `pipelines` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
