<?php
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Product';
$price = isset($_GET['price']) ? floatval($_GET['price']) : 0.00;
$image = isset($_GET['image']) ? htmlspecialchars($_GET['image']) : 'default.jpg';
$description = isset($_GET['description']) ? htmlspecialchars($_GET['description']) : 'Delicious food item. A perfect blend of fresh ingredients and rich flavors, crafted to perfection. This dish is a must-try for its unique taste and satisfying feel.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $name; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- ======= Custom Styles from Restaurantly Template ======= -->
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

        /* Custom styles for full-page product detail */
        .product-detail-page {
            width: 100%;
            min-height: calc(100vh - 80px); /* Full height minus header */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-sizing: border-box;
        }

        .product-detail-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            background-color: var(--surface-color);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            padding: 40px;
            gap: 30px;
        }

        .product-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            width: 100%;
        }

        .product-image img {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            border: 8px solid rgba(255, 255, 255, 0.1);
        }

        .product-info {
            text-align: center;
            max-width: 600px;
        }

        .product-info h2 {
            font-size: 3em;
            margin-bottom: 5px;
        }

        .product-info .price {
            font-size: 1.8em;
            color: var(--accent-color);
            font-weight: 600;
        }

        .product-description {
            margin-top: 20px;
            font-size: 1em;
            line-height: 1.6;
            text-align: center;
        }

        .add-to-cart-form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 5px;
            border: 1px solid var(--accent-color);
            border-radius: 5px;
            background-color: var(--background-color);
        }

        .quantity-btn {
            background: none;
            border: none;
            color: var(--accent-color);
            font-size: 1.5em;
            padding: 5px 10px;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .quantity-btn:hover {
            color: color-mix(in srgb, var(--accent-color), transparent 20%);
        }

        .quantity-input {
            background-color: transparent;
            border: none;
            color: var(--default-color);
            font-size: 1.2em;
            width: 50px;
            text-align: center;
            -moz-appearance: textfield; /* Firefox */
        }
        
        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .add-to-cart-form button {
            padding: 10px 20px;
            background-color: var(--accent-color);
            color: var(--contrast-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            font-family: var(--nav-font);
        }

        .add-to-cart-form button:hover {
            background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
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
            display: flex; /* Changed from 'display: none' for initial state, will be handled by JS */
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .message-modal.show {
            opacity: 1;
            visibility: visible;
        }

        .message-content {
            background-color: var(--surface-color);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .message-modal.show .message-content {
            transform: scale(1);
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
            .product-detail-container {
                flex-direction: row;
                justify-content: center;
                align-items: center;
            }

            .product-content {
                flex-direction: row;
                text-align: left;
                width: auto;
                gap: 50px;
            }

            .product-info {
                text-align: left;
            }
        }
    </style>
</head>
<body>

<header id="header" class="header">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="userhome.php" class="logo d-flex align-items-center me-auto me-lg-0">
            <h1>Restaurantly<span>.</span></h1>
        </a>
        <nav id="navbar" class="navmenu">
            <ul>
                <li><a href="userhome.php">Home</a></li>
                <li><a href="userhome.php#menu">Menu</a></li>
                <li><a href="cart.php">Cart</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="product-detail-page">
    <div class="product-detail-container">
        <div class="product-image">
            <img src="assets/img/menu/<?php echo $image; ?>" alt="<?php echo $name; ?>">
        </div>
        <div class="product-info">
            <h2><?php echo $name; ?></h2>
            <p class="price">$<?php echo number_format($price, 2); ?></p>
            <p class="product-description"><?php echo $description; ?></p>
            <div class="add-to-cart-form">
                <label for="qty">Quantity:</label>
                <div class="quantity-control">
                    <button type="button" class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                    <input type="number" id="qty" name="qty" value="1" min="1" readonly class="quantity-input">
                    <button type="button" class="quantity-btn" onclick="updateQuantity(1)">+</button>
                </div>
                <button onclick="addToCartAndRedirect('<?php echo $name; ?>', <?php echo $price; ?>)">Add to Cart</button>
            </div>
        </div>
    </div>
</main>

<div id="messageModal" class="message-modal">
    <div class="message-content">
        <h3 id="modalTitle"></h3>
        <p id="modalMessage"></p>
        <button onclick="hideMessage()">OK</button>
    </div>
</div>

<script>
    function showMessage(title, message) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('messageModal').classList.add('show');
    }

    function hideMessage() {
        document.getElementById('messageModal').classList.remove('show');
    }

    function updateQuantity(change) {
        const qtyInput = document.getElementById('qty');
        let currentQty = parseInt(qtyInput.value);
        let newQty = currentQty + change;
        if (newQty >= 1) {
            qtyInput.value = newQty;
        }
    }

    function addToCartAndRedirect(name, price) {
        const qty = parseInt(document.getElementById('qty').value);
        if (qty > 0) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const itemIndex = cart.findIndex(item => item.name === name);

            if (itemIndex !== -1) {
                cart[itemIndex].qty += qty;
            } else {
                cart.push({ name, price, qty });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            showMessage('Success!', qty + ' x ' + name + ' added to cart.');
            setTimeout(() => {
                window.location.href = 'cart.php';
            }, 1000);
        } else {
            showMessage('Error!', 'Please select a quantity greater than zero.');
        }
    }
</script>

</body>
</html>
