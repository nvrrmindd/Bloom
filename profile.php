<?php
require 'data.php';

$me = require_login();

// записи этого клиента
$mine = [];
foreach (load_bookings() as $b) {
    if ($b['client'] == client_name($me)) {
        $mine[] = $b;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile — Bloom Salon</title>
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
    <div class="container profile">
      <aside class="profile-card">
        <div class="avatar" style="background-image:url(images/nails.jpg)"></div>
        <h2><?= h(full_name($me)) ?></h2>
        <p><?= h($me['email']) ?></p>
<?php if ($me['phone'] != ''): ?>
        <p><?= h($me['phone']) ?></p>
<?php endif; ?>
<?php if ($me['dob'] != ''): ?>
        <p>Date of birth: <?= h(date('d.m.Y', strtotime($me['dob']))) ?></p>
<?php endif; ?>
        <a href="logout.php" class="btn btn-small">Log out</a>
      </aside>

      <div>
        <h2 class="title">My bookings</h2>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>№</th><th>Service</th><th>Stylist</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
<?php foreach ($mine as $r): ?>
              <tr><td>#<?= $r['id'] ?></td><td><?= h($r['service']) ?></td><td><?= h($r['master']) ?></td><td><?= h(date('d.m.Y', strtotime($r['date'])) . ' ' . $r['time']) ?></td><td><span class="status <?= h($r['status']) ?>"><?= h($STATUSES[$r['status']]) ?></span></td></tr>
<?php endforeach; ?>
<?php if (!$mine): ?>
              <tr><td colspan="5">No bookings yet. <a href="booking.php">Book now</a></td></tr>
<?php endif; ?>
            </tbody>
          </table>
        </div>

        <details class="edit">
          <summary>Edit profile</summary>
          <form action="profile.php">
            <div class="row">
              <div class="field"><label for="fn">First name</label><input id="fn" value="<?= h($me['first']) ?>" required></div>
              <div class="field"><label for="ln">Last name</label><input id="ln" value="<?= h($me['last']) ?>" required></div>
            </div>
            <div class="field">
              <label for="em">Email</label>
              <input type="email" id="em" value="<?= h($me['email']) ?>" placeholder="you@email.com" required>
              <span class="error">Enter a valid email</span>
            </div>
            <button class="btn">Save</button>
          </form>
        </details>
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
