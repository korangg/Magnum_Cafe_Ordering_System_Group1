<?php
session_start();
// Check if the user is logged in as 'user'
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}

// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$db = "ecommerce_db";
$conn = mysqli_connect($host, $user, $password, $db);

// Check for database connection errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = false;
$error = "";
// Generate a unique invoice ID
$invoice_id = "INV" . strtoupper(uniqid());

/**
 * Handles the order processing logic.
 *
 * @param array $cart The user's cart items.
 * @param mysqli $conn The database connection object.
 * @param string $username The current user's username.
 * @param string $invoice_id The unique invoice ID.
 * @return array An array containing a success boolean and any error messages.
 */
function process_order($cart, $conn, $username, $invoice_id) {
    $totalAmount = 0;
    $error = "";

    // Process each item in the cart
    foreach ($cart as $item) {
        $name = $item["name"];
        $qty = intval($item["qty"]);
        $price = floatval($item["price"]);
        $totalAmount += $qty * $price;

        // Check product stock and update it
        $product = mysqli_query($conn, "SELECT id, stock FROM products WHERE name = '" . mysqli_real_escape_string($conn, $name) . "'");
        if ($row = mysqli_fetch_assoc($product)) {
            if ($row["stock"] >= $qty) {
                $new_stock = $row["stock"] - $qty;
                $pid = $row["id"];
                mysqli_query($conn, "UPDATE products SET stock = $new_stock WHERE id = $pid");
                mysqli_query($conn, "INSERT INTO sales (product_id, quantity, sale_date) VALUES ($pid, $qty, NOW())");
            } else {
                $error .= "Not enough stock for " . htmlspecialchars($name) . ".<br>";
            }
        } else {
            $error .= "Product not found: " . htmlspecialchars($name) . ".<br>";
        }
    }

    // If there are no errors, finalize the order
    if ($error === "") {
        mysqli_query($conn, "INSERT INTO orders (username, total, payment_status, fulfillment_status, order_date) VALUES (
            '" . mysqli_real_escape_string($conn, $username) . "',
            $totalAmount,
            'Paid',
            'Not Yet',
            NOW()
        )");

        // Get user's email for sending receipt
        $emailQuery = mysqli_query($conn, "SELECT email FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'");
        $userRow = mysqli_fetch_assoc($emailQuery);
        $userEmail = $userRow["email"] ?? null;

        if ($userEmail) {
            // Include PHPMailer files
            require 'phpmailer/PHPMailer.php';
            require 'phpmailer/SMTP.php';
            require 'phpmailer/Exception.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $gmailUser = "mustafa5252005@gmail.com";
                $gmailAppPassword = "zqnfwbfchcnrtmhr"; // Your App Password

                // Configure SMTP settings for Gmail
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $gmailUser;
                $mail->Password = $gmailAppPassword;
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Set email content
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
                // Log email errors without stopping the script
                error_log("❌ Email receipt failed: " . $mail->ErrorInfo);
            }
        }

        return ['success' => true, 'invoice_id' => $invoice_id];
    } else {
        return ['success' => false, 'error' => $error];
    }
}

// Handle traditional form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["checkout"])) {
    $cart = json_decode($_POST["cart"], true);
    $result = process_order($cart, $conn, $_SESSION["username"], $invoice_id);
    $success = $result['success'];
    if (!$success) {
        $error = $result['error'];
    }
}

