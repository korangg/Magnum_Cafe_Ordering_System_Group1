<?php
session_start();
// Redirect to login page if user is not logged in or not a 'user'
if (!isset($_SESSION["username"]) || $_SESSION['usertype'] !== 'user') {
   header("location: login.php");
   exit;
}
// Get the username from the session
$username = htmlspecialchars($_SESSION["username"]);

// Database connection using mysqli_connect
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user data
$sql = "SELECT username, email FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION["username"]); // Use session username to fetch data
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>

   <script src="cart.js" defer></script>

  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Welcome - Magnum Cafe</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <link href="assets/css/main.css" rel="stylesheet">
    
<style>
    .background-video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 0;
    }

    .animated-image-right {
        z-index: 1;
        width: 100px;
        position: absolute;
        top: 450px;
        right: 200px;
        animation: moveR 15s infinite;
    }

    .animated-image-left {
        z-index: 3;
        width: 100px;
        position: absolute;
        top: 450px;
        left: 200px;
        animation: moveL 15s infinite;
    }

    @keyframes moveR {
        0%, 100% { transform: translateX(0) scale(0.9); }
        50% { transform: translateX(20px) scale(1.0); }
    }

    @keyframes moveL {
        0%, 100% { transform: translateX(0) scale(0.9); }
        50% { transform: translateX(-20px) scale(1.0); }
    }

    .text-overlay-left {
        z-index: 2;
        position: absolute;
        top: 290px;
        left: 200px;
        color: white;
        font-size: 90px;
        font-weight: bold;
        font-family: 'Open Sans', sans-serif;
    }

    .text-overlay-right {
        z-index: 4;
        position: absolute;
        top: 290px;
        right: 200px;
        color: white;
        font-size: 90px;
        font-weight: bold;
        font-family: 'Open Sans', sans-serif;
        text-align: right;
    }

    /* --- SIDEBAR TOGGLE BUTTON --- */
    .sidebar-toggle {
        position: sticky;
        top: 20px;
        right: 20px;
        font-size: 40px;
        color: white;
        z-index: 9999;
        cursor: pointer;
    }

    /* --- SIDEBAR --- */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 280px;
        background-color: #1a1a2e;
        z-index: 9999;
        transform: translateX(-100%); /* hidden */
        transition: transform 0.3s ease-in-out;
        padding: 20px;
        display: flex;
        flex-direction: column;
        color: white;
    }

    .sidebar.active {
        transform: translateX(0); /* slide in */
    }

    /* --- HEADER INSIDE SIDEBAR --- */
    .sidebar-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
    }

    .sidebar-header img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        border: 3px solid var(--accent-color);
        margin-bottom: 15px;
        object-fit: cover;
    }

    .sidebar-header h3 {
        margin: 0;
        font-family: var(--heading-font);
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--heading-color);
    }

    /* --- NAVIGATION LINKS --- */
    .sidebar-nav {
        list-style: none;
        padding: 0;
        margin: 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .sidebar-nav li {
        margin-bottom: 5px;
    }

    .sidebar-nav li a {
        color: var(--nav-dropdown-color);
        text-decoration: none;
        font-size: 16px;
        display: flex;
        align-items: center;
        padding: 12px 15px;
        border-radius: 4px;
        transition: all 0.3s ease;
    }

    .sidebar-nav li a:hover,
    .sidebar-nav li a:focus {
        background-color: var(--accent-color);
        color: var(--contrast-color);
    }

    .sidebar-nav li a i {
        margin-right: 15px;
        font-size: 18px;
        width: 20px;
        text-align: center;
    }

    .sidebar-nav li.sign-out {
        margin-top: auto;
        border-top: 1px solid color-mix(in srgb, var(--default-color), transparent 90%);
        padding-top: 15px;
    }

    .sidebar-nav li.sign-out a {
        color: color-mix(in srgb, var(--default-color), transparent 30%);
    }

    .sidebar-nav li.sign-out a:hover {
        color: #ff6b6b;
        background-color: rgba(255, 107, 107, 0.1);
    }

    /* --- OVERLAY BEHIND SIDEBAR --- */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(33, 37, 41, 0.8);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        .sidebar {
            width: 250px;
        }

        .sidebar-toggle {
            font-size: 24px;
            right: 10px;
            top: 10px;
        }
    }
</style>

</head>

