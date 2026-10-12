<?php
require 'data.php';

$me = require_login();

// названия услуг для выпадающего списка
$serviceNames = [];
foreach (load_services() as $s) {
    $serviceNames[] = $s['name'];
}

$v = ['service' => '', 'master' => '', 'date' => '', 'time' => '', 'phone' => $me['phone'], 'note' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($v as $key => $value) {
        $v[$key] = trim($_POST[$key]);
    }
    $bookings = load_bookings();

    // занято ли это время у мастера
    $busy = false;
    foreach ($bookings as $b) {
        if ($b['master'] == $v['master'] && $b['date'] == $v['date'] && $b['time'] == $v['time'] && $b['status'] != 'cancelled') {
            $busy = true;
        }
    }

    if ($v['service'] == '' || $v['master'] == '' || $v['date'] == '' || $v['time'] == '' || $v['phone'] == '') {
        $error = 'Please fill in all fields.';
    } elseif ($v['date'] < date('Y-m-d')) {
        $error = 'You cannot book a date in the past.';
    } elseif ($busy) {
        $error = 'This stylist is already booked at that time — choose another slot.';
    } else {
        $id = $bookings[count($bookings) - 1]['id'] + 1;
        $bookings[] = [
            'id'      => $id,
            'client'  => client_name($me),
            'service' => $v['service'],
            'master'  => $v['master'],
            'date'    => $v['date'],
            'time'    => $v['time'],
            'phone'   => $v['phone'],
            'note'    => $v['note'],
            'status'  => 'pending',
        ];
        save_bookings($bookings);
        header('Location: confirm.php?id=' . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking — Bloom Salon</title>
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
    <form class="form-box" action="booking.php" method="post">
      <h1>Book a visit</h1>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <div class="field">
        <label for="service">Service</label>
        <select id="service" name="service" required>
          <?php foreach ($serviceNames as $s): ?>
          <option<?= $v['service'] == $s ? ' selected' : '' ?>><?= h($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label for="master">Stylist</label>
        <select id="master" name="master" required>
          <?php foreach ($MASTERS as $m): ?>
          <option<?= $v['master'] == $m ? ' selected' : '' ?>><?= h($m) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="row">
        <div class="field">
          <label for="date">Date</label>
          <input type="date" id="date" name="date" value="<?= h($v['date']) ?>" min="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="field">
          <label for="time">Time</label>
          <select id="time" name="time" required>
            <option value="">—</option>
            <?php foreach ($SLOTS as $t): ?>
            <option<?= $v['time'] == $t ? ' selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= h($v['phone']) ?>" placeholder="+7 777 123 45 67" pattern="\+?[0-9 ]{10,16}" required>
        <span class="error">Enter a valid phone number</span>
      </div>
      <div class="field">
        <label for="note">Notes</label>
        <textarea id="note" name="note" rows="3"><?= h($v['note']) ?></textarea>
      </div>
      <button class="btn">Confirm</button>
    </form>
  </main>
</body>
</html>
