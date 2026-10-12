<?php require 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About — Bloom Salon</title>
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

  <section class="page-banner">
    <h1>About the salon Bloom</h1>
  </section>

  <main class="section">
    <div class="container">
      <div class="details">
        <div class="details-img" style="background-image:url(images/team.jpg)"></div>
        <div>
          <h2 class="section-title">Beauty without the fuss</h2>
          <p>Bloom — a beauty salon where you can choose a service, a stylist and a convenient time online in a couple of minutes.</p>
          <ul>
            <li>Experienced stylists with portfolio and rating</li>
            <li>Haircuts, coloring, manicure, facials and makeup</li>
            <li>Online booking and an up-to-date schedule</li>
          </ul>
          <a href="services.php" class="btn">Choose a service</a>
        </div>
      </div>

      <section id="contact" style="margin-top:70px">
        <h2 class="section-title">Contacts</h2>
        <div class="features">
          <div class="feature">
            <h3>Address</h3>
            <p>Astana, Primernaya St., 1</p>
          </div>
          <div class="feature">
            <h3>Phone</h3>
            <p>+7 777 000 00 00</p>
          </div>
          <div class="feature">
            <h3>Opening hours</h3>
            <p>Daily 10:00 – 20:00</p>
          </div>
        </div>
      </section>
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
