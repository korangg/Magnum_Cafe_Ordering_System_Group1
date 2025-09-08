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
$sql = "SELECT username, email, profile_pic FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$userData = mysqli_fetch_assoc($result);
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

        .header-icons .icon-container {
            position: relative;
            color: #e0e0e0;
        }

        .header-icons .icon-container .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #cf6679;
            color: white;
            font-size: 10px;
            border-radius: 50%;
            padding: 2px 5px;
        }
        
        .header-icons a {
            color: #e0e0e0;
            text-decoration: none;
        }

        /* Home Button */
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

        .dashboard-card {
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            text-align: center;
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

        .activity-history {
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .activity-history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-history-header h2 {
            font-size: 18px;
            font-weight: 500;
        }

        .activity-history-header a {
            color: #bb86fc;
            text-decoration: none;
            font-weight: 500;
        }

        .activity-icons {
            display: flex;
            justify-content: space-between;
        }

        .activity-icon-item {
            text-align: center;
            flex: 1;
            padding: 10px;
        }

        .activity-icon-item i {
            font-size: 30px;
            color: #ffc107;
            margin-bottom: 5px;
        }
        
        .activity-icon-item p {
            font-size: 12px;
            color: #b0b0b0;
            font-weight: 400;
        }
        
        .activity-icon-item:nth-child(2) i { color: #81d4fa; }
        .activity-icon-item:nth-child(3) i { color: #8bc34a; }
        .activity-icon-item:nth-child(4) i { color: #bb86fc; }

        .support-section {
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .support-section h2 {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
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
        
        .about-us-section {
            background-color: #1f1f1f;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .about-us-section h2 {
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
            <a href="#" class="icon-container"><i class="fa-solid fa-bell"></i><span class="badge">4</span></a>
            <a href="#" class="icon-container"><i class="fa-solid fa-cart-shopping"></i><span class="badge">8</span></a>
            <a href="#"><i class="fa-solid fa-gear"></i></a>
            <!-- Home Button -->
            <a href="userhome.php" class="btn-home">Home</a>
        </div>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h3>My Wallet</h3>
            <p class="balance">MYR 0.00</p>
        </div>

        <div class="activity-history">
            <div class="activity-history-header">
                <h2>Activity History</h2>
                <a href="#">View All &gt;&gt;</a>
            </div>
            <div class="activity-icons">
                <div class="activity-icon-item">
                    <i class="fa-solid fa-sack-dollar"></i>
                    <p>To Be Paid</p>
                </div>
                <div class="activity-icon-item">
                    <i class="fa-solid fa-box-open"></i>
                    <p>To Be Delivered</p>
                </div>
                <div class="activity-icon-item">
                    <i class="fa-solid fa-truck-fast"></i>
                    <p>Delivered</p>
                </div>
                <div class="activity-icon-item">
                    <i class="fa-solid fa-check-circle"></i>
                    <p>Completed</p>
                </div>
            </div>
        </div>

        <div class="support-section">
            <h2>Support</h2>
            <div class="support-item">
                <i class="fa-solid fa-exclamation-circle"></i>
                <p>Order Issues</p>
                <span class="arrow">&gt;</span>
            </div>
            <div class="support-item">
                <i class="fa-solid fa-headset"></i>
                <p>Help Center</p>
                <span class="arrow">&gt;</span>
            </div>
            <div class="support-item">
                <i class="fa-solid fa-list-ul"></i>
                <p>Complain List</p>
                <span class="arrow">&gt;</span>
            </div>
        </div>

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
