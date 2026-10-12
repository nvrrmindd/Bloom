<?php require 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Women's haircut — Bloom Salon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Bloom<span>.</span></a>
      <input type="checkbox" id="menu-toggle">
      <label for="menu-toggle" class="burger">☰</label>
      <?php require 'nav.php'; ?>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <div class="details">
        <div class="details-img" style="background-image:url(images/hair2.jpg)"></div>
        <div>
          <span class="tag">Hair</span>
          <h1>Women's haircut</h1>
          <p class="meta">Stylist: Anna Lee · 60 min</p>
          <p class="stars">★★★★★</p>
          <p class="price">from 8 000 ₸</p>
          <p>A haircut tailored to your face shape and hair type: consultation, wash, cut and styling.</p>
          <ul>
            <li>Stylist consultation</li>
            <li>Hair wash and care</li>
            <li>Haircuts of any complexity</li>
            <li>Styling</li>
          </ul>
          <a href="booking.php" class="btn">Book now</a>
        </div>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p class="logo">Bloom<span>.</span></p>
      <ul>
        <li><a href="about.php">About</a></li>
        <li><a href="about.php#contact">Contacts</a></li>
        <li><a href="#">Privacy</a></li>
        <li><a href="#">Terms</a></li>
      </ul>
      <ul>
        <li><a href="#">Instagram</a></li>
        <li><a href="#">Facebook</a></li>
        <li><a href="#">Telegram</a></li>
      </ul>
      <small>© 2026 Bloom Beauty Salon. Student Project</small>
    </div>
  </footer>
</body>
</html>
