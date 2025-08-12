<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "admin") {
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

// ✅ Add Staff
if (isset($_POST["add_staff"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password, usertype, verified) VALUES (?, ?, ?, 'staff', 1)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $password);
    mysqli_stmt_execute($stmt);
}

// ✅ Delete Staff
if (isset($_GET["delete_staff"])) {
    $id = $_GET["delete_staff"];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id AND usertype = 'staff'");
}

// ✅ Delete User
if (isset($_GET["delete_user"])) {
    $id = $_GET["delete_user"];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id AND usertype = 'user'");
}

// ✅ Manage Orders (Update & Delete)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_order"])) {
        $oid = $_POST["order_id"];
        $status = $_POST["status"];
        mysqli_query($conn, "UPDATE orders SET fulfillment_status = '$status' WHERE id = $oid");
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
    if (isset($_POST["delete_order"])) {
        $oid = $_POST["delete_order"];
        mysqli_query($conn, "DELETE FROM orders WHERE id = $oid");
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// ✅ About Page Text File
$aboutFile = "about.txt";
if (!file_exists($aboutFile)) {
    file_put_contents($aboutFile, "📚 Welcome to MyShop\n\nAt MyShop, we curate a wide selection of bestsellers, indie gems, and must-reads to suit every reader.\n\nPlease login or register to unlock member pricing and get updates on new releases!");
}
if (isset($_POST["update_about"])) {
    file_put_contents($aboutFile, $_POST["about_content"]);
    $aboutMessage = "✅ About Page Updated!";
}
$aboutContent = htmlspecialchars(file_get_contents($aboutFile));

// ✅ Contact Info JSON File
$contactInfoFile = "contact_info.txt";
if (!file_exists($contactInfoFile)) {
    $default = json_encode([
        "address" => "198 West 21th Street, Suite 721, New York NY 10016",
        "phone" => "+1235 2355 98",
        "email" => "info@yoursite.com",
        "website" => "yoursite.com"
    ]);
    file_put_contents($contactInfoFile, $default);
}
$contactInfo = json_decode(file_get_contents($contactInfoFile), true);

if (isset($_POST["update_contact_info"])) {
    $contactInfo = [
        "address" => $_POST["address"],
        "phone" => $_POST["phone"],
        "email" => $_POST["email"],
        "website" => $_POST["website"]
    ];
    file_put_contents($contactInfoFile, json_encode($contactInfo));
    $contactMessage = "✅ Contact Information Updated!";
}

// ✅ Fetch Lists
$products = mysqli_query($conn, "SELECT * FROM products");
$staffList = mysqli_query($conn, "SELECT * FROM users WHERE usertype = 'staff'");
$userList = mysqli_query($conn, "SELECT * FROM users WHERE usertype = 'user'");
$feedbackList = mysqli_query($conn, "SELECT * FROM feedback ORDER BY submitted_at DESC");
$orders = mysqli_query($conn, "SELECT * FROM orders ORDER BY order_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
        textarea, input, select { width: 100%; padding: 8px; margin-bottom: 10px; }
    </style>
</head>
<body>

<h1>👑 Admin Dashboard: <?= htmlspecialchars($_SESSION["username"]) ?></h1>
<nav><a href="logout.php">Logout</a>
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
    <input type="text" name="name" placeholder="Name" required>
    <input type="number" step="0.01" name="price" placeholder="Price (RM)" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <input type="number" name="stock" placeholder="Stock" required>
    <button type="submit" name="add_product">Add Product</button>
</form>

<h2>👨‍💼 Manage Staff</h2>
<table>
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr>
    <?php while ($s = mysqli_fetch_assoc($staffList)): ?>
        <tr>
            <td><?= $s["id"] ?></td>
            <td><?= htmlspecialchars($s["username"]) ?></td>
            <td><?= htmlspecialchars($s["email"]) ?></td>
            <td><a href="?delete_staff=<?= $s["id"] ?>" onclick="return confirm('Delete this staff?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<form method="POST">
    <h3>➕ Add Staff</h3>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="password" placeholder="Password" required>
    <button type="submit" name="add_staff">Add Staff</button>
</form>

<h2>👥 Registered Users</h2>
<table>
    <tr><th>ID</th><th>Username</th><th>Email</th><th>Verified</th><th>Action</th></tr>
    <?php while ($u = mysqli_fetch_assoc($userList)): ?>
        <tr>
            <td><?= $u["id"] ?></td>
            <td><?= htmlspecialchars($u["username"]) ?></td>
            <td><?= htmlspecialchars($u["email"]) ?></td>
            <td><?= $u["verified"] ? "✅" : "❌" ?></td>
            <td><a href="?delete_user=<?= $u["id"] ?>" onclick="return confirm('Delete this user?')">Delete</a></td>
        </tr>
    <?php endwhile; ?>
</table>

<h2>📥 User Feedback</h2>
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th></tr>
    <?php while ($f = mysqli_fetch_assoc($feedbackList)): ?>
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

<h2>📚 Edit About Page</h2>
<?php if (isset($aboutMessage)) echo "<p style='color:lime;'>$aboutMessage</p>"; ?>
<form method="POST">
    <textarea name="about_content" required><?= $aboutContent ?></textarea><br><br>
    <button type="submit" name="update_about">Update About Page</button>
</form>

<h2>📞 Edit Contact Information</h2>
<?php if (isset($contactMessage)) echo "<p style='color:lime;'>$contactMessage</p>"; ?>
<form method="POST">
    <input type="text" name="address" value="<?= htmlspecialchars($contactInfo["address"]) ?>" required>
    <input type="text" name="phone" value="<?= htmlspecialchars($contactInfo["phone"]) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($contactInfo["email"]) ?>" required>
    <input type="text" name="website" value="<?= htmlspecialchars($contactInfo["website"]) ?>" required>
    <button type="submit" name="update_contact_info">Update Contact Info</button>
</form>

</body>
</html>
