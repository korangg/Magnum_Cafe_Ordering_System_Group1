<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
if (!$conn) {
    die("DB connection failed");
}

// Retrieve token from GET or POST request
$token = isset($_GET["token"]) ? $_GET["token"] : (isset($_POST["token"]) ? $_POST["token"] : "");
$error = "";
$success = "";

// Only proceed if a token is present
if (!empty($token)) {
    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE reset_token = ?");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $error = "❌ Invalid or expired token.";
    }
} else {
    $error = "❌ No token provided.";
}

// Handle form submission for password update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["password"]) && empty($error)) {
    $newPass = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Use prepared statements for the update query
    $stmt = mysqli_prepare($conn, "UPDATE users SET password = ?, reset_token = NULL WHERE reset_token = ?");
    mysqli_stmt_bind_param($stmt, "ss", $newPass, $token);
    $update = mysqli_stmt_execute($stmt);

    if ($update) {
        $success = "✅ Password reset successful. You can now <a href='login.php'>Login</a>.";
    } else {
        $error = "❌ Could not update password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
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
      font-size: 14px; /* Increased size for better readability */
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
    <h2>Reset Password</h2>
    
    <?php if ($error): ?>
        <p style="color: #ffcccb;"><?= $error ?></p> <?php elseif ($success): ?>
        <p style="color: lightgreen;"><?= $success ?></p>
    <?php else: ?>
        <form method="POST" action="">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <input type="password" name="password" placeholder="Enter New Password" required>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
  </div>

</body>
</html>