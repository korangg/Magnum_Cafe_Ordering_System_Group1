<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE verify_token='$token' LIMIT 1");

    if ($row = mysqli_fetch_assoc($result)) {
        mysqli_query($conn, "UPDATE users SET verified=1, verify_token=NULL WHERE id={$row['id']}");
        echo "✅ Email verified! You can now <a href='login.php'>Login</a>";
    } else {
        echo "❌ Invalid or expired verification link.";
    }
} else {
    echo "❌ No token provided.";
}
?>
