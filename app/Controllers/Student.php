<?php
// app/Controllers/Student.php

require_once '../app/Helpers/Middleware.php';
require_once '../app/Helpers/Security.php';

class Student extends Controller {
    
    private $courseModel;
    private $quizModel;
    private $questionModel;
    private $resultModel;

    public function __construct() {
        Middleware::student();
        $this->courseModel = $this->model('Course');
        $this->quizModel = $this->model('Quiz');
        $this->questionModel = $this->model('Question');
        $this->resultModel = $this->model('Result');
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $db = new Database();
        $db->query('
            SELECT c.*, u.username as teacher_name 
            FROM courses c 
            JOIN enrollments e ON c.id = e.course_id 
            JOIN users u ON c.teacher_id = u.id
            WHERE e.student_id = :student_id
        ');
        $db->bind(':student_id', $_SESSION['user_id']);
        $my_courses = $db->resultSet();

        $active_quizzes = $this->quizModel->getActiveQuizzesForStudent($_SESSION['user_id']);

        $data = [
            'title' => 'Student Dashboard',
            'courses' => $my_courses,
            'quizzes' => $active_quizzes
        ];
        $this->view('student/dashboard', $data);
    }

    public function profile() {
        $userModel = $this->model('User');
        $user = $userModel->getUserById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = Security::sanitize($_POST);
            $data = [
                'id' => $_SESSION['user_id'],
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'error' => ''
            ];

            if(!empty($data['password']) && $data['password'] !== $data['confirm_password']) {
                $data['error'] = 'Şifreler eşleşmiyor.';
            }

            if(empty($data['error'])) {
                if(!empty($data['password'])) {
                    $data['password'] = Security::hashPassword($data['password']);
                }
                
                if($userModel->updateProfile($data)) {
                    $_SESSION['user_first_name'] = $data['first_name'];
                    $_SESSION['user_last_name'] = $data['last_name'];
                    Session::flash('profile_success', 'Profiliniz başarıyla güncellendi.');
                    header('Location: ' . URLROOT . '/student/profile');
                    exit;
                } else {
                    $data['error'] = 'Güncelleme sırasında bir hata oluştu.';
                }
            }
            
            $data['user'] = $user;
            $this->view('student/profile', $data);
        } else {
            $data = [
                'user' => $user,
                'error' => ''
            ];
            $this->view('student/profile', $data);
        }
    }

