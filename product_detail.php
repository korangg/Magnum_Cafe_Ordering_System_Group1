<?php
session_start();
if (!isset($_SESSION["username"]) || $_SESSION["usertype"] !== "user") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Detail</title>
    <link rel="stylesheet" href="style.css">
    <script src="cart.js" defer></script>
</head>
<body>
<header>
    <h1>Product Detail</h1>
    <nav>
        <a href="userhome.php">Home</a>
        <a href="checkout.php">Checkout</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<main>
    <section class="product-showcase">
        <div class="product-image">
            <img id="product-img" src="" alt="Product Image">
        </div>
        <div class="product-info">
            <h1 id="product-name"></h1>
            <p id="product-description"></p>
            <p id="product-price"></p>
            <label for="qty">Quantity:</label>
            <input type="number" id="qty" name="qty" value="1" min="1" style="width: 60px; margin-left: 0.5em;">
            <br><br>
            <button onclick="addQuantityToCart()">Add to Cart</button>
            <button onclick="window.location.href='checkout.php'">Checkout</button>
        </div>
    </section>
</main>

<script>
function addQuantityToCart() {
    const qty = parseInt(document.getElementById("qty").value);
    const name = document.getElementById("product-name").innerText;
    const priceText = document.getElementById("product-price").innerText;
    const price = parseFloat(priceText.replace("RM", ""));
    for (let i = 0; i < qty; i++) {
        addToCart(name, price);
    }
}

// Load product info from localStorage
const product = JSON.parse(localStorage.getItem("selectedProduct"));
if (product) {
    document.getElementById("product-name").innerText = product.name;
    document.getElementById("product-description").innerText = product.description;
    document.getElementById("product-price").innerHTML = "<strong>RM" + product.price.toFixed(2) + "</strong>";
    document.getElementById("product-img").src = "images/" + product.image;
} else {
    document.querySelector(".product-info").innerHTML = "<p>❌ Product not found.</p>";
}
</script>
</body>
</html>
