<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = mysqli_real_escape_string($conn, trim($_POST["name"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $message = mysqli_real_escape_string($conn, trim($_POST["message"]));

    mysqli_query($conn, "INSERT INTO feedback (name, email, message, submitted_at) 
                         VALUES ('$name', '$email', '$message', NOW())");

    if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
	}
    exit();
}
?>