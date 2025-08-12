<?php
session_start();
if (!isset($_SESSION["username"]) || ($_SESSION["usertype"] !== "admin" && $_SESSION["usertype"] !== "staff")) {
    header("Location: login.php");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");
$result = mysqli_query($conn, "SELECT * FROM feedback ORDER BY submitted_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feedback Messages</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #121212; color: #fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 2em; background: #1e1e1e; }
        th, td { border: 1px solid #444; padding: 10px; }
        th { background: #333; }
    </style>
</head>
<body>
<h1>📥 Feedback Messages</h1>
<a href="<?= $_SESSION["usertype"] === "admin" ? 'adminhome.php' : 'staffhome.php' ?>" style="color: #fff;">⬅ Back to Home</a>

<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Submitted At</th></tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= htmlspecialchars($row["name"]) ?></td>
            <td><?= htmlspecialchars($row["email"]) ?></td>
            <td><?= htmlspecialchars($row["message"]) ?></td>
            <td><?= $row["submitted_at"] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>
