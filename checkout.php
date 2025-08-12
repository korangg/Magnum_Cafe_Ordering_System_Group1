<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);

$success = false;
$error = "";
$invoice_id = "INV" . strtoupper(uniqid());

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["checkout"])) {
    $cart = json_decode($_POST["cart"], true);
    $totalAmount = 0;

    foreach ($cart as $item) {
        $name = $item["name"];
        $qty = intval($item["qty"]);
        $price = floatval($item["price"]);
        $totalAmount += $qty * $price;

        $product = mysqli_query($conn, "SELECT * FROM products WHERE name = '" . mysqli_real_escape_string($conn, $name) . "'");
        if ($row = mysqli_fetch_assoc($product)) {
            if ($row["stock"] >= $qty) {
                $new_stock = $row["stock"] - $qty;
                $pid = $row["id"];
                mysqli_query($conn, "UPDATE products SET stock = $new_stock WHERE id = $pid");
                mysqli_query($conn, "INSERT INTO sales (product_id, quantity, sale_date) VALUES ($pid, $qty, NOW())");
            } else {
                $error .= "Not enough stock for " . htmlspecialchars($name) . "<br>";
            }
        } else {
            $error .= "Product not found: " . htmlspecialchars($name) . "<br>";
        }
    }

    if ($error === "") {
        $username = $_SESSION["username"];
        mysqli_query($conn, "INSERT INTO orders (username, total, payment_status, fulfillment_status, order_date) VALUES (
            '" . mysqli_real_escape_string($conn, $username) . "',
            $totalAmount,
            'Paid',
            'Not Yet',
            NOW()
        )");

        $success = true;

        $emailQuery = mysqli_query($conn, "SELECT email FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'");
        $userRow = mysqli_fetch_assoc($emailQuery);
        $userEmail = $userRow["email"] ?? null;

        if ($userEmail) {
            require 'phpmailer/PHPMailer.php';
            require 'phpmailer/SMTP.php';
            require 'phpmailer/Exception.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $gmailUser = "mustafa5252005@gmail.com";
                $gmailAppPassword = "zqnfwbfchcnrtmhr";  // Your App Password

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $gmailUser;
                $mail->Password = $gmailAppPassword;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom($gmailUser, 'MyShop');
                $mail->addAddress($userEmail, $username);
                $mail->isHTML(true);
                $mail->Subject = '🧾 Your Order Receipt - ' . $invoice_id;
                $mail->Body = "<h2>Thank you for your purchase, $username!</h2>
                    <p>Click below to view your receipt:</p>
                    <a href='http://localhost/ntah_ah/ecommerce_website_final/receipt.php?invoice=$invoice_id'
                       style='background:#4CAF50;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;'>View Receipt</a>
                    <p><strong>Invoice ID:</strong> $invoice_id</p>";

                $mail->send();
            } catch (Exception $e) {
                error_log("❌ Email receipt failed: " . $mail->ErrorInfo);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="style.css">
    <script src="cart.js" defer></script>
   <script src="https://www.paypal.com/sdk/js?client-id=ATnhrGFD1FVMnnDXhVoW6GpMZl5Tk8qB5JyTtiEI_MLM4jInsLcNrAldoiRmyNJl_YtuM1ZwM4Yz9Xr-&currency=MYR"></script>
</head>
<body>
<header>
    <h1>Checkout</h1>
    <nav>
        <a href="userhome.php">Home</a>
        <a href="about.php">About</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main>
<?php if ($success): ?>
    <script>
        const cart = localStorage.getItem("cart");
        localStorage.setItem("lastReceipt", cart);
        localStorage.removeItem("cart");
        window.location.href = "receipt.php?invoice=<?= $invoice_id ?>";
    </script>
<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <h2>⚠️ Error during checkout:</h2>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<section>
    <ul id="cart-items"></ul>
    <div class="total-box">
        <strong>Total:</strong> RM <span id="total">0.00</span>
    </div>
</section>

<form method="POST" onsubmit="return submitCart()">
    <input type="hidden" name="cart" id="cartData">
    <button type="submit" name="checkout">Manual Pay Now</button>
</form>

<div style="margin-top:2em; text-align:center;">
    <h3>💳 Or Pay with PayPal</h3>
    <div id="paypal-button-container"></div>
</div>

<div style="margin-top:2em; text-align:center;">
    <a href="userhome.php"><button>⬅ Return to Homepage</button></a>
</div>
</main>

<script>
let finalAmount = 0;

function submitCart() {
    document.getElementById("cartData").value = localStorage.getItem("cart");
    return true;
}

function calculateCartTotal() {
    const cart = JSON.parse(localStorage.getItem("cart")) || [];
    let total = 0;
    cart.forEach(item => {
        total += item.qty * item.price;
    });
    document.getElementById("total").innerText = total.toFixed(2);
    return total;
}

function updatePayPalButton() {
    if (typeof paypal !== 'undefined') {
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: finalAmount.toFixed(2)
                        }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    alert('✅ Payment completed by ' + details.payer.name.given_name);
                    localStorage.setItem("lastReceipt", localStorage.getItem("cart"));
                    localStorage.removeItem("cart");
                    window.location.href = "receipt.php?invoice=<?= $invoice_id ?>";
                });
            }
        }).render('#paypal-button-container');
    }
}

window.addEventListener("DOMContentLoaded", () => {
    finalAmount = calculateCartTotal();
    if (finalAmount > 0) updatePayPalButton();
});
</script>
</body>
</html>
