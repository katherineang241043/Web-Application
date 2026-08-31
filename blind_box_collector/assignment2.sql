-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 31, 2026 at 04:40 PM
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
(17, 1, 4, 1, '2026-08-31 14:32:23', '2026-08-31 14:32:23');

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
(23, 1, 7, '2026-08-31', 0, '2026-08-31 14:32:26');

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
(1, 'Boo', 'pop@gmail.com', '01123456789', 'pop123', 'Hirono', 'pink', 'Gift', '2026-07-20 16:05:41');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `draw_history`
--
ALTER TABLE `draw_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
