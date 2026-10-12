<?php require 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stylists — Bloom Salon</title>
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
    <h1>Our stylists</h1>
  </section>

  <main class="section">
    <div class="container">
      <div class="grid">
        <article class="card master">
          <div class="avatar" style="background-image:url(images/model.jpg)"></div>
          <div class="card-body">
            <h3>Anna Lee</h3>
            <p class="meta">Stylist-colorist</p>
            <p class="stars">★★★★★</p>
            <a href="booking.php" class="btn btn-small">Book now</a>
          </div>
        </article>
        <article class="card master">
          <div class="avatar" style="background-image:url(images/wash.jpg)"></div>
          <div class="card-body">
            <h3>Dana Kim</h3>
            <p class="meta">Stylist</p>
            <p class="stars">★★★★★</p>
            <a href="booking.php" class="btn btn-small">Book now</a>
          </div>
        </article>
        <article class="card master">
          <div class="avatar" style="background-image:url(images/beard.jpg)"></div>
          <div class="card-body">
            <h3>Yerlan Seitov</h3>
            <p class="meta">Barber</p>
            <p class="stars">★★★★★</p>
            <a href="booking.php" class="btn btn-small">Book now</a>
          </div>
        </article>
        <article class="card master">
          <div class="avatar" style="background-image:url(images/nail-art.jpg)"></div>
          <div class="card-body">
            <h3>Madina Akhmetova</h3>
            <p class="meta">Nail artist</p>
            <p class="stars">★★★★★</p>
            <a href="booking.php" class="btn btn-small">Book now</a>
          </div>
        </article>
        <article class="card master">
          <div class="avatar" style="background-image:url(images/spa.jpg)"></div>
          <div class="card-body">
            <h3>Aliya Nurlan</h3>
            <p class="meta">Cosmetologist, makeup artist</p>
            <p class="stars">★★★★★</p>
            <a href="booking.php" class="btn btn-small">Book now</a>
          </div>
        </article>
      </div>

      <h2 class="section-title" style="margin-top:70px">Schedule for October 15</h2>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Stylist</th><th>10:00</th><th>12:00</th><th>14:00</th><th>16:00</th><th>18:00</th></tr>
          </thead>
          <tbody>
            <tr><td>Anna Lee</td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td></tr>
            <tr><td>Dana Kim</td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td></tr>
            <tr><td>Yerlan Seitov</td><td><span class="status free">free</span></td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td><td><span class="status free">free</span></td></tr>
            <tr><td>Madina Akhmetova</td><td><span class="status busy">booked</span></td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td><td><span class="status free">free</span></td><td><span class="status free">free</span></td></tr>
            <tr><td>Aliya Nurlan</td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td><td><span class="status busy">booked</span></td><td><span class="status free">free</span></td><td><span class="status busy">booked</span></td></tr>
          </tbody>
        </table>
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
