<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "staff") {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

// ✅ Add Product
if (isset($_POST["add_product"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $stock = $_POST["stock"];
    $stmt = mysqli_prepare($conn, "INSERT INTO products (name, price, description, stock) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sdsi", $name, $price, $description, $stock);
    mysqli_stmt_execute($stmt);
}

// ✅ Delete Product
if (isset($_GET["delete_product"])) {
    $id = $_GET["delete_product"];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
}

// ✅ Delete User (customer)
if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id AND usertype = 'user'");
}

// ✅ Fetch Lists
$products = mysqli_query($conn, "SELECT * FROM products");
$userList = mysqli_query($conn, "SELECT * FROM users WHERE usertype = 'user'");
$feedbackResult = mysqli_query($conn, "SELECT * FROM feedback ORDER BY submitted_at DESC");
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");

// ✅ Read About & Contact Content
$aboutContent = file_exists("about.html") ? file_get_contents("about.html") : "About page not created yet.";
$contactContent = file_exists("contact_us.html") ? file_get_contents("contact_us.html") : "Contact page not created yet.";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #121212; color: #fff; font-family: Arial; }
        table { width: 100%; background: #1e1e1e; color: #fff; border-collapse: collapse; margin-bottom: 2em; }
        th, td { padding: 10px; border: 1px solid #333; text-align: center; }
        th { background: #333; }
        form { background: #1e1e1e; padding: 1em; margin-bottom: 2em; }
        h2 { color: #ffa500; }
        a { color: #ff4c4c; text-decoration: none; }
        a:hover { text-decoration: underline; }
        nav a { color: #fff; margin-right: 1em; }
        pre.readonly-box {
            background: #1e1e1e; 
            padding: 1em; 
            border: 1px solid #333; 
            white-space: pre-wrap; 
            word-wrap: break-word;
            margin-bottom: 2em;
        }
    </style>
</head>
<body>

<h1>👷 Staff Dashboard: <?= htmlspecialchars($_SESSION["username"]) ?></h1>
<nav>
    <a href="logout.php">Logout</a>
	<a href="profile.php">Profile</a>
</nav>

<h2>📦 Manage Products</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Price (RM)</th><th>Description</th><th>Stock</th><th>Action</th></tr>
    <?php while ($p = mysqli_fetch_assoc($products)): ?>
        <tr>
            <td><?= $p["id"] ?></td>
            <td><?= htmlspecialchars($p["name"]) ?></td>
            <td><?= number_format($p["price"], 2) ?></td>
            <td><?= htmlspecialchars($p["description"]) ?></td>
            <td><?= $p["stock"] ?></td>
            <td><a href="?delete_product=<?= $p["id"] ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<form method="POST">
    <h3>➕ Add Product</h3>
    <label>Name: <input type="text" name="name" required></label><br><br>
    <label>Price (RM): <input type="number" step="0.01" name="price" required></label><br><br>
    <label>Description:<br><textarea name="description" required></textarea></label><br><br>
    <label>Stock: <input type="number" name="stock" required></label><br><br>
    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>👥 View & Manage Customers</h2>
<table>
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Verified</th><th>Action</th></tr>
    <?php while ($u = mysqli_fetch_assoc($userList)): ?>
        <tr>
            <td><?= $u["id"] ?></td>
            <td><?= htmlspecialchars($u["username"]) ?></td>
            <td><?= htmlspecialchars($u["email"]) ?></td>
            <td><?= $u["verified"] ? "✅" : "❌" ?></td>
            <td><a href="?delete_user=<?= $u["id"] ?>" onclick="return confirm('Delete this customer?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>📥 User Feedback</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th></tr>
    <?php while ($f = mysqli_fetch_assoc($feedbackResult)): ?>
    <tr>
        <td><?= $f["id"] ?></td>
        <td><?= htmlspecialchars($f["name"]) ?></td>
        <td><?= htmlspecialchars($f["email"]) ?></td>
        <td><?= htmlspecialchars($f["message"]) ?></td>
        <td><?= $f["submitted_at"] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>📋 Manage Orders</h2>
<table>
    <tr><th>ID</th><th>User</th><th>Date</th><th>Total (RM)</th><th>Payment</th><th>Fulfillment</th><th>Action</th></tr>
    <?php while ($o = mysqli_fetch_assoc($orders)): ?>
    <tr>
        <td><?= $o["id"] ?></td>
        <td><?= htmlspecialchars($o["username"]) ?></td>
        <td><?= $o["order_date"] ?></td>
        <td><?= number_format($o["total"], 2) ?></td>
        <td><?= htmlspecialchars($o["payment_status"]) ?></td>
        <td><?= htmlspecialchars($o["fulfillment_status"]) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $o["id"] ?>">
                <select name="status">
                    <option <?= $o["fulfillment_status"] === "Not Yet" ? 'selected' : '' ?>>Not Yet</option>
                    <option <?= $o["fulfillment_status"] === "Partly Fulfilled" ? 'selected' : '' ?>>Partly Fulfilled</option>
                    <option <?= $o["fulfillment_status"] === "Fulfilled" ? 'selected' : '' ?>>Fulfilled</option>
                </select>
                <button type="submit" name="update_order">Update</button>
            </form>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="delete_order" value="<?= $o["id"] ?>">
                <button type="submit" onclick="return confirm('Delete order?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
// ✅ Handle order update/delete
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_order"])) {
        $oid = $_POST["order_id"];
        $status = $_POST["status"];
        mysqli_query($conn, "UPDATE orders SET fulfillment_status = '$status' WHERE id = $oid");
        header("Location: ".$_SERVER['PHP_SELF']);
    }
    if (isset($_POST["delete_order"])) {
        $oid = $_POST["delete_order"];
        mysqli_query($conn, "DELETE FROM orders WHERE id = $oid");
        header("Location: ".$_SERVER['PHP_SELF']);
    }
}
?>

<h2>📖 About Page (View Only)</h2>
<pre class="readonly-box"><?= htmlspecialchars($aboutContent) ?></pre>

<h2>📞 Contact Us Page (View Only)</h2>
<pre class="readonly-box"><?= htmlspecialchars($contactContent) ?></pre>

</body>
</html>
