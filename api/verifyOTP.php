<?php
session_start();

if (isset($_SESSION['otp']) && isset($_GET['verify_otp'])) {
    $otp = $_SESSION['otp'];
    $inputed_otp = $_GET['verify_otp'];
    
    if ($otp == $inputed_otp) {
        // OTP verified, now unset the session variable
        unset($_SESSION['otp']);
        echo json_encode(['message' => 'Otp verified successfully!']);
        http_response_code(200);
    } else {
        echo json_encode(['message' => 'Invalid Otp.']);
        http_response_code(400);
    }
} else {
    echo json_encode(['message' => 'OTP or verification input is missing.']);
    http_response_code(422);
}
?>
