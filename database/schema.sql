-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 May 2026, 14:20:18
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `quiz_system_db`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `attempts`
--

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `attempt_count` int(11) DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `attempts`
--

INSERT INTO `attempts` (`id`, `student_id`, `quiz_id`, `attempt_count`, `ip_address`, `user_agent`, `last_activity`) VALUES
(1, 2, 1, 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-04 07:20:29'),
(2, 2, 2, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 20:35:00'),
(3, 2, 4, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-06 06:17:01'),
(4, 2, 5, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-07 10:39:10');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `course_name` varchar(150) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `courses`
--

INSERT INTO `courses` (`id`, `course_name`, `teacher_id`, `created_at`) VALUES
(1, 'MATEMATİK', 3, '2026-05-03 19:29:47'),
(2, 'FİZİK', 3, '2026-05-03 20:11:19'),
(3, 'kimya', 3, '2026-05-07 10:33:16');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `enrollments`
--

INSERT INTO `enrollments` (`id`, `course_id`, `student_id`, `enrolled_at`) VALUES
(1, 1, 2, '2026-05-03 19:33:50'),
(5, 2, 2, '2026-05-03 20:13:02'),
(6, 3, 2, '2026-05-07 10:38:57');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `exam_snapshots`
--

CREATE TABLE `exam_snapshots` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `exam_snapshots`
--

INSERT INTO `exam_snapshots` (`id`, `student_id`, `quiz_id`, `image_path`, `created_at`) VALUES
(4, 2, 4, 'uploads/snapshots/2_4_1778048225.jpg', '2026-05-06 06:17:05'),
(5, 2, 4, 'uploads/snapshots/2_4_1778048253.jpg', '2026-05-06 06:17:33');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` enum('multiple_choice','true_false','open_ended') NOT NULL DEFAULT 'multiple_choice',
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `image_path`, `type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `points`) VALUES
(1, 1, '2+2', NULL, 'multiple_choice', '1', '2', '3', '4', 'A', 10),
(2, 1, '3*5', NULL, 'multiple_choice', '3', '5', '35', '15', 'D', 10),
(3, 2, 'AAAAAAAAAA', NULL, 'multiple_choice', 'A', 'B', 'C', 'D', 'A', 10),
(4, 2, 'BBBBBBBB', NULL, 'multiple_choice', 'A', 'B', 'C', 'D', 'B', 20),
(5, 3, 'aaaa', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'A', 10),
(6, 3, 'bbbbbbbb', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'B', 10),
(7, 3, 'cccccccc', NULL, 'multiple_choice', 'aaaaaaa', 'bbbbbbbbbb', 'ccccccccc', 'ddddddddd', 'C', 10),
(8, 4, 'cevabı nedir', 'uploads/questions/q_img_69fadc5266923.jpg', 'multiple_choice', 'a', 'b', 'c', 'c', 'A', 10),
(9, 4, 'aaaaa', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'A', 10),
(10, 4, 'bbbbbb', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'A', 10),
(11, 4, 'dfg', NULL, 'multiple_choice', 'a', 'b', 'dw', 'cwdc', 'A', 10),
(12, 4, 'qwecfvdf', NULL, 'multiple_choice', 'd', 'd', 'd', 'd', 'A', 10),
(13, 4, 'qqqq', NULL, 'multiple_choice', 'z', 'x', 'x', 'x', 'A', 10),
(14, 4, 'ccc', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'A', 10),
(15, 5, 'sdfgh', NULL, 'multiple_choice', 'a', 'b', 'c', 'd', 'A', 10),
(16, 5, 'aaaa', 'uploads/questions/q_img_69fc6ac99302c.jpg', 'multiple_choice', 'a', 'b', 'x', 'jbj', 'A', 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `quiz_name` varchar(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `quiz_name`, `start_date`, `end_date`, `duration`, `is_active`, `created_at`) VALUES
(1, 1, 'Quiz 1', '2026-05-04 08:30:00', '2026-05-05 22:30:00', 30, 1, '2026-05-03 19:30:32'),
(2, 2, 'fizik quiz 1', '2026-05-01 23:11:00', '2026-05-04 23:11:00', 30, 1, '2026-05-03 20:11:53'),
(3, 2, 'fizik quiz 1', '2026-05-06 08:49:00', '2026-05-22 08:49:00', 30, 1, '2026-05-06 05:50:09'),
(4, 1, 'matematik quiz 2', '2026-05-06 09:12:00', '2026-05-07 09:12:00', 30, 1, '2026-05-06 06:13:04'),
(5, 3, 'kimya1', '2026-05-07 13:33:00', '2026-05-07 13:33:00', 30, 1, '2026-05-07 10:34:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `status` enum('pending','completed') DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `results`
--

INSERT INTO `results` (`id`, `student_id`, `quiz_id`, `score`, `status`, `completed_at`) VALUES
(1, 2, 1, 0.00, 'completed', '2026-05-03 19:38:39'),
(2, 2, 1, 50.00, 'completed', '2026-05-03 19:41:11'),
(3, 2, 2, 100.00, 'completed', '2026-05-03 20:13:44'),
(4, 2, 2, 0.00, 'completed', '2026-05-03 20:15:38'),
(5, 2, 1, 0.00, 'completed', '2026-05-03 20:17:37'),
(6, 2, 2, 0.00, 'completed', '2026-05-03 20:20:10'),
(7, 2, 2, 0.00, 'completed', '2026-05-03 20:24:52'),
(8, 2, 2, 33.33, 'completed', '2026-05-03 20:37:16'),
(9, 2, 4, 57.14, 'completed', '2026-05-06 06:17:49'),
(10, 2, 5, 50.00, 'completed', '2026-05-07 10:40:32');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `student_answers`
--

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL,
  `result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `student_answer` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `student_answers`
--

INSERT INTO `student_answers` (`id`, `result_id`, `question_id`, `student_answer`, `is_correct`) VALUES
(1, 1, 1, 'D', 0),
(2, 1, 2, 'A', 0),
(3, 2, 1, 'D', 0),
(4, 2, 2, 'D', 1),
(5, 3, 3, 'A', 1),
(6, 3, 4, 'B', 1),
(7, 4, 3, NULL, 0),
(8, 4, 4, NULL, 0),
(9, 5, 1, NULL, 0),
(10, 5, 2, NULL, 0),
(11, 6, 3, NULL, 0),
(12, 6, 4, NULL, 0),
(13, 7, 3, NULL, 0),
(14, 7, 4, NULL, 0),
(15, 8, 3, 'A', 1),
(16, 8, 4, 'C', 0),
(17, 9, 8, 'A', 1),
(18, 9, 9, 'D', 0),
(19, 9, 10, 'B', 0),
(20, 9, 11, 'A', 1),
(21, 9, 12, 'A', 1),
(22, 9, 13, 'A', 1),
(23, 9, 14, 'C', 0),
(24, 10, 15, 'A', 1),
(25, 10, 16, NULL, 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `last_login`) VALUES
(1, 'admin', 'admin@quizsystem.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-03 19:13:51', NULL),
(2, 'irem', 'iremngull@gmail.com', '$2y$10$8Px0J8HyQMIZQM.68NuebOLcmDOZsm7SY9P2n56yMb2xYtWj.YqMq', 'student', '2026-05-03 19:21:20', '2026-05-07 10:38:50'),
(3, 'teacher_1', 'teacher@quizsystem.local', '$2y$10$/kmawQ8/C7qFhUtvKnzmmu7aGFCm6en7vaPhOgAlQkVk8NAPPG0OS', 'teacher', '2026-05-03 19:28:32', '2026-05-07 10:40:46');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Tablo için indeksler `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Tablo için indeksler `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_enrollment` (`course_id`,`student_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Tablo için indeksler `exam_snapshots`
--
ALTER TABLE `exam_snapshots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Tablo için indeksler `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Tablo için indeksler `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Tablo için indeksler `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Tablo için indeksler `student_answers`
--
ALTER TABLE `student_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `result_id` (`result_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Tablo için AUTO_INCREMENT değeri `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Tablo için AUTO_INCREMENT değeri `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `exam_snapshots`
--
ALTER TABLE `exam_snapshots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Tablo için AUTO_INCREMENT değeri `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `student_answers`
--
ALTER TABLE `student_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `attempts`
--
ALTER TABLE `attempts`
  ADD CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `exam_snapshots`
--
ALTER TABLE `exam_snapshots`
  ADD CONSTRAINT `exam_snapshots_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_snapshots_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `student_answers`
--
ALTER TABLE `student_answers`
  ADD CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
