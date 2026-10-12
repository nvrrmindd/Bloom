<?php
require __DIR__ . '/data.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

$v = ['first' => '', 'last' => '', 'email' => '', 'phone' => '', 'dob' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($v as $key => $_) {
        $v[$key] = trim((string) ($_POST[$key] ?? ''));
    }
    $password = (string) ($_POST['password'] ?? '');
    $users    = load_users();

    $emailTaken = false;
    foreach ($users as $u) {
        if (mb_strtolower($u['email']) === mb_strtolower($v['email'])) {
            $emailTaken = true;
        }
    }

    if (!csrf_ok()) {
        $error = 'Сессия устарела, обновите страницу.';
    } elseif (!preg_match('/^[\p{L} ]{2,}$/u', $v['first']) || !preg_match('/^[\p{L} ]{2,}$/u', $v['last'])) {
        $error = 'Имя и фамилия — минимум 2 буквы.';
    } elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Введите корректный email.';
    } elseif ($v['phone'] !== '' && !preg_match('/^\+?[0-9 ]{10,16}$/', $v['phone'])) {
        $error = 'Неверный формат телефона.';
    } elseif ($v['dob'] !== '' && !(sscanf($v['dob'], '%d-%d-%d', $y, $m, $d) === 3 && checkdate($m, $d, $y) && $v['dob'] <= '2014-12-31')) {
        $error = 'Неверная дата рождения.';
    } elseif (mb_strlen($password) < 6) {
        $error = 'Пароль — минимум 6 символов.';
    } elseif ($password !== (string) ($_POST['confirm'] ?? '')) {
        $error = 'Пароли не совпадают.';
    } elseif ($emailTaken) {
        $error = 'Пользователь с таким email уже есть.';
    } else {
        $users[] = ['id' => max(array_column($users, 'id')) + 1] + $v
            + ['password' => password_hash($password, PASSWORD_DEFAULT), 'role' => 'client'];
        write_json(USERS_FILE, $users);
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Регистрация — Bloom Salon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Bloom<span>.</span></a>
    </div>
  </header>

  <main class="form-page">
    <form class="form-box" action="register.php" method="post">
      <h1>Создать аккаунт</h1>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <?= csrf_field() ?>
      <div class="row">
        <div class="field">
          <label for="first">Имя *</label>
          <input id="first" name="first" value="<?= h($v['first']) ?>" placeholder="Айгерим" pattern="[A-Za-zА-Яа-яЁё ]{2,}" required>
          <span class="error">Минимум 2 буквы</span>
        </div>
        <div class="field">
          <label for="last">Фамилия *</label>
          <input id="last" name="last" value="<?= h($v['last']) ?>" placeholder="Садыкова" pattern="[A-Za-zА-Яа-яЁё ]{2,}" required>
          <span class="error">Минимум 2 буквы</span>
        </div>
      </div>
      <div class="field">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= h($v['email']) ?>" placeholder="you@email.com" required>
        <span class="error">Введите корректный email</span>
      </div>
      <div class="field">
        <label for="phone">Телефон</label>
        <input type="tel" id="phone" name="phone" value="<?= h($v['phone']) ?>" placeholder="+7 777 123 45 67" pattern="\+?[0-9 ]{10,16}">
        <span class="error">Неверный формат телефона</span>
      </div>
      <div class="field">
        <label for="dob">Дата рождения</label>
        <input type="date" id="dob" name="dob" value="<?= h($v['dob']) ?>" max="2014-12-31">
      </div>
      <div class="field">
        <label for="password">Пароль *</label>
        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" required>
        <span class="error">Пароль — минимум 6 символов</span>
      </div>
      <div class="field">
        <label for="confirm">Повторите пароль *</label>
        <input type="password" id="confirm" name="confirm" placeholder="••••••••" minlength="6" required>
        <span class="error">Пароли должны совпадать</span>
      </div>
      <button class="btn">Зарегистрироваться</button>
      <p class="bottom">Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </form>
  </main>
</body>
</html>
