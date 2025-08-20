<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$gmailUser = "mustafa5252005@gmail.com";
$gmailAppPassword = "zqnfwbfchcnrtmhr"; // 🔥 Your App Password

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    
    // Use prepared statements to prevent SQL injection
    $sql_check = "SELECT * FROM users WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        $token = md5(uniqid($email, true));
        
        $sql_update = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ss", $token, $email);
        mysqli_stmt_execute($stmt_update);

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
            // IMPORTANT: Make sure this URL is correct for your project path
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
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    html, body {
      height: 100%;
      width: 100%;
      overflow: hidden;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      background: black;
    }

    .video-wrapper {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      overflow: hidden;
      z-index: -2;
    }

    .video-wrapper iframe {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 177.77vh; /* 100 * (16/9) */
      height: 100vh;
      pointer-events: none;
      border: none;
    }

    @media (min-aspect-ratio: 16/9) {
      .video-wrapper iframe {
        width: 100vw;
        height: 56.25vw; /* 100 / (16/9) */
      }
    }

    .video-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.4);
      z-index: -1;
    }

    .glass-container {
      width: 340px;
      padding: 30px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
    }

    .glass-container h2 {
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
    }

    .glass-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.15);
      color: white;
      font-size: 14px;
    }

    .glass-container input::placeholder {
      color: #ddd;
    }

    .glass-container button {
      width: 100%;
      padding: 12px;
      background: white;
      color: black;
      border: none;
      border-radius: 30px;
      font-weight: bold;
      margin-top: 15px;
      cursor: pointer;
    }

    .glass-container button:hover {
      background: transparent;
      border: 1px solid white;
      color: white;
    }

    .glass-container p {
      font-size: 12px;
      color: white;
      margin-top: 10px;
      text-align: center;
    }

    .glass-container a {
      color: white;
      font-weight: bold;
      text-decoration: none;
    }

    .glass-container a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <!-- Fullscreen YouTube Background Video -->
  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/jBUCaMQez2A?autoplay=1&mute=1&controls=0&loop=1&playlist=jBUCaMQez2A&modestbranding=1&showinfo=0"
      frameborder="0"
      allow="autoplay; fullscreen"
      allowfullscreen>
    </iframe>
  </div>
  <div class="video-overlay"></div>

  <!-- Forgot Password Form -->
  <div class="glass-container">
    <h2>Forgot Password</h2>
    <form method="POST">
      <?php if ($success): ?>
        <p style="color: lightgreen; text-align:center; margin-bottom: 10px;"><?= $success ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p style="color: #ff8a8a; text-align:center; margin-bottom: 10px;"><?= $error ?></p>
      <?php endif; ?>

      <input type="email" name="email" placeholder="Enter your Email" required>
      <button type="submit">Send Reset Link</button>
      <p>Remember your password? <a href="login.php">Login</a></p>
    </form>
  </div>

</body>
</html>