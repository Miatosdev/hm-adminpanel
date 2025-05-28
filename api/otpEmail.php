<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if (isset($_GET['email'])) {
    // OTP generation
    $otp = mt_rand(1000, 9999);
    session_start(); // Start session if not already started
    $_SESSION['otp'] = $otp;
    $email = $_GET['email'];

    function sendOtpEmail($email, $otp) {  
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'testmemail2003@gmail.com'; // Your email address
            $mail->Password = 'hsxwmbptncvzqmhf'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            // Recipients
            $mail->setFrom('testmemail2003@gmail.com', 'ChatNestv2 App');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Chatnestv2";
            $mail->Body = "Welcome to chatnestv2! Your OTP code is $otp.";

            // Sending email
            $mail->send();
            
            // Success response
            echo json_encode(['message' => 'Email sent successfully!', 'data' => $otp]);
            http_response_code(200);
        } catch (Exception $e) {
            // Error response
            echo json_encode(['message' => 'Email could not be sent.', 'error' => $mail->ErrorInfo]);
            http_response_code(500);
        }
    }

    sendOtpEmail($email, $otp);
}
?>
