<?php
require __DIR__ . '/data.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

// после входа возвращаем на страницу, с которой пришли (только локальные .php)
$next = (string) ($_GET['next'] ?? $_POST['next'] ?? '');
if (!preg_match('/^[a-z]+\.php(\?[\w=&%.-]*)?$/i', $next)) {
    $next = '';
}
$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $found = null;
    foreach (load_users() as $u) {
        if (mb_strtolower($u['email']) === mb_strtolower($email)) {
            $found = $u;
        }
    }

    if (!csrf_ok() || $found === null || !password_verify((string) ($_POST['password'] ?? ''), $found['password'])) {
        $error = 'Неверный email или пароль.';
    } else {
        session_regenerate_id(true);
        $_SESSION['uid'] = $found['id'];
        header('Location: ' . ($next !== '' ? $next : ($found['role'] === 'admin' ? 'admin.php' : 'index.php')));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Вход — Bloom Salon</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Bloom<span>.</span></a>
    </div>
  </header>

  <main class="form-page">
    <form class="form-box" action="login.php" method="post">
      <h1>Вход</h1>
      <?php if (isset($_GET['registered'])): ?>
      <p style="color:#2e7d4a;margin-bottom:14px">Аккаунт создан. Теперь можно войти.</p>
      <?php endif; ?>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <?= csrf_field() ?>
      <input type="hidden" name="next" value="<?= h($next) ?>">
      <div class="field">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= h($email) ?>" placeholder="you@email.com" required>
        <span class="error">Введите корректный email</span>
      </div>
      <div class="field">
        <label for="password">Пароль *</label>
        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" required>
        <span class="error">Пароль — минимум 6 символов</span>
      </div>
      <button class="btn">Войти</button>
      <p class="bottom">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
    </form>
  </main>
</body>
</html>
