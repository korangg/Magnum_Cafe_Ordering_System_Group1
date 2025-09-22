<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
	
if ($_SESSION["usertype"] == "admin") {
    $changedBy = 'Admin';
} else {
    $changedBy = 'Staff';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../phpmailer/PHPMailer.php';
require __DIR__ . '/../../phpmailer/SMTP.php';
require __DIR__ . '/../../phpmailer/Exception.php';

// Use environment variables or hardcode carefully
$gmailUser = "muhammadizzuddinfarhan2005@gmail.com";  
$gmailAppPassword = "zlcvvfzwhvwlnhuh";  

// Check if the sender email is correctly set
if (empty($gmailUser)) {
    $_SESSION['notify_error'] = "❌ Sender email address is not set.";
    return;
}

// ✅ Check if notify data exists
if (isset($_SESSION["notify_email"])) {
    $customerEmail = $_SESSION["notify_email"];
    $newUsername = $_SESSION["notify_username"];
    $newPhone = $_SESSION["notify_phone"];

    // Unset session variables only after processing
    unset($_SESSION["notify_email"], $_SESSION["notify_username"], $_SESSION["notify_phone"]);

    // Validate email address
    if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['notify_error'] = "❌ Invalid email address.";
    } else {
        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmailUser; // Ensure this is set correctly
            $mail->Password = $gmailAppPassword;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom($gmailUser, 'My Shop'); // Set the sender email
            $mail->addAddress($customerEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Your Account Details Have Been Updated";
            $mail->Body    = "
                <h3>Hello,</h3>
                <p>Your account details were recently updated by our $changedBy:</p>
                <ul>
                    <li><b>Username:</b> $newUsername</li>
                    <li><b>Phone:</b> $newPhone</li>
                </ul>
                <p>If you did not request this change, please contact support immediately.</p>
                <p>Thank you,<br>My Shop</p>
            ";

            $mail->send();
            $_SESSION['notify_success'] = "✅ Data updated & email sent successfully to $customerEmail!";
        } catch (Exception $e) {
            $_SESSION['notify_error'] = "❌ Email could not be sent. Error: {$mail->ErrorInfo}";
        }
    }
} else {
    $_SESSION['notify_error'] = "❌ No customer data found to notify.";
}
?>