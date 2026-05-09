<?php
$filesToFix = [
    'c:/xampp/htdocs/WEBPROJE/views/teacher/dashboard.php',
    'c:/xampp/htdocs/WEBPROJE/views/student/dashboard.php',
    'c:/xampp/htdocs/WEBPROJE/views/admin/dashboard.php',
    'c:/xampp/htdocs/WEBPROJE/views/teacher/manage_questions.php',
    'c:/xampp/htdocs/WEBPROJE/views/layouts/sidebar.php',
    'c:/xampp/htdocs/WEBPROJE/views/layouts/navbar.php',
    'c:/xampp/htdocs/WEBPROJE/views/layouts/header.php',
    'c:/xampp/htdocs/WEBPROJE/views/teacher/quizzes.php',
    'c:/xampp/htdocs/WEBPROJE/views/student/take_quiz.php'
];

$replacements = [
    'Ä±' => 'ı', 'Ä°' => 'İ',
    'Ã¶' => 'ö', 'Ã–' => 'Ö',
    'Ã¼' => 'ü', 'Ãœ' => 'Ü',
    'Ã§' => 'ç', 'Ã‡' => 'Ç',
    'ÅŸ' => 'ş', 'Åž' => 'Ş',
    'ÄŸ' => 'ğ', 'Äž' => 'Ğ',
    'Ã¢' => 'â',
    // English to Turkish in take_quiz.php
    'Previous' => 'Önceki',
    'Next' => 'Sonraki',
    'Finish Exam' => 'Sınavı Bitir',
    'Finish' => 'Bitir',
    'Return to Dashboard' => 'Panele Dön',
    'Quiz Submitted Successfully!' => 'Sınav Başarıyla Gönderildi!',
    'Questions' => 'Sorular',
    'Question' => 'Soru',
    'Points' => 'Puan',
    'Time is up! Your answers are being submitted automatically.' => 'Süre doldu! Cevaplarınız otomatik olarak gönderiliyor.',
    'You exited Fullscreen Mode! The exam has been terminated and automatically submitted.' => 'Tam ekrandan çıktığınız için sınavınız sonlandırıldı ve cevaplarınız gönderildi.',
    'Are you sure you want to submit your answers?' => 'Cevaplarınızı göndermek istediğinize emin misiniz?',
    'An error occurred while submitting. Please try again.' => 'Gönderim sırasında bir hata oluştu. Lütfen tekrar deneyin.',
    'Fullscreen Required' => 'Tam Ekran Zorunlu',
    'This exam requires you to be in full-screen mode and' => 'Bu sınav tam ekranda yapılmalıdır ve',
    'requires your Webcam to be turned on' => 'kamera izni verilmesi zorunludur',
    'for proctoring. If you press ESC or exit full-screen mode, your exam will be automatically submitted!' => '. Eğer ESC tuşuna basar veya tam ekrandan çıkarsanız sınavınız otomatik olarak sonlandırılacaktır!',
    'Allow Camera, Enter Fullscreen & Start Exam' => 'Kameraya İzin Ver, Tam Ekrana Geç ve Sınava Başla',
    'Your browser does not support fullscreen. Starting anyway.' => 'Tarayıcınız tam ekran desteklemiyor. Sınav başlatılıyor.'
];

foreach ($filesToFix as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original = $content;
        
        foreach ($replacements as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }
        
        if ($content !== $original) {
            file_put_contents($file, $content);
            echo "Fixed: $file\n";
        }
    }
}
?>
