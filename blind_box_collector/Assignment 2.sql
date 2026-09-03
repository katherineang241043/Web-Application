-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2026 at 10:07 PM
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
-- Database: `popmart_collector`
--

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `series_name` varchar(50) NOT NULL,
  `rarity` enum('Common','Secret') NOT NULL,
  `description` varchar(255) NOT NULL,
  `theme_color` varchar(20) NOT NULL,
  `image_file` varchar(150) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`id`, `name`, `series_name`, `rarity`, `description`, `theme_color`, `image_file`) VALUES
(1, 'Dive into Love', 'LABUBU', 'Common', '🐰 Playful & Romantic — Falling into love with a cute bird-inspired look.', '#ff6b63', 'labubu-dive-into-love.webp'),
(2, 'My Sweet Trouble', 'MOLLY', 'Common', '👧 Sweet & Mischievous — Elegant but with a little trouble.', '#f19ac2', 'molly-my-sweet-trouble.webp'),
(3, 'Shall We Dance', 'CRYBABY', 'Common', '😭 Playful & Dreamy — A cute invitation to dance.', '#f1a25f', 'crybaby-shall-we-dance.webp'),
(4, 'Whispers of Love', 'DIMOO', 'Common', '💤 Soft & Dreamy — Quietly expressing love through gentle whispers.', '#69c8e8', 'dimoo-whispers-of-love.webp'),
(5, 'My Deepest Secret', 'HIRONO', 'Common', '👻 Mysterious & Emotional — Hiding deep feelings and secrets.', '#72947e', 'hirono-my-deepest-secret.webp'),
(6, 'A Dawn Duet', 'SKULLPANDA', 'Common', '💀 Elegant & Poetic — A peaceful duet at the beginning of dawn.', '#8e78d7', 'skullpanda-a-dawn-duet.webp'),
(7, 'Make Me Blush', 'HACIPUPU', 'Common', '🐣 Cute & Shy — A playful little bird that makes you blush.', '#ffd166', 'hacipupu-make-me-blush.webp'),
(8, 'Waiting in Snow', 'TWINKLE TWINKLE', 'Common', '✨ Peaceful & Magical — Waiting quietly in a snowy world.', '#8ed8f8', 'twinkle-waiting-in-snow.webp'),
(9, 'More Than Words', 'HIRONO', 'Secret', '👻 Emotional & Mysterious — Feelings that go beyond words.', '#e85078', 'hirono-more-than-words.webp');

-- --------------------------------------------------------

--
-- Table structure for table `collection`
--

CREATE TABLE `collection` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `first_drawn_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_drawn_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `collection`
--

INSERT INTO `collection` (`id`, `user_id`, `character_id`, `quantity`, `first_drawn_at`, `last_drawn_at`) VALUES
(15, 1, 7, 2, '2026-08-31 14:32:15', '2026-08-31 14:32:26'),
(16, 1, 2, 1, '2026-08-31 14:32:20', '2026-08-31 14:32:20'),
(17, 1, 4, 1, '2026-08-31 14:32:23', '2026-08-31 14:32:23'),
(18, 4, 5, 2, '2026-09-01 01:48:36', '2026-09-01 01:50:27'),
(19, 4, 8, 2, '2026-09-01 01:50:18', '2026-09-01 01:50:22'),
(25, 6, 1, 2, '2026-09-01 02:04:49', '2026-09-01 03:00:30'),
(38, 6, 4, 1, '2026-09-01 03:00:39', '2026-09-01 03:00:39'),
(39, 1, 5, 1, '2026-09-01 03:03:14', '2026-09-01 03:03:14'),
(40, 1, 6, 1, '2026-09-01 03:03:16', '2026-09-01 03:03:16'),
(41, 11, 8, 2, '2026-09-03 19:59:12', '2026-09-03 19:59:22'),
(42, 11, 5, 1, '2026-09-03 19:59:29', '2026-09-03 19:59:29'),
(43, 11, 1, 1, '2026-09-03 19:59:38', '2026-09-03 19:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `draw_history`
--

CREATE TABLE `draw_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  `draw_date` date NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `draw_history`
--

INSERT INTO `draw_history` (`id`, `user_id`, `character_id`, `draw_date`, `is_free`, `created_at`) VALUES
(20, 1, 7, '2026-08-31', 1, '2026-08-31 14:32:15'),
(21, 1, 2, '2026-08-31', 0, '2026-08-31 14:32:20'),
(22, 1, 4, '2026-08-31', 0, '2026-08-31 14:32:23'),
(23, 1, 7, '2026-08-31', 0, '2026-08-31 14:32:26'),
(24, 4, 5, '2026-09-01', 1, '2026-09-01 01:48:36'),
(36, 6, 1, '2026-09-01', 1, '2026-09-01 02:04:49'),
(51, 6, 1, '2026-09-01', 0, '2026-09-01 03:00:30'),
(52, 6, 4, '2026-09-01', 0, '2026-09-01 03:00:39'),
(53, 1, 5, '2026-09-01', 1, '2026-09-01 03:03:14'),
(54, 1, 6, '2026-09-01', 0, '2026-09-01 03:03:16'),
(55, 11, 8, '2026-09-03', 1, '2026-09-03 19:59:12'),
(56, 11, 8, '2026-09-03', 0, '2026-09-03 19:59:22'),
(57, 11, 5, '2026-09-03', 0, '2026-09-03 19:59:29'),
(58, 11, 1, '2026-09-03', 0, '2026-09-03 19:59:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `favorite_series` varchar(50) DEFAULT '',
  `favorite_color` varchar(30) DEFAULT '',
  `collecting_purpose` varchar(30) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `password`, `favorite_series`, `favorite_color`, `collecting_purpose`, `created_at`) VALUES
(1, 'Boo', 'pop@gmail.com', '01123456789', 'pop123', 'Hirono', 'pink', 'Gift', '2026-07-20 16:05:41'),
(4, 'qwe', 'qwer@gmail.com', 'we!', '123456', 'Labubu', 'Gay', 'Decoration', '2026-09-03 01:48:07'),
(6, 'k', 'k@gmail.com', 'kkk', 'kkkkkk', 'Dimoo', '', 'Gift', '2026-09-01 02:03:10'),
(11, 'Leo Tan', 'leo@gmail.com', '0123456789', 'leo123', 'Hirono', 'red', 'Gift', '2026-09-03 19:57:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collection`
--
ALTER TABLE `collection`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_character` (`user_id`,`character_id`),
  ADD KEY `character_id` (`character_id`);

--
-- Indexes for table `draw_history`
--
ALTER TABLE `draw_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `character_id` (`character_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `collection`
--
ALTER TABLE `collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `draw_history`
--
ALTER TABLE `draw_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collection`
--
ALTER TABLE `collection`
  ADD CONSTRAINT `collection_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `collection_ibfk_2` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `draw_history`
--
ALTER TABLE `draw_history`
  ADD CONSTRAINT `draw_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `draw_history_ibfk_2` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
