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

// ✅ Handle profile update (username + phone + picture)
if (isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['new_username']);
    $newPhone = trim($_POST['new_phone']);
    $profilePic = null;

    if (!empty($newUsername) && !empty($newPhone)) {
        // ✅ Handle profile picture upload
        if (!empty($_FILES['profile_pic']['name'])) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['profile_pic']['type'], $allowedTypes) && $_FILES['profile_pic']['size'] < 2 * 1024 * 1024) {
                $profilePic = file_get_contents($_FILES['profile_pic']['tmp_name']);
            } else {
                $message = "⚠️ Invalid file type or file too large (max 2MB).";
            }
        }

        // ✅ Check if username already exists (exclude current user)
        $checkUserSql = "SELECT id FROM users WHERE username = ? AND username != ?";
        $checkUserStmt = mysqli_prepare($conn, $checkUserSql);
        mysqli_stmt_bind_param($checkUserStmt, "ss", $newUsername, $username);
        mysqli_stmt_execute($checkUserStmt);
        $checkUserResult = mysqli_stmt_get_result($checkUserStmt);

        if (mysqli_num_rows($checkUserResult) > 0) {
            $message = "⚠️ Username already taken.";
        } else {
            // ✅ Update user data
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
                $_SESSION["username"] = $newUsername; // update session
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #121212; color: #e0e0e0; }
        .container { max-width: 960px; margin: 0 auto; padding: 20px; }
        .header {
            background-color: #1f1f1f;
            background-image: linear-gradient(135deg, #1f1f1f 0%, #333 100%);
            color: #fff; padding: 40px 20px;
            border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;
            display: flex; align-items: center;
        }
        .header .profile-pic {
            width: 80px; height: 80px; border-radius: 50%;
            background-color: #2a2a2a; color: #121212;
            display: flex; justify-content: center; align-items: center;
            margin-right: 20px; overflow: hidden;
        }
        .header .profile-pic img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .header h1 { font-size: 24px; font-weight: 500; }
        .header .user-info { display: flex; align-items: center; }
        .header-icons { margin-left: auto; display: flex; gap: 20px; align-items: center; }
        .btn-home {
            background: #bb86fc; padding: 8px 14px; border-radius: 8px;
            color: white !important; font-size: 14px; font-weight: 500; transition: 0.3s;
        }
        .btn-home:hover { background: #9a67ea; }
        .main-content { padding: 20px; margin-top: -30px; }
        .dashboard-card, .about-us-section {
            background-color: #1f1f1f; border-radius: 15px; padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3); margin-bottom: 20px;
        }
        .dashboard-card h3 { font-size: 16px; color: #b0b0b0; font-weight: 400; }
        input, button { font-family: 'Poppins', sans-serif; }
        input[readonly] { background-color: #2a2a2a; color: #999; cursor: not-allowed; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="user-info">
            <div class="profile-pic">
                <?php if (!empty($userData['profile_picture'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($userData['profile_picture']) ?>" alt="Profile Picture">
                <?php else: ?>
                    <span style="color:white; font-size:36px;">👤</span>
                <?php endif; ?>
            </div>
            <h1>Hi <?= htmlspecialchars($userData['username']) ?></h1>
        </div>
        <div class="header-icons">
            <button type="button" class="btn-home" onclick="history.back()">Home</button>
        </div>
    </div>

    <div class="main-content">
        <!-- Profile Update Card -->
        <div class="dashboard-card">
            <h3>Your Profile</h3>
            <form action="" method="POST" enctype="multipart/form-data" style="margin-top:15px; text-align:left;">
                <label style="font-size:14px; color:#b0b0b0;">Username</label><br>
                <input type="text" name="new_username" value="<?= htmlspecialchars($userData['username']) ?>"
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;" required>

                <label style="font-size:14px; color:#b0b0b0;">Email</label><br>
                <input type="email" value="<?= htmlspecialchars($userData['email']) ?>" readonly
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;">

                <label style="font-size:14px; color:#b0b0b0;">Phone Number</label><br>
                <input type="text" name="new_phone" value="<?= htmlspecialchars($userData['phone']) ?>"
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;" required>

                <label style="font-size:14px; color:#b0b0b0;">Profile Picture</label><br>
                <input type="file" name="profile_pic" accept="image/*"
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;">

                <button type="submit" name="update_profile"
                        style="padding:10px 14px; border:none; border-radius:8px; background:#bb86fc; color:#fff; cursor:pointer; width:100%;">
                    Update Profile
                </button>
            </form>
            <?php if (!empty($message)): ?>
                <p style="margin-top:10px; color:#bb86fc;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>

        <!-- Account Section -->
        <div class="about-us-section">
            <h2>Account</h2>
            <a href="change_password.php">
                <p>Change Password <span class="arrow">&gt;</span></p>
            </a>
        </div>
    </div>
</div>
</body>
</html>