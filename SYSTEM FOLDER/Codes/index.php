<?php
session_start();

// Database connection using mysqli_connect
$conn = mysqli_connect("localhost", "root", "", "ecommerce_db");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Restaurantly Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
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
		<!--filter: brightness(50%); -->
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
	</style>
</head>

<body class="index-page">

  <header id="header" class="header fixed-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="contact-info d-flex align-items-center">
          <i class="bi bi-envelope d-flex align-items-center"><a href="mailto:contact@example.com">support@myshop.com</a></i>
          <i class="bi bi-phone d-flex align-items-center ms-4"><span>+60 12-345 6789</span></i>
        </div>
      </div>
    </div><!-- End Top Bar -->

    <div class="branding d-flex align-items-cente">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <!-- <img src="assets/img/logo.png" alt=""> -->
          <h1 class="sitename">Magnum Cafe</h1>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#home" class="active">Home<br></a></li>
            <li><a href="#menu">Menu</a></li>
            <li><a href="#specials">Specials</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#contact">Contact Us</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>	
    </div>

  </header>

  <main class="main">

    <!-- Home Section -->
    <section id="home" class="hero section dark-background">

    <video class="background-video" autoplay muted loop playsinline>
		<source src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/1-1.mp4" type="video/mp4">
	</video>
	
	<div style="font-family: 'Roboto', sans-serif; font-weight: bold; font-style: italic;" class="text-overlay-left">Chocolate<br>makes</div>
    <div style="font-family: 'Roboto', sans-serif; font-weight: bold; font-style: italic;" class="text-overlay-right">Everything<br>better.</div>

	<!-- Bigger Chocolate under text -->
<!--	<img src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/Youtube-Thumbails-13-1-1024x576.png" class="animated-image-left" alt="Chocolate Left">
	<img src="https://nicolaipalmkvist.com/wp-content/uploads/2024/12/Youtube-Thumbails-16-1-768x432.png" class="animated-image-right" alt="Chocolate Right">
