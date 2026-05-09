<?php

class Language {
    private static $translations = [];

    public static function load() {
        self::$translations = [
            'dashboard' => 'Panel',
            'my_courses' => 'Derslerim',
            'quizzes' => 'Sınavlar',
            'question_bank' => 'Soru Bankası',
            'my_profile' => 'Profilim',
            'browse_courses' => 'Derslere Göz At',
            'active_quizzes' => 'Aktif Sınavlar',
            'my_results' => 'Sonuçlarım',
            'leaderboard' => 'Sıralama',
            'logout' => 'Çıkış Yap',
            'welcome' => 'Hoş Geldin',
            'settings' => 'Ayarlar',
            'users' => 'Kullanıcılar',
            'system_logs' => 'Sistem Kayıtları',
            'courses' => 'Dersler',
            'students' => 'Öğrenciler',
            'teachers' => 'Öğretmenler'
        ];
    }

    public static function get($key) {
        if (empty(self::$translations)) {
            self::load();
        }
        return isset(self::$translations[$key]) ? self::$translations[$key] : $key;
    }
}
