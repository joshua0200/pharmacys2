<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/src/SMTP.php';

// passing true in constructor enables exceptions in PHPMailer

//variables
function sendMail($a, $b, $c, $d)
{

    $mail = new PHPMailer(true);

    $mySubject = $a;
    $myBody = $b;
    $toEmail = $c;
    $toName = $d;

    try {
        // Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->Username = 'psumedict@gmail.com'; // YOUR gmail email
        $mail->Password = 'etqfrxmazfojybpz'; // YOUR gmail password

        // Sender and recipient settings
        $mail->setFrom('psumedict@gmail.com', 'MED-ICT');
        $mail->addAddress($toEmail, $toName);

        // Setting the email content
        $mail->IsHTML(true);
        $mail->Subject = $mySubject;
        $mail->Body = $myBody;


        $mail->send();
    } catch (Exception $e) {
        echo "Error " . $e;
    }
}