-->
    </section><!-- /Hero Section -->


	<!-- Menu Section -->
	<section id="menu" class="menu section section-black">

		<!-- Section Title -->
		<div class="container section-title" data-aos="fade-up">
			<h2>Menu</h2>
			<p>Check Our Tasty Menu</p>
		</div><!-- End Section Title -->

		<div class="container isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

			<div class="row" data-aos="fade-up" data-aos-delay="100">
				<div class="col-lg-12 d-flex justify-content-center">
					<ul class="menu-filters isotope-filters">
						<li data-filter="*" class="filter-active">All</li>
						<li data-filter=".filter-indulgences">Indulgences</li> 
						<li data-filter=".filter-chillers">Chillers</li> 
						<li data-filter=".filter-specialty">Specialty</li>
					</ul>
				</div>
			</div><!-- Menu Filters -->

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
						// Map DB category into filter class directly
						$categoryClass = "filter-" . strtolower($row['category']);
						?>
						<div class="col-lg-6 menu-item isotope-item <?php echo $categoryClass; ?>">
							<img src="assets/img/menu/<?php echo htmlspecialchars($row['image']); ?>" class="menu-img" alt="<?php echo htmlspecialchars($row['name']); ?>">
							<div class="menu-content">
								<span><?php echo htmlspecialchars($row['name']); ?></span>
								<span>RM<?php echo number_format($row['price'], 2); ?></span>
							</div>
							<div class="menu-ingredients">
								<?php echo htmlspecialchars($row['description']); ?>
							</div>
						</div>
						<?php
					}
				} else {
					echo "<p>No menu items found in the database.</p>";
				}

				$conn->close();
				?>
			</div>
		</div>

	</section><!-- /Menu Section -->

  <!-- Specials Section -->
    <section id="specials" class="specials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Specials</h2>
        <p>Check Our Specials</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-3">
            <ul class="nav nav-tabs flex-column">
              <li class="nav-item">
                <a class="nav-link active show" data-bs-toggle="tab" href="#specials-tab-1">Berry Crepe Delight</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-2">Magnum Cendol</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-3">Magnum Crowned Jewel</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#specials-tab-4">Triple Choco Cookie</a>
              </li>
            </ul>
          </div>
          <div class="col-lg-9 mt-4 mt-lg-0">
            <div class="tab-content">
              <div class="tab-pane active show" id="specials-tab-1">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Berry Crepe Delight</h3>
                    <p class="fst-italic">Indulge in a delicate crepe filled with a luscious blend of fresh berries, drizzled with a sweet berry sauce.</p>
                    <p>Topped with a dollop of whipped cream, this delightful dessert offers a perfect balance of sweetness and tartness, making it a must-try for berry lovers!</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/menu/berry-crepe-delight.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-2">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Magnum Cendol</h3>
                    <p class="fst-italic">Experience a twist on a classic favorite with our Magnum Cendol.</p>
                    <p>This indulgent treat features creamy coconut milk, pandan jelly, and a rich chocolate ice cream core, all enveloped in a chocolate shell. It’s a refreshing dessert that combines traditional flavors with modern flair.</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/menu/magnum-cendol.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-3">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Magnum Crowned Jewel</h3>
                    <p class="fst-italic">Treat yourself like royalty with our Magnum Crowned Jewel.</p>
                    <p>This luxurious chocolate ice cream is wrapped in a rich chocolate coating and adorned with a sparkling mix of crunchy toppings. Each bite is a decadent experience that will leave you craving more.</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/menu/magnum-crowned-jewel.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="specials-tab-4">
                <div class="row">
                  <div class="col-lg-8 details order-2 order-lg-1">
                    <h3>Triple Choco Cookie</h3>
                    <p class="fst-italic">Satisfy your chocolate cravings with our Triple Choco Cookie.</p>
                    <p>This delightful creation features rich chocolate ice cream loaded with chocolate chips and chunks, all nestled between two soft, chewy cookies. It’s the ultimate treat for chocolate aficionados!</p>
                  </div>
                  <div class="col-lg-4 text-center order-1 order-lg-2">
                    <img src="assets/img/menu/triple-choco-cookie.jpg" alt="" class="img-fluid">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Specials Section -->



    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 order-1 order-lg-2">
            <img src="assets/img/magnum cafe.jpg" class="img-fluid about-img" alt="">
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

    </section><!-- /About Section -->
	
	<!-- Why Us Section -->
    <section id="why-us" class="why-us section">


      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>WHY US</h2>
        <p>Why Choose Our Restaurant</p>
      </div><!-- End Section Title -->

      <div class="container">

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

    </section><!-- /Why Us Section -->
	
   

  
	
	<!-- Events Section -->
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
                  <img src="assets/img/events-slider/birthday-party.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Birthday Parties</h3>
                  <div class="price">
                    <p><span>RM189</span></p>
                  </div>
                  <p class="fst-italic">
                    Celebrate your special day with us! Our birthday party package includes everything you need for a memorable celebration
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Fun activities for all ages</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Themed decorations to fit your party style</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Delicious ice-cream birthday cake included</span></li>
                  </ul>
                  <p>
                    Create unforgettable memories with friends and family as you enjoy a day filled with laughter and joy. 
					Let us handle the details while you focus on having fun!
                  </p>
                </div>
              </div>
            </div><!-- End Slider item -->

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/private-party.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Private Parties</h3>
                  <div class="price">
                    <p><span>RM290</span></p>
                  </div>
                  <p class="fst-italic">
                    Design your perfect event with our custom party package tailored to your needs! Whether it's a milestone celebration or a unique gathering, we’ve got you covered
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Personalized decorations to match your theme</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Customized menu options to satisfy every palate</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Flexible scheduling for your convenience</span></li>
                  </ul>
                  <p>
                    Bring your vision to life and enjoy a bespoke experience that reflects your style and preferences. 
					From concept to execution, we are here to make your dream event come true!
                  </p>
                </div>
              </div>
            </div><!-- End Slider item -->

            <div class="swiper-slide">
              <div class="row gy-4 event-item">
                <div class="col-lg-6">
                  <img src="assets/img/events-slider/custom-party.jpg" class="img-fluid" alt="">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 content">
                  <h3>Custom Parties</h3>
                  <div class="price">
                    <p><span>RM99</span></p>
                  </div>
                  <p class="fst-italic">
                    Host an exclusive gathering with our private party package, designed for those seeking a more intimate setting. 
					Perfect for corporate events, anniversaries, or small celebrations
                  </p>
                  <ul>
                    <li><i class="bi bi-check2-circle"></i> <span>Private venue access for a more personalized experience</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Dedicated staff to cater to your every need</span></li>
                    <li><i class="bi bi-check2-circle"></i> <span>Premium catering options for a luxurious dining experience</span></li>
                  </ul>
                  <p>
                    Enjoy a sophisticated atmosphere where you can connect with your guests without distractions. 
					We ensure every detail is taken care of, allowing you to relax and enjoy the occasion
                  </p>
                </div>
              </div>
            </div><!-- End Slider item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Events Section -->

  

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Testimonials</h2>
        <p>What they're saying about us</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="swiper init-swiper" data-speed="600" data-delay="5000" data-breakpoints="{ &quot;320&quot;: { &quot;slidesPerView&quot;: 1, &quot;spaceBetween&quot;: 40 }, &quot;1200&quot;: { &quot;slidesPerView&quot;: 3, &quot;spaceBetween&quot;: 40 } }">
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
                <span>Magnum Cafe ni memang best! Ice cream dia, wow! Rasa macam heaven, especially bila combine dengan chocolate. Perfect for a sweet escape after kerja. Kena datang lagi!</span>
                <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials1.jpg" class="testimonial-img" alt="">
                <h3>Fattah Amin</h3>
                <h4>Actor &amp; Singer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Magnum Cafe ni sangat istimewa. Setiap kali saya datang, saya rasa macam menyanyi di atas pentas! Rasa ice cream di sini sangat kaya dan menggoda. Truly a delightful experience!</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials2.jpg" class="testimonial-img" alt="">
                <h3>Dato' Siti Nurhaliza</h3>
                <h4>Singer &amp; Icon</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Magnum Cafe ni buat saya rasa like a kid again! Ice cream dia semua sedap, dan ambience sangat chic. Perfect untuk lepak dengan kawan-kawan!</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials3.jpg" class="testimonial-img" alt="">
                <h3>Nora Danish</h3>
                <h4>Actress &amp; Model</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Wah, Magnum Cafe! Setiap suapan ice cream tu macam satu lagu yang indah. Saya suka kombinasi rasa yang kreatif. Memang best untuk lepas penat berlatih. Highly recommended!</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials4.jpg" class="testimonial-img" alt="">
                <h3>Ernie Zakri</h3>
                <h4>Singer</h4>
              </div>
            </div><!-- End testimonial item -->

            <div class="swiper-slide">
              <div class="testimonial-item">
                <p>
                  <i class="bi bi-quote quote-icon-left"></i>
                  <span>Magnum Cafe ni power! Ice cream dia memang 'luxury' dan setiap rasa ada cerita. Suasana dia pun sangat 'Instagrammable'. Perfect untuk buat content dan enjoy dengan family!</span>
                  <i class="bi bi-quote quote-icon-right"></i>
                </p>
                <img src="assets/img/testimonials/testimonials5.jpg" class="testimonial-img" alt="">
                <h3>Dato' Aliff Syukri</h3>
                <h4>Entrepreneur &amp; Influencer</h4>
              </div>
            </div><!-- End testimonial item -->

          </div>
          <div class="swiper-pagination"></div>
        </div>

      </div>

    </section><!-- /Testimonials Section -->

    <!-- Gallery Section -->
    <section id="gallery" class="gallery section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Some photos from Our Cafe</p>
      </div><!-- End Section Title -->

      <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-0">

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery1.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery1.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery2.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery2.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery3.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery3.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery4.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery4.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery5.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery5.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery6.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery6.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery7.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery7.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

          <div class="col-lg-3 col-md-4">
            <div class="gallery-item">
              <a href="assets/img/gallery/gallery8.jpg" class="glightbox" data-gallery="images-gallery">
                <img src="assets/img/gallery/gallery8.jpg" alt="" class="img-fluid">
              </a>
            </div>
          </div><!-- End Gallery Item -->

        </div>

      </div>

    </section><!-- /Gallery Section -->

    <!-- Chefs Section -->
    <section id="chefs" class="chefs section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Chef</h2>
        <p>Necessitatibus eius consequatur</p>
      </div><!-- End Section Title -->

      <div class="container">

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
          </div><!-- End Team Member -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <img src="assets/img/chefs/chef-2.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Reynold Poernomo</h4>
                  <span>Sous</span>
                </div>
                <div class="social">
                  <a href="https://x.com/masterchefau/status/844736312452169729"><i class="bi bi-twitter-x"></i></a>
                  <a href="https://www.facebook.com/reynoldpoer/"><i class="bi bi-facebook"></i></a>
                  <a href="https://www.instagram.com/reynoldpoer/?hl=en"><i class="bi bi-instagram"></i></a>
                  <a href="https://au.linkedin.com/in/reynold-poernomo-989293281"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <img src="assets/img/chefs/chef-3.jpg" class="img-fluid" alt="">
              <div class="member-info">
                <div class="member-info-content">
                  <h4>Chef Wan</h4>
                  <span>Pastry Cook</span>
                </div>
                <div class="social">
                  <a href="https://x.com/chefwann"><i class="bi bi-twitter-x"></i></a>
                  <a href="https://www.instagram.com/chefwan1958_official/?hl=en"><i class="bi bi-facebook"></i></a>
                  <a href="https://my.linkedin.com/company/dewanbychefwan1958"><i class="bi bi-instagram"></i></a>
                  <a href="https://www.facebook.com/cafechefwan/"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Chefs Section -->
	
	
	
	
	
	
	
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
                  <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
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
      <div class="col-lg-8 footer-about">
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

</footer>

       

        

        

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">Magnum Cafe</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
      </div>
    </div>

  </footer>


  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>