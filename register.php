<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// 📨 INSERT YOUR GMAIL AND APP PASSWORD HERE
$gmailUser = "mustafa5252005@gmail.com";        // ← Replace with your Gmail
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
            // IMPORTANT: Make sure this URL is correct for your project path
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
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
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

    .glass-container .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12px;
      color: white;
    }

    .glass-container .options input {
      margin-right: 5px;
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

  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/jBUCaMQez2A?autoplay=1&mute=1&controls=0&loop=1&playlist=jBUCaMQez2A&modestbranding=1&showinfo=0"
      frameborder="0"
      allow="autoplay; fullscreen"
      allowfullscreen>
    </iframe>
  </div>
  <div class="video-overlay"></div>

  <div class="glass-container">
    <h2>Register</h2>
    <form method="POST">
      <?php if ($success): ?>
        <p style="color: lightgreen; text-align:center;"><?= $success ?></p>
      <?php endif; ?>
      <?php if ($error): ?>
        <p style="color: red; text-align:center;"><?= $error ?></p>
      <?php endif; ?>

      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Register</button>
      <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>

</body>
</html>