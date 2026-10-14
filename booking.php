<?php
require 'data.php';

$me = require_login();

// названия услуг для выпадающего списка
$serviceNames = [];
foreach (load_services() as $s) {
    $serviceNames[] = $s['name'];
}

$v = ['service' => '', 'master' => '', 'date' => '', 'time' => '', 'note' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($v as $key => $value) {
        $v[$key] = trim($_POST[$key]);
    }
    if ($v['service'] == '' || $v['master'] == '' || $v['date'] == '' || $v['time'] == '') {
        $error = 'Please fill in all fields.';
    } elseif (!in_array($v['master'], $MASTERS) || !in_array($v['time'], $SLOTS)) {
        $error = 'Please choose a stylist and time from the list.';
    } elseif ($v['date'] < date('Y-m-d')) {
        $error = 'You cannot book a date in the past.';
    } else {
        // блокировка: два одновременных запроса не смогут занять один слот
        $lock = fopen(__DIR__ . '/data/booking.lock', 'c');
        flock($lock, LOCK_EX);
        $bookings = load_bookings();

        if (in_array($v['time'], busy_slots($bookings, $v['master'], $v['date']))) {
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
                'phone'   => $me['phone'], // телефон из профиля
                'note'    => $v['note'],
                'status'  => 'pending',
            ];
            save_bookings($bookings);
        }
        flock($lock, LOCK_UN);
        fclose($lock);

        if ($error == '') {
            header('Location: confirm.php?id=' . $id);
            exit;
        }
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
            <option value="<?= $t ?>"<?= $v['time'] == $t ? ' selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="field">
        <label for="note">Notes</label>
        <textarea id="note" name="note" rows="3"><?= h($v['note']) ?></textarea>
      </div>
      <button class="btn">Confirm</button>
    </form>
  </main>

  <script>
    // показываем только свободное время выбранного мастера на выбранную дату
    var master = document.getElementById('master'),
        date = document.getElementById('date'),
        time = document.getElementById('time');

    function refreshSlots() {
      if (!date.value) return;
      fetch('slots.php?master=' + encodeURIComponent(master.value) + '&date=' + encodeURIComponent(date.value))
        .then(function (r) { return r.json(); })
        .then(function (busy) {
          Array.prototype.forEach.call(time.options, function (o) {
            if (o.value === '') return;
            var taken = busy.indexOf(o.value) !== -1;
            o.disabled = taken;
            o.textContent = o.value + (taken ? ' (booked)' : '');
            if (taken && o.selected) time.value = '';
          });
        });
    }
    master.addEventListener('change', refreshSlots);
    date.addEventListener('change', refreshSlots);
    refreshSlots();
  </script>
</body>
</html>
