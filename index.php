<?php require 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home — Bloom Salon</title>
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

  <main>
    <section class="hero">
      <div class="container">
        <img src="images/hero-tools.png" alt="Stylist tools">
        <div>
<?php if ($me = current_user()): ?>
          <small>Hi, <?= h($me['first']) ?></small>
<?php else: ?>
          <small>Bloom Beauty Salon</small>
<?php endif; ?>
          <h1>Book your<br>beauty</h1>
          <p>Choose a service, a stylist and a free slot — book your visit online in a couple of minutes.</p>
          <div class="actions">
            <a href="booking.php" class="btn btn-outline">Book now</a>
            <a href="services.php" class="link">View services</a>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <h2 class="section-title">Why Bloom</h2>
        <div class="features">
          <div class="feature">
            <img class="icon" src="images/items/scissors.png" alt="Scissors">
            <h3>Services</h3>
            <p>Haircuts, coloring, manicure, facials and makeup.</p>
          </div>
          <div class="feature">
            <img class="icon" src="images/items/dryer.png" alt="Hair dryer">
            <h3>Stylists</h3>
            <p>An experienced team — check each stylist's portfolio and rating.</p>
          </div>
          <div class="feature">
            <img class="icon" src="images/items/comb.png" alt="Comb">
            <h3>Schedule</h3>
            <p>See free slots and pick a convenient date and time.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <h2 class="section-title">Popular services</h2>
        <div class="grid">
        <article class="card hair">
          <div class="card-img" style="background-image:url(images/hair2.jpg)"></div>
          <div class="card-body">
            <span class="tag">Hair</span>
            <h3>Women's haircut</h3>
            <p class="meta">Anna Lee · 60 min</p>
            <p class="stars">★★★★★</p>
            <p class="price">from 8 000 ₸</p>
            <a href="details.php" class="btn btn-small">Details</a>
          </div>
        </article>
        <article class="card hair">
          <div class="card-img" style="background-image:url(images/tools.png)"></div>
          <div class="card-body">
            <span class="tag">Hair</span>
            <h3>Coloring</h3>
            <p class="meta">Anna Lee · 180 min</p>
            <p class="stars">★★★★★</p>
            <p class="price">from 25 000 ₸</p>
            <a href="details.php" class="btn btn-small">Details</a>
          </div>
        </article>
        <article class="card hair">
          <div class="card-img" style="background-image:url(images/salon.png)"></div>
          <div class="card-body">
            <span class="tag">Hair</span>
            <h3>Styling & curls</h3>
            <p class="meta">Dana Kim · 45 min</p>
            <p class="stars">★★★★☆</p>
            <p class="price">from 6 000 ₸</p>
            <a href="details.php" class="btn btn-small">Details</a>
          </div>
        </article>
        <article class="card hair">
          <div class="card-img" style="background-image:url(images/barber.jpg)"></div>
          <div class="card-body">
            <span class="tag">Hair</span>
            <h3>Men's haircut</h3>
            <p class="meta">Yerlan Seitov · 40 min</p>
            <p class="stars">★★★★★</p>
            <p class="price">from 5 000 ₸</p>
            <a href="details.php" class="btn btn-small">Details</a>
          </div>
        </article>
        </div>
      </div>
    </section>
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