<body class="index-page">

  <div class="sidebar" id="user-sidebar">
      <div class="sidebar-header">
        
      </div>
      <ul class="sidebar-nav">
          <li><a href="profile.php"><i class="bi bi-person-circle"></i> Profile</a></li>
          <li><a href="checkout.php"><i class="bi bi-cart-check"></i> Checkout</a></li>
          <li><a href="receipt.php"><i class="bi bi-receipt"></i> Receipt</a></li>
          <li><a href="order_status.php"><i class="bi bi-truck"></i> View Order Status</a></li>
          <li class="sign-out"><a href="logout.php"><i class="bi bi-box-arrow-left"></i> Sign Out</a></li>
      </ul>
  </div>
  <div class="sidebar-overlay" id="sidebar-overlay"></div>
  <header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">support@myshop.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+60 12-345 6789</span></i>
        </div>
      </div>
    </div><div class="branding d-flex align-items-center">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        
        

        <a href="userhome.php" class="logo d-flex align-items-center me-auto me-xl-0">
          <h1 class="sitename">Magnum Cafe</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#home" class="active">Home<br></a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#specials">Specials</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact Us</a></li>
			<li><a href="cart.php">Cart</a></li>
            </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>
		
		<i class="bi bi-person-circle sidebar-toggle" id="sidebar-toggle-btn"></i>

      </div>	
    </div>

  </header>

  <main class="main">

    <section id="home" class="hero section dark-background">

    <video class="background-video" autoplay muted loop playsinline>
		<source src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/1-1.mp4" type="video/mp4">
	</video>
	
	<div class="text-overlay-left">Chocolate<br>makes</div>
	<div class="text-overlay-right">Everything<br>better.</div>

    </section>
	<section id="menu" class="menu section section-black">
    <div class="container section-title" data-aos="fade-up">
        <h2>Menu</h2>
        <p>Check Our Tasty Menu</p>
    </div>
    <div class="container isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

        <div class="row" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-12 d-flex justify-content-center">
                <ul class="menu-filters isotope-filters">
                    <li data-filter="*" class="filter-active">All</li>
                    <li data-filter=".filter-starters">Starters</li>
                    <li data-filter=".filter-salads">Salads</li>
                    <li data-filter=".filter-specialty">Specialty</li>
                </ul>
            </div>
        </div>

        <div class="row isotope-container" data-aos="fade-up" data-aos-delay="200">
            <?php
            $conn = new mysqli("localhost", "root", "", "ecommerce_db");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM products ORDER BY id ASC";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // map DB category into filter class
                    $categoryClass = "filter-" . strtolower($row['category']);
                    ?>
                    <div class="col-lg-6 menu-item isotope-item <?php echo $categoryClass; ?>">
                        <img src="assets/img/menu/<?php echo htmlspecialchars($row['image']); ?>" class="menu-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
                        <div class="menu-content">
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></a>
                            <span>$<?php echo number_format($row['price'], 2); ?></span>
                        </div>
                        <div class="menu-ingredients">
                            <?php echo htmlspecialchars($row['description']); ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No menu items found in database.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</section>

	


