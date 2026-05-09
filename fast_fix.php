<?php
$filesToFix = [
    'c:/xampp/htdocs/WEBPROJE/views/teacher/manage_questions.php',
    'c:/xampp/htdocs/WEBPROJE/views/layouts/sidebar.php',
    'c:/xampp/htdocs/WEBPROJE/views/layouts/navbar.php',
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
    'S??nav??' => 'Sınavı',
    'S??re' => 'Süre',
    'g??nderiliyor' => 'gönderiliyor',
    '????kt??????n??z' => 'çıktığınız',
    'i??in' => 'için',
    's??nav??n??z' => 'sınavınız',
    'sonland??r??ld??' => 'sonlandırıldı',
    'cevaplar??n??z' => 'cevaplarınız',
    'g??nderildi' => 'gönderildi',
    'manageSorular' => 'manageQuestions',
    'SorularÄ± YÃ¶net' => 'Soruları Yönet',
    'Previous' => 'Önceki',
    'Next' => 'Sonraki',
    'Finish Exam' => 'Sınavı Bitir',
    'Finish' => 'Bitir',
    'Return to Dashboard' => 'Panele Dön'
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
        }
    }
}
echo "Done";
