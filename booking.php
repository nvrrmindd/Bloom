<?php
require __DIR__ . '/data.php';

$me = require_login();

$v = ['service' => 'Женская стрижка', 'master' => '', 'date' => '', 'time' => '', 'phone' => $me['phone'], 'note' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $key => $_) {
        $v[$key] = trim((string) ($_POST[$key] ?? ''));
    }
    $rows = load_bookings();

    $dateOk = sscanf($v['date'], '%d-%d-%d', $y, $m, $d) === 3 && checkdate($m, $d, $y) && $v['date'] >= date('Y-m-d');
    $busy = false;
    foreach ($rows as $r) {
        if ($r['master'] === $v['master'] && $r['date'] === $v['date'] && $r['time'] === $v['time'] && $r['status'] !== 'cancelled') {
            $busy = true;
        }
    }

    if (!csrf_ok() || !in_array($v['service'], SERVICES, true) || !in_array($v['master'], MASTERS, true)
        || !in_array($v['time'], SLOTS, true) || !$dateOk
        || !preg_match('/^\+?[0-9 ]{10,16}$/', $v['phone'])) {
        $error = 'Проверьте, что все поля заполнены верно.';
    } elseif ($busy) {
        $error = 'Это время у мастера уже занято — выберите другое.';
    } else {
        $id = $rows ? max(array_column($rows, 'id')) + 1 : 10001;
        $rows[] = ['id' => $id, 'client' => client_name($me)] + $v + ['status' => 'pending'];
        save_bookings($rows);
        header('Location: confirm.php?id=' . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Запись — Bloom Salon</title>
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

  <main class="form-page">
    <form class="form-box" action="booking.php" method="post">
      <h1>Запись в салон</h1>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <?= csrf_field() ?>
      <div class="field">
        <label for="service">Услуга</label>
        <select id="service" name="service" required>
          <?php foreach (SERVICES as $s): ?>
          <option<?= $v['service'] === $s ? ' selected' : '' ?>><?= h($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="master">Мастер</label>
        <select id="master" name="master" required>
          <?php foreach (MASTERS as $m): ?>
          <option<?= $v['master'] === $m ? ' selected' : '' ?>><?= h($m) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="row">
        <div class="field">
          <label for="date">Дата</label>
          <input type="date" id="date" name="date" value="<?= h($v['date']) ?>" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="field">
          <label for="time">Время</label>
          <select id="time" name="time" required>
            <option value="">—</option>
            <?php foreach (SLOTS as $t): ?>
            <option<?= $v['time'] === $t ? ' selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="phone">Телефон</label>
        <input type="tel" id="phone" name="phone" value="<?= h($v['phone']) ?>" placeholder="+7 777 123 45 67" pattern="\+?[0-9 ]{10,16}" required>
        <span class="error">Введите корректный номер телефона</span>
      </div>
      <div class="field">
        <label for="note">Пожелания</label>
        <textarea id="note" name="note" rows="3"><?= h($v['note']) ?></textarea>
      </div>
      <button class="btn">Подтвердить</button>
    </form>
  </main>
</body>
</html>
