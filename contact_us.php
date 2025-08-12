<?php
session_start();

// Read contact info
$infoFile = "contact_info.txt";
if (!file_exists($infoFile)) {
    file_put_contents($infoFile, json_encode([
        "address" => "198 West 21th Street, Suite 721, New York NY 10016",
        "phone" => "+1235 2355 98",
        "email" => "info@yoursite.com",
        "website" => "yoursite.com"
    ]));
}
$contactInfo = json_decode(file_get_contents($infoFile), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #1e1f26;
      margin: 0;
      padding: 0;
      color: #fff;
    }

    h2 {
      text-align: center;
      padding: 40px 0 20px;
      font-size: 36px;
      margin: 0;
      color: #fff;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      width: 100%;
      background: #1e1f26;
    }

    .contact-form, .contact-info {
      flex: 1 1 50%;
      padding: 60px;
      box-sizing: border-box;
    }

    .contact-form {
      background-color: #2d2e36;
    }

    .contact-form h3 {
      margin-bottom: 40px;
      font-size: 32px;
    }

    .contact-form input,
    .contact-form textarea {
      width: 100%;
      padding: 15px;
      margin-bottom: 30px;
      background: transparent;
      border: none;
      border-bottom: 2px solid #555;
      color: #fff;
      font-size: 18px;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
      outline: none;
      border-color: #ffa726;
    }

    .contact-form button {
      background: #ffa726;
      border: none;
      padding: 16px 40px;
      color: white;
      font-size: 18px;
      border-radius: 4px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .contact-form button:hover {
      background: #fb8c00;
    }

    .contact-info {
      background-color: #1e1f26;
    }

    .contact-info h4 {
      font-size: 32px;
      margin-bottom: 30px;
    }

    .contact-info p {
      margin-bottom: 30px;
      font-size: 18px;
      color: #bbb;
    }

    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      font-size: 18px;
      color: #bbb;
    }

    .info-item .icon-circle {
      width: 50px;
      height: 50px;
      background: #2d2e36;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffa726;
      font-size: 20px;
      margin-right: 20px;
    }

    .info-item span {
      color: #fff;
      margin-right: 10px;
      font-weight: bold;
    }

    a {
      color: #ffa726;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .contact-form, .contact-info {
        padding: 40px 30px;
      }
    }
  </style>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
</head>
<body>

<h2>Contact Us</h2>

<div class="container">
  <div class="contact-form">
    <h3>Write us</h3>
    <form action="send_feedback.php" method="POST">
      <input type="text" name="name" placeholder="Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="subject" placeholder="Subject" required>
      <textarea name="message" placeholder="Message" rows="6" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>

  <div class="contact-info">
    <h4>Contact Information</h4>
    <p>We're open for any suggestion or just to have a chat.</p>

    <div class="info-item">
      <div class="icon-circle"><i class="fas fa-map-marker-alt"></i></div>
      <div><span>Address:</span> <?= htmlspecialchars($contactInfo['address']) ?></div>
    </div>

    <div class="info-item">
      <div class="icon-circle"><i class="fas fa-phone-alt"></i></div>
      <div><span>Phone:</span> <?= htmlspecialchars($contactInfo['phone']) ?></div>
    </div>

    <div class="info-item">
      <div class="icon-circle"><i class="fas fa-paper-plane"></i></div>
      <div><span>Email:</span> <a href="mailto:<?= htmlspecialchars($contactInfo['email']) ?>"><?= htmlspecialchars($contactInfo['email']) ?></a></div>
    </div>

    <div class="info-item">
      <div class="icon-circle"><i class="fas fa-globe"></i></div>
      <div><span>Website:</span> <a href="#"><?= htmlspecialchars($contactInfo['website']) ?></a></div>
    </div>
  </div>
</div>

</body>
</html>
