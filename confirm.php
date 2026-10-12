<?php
require 'data.php';

$me = require_login();

// ищем запись по номеру из адреса; чужую запись видит только админ
$booking = null;
foreach (load_bookings() as $b) {
    if ($b['id'] == $_GET['id'] && ($me['role'] == 'admin' || $b['client'] == client_name($me))) {
        $booking = $b;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking confirmed — Bloom Salon</title>
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

  <main class="form-page">
    <div class="form-box success">
<?php if ($booking == null): ?>
      <h1>Booking not found</h1>
      <a href="booking.php" class="btn">Book now</a>
<?php else: ?>
      <div class="check">✓</div>
      <h1>Booking confirmed</h1>
      <p>See you at Bloom! Booking details are below.</p>
      <dl>
        <dt>Booking ID</dt><dd>#<?= $booking['id'] ?></dd>
        <dt>Service</dt><dd><?= h($booking['service']) ?></dd>
        <dt>Stylist</dt><dd><?= h($booking['master']) ?></dd>
        <dt>Date and time</dt><dd><?= h(date('d.m.Y', strtotime($booking['date'])) . ', ' . $booking['time']) ?></dd>
      </dl>
      <a href="profile.php" class="btn">My bookings</a>
<?php endif; ?>
    </div>
  </main>
</body>
</html>
