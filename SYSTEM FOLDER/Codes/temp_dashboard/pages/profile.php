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

// ✅ Fetch user data
$sql = "SELECT username, email, phone, profile_picture FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

// ✅ Handle profile update
if (isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['new_username']);
    $newPhone = trim($_POST['new_phone']);
    $profilePic = null;

    if (!empty($newUsername) && !empty($newPhone)) {
        if (!empty($_FILES['profile_pic']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['profile_pic']['type'], $allowedTypes) && $_FILES['profile_pic']['size'] < 2 * 1024 * 1024) {
                $profilePic = file_get_contents($_FILES['profile_pic']['tmp_name']);
            } else {
                $message = "⚠️ Invalid file type or file too large (max 2MB).";
            }
        }

        $checkUserSql = "SELECT id FROM users WHERE username = ? AND username != ?";
        $checkUserStmt = mysqli_prepare($conn, $checkUserSql);
        mysqli_stmt_bind_param($checkUserStmt, "ss", $newUsername, $username);
        mysqli_stmt_execute($checkUserStmt);
        $checkUserResult = mysqli_stmt_get_result($checkUserStmt);

        if (mysqli_num_rows($checkUserResult) > 0) {
            $message = "⚠️ Username already taken.";
        } else {
            if ($profilePic !== null) {
                $updateSql = "UPDATE users SET username = ?, phone = ?, profile_picture = ? WHERE username = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "ssss", $newUsername, $newPhone, $profilePic, $username);
                mysqli_stmt_send_long_data($updateStmt, 2, $profilePic);
            } else {
                $updateSql = "UPDATE users SET username = ?, phone = ? WHERE username = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "sss", $newUsername, $newPhone, $username);
            }

            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION["username"] = $newUsername;
                $username = $newUsername;
                $userData['username'] = $newUsername;
                $userData['phone'] = $newPhone;
                if ($profilePic !== null) {
                    $userData['profile_picture'] = $profilePic;
                }
                $message = "✅ Profile updated successfully!";
            } else {
                $message = "❌ Error updating profile.";
            }
        }
    } else {
        $message = "⚠️ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
    html, body { height:100%; width:100%; overflow:hidden; }
    body { display:flex; justify-content:center; align-items:center; background:black; position:relative; }

    .video-wrapper {
      position: fixed;
      top: 0; left: 0;
      height: 100vh; width: 100vw;
      overflow: hidden;
      z-index: -2;
    }
    .video-wrapper iframe {
      position: absolute;
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      width: 177.77vh; height: 100vh;
      pointer-events: none;
      border: none;
    }
    @media (min-aspect-ratio: 16/9) {
      .video-wrapper iframe {
        width: 100vw;
        height: 56.25vw;
      }
    }
    .video-overlay {
      position: fixed;
      top:0; left:0;
      width:100%; height:100%;
      background: rgba(0,0,0,0.4);
      z-index: -1;
    }
    .glass-container {
      width: 380px;
      padding: 30px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 4px 30px rgba(0,0,0,0.2);
      color: white;
    }
    .glass-container h2 {
      text-align: center;
      margin-bottom: 20px;
    }
	.glass-container label {
	  display: block;
	  margin-top: 10px;
	  font-size: 13px;
	  font-weight: 500;
	  color: #ddd;
	}
    .glass-container input, .glass-container button {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.15);
      color: white;
      font-size: 14px;
    }
    .glass-container input[readonly] {
      color: #ddd;
      cursor: not-allowed;
    }
    .glass-container button {
      background: white;
      color: black;
      border-radius: 30px;
      font-weight: bold;
      cursor: pointer;
    }
    .glass-container button:hover {
      background: transparent;
      border: 1px solid white;
      color: white;
    }
    .profile-pic {
      width: 80px; height: 80px;
      border-radius: 50%;
      overflow: hidden;
      margin: 0 auto 15px auto;
      display: flex; align-items:center; justify-content:center;
      background: rgba(0,0,0,0.4);
    }
    .profile-pic img {
      width: 100%; height: 100%; object-fit: cover;
    }
    .glass-container p { font-size: 12px; text-align:center; margin-top:10px; }
    .glass-container a { color:white; font-weight:bold; text-decoration:none; }
    .glass-container a:hover { text-decoration:underline; }
  </style>
</head>
<body>

  <!-- Video Background -->
  <div class="video-wrapper">
    <iframe src="https://www.youtube.com/embed/w_f01ddamVI?autoplay=1&mute=1&controls=0&loop=1&playlist=w_f01ddamVI&modestbranding=1&showinfo=0"
      frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
  </div>
  <div class="video-overlay"></div>

  <!-- Profile Form -->
  <div class="glass-container">
    <div class="profile-pic">
      <?php if (!empty($userData['profile_picture'])): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($userData['profile_picture']) ?>" alt="Profile Picture">
      <?php else: ?>
        <span style="font-size:36px;">👤</span>
      <?php endif; ?>
    </div>
    <h2>Hi <?= htmlspecialchars($userData['username']) ?></h2>
    <form method="POST" enctype="multipart/form-data">
	  <!-- Username -->
	  <label for="new_username">Username</label>
	  <input type="text" id="new_username" name="new_username" 
			 value="<?= htmlspecialchars($userData['username']) ?>" required>

	  <!-- Email -->
	  <label for="email">Email</label>
	  <input type="email" id="email" 
			 value="<?= htmlspecialchars($userData['email']) ?>" readonly>

	  <!-- Phone -->
	  <label for="new_phone">Phone Number</label>
	  <input type="text" id="new_phone" name="new_phone" 
			 value="<?= htmlspecialchars($userData['phone']) ?>">

	  <!-- Profile Picture -->
	  <label for="profile_pic">Profile Picture</label>
	  <input type="file" id="profile_pic" name="profile_pic" accept="image/*">

	  <button type="submit" name="update_profile">Update Profile</button>
	</form>
    <?php if (!empty($message)): ?>
      <p style="color:#ffccff;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <p>
	  <a href="change_password.php">Change Password</a> | 
	  <a href="<?php echo $_SESSION['usertype'] == 'admin' ? 'adminDashboard.php' : 'staffDashboard.php'; ?>">Home</a>
	</p>
  </div>

</body>
</html>