    public function results() {
        $db = new Database();
        $db->query('
            SELECT r.*, q.quiz_name, c.course_name
            FROM results r
            JOIN quizzes q ON r.quiz_id = q.id
            JOIN courses c ON q.course_id = c.id
            WHERE r.student_id = :student_id
            ORDER BY r.completed_at DESC
        ');
        $db->bind(':student_id', $_SESSION['user_id']);
        $my_results = $db->resultSet();
        
        $data = [
            'results' => $my_results
        ];
        $this->view('student/results', $data);
    }

    public function courses() {
        $all_courses = $this->courseModel->getAllCourses();
        
        // Get enrolled courses for student
        $db = new Database();
        $db->query('SELECT course_id FROM enrollments WHERE student_id = :student_id');
        $db->bind(':student_id', $_SESSION['user_id']);
        $enrollments = $db->resultSet();
        
        $enrolled_ids = [];
        foreach($enrollments as $e) {
            $enrolled_ids[] = $e->course_id;
        }
        
        $data = [
            'courses' => $all_courses,
            'enrolled_ids' => $enrolled_ids
        ];
        $this->view('student/courses', $data);
    }

    public function enroll() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $course_id = $_POST['course_id'];
            $student_id = $_SESSION['user_id'];

            // Normally we'd have an Enrollment model. For speed, using a direct DB call in controller (not best practice but works for now)
            $db = new Database();
            try {
                $db->query('INSERT INTO enrollments (course_id, student_id) VALUES (:course_id, :student_id)');
                $db->bind(':course_id', $course_id);
                $db->bind(':student_id', $student_id);
                $db->execute();
                echo json_encode(['success' => true, 'message' => 'Enrolled successfully']);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Already enrolled or error occurred']);
            }
        }
    }

    public function courseDetails($course_id) {
        $db = new Database();
        
        // Get Course Info
        $db->query('SELECT c.*, u.username as teacher_name FROM courses c JOIN users u ON c.teacher_id = u.id WHERE c.id = :id');
        $db->bind(':id', $course_id);
        $course = $db->single();

        if (!$course) {
            die('Course not found');
        }

        // Get Active Quizzes for this course that student hasn't taken
        $db->query('
            SELECT q.* 
            FROM quizzes q 
            WHERE q.course_id = :course_id 
            AND q.is_active = 1 
            AND q.id NOT IN (SELECT quiz_id FROM results WHERE student_id = :student_id)
            ORDER BY q.end_date ASC
        ');
        $db->bind(':course_id', $course_id);
        $db->bind(':student_id', $_SESSION['user_id']);
        $active_quizzes = $db->resultSet();

        // Get Student Results for this course
        $db->query('
            SELECT r.*, q.quiz_name
            FROM results r
            JOIN quizzes q ON r.quiz_id = q.id
            WHERE r.student_id = :student_id AND q.course_id = :course_id
            ORDER BY r.completed_at DESC
        ');
        $db->bind(':student_id', $_SESSION['user_id']);
        $db->bind(':course_id', $course_id);
        $results = $db->resultSet();

        $data = [
            'course' => $course,
            'active_quizzes' => $active_quizzes,
            'results' => $results
        ];

        $this->view('student/course_details', $data);
    }

    public function quizzes() {
        $quizzes = $this->quizModel->getActiveQuizzesForStudent($_SESSION['user_id']);
        
        $data = [
            'quizzes' => $quizzes
        ];
        $this->view('student/quizzes', $data);
    }

    public function takeQuiz($quiz_id) {
        $quiz = $this->quizModel->getQuizById($quiz_id);
        
        if (!$quiz || !$quiz->is_active) {
            die('Quiz not found or not active.');
        }

        if ($this->resultModel->hasCompleted($_SESSION['user_id'], $quiz_id)) {
            die('You have already completed this quiz.');
        }

        // Log attempt
        $ip = $_SERVER['REMOTE_ADDR'];
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $this->resultModel->logAttempt($_SESSION['user_id'], $quiz_id, $ip, $ua);

        $questions = $this->questionModel->getQuestionsByQuiz($quiz_id);

        $data = [
            'quiz' => $quiz,
            'questions' => $questions,
            'strict_mode' => getenv('STRICT_EXAM_MODE') === 'true' // Toggleable security
        ];

        $this->view('student/take_quiz', $data);
    }

    public function submitQuiz() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $quiz_id = $_POST['quiz_id'];
            $student_id = $_SESSION['user_id'];
            $answers = $_POST['answers'] ?? []; // Array of question_id => answer

            $questions = $this->questionModel->getQuestionsByQuiz($quiz_id);
            
            $total_score = 0;
            $max_score = 0;

            // Database instance for getting last result ID
            $db = new Database();

            foreach ($questions as $question) {
                $max_score += $question->points;
                $is_correct = false;
                
                if (isset($answers[$question->id])) {
                    if ($answers[$question->id] === $question->correct_answer) {
                        $total_score += $question->points;
                        $is_correct = true;
                    } else {
                        // Negatif puanlama (Optional logic: deduct points)
                        // $total_score -= ($question->points * 0.25);
                    }
                }
            }

            // Normalize score to 100
            $final_score = ($max_score > 0) ? ($total_score / $max_score) * 100 : 0;
            $final_score = round(max(0, $final_score) / 10) * 10; // En yakın 10'un katına yuvarla

            if ($this->resultModel->saveResult($student_id, $quiz_id, $final_score)) {
                
                $result_id = $db->lastInsertId(); // Getting result ID

                // Save individual answers
                foreach ($questions as $question) {
                    $ans = $answers[$question->id] ?? null;
                    $is_correct = ($ans === $question->correct_answer);
                    $this->resultModel->saveAnswer($result_id, $question->id, $ans, $is_correct);
                }

                echo json_encode(['success' => true, 'score' => $final_score]);
            } else {
                echo json_encode(['success' => false, 'message' => 'You have already completed this quiz.']);
            }
        }
    }

    public function uploadSnapshot() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['image']) && isset($_POST['quiz_id'])) {
            $image_parts = explode(";base64,", $_POST['image']);
            if(count($image_parts) != 2) {
                echo json_encode(['success' => false, 'message' => 'Invalid image format']);
                return;
            }
            
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_base64 = base64_decode($image_parts[1]);
            
            $fileName = $_SESSION['user_id'] . '_' . $_POST['quiz_id'] . '_' . time() . '.jpg';
            $fileDir = dirname(dirname(dirname(__FILE__))) . '/public/uploads/snapshots/';
            $filePath = $fileDir . $fileName;
            
            if(file_put_contents($filePath, $image_base64)) {
                $db = new Database();
                $db->query('INSERT INTO exam_snapshots (student_id, quiz_id, image_path) VALUES (:student_id, :quiz_id, :image_path)');
                $db->bind(':student_id', $_SESSION['user_id']);
                $db->bind(':quiz_id', $_POST['quiz_id']);
                $db->bind(':image_path', 'uploads/snapshots/' . $fileName);
                $db->execute();
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save image.']);
            }
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
