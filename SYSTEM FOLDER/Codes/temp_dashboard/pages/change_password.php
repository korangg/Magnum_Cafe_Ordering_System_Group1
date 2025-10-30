<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = $_SESSION["username"];
$message = "";

// Fetch user
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

// Change password
if (isset($_POST["change_password"])) {
    $oldPass = $_POST["old_password"];
    $newPass = $_POST["new_password"];
    $confirmPass = $_POST["confirm_password"];

    if (!password_verify($oldPass, $userData['password'])) {
        $message = "❌ Old password is incorrect.";
    } elseif ($newPass !== $confirmPass) {
        $message = "❌ New passwords do not match.";
    } 
    // ✅ Check password strength
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{6,}$/', $newPass)) {
        $message = "❌ Invalid password. It must be at least 6 characters long and include an uppercase letter, lowercase letter, and number.";
    } 
    else {
        $hashedNew = password_hash($newPass, PASSWORD_DEFAULT);
        $updateSql = "UPDATE users SET password = ? WHERE username = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ss", $hashedNew, $username);
        if (mysqli_stmt_execute($updateStmt)) {
            $message = "✅ Password updated successfully!";
        } else {
            $message = "❌ Failed to update password.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Change Password</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
    html, body { height: 100%; width: 100%; overflow: hidden; }
    body { display: flex; justify-content: center; align-items: center; position: relative; background: black; }
    .video-wrapper { position: fixed; top: 0; left: 0; height: 100vh; width: 100vw; overflow: hidden; z-index: -2; }
    .video-wrapper iframe { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 177.77vh; height: 100vh; pointer-events: none; border: none; }
    @media (min-aspect-ratio: 16/9) {
      .video-wrapper iframe { width: 100vw; height: 56.25vw; }
    }
    .video-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); z-index: -1; }
    .glass-container { width: 340px; padding: 30px; background: rgba(255, 255, 255, 0.1); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); }
    .glass-container h2 { text-align: center; color: #fff; margin-bottom: 20px; }
    .glass-container input { width: 100%; padding: 12px; margin: 10px 0; border: none; border-radius: 10px; background: rgba(255, 255, 255, 0.15); color: white; font-size: 14px; }
    .glass-container input::placeholder { color: #ddd; }
    .glass-container button { width: 100%; padding: 12px; background: white; color: black; border: none; border-radius: 30px; font-weight: bold; margin-top: 15px; cursor: pointer; }
    .glass-container button:hover { background: transparent; border: 1px solid white; color: white; }
    .glass-container p { font-size: 12px; color: white; margin-top: 10px; text-align: center; }
  </style>
</head>
<body>

  <!-- YouTube Background -->
  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/jBUCaMQez2A?autoplay=1&mute=1&controls=0&loop=1&playlist=jBUCaMQez2A&modestbranding=1&showinfo=0"
      frameborder="0" allow="autoplay; fullscreen" allowfullscreen>
    </iframe>
  </div>
  <div class="video-overlay"></div>

  <!-- Change Password Form -->
  <div class="glass-container">
    <h2>Change Password</h2>
    <form method="POST">
      <?php if ($message): ?>
        <p style="color: white; text-align:center;"><?= $message ?></p>
      <?php endif; ?>
      <input type="password" name="old_password" placeholder="Old Password" required>
      <input type="password" name="new_password" placeholder="New Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
      <button type="submit" name="change_password">Update Password</button>
      <p>
		<?php
		if (isset($_SESSION['usertype'])) {
			if ($_SESSION['usertype'] === 'admin') {
				$backPage = 'adminDashboard.php';
			} elseif ($_SESSION['usertype'] === 'staff') {
				$backPage = 'staffDashboard.php';
			} else {
				$backPage = 'login.php';
			}
		} else {
			$backPage = 'login.php';
		}
		?>
		<a href="<?= $backPage ?>" style="color:white; text-decoration:none;">⬅ Back</a>
	</p>
    </form>
  </div>

</body>
</html>