<section id="specials" class="specials section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Specials</h2>
        <p>Check Our Specials</p>
      </div><div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-3">
            <ul class="nav nav-tabs flex-column">
              <li class="nav-item">
                <a class="nav-link active show" data-bs-toggle="tab" href="#specials-tab-1">Modi sit est</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-2">Unde praesentium sed</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-3">Pariatur explicabo vel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-4">Nostrum qui quasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-5">Iusto ut expedita aut</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="tab-content">
              <div class="tab-pane active show" id="specials-tab-1">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Architecto ut aperiam autem id</h3>
                    <p class="fst-italic">Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka</p>
                    <p>Et nobis maiores eius. Voluptatibus ut enim blanditiis atque harum sint. Laborum eos ipsum ipsa odit magni. Incidunt hic ut molestiae aut qui. Est repellat minima eveniet eius et quis magni nihil. Consequatur dolorem quaerat quos qui similique accusamus nostrum rem vero</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/specials-1.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-2">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Et blanditiis nemo veritatis excepturi</h3>
                    <p class="fst-italic">Qui laudantium consequatur laborum sit qui ad sapiente dila parde sonata raqer a videna mareta paulona marka</p>
                    <p>Ea ipsum voluptatem consequatur quis est. Illum error ullam omnis quia et reiciendis sunt sunt est. Non aliquid repellendus itaque accusamus eius et velit ipsa voluptates. Optio nesciunt eaque beatae accusamus lerode pakto madirna desera vafle de nideran pal</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/specials-2.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-3">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Impedit facilis occaecati odio neque aperiam sit</h3>
                    <p class="fst-italic">Eos voluptatibus quo. Odio similique illum id quidem non enim fuga. Qui natus non sunt dicta dolor et. In asperiores velit quaerat perferendis aut</p>
                    <p>Iure officiis odit rerum. Harum sequi eum illum corrupti culpa veritatis quisquam. Neque necessitatibus illo rerum eum ut. Commodi ipsam minima molestiae sed laboriosam a iste odio. Earum odit nesciunt fugiat sit ullam. Soluta et harum voluptatem optio quae</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/specials-3.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-4">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Fuga dolores inventore laboriosam ut est accusamus laboriosam dolore</h3>
                    <p class="fst-italic">Totam aperiam accusamus. Repellat consequuntur iure voluptas iure porro quis delectus</p>
                    <p>Eaque consequuntur consequuntur libero expedita in voluptas. Nostrum ipsam necessitatibus aliquam fugiat debitis quis velit. Eum ex maxime error in consequatur corporis atque. Eligendi asperiores sed qui veritatis aperiam quia a laborum inventore</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/specials-4.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-5">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Est eveniet ipsam sindera pad rone matrelat sando reda</h3>
                    <p class="fst-italic">Omnis blanditiis saepe eos autem qui sunt debitis porro quia.</p>
                    <p>Exercitationem nostrum omnis. Ut reiciendis repudiandae minus. Omnis recusandae ut non quam ut quod eius qui. Ipsum quia odit vero atque qui quibusdam amet. Occaecati sed est sint aut vitae molestiae voluptate vel</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/specials-5.png" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section>
	
	<section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="assets/img/about.jpg" class="img-fluid about-img" alt="">
          </div>
          <div class="col-lg-6 order-2 order-lg-1 content">
            <h3>Who We Are</h3>
            <p class="fst-italic">
              Welcome to Magnum Cafe, where every cup tells a story and every bite is an adventure! Nestled in the heart of our community, 
			  we’re not just a cafe; we’re a vibrant gathering place for food lovers and coffee aficionados alike.<br><br>

			  At Magnum Cafe, we pride ourselves on serving a curated menu of delectable dishes and artisanal beverages that celebrate local flavors and global inspirations.
			  From our rich, aromatic coffees to our mouthwatering pastries and gourmet meals, every item is crafted with passion and care.<br><br>

			  Join us in our journey to make every dining experience not just a meal, but a memorable occasion.
			  Let’s spice up life together, one delicious bite at a time!
            </p>
			<h3>Contact</h3>
            <ul>
              <li><i class="bi bi-check2-all"></i> <span>Email: support@myshop.com</span></li>
              <li><i class="bi bi-check2-all"></i> <span>Phone: +60 12-345 6789</span></li>
            </ul>
          </div>
        </div>

      </div>

    </section>
	<section id="why-us" class="why-us section">


      <div class="container section-title" data-aos="fade-up">
        <h2>WHY US</h2>
        <p>Why Choose Our Restaurant</p>
      </div><div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="card-item">
              <span>01</span>
              <h4>Artisanal Flavors</h4>
              <p>We craft our ice cream using high-quality, locally sourced ingredients, offering unique and indulgent flavors that change with the seasons</p>
            </div>
          </div><!-- Card Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="card-item">
              <span>02</span>
              <h4>Custom Creations</h4>
              <p>At Magnum Cafe, you can personalize your dessert! Choose from a variety of toppings, sauces, and cones to create your perfect ice cream masterpiece</p>
            </div>
          </div><!-- Card Item -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="card-item">
              <span>03</span>
              <h4>Family-Friendly Atmosphere</h4>
              <p>Our cafe is designed for everyone! With a fun and inviting space, it’s the perfect spot for families and friends to gather and enjoy sweet moments together</p>
            </div>
          </div><!-- Card Item -->
		  
		</div>

      </div>

    </section>
	
	
	
	
	<section id="events" class="events section">

      <img class="slider-bg" src="assets/img/events-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">

        <div class="swiper init-swiper" data-aos="fade-up" data-aos-delay="100">
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-1.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Birthday Parties</h3>
                  <div class="price">
                    <p><span>$189</span></p>
                  </div>
                  <p class="fst-italic">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                    magna aliqua.
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                  </ul>
                  <p>
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                    velit esse cillum dolore eu fugiat nulla pariatur
                  </p>
                </div>
              </div>
            </div><div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-2.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Private Parties</h3>
                  <div class="price">
                    <p><span>$290</span></p>
                  </div>
                  <p class="fst-italic">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                    magna aliqua.
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                  </ul>
                  <p>
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                    velit esse cillum dolore eu fugiat nulla pariatur
                  </p>
                </div>
              </div>
            </div><div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/events-slider-3.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Custom Parties</h3>
                  <div class="price">
                    <p><span>$99</span></p>
                  </div>
                  <p class="fst-italic">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                    magna aliqua.
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                  </ul>
                  <p>
                    Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                    velit esse cillum dolore eu fugiat nulla pariatur
                  </p>
                </div>
              </div>
            </div></div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><section id="testimonials" class="testimonials section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>What they're saying about us</p>
      </div><div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints='{ "320": { "slidesPerView": 1, "spaceBetween": 40 }, "1200": { "slidesPerView": 3, "spaceBetween": 40 } }'>
          <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 600,
              "autoplay": {
                "delay": 5000
              },
              "slidesPerView": "auto",
              "pagination": {
                "el": ".swiper-pagination",
                "type": "bullets",
                "clickable": true
              },
              "breakpoints": {
                "320": {
                  "slidesPerView": 1,
                  "spaceBetween": 40
                },
                "1200": {
                  "slidesPerView": 3,
                  "spaceBetween": 20
                }
              }
            }
          </script>
          <div class="swiper-wrapper">

            <div class="swiper-slide">
              <div class="testimonial-item" "="">
            <p>
              <i class=" bi bi-quote quote-icon-left"></i>
                <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-1.jpg" class="testimonial-img" alt="">
                <h3>Saul Goodman</h3>
                <h4>Ceo &amp; Founder</h4>
              </div>
            </div><div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-2.jpg" class="testimonial-img" alt="">
                <h3>Sara Wilsson</h3>
                <h4>Designer</h4>
              </div>
            </div><div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-3.jpg" class="testimonial-img" alt="">
                <h3>Jena Karlis</h3>
                <h4>Store Owner</h4>
              </div>
            </div><div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-4.jpg" class="testimonial-img" alt="">
                <h3>Matt Brandon</h3>
                <h4>Freelancer</h4>
              </div>
            </div><div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials-5.jpg" class="testimonial-img" alt="">
                <h3>John Larson</h3>
                <h4>Entrepreneur</h4>
              </div>
            </div></div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><section id="gallery" class="gallery section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Some photos from Our Cafe</p>
      </div><div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery4.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery4.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery7.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery7.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div></div>

      </div>

    </section><section id="chefs" class="chefs section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Chef</h2>
        <p>Necessitatibus eius consequatur</p>
      </div><div class="container">

        <div class="row gy-4">

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <img src="assets/img/chefs/chef.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Miko Aspiras</h4>
                  <span>Head Pastry</span>
                </div>
                <div class="social">
                  <a href="https://x.com/chefmikoaspiras"><i class="bi bi-twitter-x"></i></a>
                  <a href="https://www.facebook.com/gelatobychefmiko/"><i class="bi bi-facebook"></i></a>
                  <a href="https://www.instagram.com/chefmikoaspiras/"><i class="bi bi-instagram"></i></a>
                  <a href="https://au.linkedin.com/in/michael-miko-aspiras-66673040?original_referer=https%3A%2F%2Fwww.google.com%2F"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <img src="assets/img/chefs/chefs-2.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Sarah Jhonson</h4>
                  <span>Patissier</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <img src="assets/img/chefs/chefs-3.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>William Anderson</h4>
                  <span>Cook</span>
                </div>
                <div class="social">
                  <a href=""><i class="bi bi-twitter-x"></i></a>
                  <a href=""><i class="bi bi-facebook"></i></a>
                  <a href=""><i class="bi bi-instagram"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div></div>

      </div>

    </section>
	<!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contact</h2>
        <p>Contact Us</p>
      </div><!-- End Section Title -->

      <div class="mb-5" data-aos="fade-up" data-aos-delay="200">
        <iframe style="border:0; width: 100%; height: 400px;" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.46535224052!2d101.7165646744704!3d2.9682943542388105!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdca11e9d0d76b%3A0xc61cfdea77542db3!2sIOI%20City%20Mall%2C%2062050%20Serdang%2C%20Selangor!5e0!3m2!1sen!2smy!4v1756353990932!5m2!1sen!2smy" frameborder="0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div><!-- End Google Maps -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-4">
            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="300">
              <i class="bi bi-geo-alt flex-shrink-0"></i>
              <div>
                <h3>Location</h3>
                <p>3rd Floor (South Court), Mid Valley Megamall, Lingkaran Syed Putra, 59200 Kuala Lumpur, Malaysia.</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Open Hours</h3>
                <p>Monday-Sunday:<br>10:00 AM - 10:00 PM</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="400">
              <i class="bi bi-telephone flex-shrink-0"></i>
              <div>
                <h3>Call Us</h3>
                <p>+60 12-345 6789</p>
              </div>
            </div><!-- End Info Item -->

            <div class="info-item d-flex" data-aos="fade-up" data-aos-delay="500">
              <i class="bi bi-envelope flex-shrink-0"></i>
              <div>
                <h3>Email Us</h3>
                <p>support@myshop.com</p>
              </div>
            </div><!-- End Info Item -->

          </div>
		  
		  <div class="col-lg-8">
			<form action="send_feedback.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
				<div class="row gy-4">

					<div class="col-md-6">
						<input type="text" name="name" class="form-control" placeholder="Your Name" value="<?= htmlspecialchars($name) ?>" required="">
					</div>

					<div class="col-md-6">
						<input type="email" class="form-control" name="email" placeholder="Your Email" value="<?= htmlspecialchars($email) ?>" required="">
					</div>

					<div class="col-md-12">
						<input type="text" class="form-control" name="subject" placeholder="Subject" required="">
					</div>

					<div class="col-md-12">
						<textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
					</div>

					<div class="col-md-12 text-center">
						<div class="loading">Loading</div>
						<div class="error-message"></div>
						<div class="sent-message">Your message has been sent. Thank you!</div>

						<button type="submit">Send Message</button>
					</div>

				</div>
			</form>
		  </div><!-- End Contact Form -->

	
	</main>

  <footer id="footer" class="footer" style="text-align:center; padding:40px 0; background:#111; color:#fff;">
      <div class="container footer-top">
          <div class="row gy-4 justify-content-center">
              <div class="col-lg-8 footer-about text-center">
                  <a href="index.html" class="logo d-flex justify-content-center align-items-center mb-3">
                      <span class="sitename" style="font-size:28px; font-weight:bold;">Magnum Cafe</span>
                  </a>
                  <div class="footer-contact pt-3" style="font-size:18px; line-height:1.8;">
                      <p>3rd Floor (South Court), Mid Valley Megamall</p>
                      <p>Lingkaran Syed Putra, 59200 Kuala Lumpur, Malaysia</p>
                      <p class="mt-3"><strong>Phone:</strong> <span>+60 12-345 6789</span></p>
                      <p><strong>Email:</strong> <span>support@myshop.com</span></p>
                  </div>
                  <div class="social-links d-flex justify-content-center mt-4" style="font-size:22px;">
					<a href="https://x.com/magnumicecream?lang=en"><i class="bi bi-twitter-x"></i></a>
					<a href="https://www.facebook.com/MagnumMalaysia/"><i class="bi bi-facebook"></i></a>
					<a href="https://www.instagram.com/magnumofficialmy/?hl=en"><i class="bi bi-instagram"></i></a>
					<a href="https://www.linkedin.com/company/themiceco"><i class="bi bi-linkedin"></i></a>
				  </div>
              </div>
          </div>
      </div>
      <div class="container copyright text-center mt-4">
          <p>© <span>Copyright</span> <strong class="px-1 sitename">Magnum Cafe</strong> <span>All Rights Reserved</span></p>
          <div class="credits">
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
          </div>
      </div>
  </footer>


  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <div id="preloader"></div>

  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="assets/js/main.js"></script>
  
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const toggleBtn = document.getElementById('sidebar-toggle-btn');
          const sidebar = document.getElementById('user-sidebar');
          const overlay = document.getElementById('sidebar-overlay');

          function closeSidebar() {
              sidebar.classList.remove('active');
              overlay.classList.remove('active');
          }

          toggleBtn.addEventListener('click', function(e) {
              e.stopPropagation();
              sidebar.classList.add('active');
              overlay.classList.add('active');
          });

          overlay.addEventListener('click', function() {
              closeSidebar();
          });

          document.addEventListener('keydown', function(e) {
              if (e.key === "Escape" && sidebar.classList.contains('active')) {
                  closeSidebar();
              }
          });
      });
  </script>

</body>

</html>