<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
$username = $_SESSION["username"];

// Fetch orders for this user
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE username = '$username' ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>My Orders</title>

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&family=Poppins:wght@100;300;400;500;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            background-color: var(--background-color);
            color: var(--default-color);
            font-family: var(--default-font);
            margin: 0;
            padding: 0;
        }
        .section {
            padding: 60px 0;
        }
        .section-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .section-title h2 {
            font-size: 14px; /* Small size */
            font-weight: 500;
            text-transform: uppercase;
        }
        .section-title h3 {
            font-size: 36px; /* Big size */
            font-weight: 600;
            color: #cda45e; /* Set to desired color */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface-color);
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        th {
            background: var(--accent-color);
            color: var(--heading-color);
        }
        td {
            color: var(--default-color);
        }
        nav a {
            color: var(--accent-color);
            margin-right: 15px;
        }
    </style>
</head>
<body>

<header id="header" class="header fixed-top">
    <div class="branding d-flex align-items-center">
        <div class="container d-flex justify-content-between">
            <a href="userhome.php" class="logo d-flex align-items-center">
                <h1 class="sitename">Magnum Cafe</h1>
            </a>
            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="userhome.php">Home</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="section">
    <div class="container">
        <div class="section-title" style="margin-top: 20px;">
            <h2>Order Status</h2>
            <h3>Review Your Orders</h3>
        </div>

        <?php if (mysqli_num_rows($orders) > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Payment Status</th>
                <th>Fulfillment Status</th>
                <th>Total (RM)</th>
            </tr>
            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
            <tr>
                <td><?= htmlspecialchars($order["id"]) ?></td>
                <td><?= htmlspecialchars($order["order_date"]) ?></td>
                <td><?= htmlspecialchars($order["payment_status"]) ?></td>
                <td><?= htmlspecialchars($order["fulfillment_status"]) ?></td>
                <td><?= number_format($order["total"], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>
    </div>
</main>

</body>
</html>