<?php
require __DIR__ . '/data.php';

$booking = null;
foreach (load_bookings() as $r) {
    if ($r['id'] === (int) ($_GET['id'] ?? 0)) {
        $booking = $r;
    }
}
if ($booking === null) {
    http_response_code(404);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Запись подтверждена — Bloom Salon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="container">
      <a href="index.html" class="logo">Bloom<span>.</span></a>
      <input type="checkbox" id="menu-toggle">
      <label for="menu-toggle" class="burger">☰</label>
      <nav>
        <ul>
        <li><a href="index.html">Главная</a></li>
        <li><a href="services.html">Услуги</a></li>
        <li><a href="masters.html">Мастера</a></li>
        <li><a href="booking.php" class="active">Запись</a></li>
        <li><a href="about.html">О нас</a></li>
        <li><a href="profile.php">Профиль</a></li>
        <li><a href="login.html">Выйти</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="form-page">
    <div class="form-box success">
<?php if ($booking === null): ?>
      <h1>Запись не найдена</h1>
      <a href="booking.php" class="btn">Записаться</a>
<?php else: ?>
      <div class="check">✓</div>
      <h1>Запись подтверждена</h1>
      <p>Ждём вас в Bloom! Детали записи ниже.</p>
      <dl>
        <dt>Номер записи</dt><dd>#<?= (int) $booking['id'] ?></dd>
        <dt>Услуга</dt><dd><?= h($booking['service']) ?></dd>
        <dt>Мастер</dt><dd><?= h($booking['master']) ?></dd>
        <dt>Дата и время</dt><dd><?= h(date('d.m.Y', strtotime($booking['date'])) . ', ' . $booking['time']) ?></dd>
      </dl>
      <a href="profile.php" class="btn">Мои записи</a>
<?php endif; ?>
    </div>
  </main>
</body>
</html>
