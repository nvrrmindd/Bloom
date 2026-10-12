<?php
require __DIR__ . '/data.php';

$me = require_login();

$mine = array_filter(load_bookings(), fn(array $r): bool => $r['client'] === client_name($me));
usort($mine, fn(array $a, array $b): int => [$b['date'], $b['time']] <=> [$a['date'], $a['time']]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Профиль — Bloom Salon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Bloom<span>.</span></a>
      <input type="checkbox" id="menu-toggle">
      <label for="menu-toggle" class="burger">☰</label>
      <?php require __DIR__ . "/nav.php"; ?>
    </div>
  </header>

  <main class="section">
    <div class="container profile">
      <aside class="profile-card fade-up">
        <div class="avatar" style="background-image:url(images/nails.jpg)"></div>
        <h2><?= h(full_name($me)) ?></h2>
        <p><?= h($me['email']) ?></p>
<?php if ($me['phone'] !== ''): ?>
        <p><?= h($me['phone']) ?></p>
<?php endif; ?>
<?php if ($me['dob'] !== ''): ?>
        <p>Дата рождения: <?= h(date('d.m.Y', strtotime($me['dob']))) ?></p>
<?php endif; ?>
        <a href="logout.php" class="btn btn-small">Выйти</a>
      </aside>

      <div class="fade-up delay-1">
        <h2 class="title">Мои записи</h2>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>№</th><th>Услуга</th><th>Мастер</th><th>Дата</th><th>Статус</th></tr>
            </thead>
            <tbody>
<?php foreach ($mine as $r): ?>
              <tr><td>#<?= (int) $r['id'] ?></td><td><?= h($r['service']) ?></td><td><?= h($r['master']) ?></td><td><?= h(date('d.m.Y', strtotime($r['date'])) . ' ' . $r['time']) ?></td><td><span class="status <?= h($r['status']) ?>"><?= h(STATUSES[$r['status']] ?? $r['status']) ?></span></td></tr>
<?php endforeach; ?>
<?php if (!$mine): ?>
              <tr><td colspan="5">Записей пока нет. <a href="booking.php">Записаться</a></td></tr>
<?php endif; ?>
            </tbody>
          </table>
        </div>

        <details class="edit">
          <summary>Редактировать профиль</summary>
          <form action="profile.php">
            <div class="row">
              <div class="field"><label for="fn">Имя</label><input id="fn" value="<?= h($me['first']) ?>" required></div>
              <div class="field"><label for="ln">Фамилия</label><input id="ln" value="<?= h($me['last']) ?>" required></div>
            </div>
            <div class="field">
              <label for="em">Email</label>
              <input type="email" id="em" value="<?= h($me['email']) ?>" placeholder="you@email.com" required>
              <span class="error">Введите корректный email</span>
            </div>
            <button class="btn">Сохранить</button>
          </form>
        </details>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <p class="logo">Bloom<span>.</span></p>
      <ul>
        <li><a href="about.php">О нас</a></li>
        <li><a href="about.php#contact">Контакты</a></li>
        <li><a href="#">Конфиденциальность</a></li>
        <li><a href="#">Условия</a></li>
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
