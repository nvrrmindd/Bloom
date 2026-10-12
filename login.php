<?php
require 'data.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // ищем пользователя по email
    $found = null;
    foreach (load_users() as $user) {
        if (strtolower($user['email']) == strtolower($email)) {
            $found = $user;
        }
    }

    if ($found == null || !password_verify($password, $found['password'])) {
        $error = 'Invalid email or password.';
    } else {
        $_SESSION['user_id'] = $found['id'];
        if ($found['role'] == 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in — Bloom Salon</title>
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
      <h1>Log in</h1>
      <?php if (isset($_GET['registered'])): ?>
      <p style="color:#2e7d4a;margin-bottom:14px">Account created. You can log in now.</p>
      <?php endif; ?>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <div class="field">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= h($email) ?>" placeholder="you@email.com" required>
        <span class="error">Enter a valid email</span>
      </div>
      <div class="field">
        <label for="password">Password *</label>
        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" required>
        <span class="error">Password must be at least 6 characters</span>
      </div>
      <button class="btn">Log in</button>
      <p class="bottom">No account? <a href="register.php">Sign up</a></p>
    </form>
  </main>
</body>
</html>
