<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// 📨 INSERT YOUR GMAIL AND APP PASSWORD HERE
$gmailUser = "mustafa5252005@gmail.com";               // ← Replace with your Gmail
$gmailAppPassword = "zqnfwbfchcnrtmhr"; // ← Replace with 16-char app password

$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
   $usertype = 'user';
    $verify_token = md5(uniqid($username, true));

    // Insert user into database
    $sql = "INSERT INTO users (username, email, password, usertype, verify_token, verified)
            VALUES (?, ?, ?, ?, ?, 0)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $password, $usertype, $verify_token);

    if (mysqli_stmt_execute($stmt)) {
        // Send email
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
            $mail->addAddress($email, $username);
            $mail->isHTML(true);
            $mail->Subject = 'Verify Your Email';
            $verifyLink = "http://localhost/ntah_ah/ecommerce_website_final/verify.php?token=$verify_token";
            $mail->Body = "
                <h3>Welcome, $username!</h3>
                <p>Please verify your email address by clicking the button below:</p>
                <a href='$verifyLink' style='background:#4e54c8;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;'>Verify Now</a>
            ";

            $mail->send();
            $success = "✅ Registration successful. Please check your email to verify your account.";
        } catch (Exception $e) {
            $error = "❌ Registration succeeded, but email failed: " . $mail->ErrorInfo;
        }
    } else {
        $error = "❌ Registration failed: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Register</h1>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>
<main>
    <form method="POST">
        <h2>Create Account</h2>
        <?php if ($success): ?><p style="color:green"><?= $success ?></p><?php endif; ?>
        <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
        <label>Username: <input type="text" name="username" required></label>
        <label>Email: <input type="email" name="email" required></label>
        <label>Password: <input type="password" name="password" required></label>
        
        <button type="submit">Register</button>
    </form>
</main>
</body>
</html>