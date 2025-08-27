<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);

// We will store the result message and status in variables
// instead of echoing them immediately.
$message = "";
$status = "error"; // Can be 'success' or 'error'

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    // Use prepared statements to prevent SQL injection
    $sql = "SELECT id FROM users WHERE verify_token = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Token is valid, update the user
        $update_sql = "UPDATE users SET verified=1, verify_token=NULL WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "i", $row['id']);
        mysqli_stmt_execute($update_stmt);

        $status = "success";
        $message = "Your email has been verified! You can now <a href='login.php' style='text-decoration: underline'>Login</a>.";
    } else {
        $message = "❌ Invalid or expired verification link.";
    }
} else {
    $message = "❌ No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Email Verification</title>
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
      position: fixed; top: 0; left: 0; height: 100vh; width: 100vw; overflow: hidden; z-index: -2;
    }
    .video-wrapper iframe {
      position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 177.77vh; height: 100vh; pointer-events: none; border: none;
    }
    @media (min-aspect-ratio: 16/9) {
      .video-wrapper iframe { width: 100vw; height: 56.25vw; }
    }
    .video-overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: -1;
    }
    .glass-container {
      width: 380px;
      padding: 40px 30px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .glass-container h2 {
      color: #fff;
      margin-bottom: 20px;
    }
    .glass-container p {
      font-size: 16px;
      color: #eee;
      line-height: 1.5;
    }
    .glass-container a {
      color: #fff;
      font-weight: bold;
      text-decoration: none;
    }
    .glass-container a:hover {
      text-decoration: underline;
    }
    
    /* Checkmark Animation Styles */
    .checkmark-wrapper {
        width: 130px;
        height: 130px;
        margin: 0 auto 20px auto;
    }
    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #7ac142;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }
    .checkmark {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #fff;
        stroke-miterlimit: 10;
        box-shadow: inset 0px 0px 0px #7ac142;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }
    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }
    @keyframes stroke {
        100% { stroke-dashoffset: 0; }
    }
    @keyframes scale {
        0%, 100% { transform: none; }
        50% { transform: scale3d(1.1, 1.1, 1); }
    }
    @keyframes fill {
        100% { box-shadow: inset 0px 0px 0px 80px #7ac142; }
    }
  </style>
</head>
<body>

  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/jBUCaMQez2A?autoplay=1&mute=1&controls=0&loop=1&playlist=jBUCaMQez2A&modestbranding=1&showinfo=0" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
  </div>
  <div class="video-overlay"></div>

  <div class="glass-container">
    <?php if ($status === 'success'): ?>
        <div class="checkmark-wrapper">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
            </svg>
        </div>
        <h2>Verification Successful!</h2>
    <?php else: ?>
        <h2>Verification Failed</h2>
    <?php endif; ?>

    <p><?php echo $message; ?></p>
  </div>

</body>
</html>