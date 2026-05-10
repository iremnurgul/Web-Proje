<?php
// app/Controllers/Teacher.php

require_once '../app/Helpers/Middleware.php';
require_once '../app/Helpers/Security.php';

class Teacher extends Controller {
    
    private $courseModel;
    private $quizModel;
    private $questionModel;

    public function __construct() {
        Middleware::teacher();
        $this->courseModel = $this->model('Course');
        $this->quizModel = $this->model('Quiz');
        $this->questionModel = $this->model('Question');
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $courses = $this->courseModel->getCoursesByTeacher($_SESSION['user_id']);
        $data = [
            'title' => 'Teacher Dashboard',
            'courses' => $courses
        ];
        $this->view('teacher/dashboard', $data);
    }

    public function courses() {
        $courses = $this->courseModel->getCoursesByTeacher($_SESSION['user_id']);
        
        $data = [
            'courses' => $courses
        ];
        $this->view('teacher/courses', $data);
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
                $data['error'] = 'Åifreler eÅŸleÅŸmiyor.';
            }

            if(empty($data['error'])) {
                if(!empty($data['password'])) {
                    $data['password'] = Security::hashPassword($data['password']);
                }
                
                if($userModel->updateProfile($data)) {
                    $_SESSION['user_first_name'] = $data['first_name'];
                    $_SESSION['user_last_name'] = $data['last_name'];
                    Session::flash('profile_success', 'Profiliniz baÅŸarÄ±yla gÃ¼ncellendi.');
                    header('Location: ' . URLROOT . '/teacher/profile');
                    exit;
                } else {
                    $data['error'] = 'GÃ¼ncelleme sÄ±rasÄ±nda bir hata oluÅŸtu.';
                }
            }
            