// Handle AJAX call from PayPal
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["paypal_checkout"])) {
    header('Content-Type: application/json');
    $cart = json_decode($_POST["cart"], true);
    $result = process_order($cart, $conn, $_SESSION["username"], $invoice_id);
    echo json_encode($result);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- PayPal SDK is included correctly -->
    <script src="https://www.paypal.com/sdk/js?client-id=ATnhrGFD1FVMnnDXhVoW6GpMZl5Tk8qB5JyTtiEI_MLM4jInsLcNrAldoiRmyNJl_YtuM1ZwM4Yz9Xr-&currency=MYR"></script>
    <!-- Custom Styles from your previous dark theme template -->
    <style>
        /* Font & Color Variables */
        :root {
            --default-font: "Roboto", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --heading-font: "Playfair Display", sans-serif;
            --nav-font: "Poppins", sans-serif;
        }

        /* Global Colors - The following color variables are used throughout the website. Updating them here will change the color scheme of the entire website */
        :root {
            --background-color: #0c0b09; /* Background color for the entire website, including individual sections */
            --default-color: rgba(255, 255, 255, 0.7); /* Default color used for the majority of the text content across the entire website */
            --heading-color: #ffffff; /* Color for headings, subheadings and title throughout the website */
            --accent-color: #cda45e; /* Accent color that represents your brand on the website. It's used for buttons, links, and other elements that need to stand out */
            --surface-color: #29261f; /* The surface color is used as a background of boxed elements within sections, such as cards, icon boxes, or other elements that require a visual separation from the global background. */
            --contrast-color: #0c0b09; /* Contrast color for text, ensuring readability against backgrounds of accent, heading, or default colors. */
        }

        /* Nav Menu Colors - The following color variables are used specifically for the navigation menu. They are separate from the global colors to allow for more customization options */
        :root {
            --nav-color: #ffffff; /* The default color of the main navmenu links */
            --nav-hover-color: #cda45e; /* Applied to main navmenu links when they are hovered over or active */
            --nav-mobile-background-color: #29261f; /* Used as the background color for mobile navigation menu */
            --nav-dropdown-background-color: #29261f; /* Used as the background color for dropdown items that appear when hovering over primary navigation items */
            --nav-dropdown-color: #ffffff; /* Used for navigation links of the dropdown items in the navigation menu. */
            --nav-dropdown-hover-color: #cda45e; /* Similar to --nav-hover-color, this color is applied to dropdown navigation links when they are hovered over. */
        }

        /* Color Presets - These classes override global colors when applied to any section or element, providing reuse of the sam color scheme. */
        .light-background { --background-color: #29261f; --surface-color: #464135; }
        .dark-background { --background-color: #000000; --default-color: #ffffff; --heading-color: #ffffff; --surface-color: #1a1a1a; --contrast-color: #ffffff; }

        /* General Styling & Shared Classes */
        body { color: var(--default-color); background-color: var(--background-color); font-family: var(--default-font); margin: 0; padding: 0; }
        a { color: var(--accent-color); text-decoration: none; transition: 0.3s; }
        a:hover { color: color-mix(in srgb, var(--accent-color), transparent 25%); text-decoration: none; }
        h1, h2, h3, h4, h5, h6 { color: var(--heading-color); font-family: var(--heading-font); }

        /* Global Header */
        .header { --background-color: rgba(12, 11, 9, 0.61); color: var(--default-color); transition: all 0.5s; z-index: 997; }
        .header .topbar { height: 40px; padding: 0; font-size: 14px; transition: all 0.5s; }
        .header .topbar .contact-info i { font-style: normal; color: var(--accent-color); }
        .header .branding { background-color: var(--background-color); min-height: 60px; padding: 10px 0; transition: 0.3s; border-bottom: 1px solid var(--background-color); }
        .header .logo { line-height: 1; }
        .header .logo h1 { font-size: 30px; margin: 0; font-weight: 700; color: var(--heading-color); }
        
        /* Navigation Menu */
        .navmenu ul { margin: 0; padding: 0; display: flex; list-style: none; align-items: center; }
        .navmenu a, .navmenu a:focus { color: var(--nav-color); padding: 18px 15px; font-size: 14px; font-family: var(--nav-font); font-weight: 400; display: flex; align-items: center; justify-content: space-between; white-space: nowrap; transition: 0.3s; }
        .navmenu li:hover>a, .navmenu .active, .navmenu .active:focus { color: var(--nav-hover-color); }

        /* Global Sections */
        section, .section { color: var(--default-color); background-color: var(--background-color); padding: 60px 0; scroll-margin-top: 77px; overflow: clip; }
        .section-title { padding-bottom: 60px; position: relative; }
        .section-title h2 { font-size: 14px; font-weight: 500; padding: 0; line-height: 1px; margin: 0; letter-spacing: 1.5px; text-transform: uppercase; color: color-mix(in srgb, var(--default-color), transparent 30%); position: relative; }
        .section-title h2::after { content: ""; width: 120px; height: 1px; display: inline-block; background: var(--accent-color); margin: 4px 10px; }
        .section-title p { color: var(--accent-color); margin: 0; font-size: 36px; font-weight: 600; font-family: var(--heading-font); }

        /* Full-page checkout specific styles */
        .checkout-page {
            width: 100%;
            min-height: calc(100vh - 80px); /* Full height minus header */
            padding: 40px 5%;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
        }

        .checkout-container {
            width: 100%;
            display: grid;
            grid-template-columns: 1fr;
            gap: 40px;
            background-color: var(--surface-color);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            max-width: 1200px;
        }
        
        .checkout-container h3 {
            color: var(--heading-color);
            font-size: 2em;
            margin-bottom: 20px;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 5px;
            color: var(--default-color);
        }

        .checkout-form input[type="text"],
        .checkout-form input[type="email"],
        .checkout-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--accent-color);
            border-radius: 5px;
            background-color: var(--background-color);
            color: var(--default-color);
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .checkout-form button {
            padding: 15px 30px;
            background-color: var(--accent-color);
            color: var(--contrast-color);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1em;
            text-transform: uppercase;
            font-weight: bold;
            transition: background-color 0.2s ease;
            width: 100%;
            margin-top: 20px;
        }
        
        .checkout-form button:hover {
            background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
        }

        .order-summary-box {
            background-color: color-mix(in srgb, var(--background-color), transparent 20%);
            padding: 30px;
            border-radius: 8px;
        }

        .order-summary-box ul {
            list-style: none;
            padding: 0;
        }

        .order-summary-box li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1.1em;
            color: var(--default-color);
        }

        .order-summary-box li:last-child {
            margin-bottom: 0;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .order-summary-box .total {
            font-size: 1.5em;
            color: var(--accent-color);
            font-weight: bold;
        }

        .message-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }

        .message-content {
            background-color: var(--surface-color);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .message-content h3 {
            margin-top: 0;
            color: var(--heading-color);
        }
        
        .message-content p {
            font-size: 1em;
            color: var(--default-color);
        }

        .message-content button {
            padding: 10px 20px;
            margin-top: 20px;
            background-color: var(--accent-color);
            color: var(--contrast-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (min-width: 992px) {
            .checkout-container {
                grid-template-columns: 2fr 1fr;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="userhome.php" class="logo d-flex align-items-center me-auto me-lg-0">
            <h1>Restaurantly<span>.</span></h1>
        </a>
        <nav class="navmenu">
            <ul>
                <li><a href="userhome.php">Home</a></li>
                <li><a href="userhome.php#menu">Menu</a></li>
         
                <li><a href="cart.php">Cart</a></li>
               
            </ul>
        </nav>
    </div>
</header>

<main class="section checkout-page">
    <div class="checkout-container">
        <!-- Billing details and manual pay form -->
        <div class="checkout-form">
            <div class="section-title">
                <h2>Billing Details</h2>
                <p>Confirm Your Information</p>
            </div>
            <form id="manual-checkout-form" method="POST" onsubmit="return submitCart()">
                <input type="hidden" name="cart" id="cartData">
                <!-- Placeholder for billing form fields if needed -->
                <button type="submit" name="checkout">Manual Pay Now</button>
            </form>
            
            <!-- PayPal button container -->
            <div style="margin-top:2em;">
                <div class="section-title" style="padding-bottom: 20px;">
                    <h3>💳 Or Pay with PayPal</h3>
                </div>
                <div id="paypal-button-container"></div>
            </div>

            <!-- Return button -->
            <div class="return-button-container">
                <a href="userhome.php"><button>⬅ Return to Homepage</button></a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary-box">
            <div class="section-title" style="padding-bottom: 20px;">
                <h3>Your Order</h3>
            </div>
            <div id="orderSummaryList">
                <!-- Order summary items will be rendered here by JavaScript -->
            </div>
        </div>
    </div>
</main>

<!-- Message Modal for successful or failed payments -->
<div id="messageModal" class="message-modal">
    <div class="message-content">
        <h3 id="modalTitle"></h3>
        <p id="modalMessage"></p>
        <button onclick="hideMessage()">OK</button>
    </div>
</div>

<script>
    let finalAmount = 0;

    /**
     * Shows a custom modal with a title and message.
     * @param {string} title - The title of the message.
     * @param {string} message - The content of the message.
     */
    function showMessage(title, message) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('messageModal').style.display = 'flex';
    }

    /**
     * Hides the custom modal.
     */
    function hideMessage() {
        document.getElementById('messageModal').style.display = 'none';
    }

    /**
     * Prepares the cart data for manual submission.
     * @returns {boolean} Always returns true to allow form submission.
     */
    function submitCart() {
        document.getElementById("cartData").value = localStorage.getItem("cart");
        return true;
    }

    /**
     * Renders the cart items and calculates the total amount.
     * @returns {number} The total amount.
     */
    function renderOrderSummary() {
        const orderSummaryList = document.getElementById('orderSummaryList');
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let html = '<ul>';
        let total = 0;

        if (cart.length > 0) {
            cart.forEach(item => {
                const itemTotal = item.price * item.qty;
                total += itemTotal;
                html += `
                    <li>
                        <span>${item.name} (${item.qty})</span>
                        <span>RM ${itemTotal.toFixed(2)}</span>
                    </li>
                `;
            });
            html += `
                <li style="font-size: 1.5em; font-weight: bold; margin-top: 20px;">
                    <span>Total</span>
                    <span class="total">RM ${total.toFixed(2)}</span>
                </li>
            `;
        } else {
            html += '<li>Your cart is empty.</li>';
        }

        html += '</ul>';
        orderSummaryList.innerHTML = html;
        return total;
    }

    /**
     * Renders the PayPal buttons after the page has loaded.
     */
    function renderPayPalButtons() {
        if (typeof paypal !== 'undefined') {
            paypal.Buttons({
                // Create the order on PayPal with the calculated total
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: finalAmount.toFixed(2)
                            }
                        }]
                    });
                },
                // Handle the successful payment
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Use fetch to send cart data to the PHP script for processing
                        const cartData = new FormData();
                        cartData.append('cart', localStorage.getItem("cart"));
                        cartData.append('paypal_checkout', '1'); // A flag to tell PHP it's a PayPal payment

                        // Make an asynchronous request to the same PHP file
                        fetch('checkout.php', {
                            method: 'POST',
                            body: cartData
                        })
                        .then(response => response.json())
                        .then(result => {
                            // Check the server's response
                            if (result.success) {
                                // Clear the local cart and redirect to the receipt page
                                localStorage.setItem("lastReceipt", localStorage.getItem("cart"));
                                localStorage.removeItem("cart");
                                showMessage('Order Placed!', 'Thank you for your order! It has been successfully placed.');
                                setTimeout(() => {
                                    window.location.href = `receipt.php?invoice=${result.invoice_id}`;
                                }, 3000);
                            } else {
                                // Show the error from the server
                                showMessage('Error', '⚠️ ' + result.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showMessage('Error', '⚠️ An unexpected error occurred. Please try again.');
                        });
                    });
                },
                // Handle payment cancellation
                onCancel: function(data) {
                    console.log('Payment was cancelled');
                }
            }).render('#paypal-button-container');
        }
    }

    // Initial setup on page load
    window.addEventListener("DOMContentLoaded", () => {
        finalAmount = renderOrderSummary();
        if (finalAmount > 0) {
            renderPayPalButtons();
        }
    });
</script>
</body>
</html>
