<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$gmailUser = "mustafa5252005@gmail.com";
$gmailAppPassword = "zqnfwbfchcnrtmhr";  // 🔥 Your App Password (no space)

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $token = md5(uniqid($email, true));

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "UPDATE users SET reset_token = '$token' WHERE email = '$email'");

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $gmailUser;
            $mail->Password = $gmailAppPassword;
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom($gmailUser, 'MyShop');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Reset Your Password";
            $resetLink = "http://localhost/ntah_ah/ecommerce_website_final/reset_password.php?token=$token";
            $mail->Body = "
                <h3>Password Reset Requested</h3>
                <p>Click below to reset your password:</p>
                <a href='$resetLink' style='padding:10px 20px; background:#4e54c8; color:white; text-decoration:none; border-radius:5px;'>Reset Password</a>
            ";

            $mail->send();
            $success = "✅ Reset link sent to your email.";
        } catch (Exception $e) {
            $error = "❌ Email failed: " . $mail->ErrorInfo;
        }
    } else {
        $error = "❌ Email not found.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Forgot Password</h1>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <form method="POST" action="">
        <h2>Reset Password</h2>
        <?php if ($success): ?><p style="color:green"><?= $success ?></p><?php endif; ?>
        <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>

        <label>Email:
            <input type="email" name="email" required>
        </label>
        <button type="submit">Send Reset Link</button>
    </form>
</main>
</body>
</html>
