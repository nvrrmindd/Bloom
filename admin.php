<?php
require __DIR__ . '/data.php';

require_admin();

$rows  = load_bookings();
$users = load_users();

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
          <div class="stat"><b>8</b>Услуг</div>
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
        <form class="inline-form" action="admin.php">
          <input placeholder="Название" required>
          <select required><option value="">Категория</option><option>Волосы</option><option>Ногти</option><option>Лицо</option></select>
          <input type="number" placeholder="Цена, ₸" min="1000" required>
          <input placeholder="Мастер" required>
          <button class="btn btn-small">Добавить</button>
        </form>
        <div class="table-wrap">
          <table>
            <thead><tr><th>Услуга</th><th>Категория</th><th>Цена</th><th></th></tr></thead>
            <tbody>
              <tr><td>Женская стрижка</td><td>Волосы</td><td>8 000 ₸</td><td><a href="#" class="btn btn-small">Изменить</a> <a href="#" class="btn btn-small btn-danger">Удалить</a></td></tr>
              <tr><td>Маникюр</td><td>Ногти</td><td>7 000 ₸</td><td><a href="#" class="btn btn-small">Изменить</a> <a href="#" class="btn btn-small btn-danger">Удалить</a></td></tr>
              <tr><td>Чистка лица</td><td>Лицо</td><td>12 000 ₸</td><td><a href="#" class="btn btn-small">Изменить</a> <a href="#" class="btn btn-small btn-danger">Удалить</a></td></tr>
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
