-- Veritabanı oluşturma
CREATE DATABASE IF NOT EXISTS quiz_system_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quiz_system_db;

-- Kullanıcılar (Users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Dersler (Courses)
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(150) NOT NULL,
    teacher_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Ders Kayıtları (Course Enrollments - Öğrencilerin derslere kaydı)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (course_id, student_id)
);

-- Sınavlar (Quizzes)
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    quiz_name VARCHAR(200) NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    duration INT NOT NULL, -- Dakika cinsinden
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Sorular (Questions)
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    type ENUM('multiple_choice', 'true_false', 'open_ended') NOT NULL DEFAULT 'multiple_choice',
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_answer VARCHAR(255) NOT NULL,
    points INT DEFAULT 10,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Öğrenci Sonuçları (Results)
CREATE TABLE IF NOT EXISTS results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score DECIMAL(5,2) DEFAULT 0.00,
    status ENUM('pending', 'completed') DEFAULT 'pending',
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Öğrenci Cevapları (Student Answers - Detaylı analiz için)
CREATE TABLE IF NOT EXISTS student_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    result_id INT NOT NULL,
    question_id INT NOT NULL,
    student_answer TEXT,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (result_id) REFERENCES results(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

-- Denemeler (Attempts - Sınav güvenliği ve giriş takibi)
CREATE TABLE IF NOT EXISTS attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    quiz_id INT NOT NULL,
    attempt_count INT DEFAULT 1,
    ip_address VARCHAR(45),
    user_agent TEXT,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Bildirimler (Notifications)
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sistem Logları (Logs)
CREATE TABLE IF NOT EXISTS logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    details TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Örnek bir admin ekleme (Şifre: Admin123! - Bcrypt ile hashlenmiş hali)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@quizsystem.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
