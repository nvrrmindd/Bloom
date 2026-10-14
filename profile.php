<?php
require 'data.php';

$me = require_login();
$error = '';

// сохранение профиля
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first = trim($_POST['first']);
    $last  = trim($_POST['last']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $users = load_users();

    // email не должен быть занят другим пользователем
    $emailTaken = false;
    foreach ($users as $user) {
        if (strtolower($user['email']) == strtolower($email) && $user['id'] != $me['id']) {
            $emailTaken = true;
        }
    }

    if ($first == '' || $last == '' || $email == '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email.';
    } elseif ($emailTaken) {
        $error = 'A user with this email already exists.';
    } else {
        $oldName = client_name($me);
        foreach ($users as $i => $user) {
            if ($user['id'] == $me['id']) {
                $users[$i]['first'] = $first;
                $users[$i]['last']  = $last;
                $users[$i]['email'] = $email;
                $users[$i]['phone'] = $phone;
                $me = $users[$i];
            }
        }
        save_users($users);

        // в записях клиент подписан именем — обновляем его, чтобы история не потерялась
        $bookings = load_bookings();
        foreach ($bookings as $i => $b) {
            if ($b['client'] == $oldName) {
                $bookings[$i]['client'] = client_name($me);
                $bookings[$i]['phone']  = $phone;
            }
        }
        save_bookings($bookings);

        header('Location: profile.php?saved=1');
        exit;
    }
}

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

<?php if (isset($_GET['saved'])): ?>
        <p style="color:#2e7d4a;margin-top:20px">Profile saved.</p>
<?php endif; ?>
        <details class="edit" <?= $error != '' ? 'open' : '' ?>>
          <summary>Edit profile</summary>
          <form action="profile.php" method="post">
<?php if ($error != ''): ?>
            <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
<?php endif; ?>
            <div class="row">
              <div class="field"><label for="fn">First name *</label><input id="fn" name="first" value="<?= h($me['first']) ?>" required></div>
              <div class="field"><label for="ln">Last name *</label><input id="ln" name="last" value="<?= h($me['last']) ?>" required></div>
            </div>
            <div class="field">
              <label for="em">Email *</label>
              <input type="email" id="em" name="email" value="<?= h($me['email']) ?>" placeholder="you@email.com" required>
              <span class="error">Enter a valid email</span>
            </div>
            <div class="field">
              <label for="ph">Phone</label>
              <input type="tel" id="ph" name="phone" value="<?= h($me['phone']) ?>" placeholder="+7 777 123 45 67" pattern="\+?[0-9 ]{10,16}">
              <span class="error">Invalid phone format</span>
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
