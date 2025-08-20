<?php
header('Content-Type: application/json');

// NOTE: Please update these database credentials if they are different
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    echo json_encode(['stock' => 0]); // Return 0 stock on connection failure
    exit();
}

$productName = isset($_GET['name']) ? mysqli_real_escape_string($conn, $_GET['name']) : '';

$query = "SELECT stock FROM products WHERE name = '$productName'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(['stock' => (int)$row['stock']]);
} else {
    echo json_encode(['stock' => 0]);
}

mysqli_close($conn);
?>
