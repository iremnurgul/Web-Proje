-- phpMyAdmin SQL Dump
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_number` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `user_number` (`user_number`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(150) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `quiz_name` varchar(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` enum('multiple_choice','true_false','open_ended') NOT NULL DEFAULT 'multiple_choice',
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 10,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enrollment` (`course_id`,`student_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `attempt_count` int(11) DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `exam_snapshots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `exam_snapshots_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_snapshots_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `status` enum('pending','completed') DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- phpMyAdmin SQL Dump
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `student_answers`, `results`, `notifications`, `logs`, `exam_snapshots`, `attempts`, `enrollments`, `questions`, `quizzes`, `courses`, `users`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `user_number` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `user_number` (`user_number`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_name` varchar(150) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `quiz_name` varchar(200) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` enum('multiple_choice','true_false','open_ended') NOT NULL DEFAULT 'multiple_choice',
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_answer` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 10,
  PRIMARY KEY (`id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_enrollment` (`course_id`,`student_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `attempt_count` int(11) DEFAULT 1,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `attempts_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `exam_snapshots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `exam_snapshots_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_snapshots_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `score` decimal(5,2) DEFAULT 0.00,
  `status` enum('pending','completed') DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `quiz_id` (`quiz_id`),
  CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `results_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `student_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `student_answer` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `result_id` (`result_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `student_answers_ibfk_1` FOREIGN KEY (`result_id`) REFERENCES `results` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed Data

-- Users (1 Admin, 5 Teachers, 5 Students)
INSERT INTO `users` (`id`, `first_name`, `last_name`, `user_number`, `username`, `email`, `password`, `role`) VALUES
(1, 'Admin', 'Kullanıcısı', 9999, 'admin', 'admin@example.com', '$2y$10$91AFLM8OHJoULovwjc/G2uJP4VNmnS.Rm1ZvB4ZO8gTtuJqeiHT7a', 'admin'),
(2, 'Ali', 'Yılmaz', 2001, NULL, NULL, NULL, 'teacher'),
(3, 'Ayşe', 'Demir', 2002, NULL, NULL, NULL, 'teacher'),
(4, 'Mehmet', 'Kaya', 2003, NULL, NULL, NULL, 'teacher'),
(5, 'Fatma', 'Çelik', 2004, NULL, NULL, NULL, 'teacher'),
(6, 'Mustafa', 'Şahin', 2005, NULL, NULL, NULL, 'teacher'),
(7, 'Caner', 'Türk', 1001, NULL, NULL, NULL, 'student'),
(8, 'Elif', 'Yıldız', 1002, NULL, NULL, NULL, 'student'),
(9, 'Burak', 'Arslan', 1003, NULL, NULL, NULL, 'student'),
(10, 'Deniz', 'Koç', 1004, NULL, NULL, NULL, 'student'),
(11, 'Cemre', 'Bulut', 1005, NULL, NULL, NULL, 'student');

-- Courses
INSERT INTO `courses` (`id`, `course_name`, `teacher_id`) VALUES
(1, 'Matematik 101', 2),
(2, 'Fizik 101', 2),
(3, 'Kimya 101', 2),
(4, 'Tarih 101', 3),
(5, 'Coğrafya 101', 3),
(6, 'Felsefe 101', 3),
(7, 'Psikoloji 101', 3),
(8, 'Biyoloji 101', 4),
(9, 'Anatomi 101', 4),
(10, 'Edebiyat 101', 5),
(11, 'İngilizce 101', 6),
(12, 'Almanca 101', 6),
(13, 'Fransızca 101', 6);

-- Enrollments
INSERT INTO `enrollments` (`course_id`, `student_id`) VALUES
(1, 7), (1, 8), (1, 9), (1, 10), (1, 11),
(2, 7), (2, 8), (2, 9), (2, 10), (2, 11),
(3, 7), (3, 8), (3, 9), (3, 10), (3, 11),
(4, 7), (4, 8), (4, 9), (4, 10),
(5, 7), (5, 8), (5, 9), (5, 10),
(6, 7), (6, 8), (6, 9), (6, 10),
(7, 7), (7, 8), (7, 9), (7, 10),
(8, 8), (8, 9), (8, 10), (8, 11),
(9, 8), (9, 9), (9, 10), (9, 11),
(10, 7), (10, 8), (10, 9), (10, 10), (10, 11),
(11, 7), (11, 8), (11, 9), (11, 10), (11, 11),
(12, 7), (12, 8), (12, 9), (12, 10), (12, 11),
(13, 7), (13, 8), (13, 9), (13, 10), (13, 11);



-- Generated Quizzes
INSERT INTO `quizzes` (`id`, `course_id`, `quiz_name`, `start_date`, `end_date`, `duration`, `is_active`) VALUES 
(1, 1, 'Matematik 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(2, 2, 'Fizik 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(3, 3, 'Kimya 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(4, 4, 'Tarih 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(5, 5, 'Coğrafya 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(6, 6, 'Felsefe 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(7, 7, 'Psikoloji 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(8, 8, 'Biyoloji 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(9, 9, 'Anatomi 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(10, 10, 'Edebiyat 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(11, 11, 'İngilizce 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(12, 12, 'Almanca 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1),
(13, 13, 'Fransızca 101 Quiz 1', '2026-05-11 16:00:34', '2026-06-10 16:00:34', 30, 1);

-- Generated Questions
INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `image_path`, `type`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_answer`, `points`) VALUES 
(1, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 1)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(2, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 2)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(3, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 3)', 'uploads/questions/math_graph.jpg', 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(4, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 4)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(5, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 5)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(6, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 6)', 'uploads/questions/math_graph.jpg', 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(7, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 7)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(8, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 8)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(9, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 9)', 'uploads/questions/math_graph.jpg', 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(10, 1, 'f(x) = 2x^2 + 3x - 5 fonksiyonunun kökleri toplamı kaçtır? (Soru 10)', NULL, 'multiple_choice', '-1.5', '1.5', '2.5', '-2.5', 'A', 10),
(11, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 1)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(12, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 2)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(13, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 3)', 'uploads/questions/physics_vector.jpg', 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(14, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 4)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(15, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 5)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(16, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 6)', 'uploads/questions/physics_vector.jpg', 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(17, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 7)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(18, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 8)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(19, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 9)', 'uploads/questions/physics_vector.jpg', 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(20, 2, 'Kütlesi 5 kg olan bir cisme 20 N luk net kuvvet etki ederse ivmesi kaç m/s^2 olur? (Soru 10)', NULL, 'multiple_choice', '2', '4', '5', '100', 'B', 10),
(21, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 1)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(22, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 2)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(23, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 3)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(24, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 4)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(25, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 5)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(26, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 6)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(27, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 7)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(28, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 8)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(29, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 9)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(30, 3, 'Periyodik cetvelde 1A grubunda bulunan elementlerin genel adı nedir? (Soru 10)', NULL, 'multiple_choice', 'Alkali metaller', 'Toprak alkali metaller', 'Halojenler', 'Soygazlar', 'A', 10),
(31, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 1)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(32, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 2)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(33, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 3)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(34, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 4)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(35, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 5)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(36, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 6)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(37, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 7)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(38, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 8)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(39, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 9)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(40, 4, 'Osmanlı İmparatorluğu nun kurucusu kimdir? (Soru 10)', NULL, 'multiple_choice', 'Orhan Bey', 'Ertuğrul Gazi', 'Osman Bey', 'Fatih Sultan Mehmet', 'C', 10),
(41, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 1)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(42, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 2)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(43, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 3)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(44, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 4)', 'uploads/questions/geo_map.jpg', 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(45, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 5)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(46, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 6)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(47, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 7)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(48, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 8)', 'uploads/questions/geo_map.jpg', 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(49, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 9)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(50, 5, 'Türkiye nin en yüksek dağı hangisidir? (Soru 10)', NULL, 'multiple_choice', 'Erciyes', 'Ağrı', 'Süphan', 'Uludağ', 'B', 10),
(51, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 1)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(52, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 2)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(53, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 3)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(54, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 4)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(55, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 5)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(56, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 6)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(57, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 7)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(58, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 8)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(59, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 9)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(60, 6, ' Düşünüyorum, öyleyse varım (Cogito ergo sum) sözü hangi filozofa aittir? (Soru 10)', NULL, 'multiple_choice', 'Platon', 'Aristoteles', 'René Descartes', 'Immanuel Kant', 'C', 10),
(61, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 1)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(62, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 2)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(63, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 3)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(64, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 4)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(65, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 5)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(66, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 6)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(67, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 7)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(68, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 8)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(69, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 9)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(70, 7, 'Psikanaliz kuramının kurucusu kimdir? (Soru 10)', NULL, 'multiple_choice', 'Carl Jung', 'Sigmund Freud', 'Ivan Pavlov', 'B.F. Skinner', 'B', 10),
(71, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 1)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(72, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 2)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(73, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 3)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(74, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 4)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(75, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 5)', 'uploads/questions/cell_structure.jpg', 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(76, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 6)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(77, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 7)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(78, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 8)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(79, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 9)', NULL, 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(80, 8, 'Hücrenin enerji santrali olarak bilinen organeli hangisidir? (Soru 10)', 'uploads/questions/cell_structure.jpg', 'multiple_choice', 'Mitokondri', 'Ribozom', 'Lizozom', 'Golgi Cisimciği', 'A', 10),
(81, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 1)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(82, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 2)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(83, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 3)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(84, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 4)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(85, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 5)', 'uploads/questions/skeleton.jpg', 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(86, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 6)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(87, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 7)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(88, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 8)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(89, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 9)', NULL, 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(90, 9, 'İnsan vücudundaki en uzun kemik hangisidir? (Soru 10)', 'uploads/questions/skeleton.jpg', 'multiple_choice', 'Kaval kemiği', 'Uyluk (Femur) kemiği', 'Pazı kemiği', 'Kaval kemiği', 'B', 10),
(91, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 1)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(92, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 2)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(93, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 3)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(94, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 4)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(95, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 5)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(96, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 6)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(97, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 7)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(98, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 8)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(99, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 9)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(100, 10, 'İstiklal Marşı nın yazarı kimdir? (Soru 10)', NULL, 'multiple_choice', 'Orhan Veli Kanık', 'Yahya Kemal Beyatlı', 'Mehmet Akif Ersoy', 'Nazım Hikmet', 'C', 10),
(101, 11, 'Choose the correct form: She _____ to school every day. (Soru 1)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(102, 11, 'Choose the correct form: She _____ to school every day. (Soru 2)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(103, 11, 'Choose the correct form: She _____ to school every day. (Soru 3)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(104, 11, 'Choose the correct form: She _____ to school every day. (Soru 4)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(105, 11, 'Choose the correct form: She _____ to school every day. (Soru 5)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(106, 11, 'Choose the correct form: She _____ to school every day. (Soru 6)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(107, 11, 'Choose the correct form: She _____ to school every day. (Soru 7)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(108, 11, 'Choose the correct form: She _____ to school every day. (Soru 8)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(109, 11, 'Choose the correct form: She _____ to school every day. (Soru 9)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(110, 11, 'Choose the correct form: She _____ to school every day. (Soru 10)', NULL, 'multiple_choice', 'go', 'goes', 'going', 'gone', 'B', 10),
(111, 12, 'Guten Morgen ne demektir? (Soru 1)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(112, 12, 'Guten Morgen ne demektir? (Soru 2)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(113, 12, 'Guten Morgen ne demektir? (Soru 3)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(114, 12, 'Guten Morgen ne demektir? (Soru 4)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(115, 12, 'Guten Morgen ne demektir? (Soru 5)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(116, 12, 'Guten Morgen ne demektir? (Soru 6)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(117, 12, 'Guten Morgen ne demektir? (Soru 7)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(118, 12, 'Guten Morgen ne demektir? (Soru 8)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(119, 12, 'Guten Morgen ne demektir? (Soru 9)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(120, 12, 'Guten Morgen ne demektir? (Soru 10)', NULL, 'multiple_choice', 'İyi akşamlar', 'Günaydın', 'İyi geceler', 'İyi günler', 'B', 10),
(121, 13, 'Bonjour ne demektir? (Soru 1)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(122, 13, 'Bonjour ne demektir? (Soru 2)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(123, 13, 'Bonjour ne demektir? (Soru 3)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(124, 13, 'Bonjour ne demektir? (Soru 4)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(125, 13, 'Bonjour ne demektir? (Soru 5)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(126, 13, 'Bonjour ne demektir? (Soru 6)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(127, 13, 'Bonjour ne demektir? (Soru 7)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(128, 13, 'Bonjour ne demektir? (Soru 8)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(129, 13, 'Bonjour ne demektir? (Soru 9)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10),
(130, 13, 'Bonjour ne demektir? (Soru 10)', NULL, 'multiple_choice', 'Günaydın/Merhaba', 'Nasılsın', 'Hoşça kal', 'Teşekkürler', 'A', 10);

COMMIT;
