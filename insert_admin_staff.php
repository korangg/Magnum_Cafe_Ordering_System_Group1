<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Function to check and insert
function insertUserIfNotExists($conn, $username, $email, $plainPassword, $usertype) {
    $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "⚠️ User '$username' already exists.<br>";
    } else {
        $hashed = password_hash($plainPassword, PASSWORD_BCRYPT);
        $insert = mysqli_prepare($conn, "INSERT INTO users (username, email, password, usertype) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insert, "ssss", $username, $email, $hashed, $usertype);
        mysqli_stmt_execute($insert);
        echo "✅ Inserted '$username' as $usertype.<br>";
        mysqli_stmt_close($insert);
    }

    mysqli_stmt_close($check);
}

// Insert Admin and Staff
insertUserIfNotExists($conn, "admin1", "admin@example.com", "adminpass", "admin");
insertUserIfNotExists($conn, "staff1", "staff@example.com", "staffpass", "staff");

mysqli_close($conn);
?>
