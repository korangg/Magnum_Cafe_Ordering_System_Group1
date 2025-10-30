<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .menu-item {
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .menu-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .menu-item h3 {
            margin: 10px 0;
            font-size: 1.2em;
        }

        .menu-item p {
            font-size: 1em;
            color: #555;
            margin: 5px 0 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Our Menu</h1>
    <nav>
        <a href="userhome.php">Home</a>
        <a href="about.php">About</a>
        <a href="cart.php">Cart</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="menu-grid">
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Burger&price=15.00&image=burger.jpg'">
        <img src="images/burger.jpg" alt="Burger">
        <h3>Burger</h3>
        <p>RM15.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Pizza&price=25.00&image=pizza.jpg'">
        <img src="images/pizza.jpg" alt="Pizza">
        <h3>Pizza</h3>
        <p>RM25.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Spaghetti&price=20.00&image=spaghetti.jpg'">
        <img src="images/spaghetti.jpg" alt="Spaghetti">
        <h3>Spaghetti</h3>
        <p>RM20.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Fries&price=8.00&image=fries.jpg'">
        <img src="images/fries.jpg" alt="Fries">
        <h3>Fries</h3>
        <p>RM8.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Salad&price=12.00&image=salad.jpg'">
        <img src="images/salad.jpg" alt="Salad">
        <h3>Salad</h3>
        <p>RM12.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Coffee&price=10.00&image=coffee.jpg'">
        <img src="images/coffee.jpg" alt="Coffee">
        <h3>Coffee</h3>
        <p>RM10.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Soda&price=5.00&image=soda.jpg'">
        <img src="images/soda.jpg" alt="Soda">
        <h3>Soda</h3>
        <p>RM5.00</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Ice%20Cream&price=7.50&image=icecream.jpg'">
        <img src="images/icecream.jpg" alt="Ice Cream">
        <h3>Ice Cream</h3>
        <p>RM7.50</p>
    </div>
    <div class="menu-item" onclick="window.location.href='product_detail.php?name=Cake&price=18.00&image=cake.jpg'">
        <img src="images/cake.jpg" alt="Cake">
        <h3>Cake</h3>
        <p>RM18.00</p>
    </div>
</main>

</body>
</html>