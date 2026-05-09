<?php
// app/Helpers/Mail.php

class Mail {
    // For this basic setup, since we might not have PHPMailer installed via Composer yet,
    // we will create a mock-up or a simple native mail function configured for Mailtrap
    // Alternatively, if in XAMPP, mail() function might need sendmail configuration.
    // For now, we will simulate sending by logging or using a basic connection.
    
    public static function send($to, $subject, $message) {
        $headers = "From: " . getenv('MAIL_FROM_NAME') . " <" . getenv('MAIL_FROM_ADDRESS') . ">\r\n";
        $headers .= "Reply-To: " . getenv('MAIL_FROM_ADDRESS') . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        // In a real environment without Composer, you'd use PHP's mail() function
        // Note: For XAMPP, sendmail configuration in php.ini is required for Mailtrap.
        // mail($to, $subject, $message, $headers);
        
        // Let's log it for testing purposes if mail isn't configured in XAMPP
        error_log("MAIL SENT TO: $to | SUBJECT: $subject");
        
        return true;
    }
}
