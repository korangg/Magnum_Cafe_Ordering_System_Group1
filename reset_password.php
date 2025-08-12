<?php
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
if (!$conn) die("DB connection failed");

$token = isset($_GET["token"]) ? $_GET["token"] : (isset($_POST["token"]) ? $_POST["token"] : "");
$error = "";
$success = "";

// Validate token
if (!empty($token)) {
    $result = mysqli_query($conn, "SELECT * FROM users WHERE reset_token = '$token'");
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        $error = "❌ Invalid or expired token.";
    }
} else {
    $error = "❌ No token found.";
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["password"]) && empty($error)) {
    $newPass = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $update = mysqli_query($conn, "UPDATE users SET password = '$newPass', reset_token = NULL WHERE reset_token = '$token'");

    if ($update) {
        $success = "✅ Password reset successful. <a href='login.php'>Login here</a>";
    } else {
        $error = "❌ Could not update password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Reset Password</h1>
    <nav>
        <a href="index.html">Home</a>
        <a href="login.php">Login</a>
    </nav>
</header>

<main>
    <?php if ($error): ?>
        <p style="color: red; text-align:center;"><?= $error ?></p>
    <?php elseif ($success): ?>
        <p style="color: green; text-align:center;"><?= $success ?></p>
    <?php else: ?>
        <form method="POST" action="">
            <h2>🔐 Enter New Password</h2>
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
            <label>New Password:
                <input type="password" name="password" required>
            </label>
            <button type="submit">Reset Password</button>
        </form>
    <?php endif; ?>
</main>

</body>
</html>
