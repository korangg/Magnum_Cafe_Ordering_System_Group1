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

// Fetch user data
$sql = "SELECT username, email, phone, profile_pic FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);

// Handle profile update (username + phone)
if (isset($_POST['update_profile'])) {
    $newUsername = trim($_POST['new_username']);
    $newPhone = trim($_POST['new_phone']);

    if (!empty($newUsername) && !empty($newPhone)) {
        // Check if username already exists (exclude current user)
        $checkUserSql = "SELECT id FROM users WHERE username = ? AND username != ?";
        $checkUserStmt = mysqli_prepare($conn, $checkUserSql);
        mysqli_stmt_bind_param($checkUserStmt, "ss", $newUsername, $username);
        mysqli_stmt_execute($checkUserStmt);
        $checkUserResult = mysqli_stmt_get_result($checkUserStmt);

        if (mysqli_num_rows($checkUserResult) > 0) {
            $message = "⚠️ Username already taken.";
        } else {
            // Update username + phone
            $updateSql = "UPDATE users SET username = ?, phone = ? WHERE username = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "sss", $newUsername, $newPhone, $username);

            if (mysqli_stmt_execute($updateStmt)) {
                $_SESSION["username"] = $newUsername; // Update session
                $username = $newUsername;
                $userData['username'] = $newUsername;
                $userData['phone'] = $newPhone;
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #121212;
            color: #e0e0e0;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #1f1f1f;
            background-image: linear-gradient(135deg, #1f1f1f 0%, #333 100%);
            color: #fff;
            padding: 40px 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            display: flex;
            align-items: center;
        }

        .header .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #121212;
            font-size: 36px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 500;
        }
        
        .header .user-info {
            display: flex;
            align-items: center;
        }

        .header-icons {
            margin-left: auto;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .header-icons a {
            color: #e0e0e0;
            text-decoration: none;
        }

        .btn-home {
            background: #bb86fc;
            padding: 8px 14px;
            border-radius: 8px;
            color: white !important;
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-home:hover {
            background: #9a67ea;
        }

        .main-content {
            padding: 20px;
            margin-top: -30px;
        }

        .dashboard-card, .support-section, .about-us-section {
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .dashboard-card h3 {
            font-size: 16px;
            color: #b0b0b0;
            font-weight: 400;
        }

        .dashboard-card .balance {
            font-size: 28px;
            font-weight: 600;
            color: #e0e0e0;
            margin-top: 5px;
        }

        .support-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #333;
        }

        .support-item:last-child {
            border-bottom: none;
        }

        .support-item i {
            font-size: 20px;
            color: #bb86fc;
            margin-right: 15px;
        }

        .support-item p {
            font-size: 16px;
            flex-grow: 1;
            color: #e0e0e0;
        }

        .support-item .arrow {
            font-size: 16px;
            color: #bb86fc;
        }

        .about-us-section h2, .support-section h2 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .about-us-section a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-decoration: none;
            color: #e0e0e0;
            padding: 10px 0;
        }

        .about-us-section a:hover {
            color: #bb86fc;
        }

        input, button {
            font-family: 'Poppins', sans-serif;
        }

        input[readonly] {
            background-color: #2a2a2a;
            color: #999;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="user-info">
            <div class="profile-pic">Hi</div>
            <h1>Hi <?= htmlspecialchars($userData['username']) ?></h1>
        </div>
        <div class="header-icons">
          
        </div>
    </div>

    <div class="main-content">
        <!-- Profile Update Card -->
        <div class="dashboard-card">
            <h3>Your Profile</h3>
            <p class="balance">Username: <?= htmlspecialchars($userData['username']) ?></p>
            <p style="margin-top:5px; font-size:14px; color:#b0b0b0;">Email: <?= htmlspecialchars($userData['email']) ?></p>
            <p style="margin-top:5px; font-size:14px; color:#b0b0b0;">Phone: <?= htmlspecialchars($userData['phone']) ?></p>

            <form action="" method="POST" style="margin-top:15px; text-align:left;">
                <label style="font-size:14px; color:#b0b0b0;">New Username</label><br>
                <input type="text" name="new_username" value="<?= htmlspecialchars($userData['username']) ?>" 
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;" required>

                <label style="font-size:14px; color:#b0b0b0;">Email (cannot change)</label><br>
                <input type="email" value="<?= htmlspecialchars($userData['email']) ?>" readonly
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;">

                <label style="font-size:14px; color:#b0b0b0;">Phone Number</label><br>
                <input type="text" name="new_phone" value="<?= htmlspecialchars($userData['phone']) ?>" 
                       style="padding:8px; border-radius:8px; border:1px solid #333; width:100%; margin-bottom:10px;" required>

                <button type="submit" name="update_profile" 
                        style="padding:10px 14px; border:none; border-radius:8px; background:#bb86fc; color:#fff; cursor:pointer; width:100%;">
                    Update Profile
                </button>
            </form>

            <?php if (!empty($message)): ?>
                <p style="margin-top:10px; color:#bb86fc;"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </div>

        <!-- Account Section (unchanged) -->
        <div class="about-us-section">
            <h2>Account</h2>
            <a href="change_password.php">
                <p>Change Password</p>
                <span class="arrow">&gt;</span>
            </a>
        </div>
    </div>
</div>

</body>
</html>
