<?php
session_start();
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $usertype = $user["usertype"];
        $hashed = $user["password"];

        if ($usertype === "user" && !$user["verified"]) {
            $error = "❌ Please verify your email before logging in.";
        } elseif (password_verify($password, $hashed)) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["usertype"] = $user["usertype"];

            if ($usertype === "admin") {
                header("Location: adminhome.php");
            } elseif ($usertype === "staff") {
                header("Location: staffhome.php");
            } else {
                header("Location: userhome.php");
            }
            exit();
        }
    }

    if (!$error) {
        $error = "❌ Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
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

  <!-- Fullscreen YouTube Background Video -->
  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/jBUCaMQez2A?autoplay=1&mute=1&controls=0&loop=1&playlist=jBUCaMQez2A&modestbranding=1&showinfo=0"
      frameborder="0"
      allow="autoplay; fullscreen"
      allowfullscreen>
    </iframe>
  </div>
  <div class="video-overlay"></div>

  <!-- Login Form -->
  <div class="glass-container">
    <h2>Login</h2>
    <form method="POST">
      <?php if ($error): ?>
        <p style="color: red;"><?= $error ?></p>
      <?php endif; ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>

      <div class="options">
        
        <a href="forgot_password.php">Forgot password?</a>
      </div>

      <button type="submit">Login</button>
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </form>
  </div>

</body>
</html>