            $data['user'] = $user;
            $this->view('teacher/profile', $data);
        } else {
            $data = [
                'user' => $user,
                'error' => ''
            ];
            $this->view('teacher/profile', $data);
        }
    }


    public function courseDetails($course_id) {
        $db = new Database();
        
        // Ensure this course belongs to the logged-in teacher
        $db->query('SELECT * FROM courses WHERE id = :id AND teacher_id = :teacher_id');
        $db->bind(':id', $course_id);
        $db->bind(':teacher_id', $_SESSION['user_id']);
        $course = $db->single();

        if (!$course) {
            die('Course not found or unauthorized');
        }

        // Get Enrolled Students
        $db->query('
            SELECT u.id, u.username, u.email, e.enrolled_at 
            FROM enrollments e 
            JOIN users u ON e.student_id = u.id 
            WHERE e.course_id = :course_id
            ORDER BY e.enrolled_at DESC
        ');
        $db->bind(':course_id', $course_id);
        $students = $db->resultSet();

        // Get Quizzes for this course
        $db->query('SELECT * FROM quizzes WHERE course_id = :course_id ORDER BY created_at DESC');
        $db->bind(':course_id', $course_id);
        $quizzes = $db->resultSet();

        $data = [
            'course' => $course,
            'students' => $students,
            'quizzes' => $quizzes
        ];

        $this->view('teacher/course_details', $data);
    }

    public function quizzes() {
        $quizzes = $this->quizModel->getQuizzesByTeacher($_SESSION['user_id']);
        $courses = $this->courseModel->getCoursesByTeacher($_SESSION['user_id']);
        
        $data = [
            'quizzes' => $quizzes,
            'courses' => $courses
        ];
        $this->view('teacher/quizzes', $data);
    }

    public function addQuiz() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'CSRF Token Failed']);
                exit;
            }

            $_POST = Security::sanitize($_POST);

            $data = [
                'course_id' => trim($_POST['course_id']),
                'quiz_name' => trim($_POST['quiz_name']),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'duration' => trim($_POST['duration']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0
            ];

            if ($quiz_id = $this->quizModel->addQuiz($data)) {
                echo json_encode(['success' => true, 'message' => 'Quiz added successfully', 'quiz_id' => $quiz_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add quiz']);
            }
        }
    }

    public function deleteQuiz($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'CSRF Token Failed']);
                exit;
            }
            
            // Check ownership
            $quiz = $this->quizModel->getQuizById($id);
            if ($quiz) {
                // Ensure teacher owns it
                $db = new Database();
                $db->query('SELECT * FROM courses WHERE id = :course_id AND teacher_id = :teacher_id');
                $db->bind(':course_id', $quiz->course_id);
                $db->bind(':teacher_id', $_SESSION['user_id']);
                $course = $db->single();
                
                if ($course) {
                    if ($this->quizModel->deleteQuiz($id)) {
                        echo json_encode(['success' => true, 'message' => 'Sınav başarıyla silindi']);
                        return;
                    }
                }
            }
            echo json_encode(['success' => false, 'message' => 'Silme işlemi başarısız veya yetkisiz']);
        }
    }

    public function questions() {
        $quizzes = $this->quizModel->getQuizzesByTeacher($_SESSION['user_id']);
        
        $data = [
            'quizzes' => $quizzes
        ];
        $this->view('teacher/questions', $data);
    }

    public function addQuestion() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'CSRF Token Failed']);
                exit;
            }

            $_POST = Security::sanitize($_POST);

            $image_path = null;
            if (isset($_FILES['question_image']) && $_FILES['question_image']['error'] == 0) {
                $upload_dir = dirname(dirname(dirname(__FILE__))) . '/public/uploads/questions/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['question_image']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid('q_img_') . '.' . $file_extension;
                $target_file = $upload_dir . $file_name;
                
                if (move_uploaded_file($_FILES['question_image']['tmp_name'], $target_file)) {
                    $image_path = 'uploads/questions/' . $file_name;
                }
            }

            $data = [
                'quiz_id' => trim($_POST['quiz_id']),
                'question_text' => trim($_POST['question_text']),
                'image_path' => $image_path,
                'type' => 'multiple_choice', // For now, default
                'option_a' => trim($_POST['option_a']),
                'option_b' => trim($_POST['option_b']),
                'option_c' => trim($_POST['option_c']),
                'option_d' => trim($_POST['option_d']),
                'correct_answer' => trim($_POST['correct_answer']),
                'points' => trim($_POST['points'])
            ];

            if ($this->questionModel->addQuestion($data)) {
                echo json_encode(['success' => true, 'message' => 'Question added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add question']);
            }
        }
    }

    public function manageQuestions($quiz_id) {
        $db = new Database();
        
        $db->query('SELECT * FROM quizzes WHERE id = :id');
        $db->bind(':id', $quiz_id);
        $quiz = $db->single();

        if(!$quiz) die('Quiz not found');

        $questions = $this->questionModel->getQuestionsByQuiz($quiz_id);

        $data = [
            'title' => 'Manage Questions',
            'quiz' => $quiz,
            'questions' => $questions
        ];

        $this->view('teacher/manage_questions', $data);
    }

    public function deleteSnapshot($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database();
            $db->query('SELECT image_path FROM exam_snapshots WHERE id = :id');
            $db->bind(':id', $id);
            $snap = $db->single();

            if($snap) {
                $filePath = dirname(dirname(dirname(__FILE__))) . '/public/' . $snap->image_path;
                if(file_exists($filePath)) {
                    unlink($filePath);
                }
                
                $db->query('DELETE FROM exam_snapshots WHERE id = :id');
                $db->bind(':id', $id);
                if($db->execute()) {
                    echo json_encode(['success' => true]);
                    return;
                }
            }
            echo json_encode(['success' => false]);
        }
    }

    public function quizResults($quiz_id) {
        $db = new Database();
        
        $db->query('SELECT quiz_name FROM quizzes WHERE id = :id');
        $db->bind(':id', $quiz_id);
        $quiz = $db->single();

        $db->query('
            SELECT r.*, u.username, u.email 
            FROM results r
            JOIN users u ON r.student_id = u.id
            WHERE r.quiz_id = :quiz_id
            ORDER BY r.score DESC
        ');
        $db->bind(':quiz_id', $quiz_id);
        $results = $db->resultSet();

        $data = [
            'quiz' => $quiz,
            'results' => $results,
            'quiz_id' => $quiz_id
        ];

        $this->view('teacher/quiz_results', $data);
    }

    public function snapshots($quiz_id, $student_id) {
        $db = new Database();
        
        // Get quiz info
        $db->query('SELECT quiz_name FROM quizzes WHERE id = :id');
        $db->bind(':id', $quiz_id);
        $quiz = $db->single();

        // Get student info
        $db->query('SELECT username FROM users WHERE id = :id');
        $db->bind(':id', $student_id);
        $student = $db->single();

        // Get snapshots
        $db->query('SELECT * FROM exam_snapshots WHERE quiz_id = :quiz_id AND student_id = :student_id ORDER BY created_at ASC');
        $db->bind(':quiz_id', $quiz_id);
        $db->bind(':student_id', $student_id);
        $snapshots = $db->resultSet();

        $data = [
            'title' => 'Proctoring Snapshots',
            'quiz' => $quiz,
            'student' => $student,
            'snapshots' => $snapshots
        ];

        $this->view('teacher/snapshots', $data);
    }

    public function bulkAddQuestions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!Security::verifyCsrfToken($_POST['csrf_token'])) {
                echo json_encode(['success' => false, 'message' => 'CSRF Token Failed']);
                exit;
            }

            $quiz_id = $_POST['quiz_id'];
            $question_texts = $_POST['question_text'];
            
            $successCount = 0;
            
            for ($i = 0; $i < count($question_texts); $i++) {
                $image_path = null;
                if (isset($_FILES['images']['name'][$i]) && $_FILES['images']['error'][$i] == 0) {
                    $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '_' . time() . '.' . $ext;
                    $targetDir = 'uploads/questions/';
                    
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    $targetFile = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $targetFile)) {
                        $image_path = 'uploads/questions/' . $fileName;
                    }
                }
                
                $data = [
                    'quiz_id' => trim($quiz_id),
                    'question_text' => trim($_POST['question_text'][$i]),
                    'type' => 'multiple_choice',
                    'option_a' => trim($_POST['option_a'][$i]),
                    'option_b' => trim($_POST['option_b'][$i]),
                    'option_c' => trim($_POST['option_c'][$i]),
                    'option_d' => trim($_POST['option_d'][$i]),
                    'correct_answer' => trim($_POST['correct_answer'][$i]),
                    'points' => trim($_POST['points'][$i]),
                    'image_path' => $image_path
                ];
                
                $data = Security::sanitize($data);
                
                if ($this->questionModel->addQuestion($data)) {
                    $successCount++;
                }
            }
            
            echo json_encode(['success' => true, 'message' => "$successCount questions added successfully."]);
        }
    }
}

