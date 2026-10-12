<?php
require __DIR__ . '/data.php';

require_admin();

$rows     = load_bookings();
$users    = load_users();
$services = load_services();

// услуги: add / update / delete
$action = $_POST['action'] ?? '';
if ($action !== '' && csrf_ok()) {
    $i     = (int) ($_POST['i'] ?? -1);
    $name  = trim((string) ($_POST['name'] ?? ''));
    $cat   = (string) ($_POST['cat'] ?? '');
    $price = (int) ($_POST['price'] ?? 0);
    $master = trim((string) ($_POST['master'] ?? ''));
    $valid = $name !== '' && $master !== '' && isset(CATEGORIES[$cat]) && $price > 0;

    if ($action === 'add' && $valid) {
        $services[] = [$cat, $name, $master, 60, 5, $price, 'images/salon.png'];
    } elseif ($action === 'update' && $valid && isset($services[$i])) {
        [$services[$i][0], $services[$i][1], $services[$i][2], $services[$i][5]] = [$cat, $name, $master, $price];
    } elseif ($action === 'delete' && isset($services[$i])) {
        unset($services[$i]);
    }
    save_services($services);
    header('Location: admin.php#services');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int) ($_POST['id'] ?? 0);
    $status = (string) ($_POST['status'] ?? '');
    if (csrf_ok() && isset(STATUSES[$status])) {
        foreach ($rows as &$r) {
            if ($r['id'] === $id) {
                $r['status'] = $status;
            }
        }
        unset($r);
        save_bookings($rows);
    }
    header('Location: admin.php#bookings');
    exit;
}

usort($rows, fn(array $a, array $b): int => $b['id'] <=> $a['id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Админ-панель — Bloom Salon</title>
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

  <div class="admin">
    <aside class="sidebar">
      <a href="#dashboard">Дашборд</a>
      <a href="#users">Пользователи</a>
      <a href="#services">Услуги</a>
      <a href="#bookings">Записи</a>
      <a href="logout.php">Выйти</a>
    </aside>

    <main class="admin-main">
      <section id="dashboard">
        <h2>Дашборд</h2>
        <div class="stats">
          <div class="stat"><b><?= count($users) ?></b>Пользователей</div>
          <div class="stat"><b><?= count($rows) ?></b>Записей</div>
          <div class="stat"><b><?= count($services) ?></b>Услуг</div>
          <div class="stat"><b>5</b>Мастеров</div>
        </div>
      </section>

      <section id="users">
        <h2>Пользователи</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th></tr></thead>
            <tbody>
<?php foreach ($users as $u): ?>
              <tr><td><?= (int) $u['id'] ?></td><td><?= h(full_name($u)) ?></td><td><?= h($u['email']) ?></td><td><?= $u['role'] === 'admin' ? 'Админ' : 'Клиент' ?></td></tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="services">
        <h2>Услуги</h2>
        <form class="inline-form" action="admin.php" method="post">
          <?= csrf_field() ?>
          <input name="name" placeholder="Название" required>
          <select name="cat" required>
            <option value="">Категория</option>
<?php foreach (CATEGORIES as $key => $label): ?>
            <option value="<?= $key ?>"><?= $label ?></option>
<?php endforeach; ?>
          </select>
          <input type="number" name="price" placeholder="Цена, ₸" min="1000" required>
          <input name="master" placeholder="Мастер" required>
          <button class="btn btn-small" name="action" value="add">Добавить</button>
        </form>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Услуга</th><th>Категория</th><th>Цена, ₸</th><th>Мастер</th><th></th></tr></thead>
            <tbody>
<?php foreach ($services as $i => [$cat, $name, $master, , , $price]): ?>
              <tr>
                <td><input form="s<?= $i ?>" name="name" value="<?= h($name) ?>" required></td>
                <td>
                  <select form="s<?= $i ?>" name="cat">
<?php foreach (CATEGORIES as $key => $label): ?>
                    <option value="<?= $key ?>"<?= $cat === $key ? ' selected' : '' ?>><?= $label ?></option>
<?php endforeach; ?>
                  </select>
                </td>
                <td><input form="s<?= $i ?>" type="number" name="price" value="<?= (int) $price ?>" min="1000" required></td>
                <td><input form="s<?= $i ?>" name="master" value="<?= h($master) ?>" required></td>
                <td>
                  <!-- поля строки привязаны к этой форме через form="s<?= $i ?>" -->
                  <form id="s<?= $i ?>" action="admin.php" method="post" style="display:flex;gap:6px">
                    <?= csrf_field() ?>
                    <input type="hidden" name="i" value="<?= $i ?>">
                    <button class="btn btn-small" name="action" value="update">Сохранить</button>
                    <button class="btn btn-small btn-danger" name="action" value="delete" formnovalidate>Удалить</button>
                  </form>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="bookings">
        <h2>Записи</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>№</th><th>Клиент</th><th>Услуга</th><th>Дата</th><th>Статус</th></tr></thead>
            <tbody>
<?php foreach ($rows as $r): ?>
              <tr>
                <td>#<?= (int) $r['id'] ?></td>
                <td><?= h($r['client']) ?></td>
                <td><?= h($r['service']) ?></td>
                <td><?= h(date('d.m.Y', strtotime($r['date'])) . ' ' . $r['time']) ?></td>
                <td>
                  <form action="admin.php" method="post" style="display:flex;gap:8px">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                    <select name="status">
<?php foreach (STATUSES as $key => $label): ?>
                      <option value="<?= h($key) ?>"<?= $r['status'] === $key ? ' selected' : '' ?>><?= h($label) ?></option>
<?php endforeach; ?>
                    </select>
                    <button class="btn btn-small">OK</button>
                  </form>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

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
