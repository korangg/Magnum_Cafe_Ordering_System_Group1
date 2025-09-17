<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "ecommerce_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($product['name']); ?> - Details</title>
    <style>
        body {
            background-color: #121212;
            color: #f1f1f1;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .product-container {
            max-width: 600px;
            margin: 80px auto;
            background: #1e1e1e;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.6);
            padding: 25px;
        }
        .product-container h2 {
            margin-bottom: 15px;
            color: #fff;
        }
        img {
            max-width: 100%;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        p {
            font-size: 16px;
            margin: 8px 0;
        }
        .add-to-cart {
            margin-top: 25px;
        }
        .quantity-selector {
            display: inline-flex;
            align-items: center;
            background: #2c2c2c;
            border-radius: 8px;
            padding: 5px;
            margin-right: 15px;
        }
        .quantity-selector button {
            background: none;
            border: none;
            color: #fff;
            font-size: 20px;
            width: 35px;
            height: 35px;
            cursor: pointer;
            transition: 0.2s;
        }
        .quantity-selector button:hover {
            color: #ff9800;
        }
        .quantity-selector input {
            width: 50px;
            text-align: center;
            border: none;
            background: transparent;
            color: #fff;
            font-size: 16px;
        }
        .cart-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #ff9800, #ff5722);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        .cart-btn:hover {
            background: linear-gradient(135deg, #ffb74d, #ff7043);
            transform: scale(1.05);
        }
        .out-of-stock {
            color: red;
            font-weight: bold;
            margin-top: 15px;
            font-size: 18px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            color: #bbb;
            text-decoration: none;
            transition: 0.3s ease;
        }
        a:hover {
            color: #ff9800;
        }
    </style>
</head>
<body>
    <div class="product-container">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <img src="assets/img/menu/<?php echo htmlspecialchars($product['image']); ?>" alt="">
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
        <p><?php echo htmlspecialchars($product['description']); ?></p>

        <?php if ((int)$product['stock'] > 0): ?>
        <div class="add-to-cart">
            <div class="quantity-selector">
                <button type="button" onclick="changeQty(-1)">−</button>
                <input type="number" id="quantity" value="1" min="1" max="<?php echo (int)$product['stock']; ?>" readonly>
                <button type="button" onclick="changeQty(1)">+</button>
            </div>

            <button type="button" class="cart-btn" onclick="addToCart()">🛒 Add to Cart</button>
        </div>
        <?php else: ?>
        <p class="out-of-stock">❌ Food out of order</p>
        <?php endif; ?>

        <a href="userhome.php">← Back to Menu</a>
    </div>

    <script>
        function changeQty(val) {
            const qtyInput = document.getElementById("quantity");
            let current = parseInt(qtyInput.value, 10) || 1;
            const min = parseInt(qtyInput.min, 10) || 1;
            const max = parseInt(qtyInput.max, 10) || 1;

            let newVal = current + val;
            if (newVal < min) newVal = min;
            if (newVal > max) newVal = max;
            qtyInput.value = newVal;
        }

        function addToCart() {
            // read qty and stock as integers
            const qty = parseInt(document.getElementById("quantity").value, 10) || 1;
            const maxStock = parseInt(<?php echo json_encode((int)$product['stock']); ?>, 10);

            // build product object with numeric qty
            const productToAdd = {
                id: parseInt(<?php echo json_encode((int)$product['id']); ?>, 10),
                name: <?php echo json_encode($product['name']); ?>,
                price: parseFloat(<?php echo json_encode((float)$product['price']); ?>),
                image: <?php echo json_encode($product['image']); ?>,
                qty: qty
            };

            // load cart and normalize types
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.map(it => {
                it.qty = parseInt(it.qty, 10) || 0;
                it.id = parseInt(it.id, 10) || it.id;
                return it;
            });

            // find existing by numeric id
            const idx = cart.findIndex(it => it.id === productToAdd.id);

            if (idx > -1) {
                const existingQty = cart[idx].qty;
                // allow equal to stock, block only if exceeds
                if ((existingQty + productToAdd.qty) > maxStock) {
                    alert("⚠️ Sorry, only " + maxStock + " stock available for " + productToAdd.name);
                    return;
                }
                cart[idx].qty = existingQty + productToAdd.qty;
            } else {
                if (productToAdd.qty > maxStock) {
                    alert("⚠️ Sorry, only " + maxStock + " stock available for " + productToAdd.name);
                    return;
                }
                cart.push(productToAdd);
            }

            // save normalized cart
            localStorage.setItem('cart', JSON.stringify(cart));
            alert(productToAdd.name + " added to cart!");
        }
    </script>
</body>
</html>
