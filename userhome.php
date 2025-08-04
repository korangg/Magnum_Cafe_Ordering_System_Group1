<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
$result = mysqli_query($conn, "SELECT * FROM products WHERE stock > 0");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Homepage</title>
    <link rel="stylesheet" href="style.css">
    <script src="cart.js" defer></script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .user-label {
            position: absolute;
            top: 10px;
            left: 15px;
            background: #222;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            z-index: 10;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1em 2em;
            background: none;
            color: white;
            font-weight: bold;
            z-index: 10;
            position: relative;
        }
        nav a {
            color: white;
            margin-left: 1em;
            text-decoration: none;
            font-weight: bold;
        }
        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .animated-image-right {
            z-index: 1;
            width: 600px;
            position: absolute;
            top: 450px;
            right: 200px;
            animation: moveR 15s infinite;
        }
        .animated-image-left {
            z-index: 3;
            width: 400px;
            position: absolute;
            top: 450px;
            left: 200px;
            animation: moveL 15s infinite;
        }
        @keyframes moveR {
            0%, 100% { transform: translateX(0) scale(0.9); }
            50% { transform: translateX(20px) scale(1.0); }
        }
        @keyframes moveL {
            0%, 100% { transform: translateX(0) scale(0.9); }
            50% { transform: translateX(-20px) scale(1.0); }
        }
        .text-overlay-left {
            z-index: 2;
            position: absolute;
            top: 290px;
            left: 200px;
            color: white;
            font-size: 90px;
            font-weight: bold;
            font-family: 'Open Sans', sans-serif;
        }
        .text-overlay-right {
            z-index: 4;
            position: absolute;
            top: 290px;
            right: 200px;
            color: white;
            font-size: 90px;
            font-weight: bold;
            font-family: 'Open Sans', sans-serif;
            text-align: right;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2em;
            justify-content: center;
            padding: 2em;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 1em;
            text-align: center;
            width: 220px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .product-card img {
            width: 100%;
            border-radius: 10px;
        }
        .product-card button {
            margin-top: 0.5em;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="user-label">🔑 Logged in as: <?= htmlspecialchars($_SESSION["username"]) ?></div>

<video class="background-video" autoplay loop muted>
    <source src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/1-1.mp4" type="video/mp4">
</video>

<div class="text-overlay-left">Chocolate<br>makes</div>
<div class="text-overlay-right">Everything<br>better.</div>

<!-- Bigger Chocolate under text -->
<img src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/Youtube-Thumbails-13-1-1024x576.png" class="animated-image-left" alt="Chocolate Left">
<img src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/Youtube-Thumbails-16-1-768x432.png" class="animated-image-right" alt="Chocolate Right">

<header>
    <h1>Welcome to My Shop</h1>
    <nav>
        <a href="userhome.php">Home</a>
        <a href="about.php">About</a>
        <a href="checkout.php">Checkout</a>
        <a href="contact_us.php">Contact</a>
        <a href="user_orders.php">View Order Status</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<section class="product-grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
            $name = htmlspecialchars($row["name"], ENT_QUOTES);
            $desc = htmlspecialchars($row["description"], ENT_QUOTES);
            $image = htmlspecialchars($row["name"]) . ".jpg";
        ?>
        <div class="product-card">
            <img src="images/<?= $image ?>" alt="<?= $name ?>">
            <h2><?= $name ?></h2>
            <p>RM<?= number_format($row["price"], 2) ?></p>
            <p><?= $desc ?></p>
            <button onclick="addToCart('<?= $name ?>', <?= $row["price"] ?>)">Add to Cart</button>
            <button onclick="viewProduct('<?= $name ?>', <?= $row["price"] ?>, '<?= $image ?>', '<?= $desc ?>')">View</button>
        </div>
    <?php endwhile; ?>
</section>

</body>
</html>
