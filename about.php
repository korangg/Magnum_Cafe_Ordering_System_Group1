<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .user-label {
            position: absolute;
            top: 10px;
            left: 15px;
            background: #222;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
        }
    </style>
</head>
<body>
<div class="user-label">👤 Logged in as: <?php echo htmlspecialchars($_SESSION["username"]); ?></div>

<header>
    <h1>About Our Shop</h1>
    <nav>
        <a href="userhome.php">Home</a>
        <a href="checkout.php">Checkout</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
    <section class="about">
        <img src="images/about.jpg" alt="About Image">
        <div>
            <h2>Who We Are</h2>
            <p>We’re a local bookstore offering handpicked titles across all genres — from romance and mystery to business and personal growth. Our mission is to make reading exciting and affordable for everyone!</p>
            <h3>Contact</h3>
            <p>Email: support@myshop.com</p>
            <p>Phone: +60 12-345 6789</p>
        </div>
    </section>
</main>
</body>
</html>
