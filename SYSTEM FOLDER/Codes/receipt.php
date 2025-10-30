<?php
session_start();

// ✅ Ensure only logged-in users of type 'user' can view
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}

/*
----------------------------------------------------
|  Handle Invoice ID: from URL or Session fallback  |
----------------------------------------------------
*/
if (isset($_GET['invoice'])) {
    // If invoice is passed in URL (after PayPal), save it in session
    $_SESSION['last_invoice'] = $_GET['invoice'];
    $invoiceId = $_GET['invoice'];
} elseif (isset($_SESSION['last_invoice'])) {
    // If user returns later, use stored invoice from session
    $invoiceId = $_SESSION['last_invoice'];
} else {
    // No invoice found anywhere
    echo "❌ Invoice ID missing.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt - <?= htmlspecialchars($invoiceId) ?></title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
        background: #f0f2f5;
        font-family: Arial, sans-serif;
    }
    .receipt {
        max-width: 600px;
        margin: 2em auto;
        background: white;
        padding: 2em;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .receipt h1 {
        text-align: center;
        margin-bottom: 1em;
    }
    .receipt ul {
        list-style: none;
        padding: 0;
    }
    .receipt li {
        border-bottom: 1px solid #ddd;
        padding: 0.5em 0;
    }
    .receipt .total {
        font-weight: bold;
        margin-top: 1em;
        font-size: 1.2em;
        text-align: right;
    }
    .print-btn {
        display: block;
        margin: 2em auto 0;
        padding: 10px 20px;
        background: #4e54c8;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }
    .print-btn:hover {
        background: #3c3fb0;
    }
  </style>
</head>
<body>
<div class="receipt">
    <h1>🧾 Receipt</h1>
    <p><strong>Invoice ID:</strong> <?= htmlspecialchars($invoiceId) ?></p>
    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION["username"]) ?></p>
    <ul id="receipt-items"></ul>
    <p class="total">Total: RM <span id="receipt-total">0.00</span></p>
    <button class="print-btn" onclick="window.print()">🖨️ Print Receipt</button>
</div>

<script>
// ✅ Load last receipt data from localStorage
const cartData = JSON.parse(localStorage.getItem("lastReceipt"));
if (cartData && cartData.length > 0) {
    const ul = document.getElementById("receipt-items");
    const totalBox = document.getElementById("receipt-total");
    let total = 0;
    cartData.forEach(item => {
        const li = document.createElement("li");
        li.textContent = `${item.name} - RM${item.price.toFixed(2)} x ${item.qty}`;
        ul.appendChild(li);
        total += item.price * item.qty;
    });
    totalBox.textContent = total.toFixed(2);
} else {
    document.getElementById("receipt-items").innerHTML = "<li>No products in receipt.</li>";
}
</script>
</body>
</html>
