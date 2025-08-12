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
    <meta charset="UTF-8">
    <title>My Orders</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; background: #f9f9f9; color: #333; padding: 2em; }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 2em; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #f0f0f0; }
        a { color: #007BFF; text-decoration: none; }
        a:hover { text-decoration: underline; }
        nav a { margin-right: 1em; }
    </style>
</head>
<body>

<h1>📋 My Order Status</h1>
<nav>
    <a href="userhome.php">Home</a>
    <a href="logout.php">Logout</a>
</nav>

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

</body>
</html>
