<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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

        /* Full-page cart specific styles */
        .cart-page {
            width: 100%;
            min-height: calc(100vh - 80px); /* Full height minus header */
            padding: 40px 5%;
            box-sizing: border-box;
        }
        
        .cart-container {
            width: 100%;
            background-color: var(--surface-color);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .cart-table th, .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .cart-table th {
            color: var(--heading-color);
            font-family: var(--heading-font);
        }
        
        .cart-table td {
            color: var(--default-color);
        }
        
        .cart-table .item-name {
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .cart-table .remove-btn {
            background: none;
            border: none;
            color: #ff4545;
            cursor: pointer;
            font-size: 1.2em;
            transition: color 0.2s ease;
        }
        
        .cart-table .remove-btn:hover {
            color: #ff0000;
        }
        
        .cart-summary {
            margin-top: 30px;
            text-align: right;
            font-size: 1.2em;
            font-family: var(--heading-font);
        }
        
        .cart-summary p {
            margin: 10px 0;
        }
        
        .cart-summary .total-price {
            font-size: 1.5em;
            color: var(--accent-color);
        }
        
        .checkout-btn {
            padding: 15px 30px;
            background-color: var(--accent-color);
            color: var(--contrast-color);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1em;
            text-transform: uppercase;
            font-family: var(--nav-font);
            font-weight: bold;
            transition: background-color 0.2s ease;
            margin-top: 20px;
        }
        
        .checkout-btn:hover {
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
        
        @media (max-width: 768px) {
            .cart-container {
                padding: 20px;
            }
            .cart-table th, .cart-table td {
                padding: 8px;
                font-size: 0.9em;
            }
            .checkout-btn {
                width: 100%;
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
                <li><a href="cart.php" class="active">Cart</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="section cart-page">
    <div class="cart-container">
        <div class="section-title">
            <h2>Your Cart</h2>
            <p>Review Your Order</p>
        </div>

        <div id="cart-list">
            <!-- Cart items will be rendered here by JavaScript -->
        </div>

        <div class="cart-summary">
            <p>Subtotal: $<span id="subtotal">0.00</span></p>
            <p>Total: <span class="total-price">$<span id="total">0.00</span></span></p>
        </div>
        
        <div style="text-align: right;">
            <button class="checkout-btn" onclick="proceedToCheckout()">Proceed to Checkout</button>
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
    document.addEventListener('DOMContentLoaded', () => {
        renderCart();
    });

    function showMessage(title, message) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalMessage').textContent = message;
        document.getElementById('messageModal').style.display = 'flex';
    }

    function hideMessage() {
        document.getElementById('messageModal').style.display = 'none';
    }

    function renderCart() {
        const cartList = document.getElementById('cart-list');
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        let html = '';
        let subtotal = 0;

        if (cart.length > 0) {
            html += '<table class="cart-table">';
            html += '<thead><tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th><th></th></tr></thead>';
            html += '<tbody>';
            cart.forEach(item => {
                const itemSubtotal = item.price * item.qty;
                subtotal += itemSubtotal;
                html += `
                    <tr>
                        <td class="item-name">${item.name}</td>
                        <td>$${item.price.toFixed(2)}</td>
                        <td>${item.qty}</td>
                        <td>$${itemSubtotal.toFixed(2)}</td>
                        <td><button class="remove-btn" onclick="removeFromCart('${item.name}')">&times;</button></td>
                    </tr>
                `;
            });
            html += '</tbody></table>';
        } else {
            html = '<p style="text-align: center; font-size: 1.2em;">Your cart is empty.</p>';
        }

        cartList.innerHTML = html;
        subtotalElement.textContent = subtotal.toFixed(2);
        totalElement.textContent = subtotal.toFixed(2); // Assuming no tax/shipping for now
    }

    function removeFromCart(name) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.name !== name);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }

    function proceedToCheckout() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        if (cart.length > 0) {
            window.location.href = 'checkout.php';
        } else {
            showMessage('Empty Cart', 'Your cart is empty. Please add items before checking out.');
        }
    }
</script>

</body>
</html>
