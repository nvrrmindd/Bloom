<?php
require 'data.php';

// расписание на выбранный день (по умолчанию — сегодня)
$day = isset($_GET['date']) ? $_GET['date'] : '';
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $day)) {
    $day = date('Y-m-d');
}
$bookings = load_bookings();
?>
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

      <h2 class="section-title" style="margin-top:70px">Schedule for <?= h(date('F j', strtotime($day))) ?></h2>
      <form method="get" class="search-bar" style="max-width:320px;margin:0 auto 20px">
        <input type="date" name="date" value="<?= h($day) ?>" onchange="this.form.submit()">
        <noscript><button class="btn btn-small">Show</button></noscript>
      </form>
      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Stylist</th><?php foreach ($SLOTS as $t): ?><th><?= $t ?></th><?php endforeach; ?></tr>
          </thead>
          <tbody>
            <?php foreach ($MASTERS as $name): $busy = busy_slots($bookings, $name, $day); ?>
            <tr>
              <td><?= h($name) ?></td>
              <?php foreach ($SLOTS as $t): ?>
              <?php if (in_array($t, $busy)): ?>
              <td><span class="status busy">booked</span></td>
              <?php else: ?>
              <td><span class="status free">free</span></td>
              <?php endif; ?>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
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
