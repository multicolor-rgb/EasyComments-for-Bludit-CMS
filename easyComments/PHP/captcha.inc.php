<?php

#if (!isset($_SESSION)) {
#    session_start();
#}

// Generuj bardziej skomplikowane pytanie CAPTCHA
$question = generateRandomStringCaptcha();

// Zapisz pytanie CAPTCHA w sesji
$_SESSION['captcha_question'] = $question;

// Funkcja do generowania pytania CAPTCHA opartego na losowym ciągu znaków
function generateRandomStringCaptcha($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha = '';
    
   
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $captcha;
}