<?php
require 'data.php';

if (current_user()) {
    header('Location: index.php');
    exit;
}

$v = ['first' => '', 'last' => '', 'email' => '', 'phone' => '', 'dob' => ''];
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($v as $key => $value) {
        $v[$key] = trim($_POST[$key]);
    }
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];
    $users    = load_users();

    // проверяем, не занят ли email
    $emailTaken = false;
    foreach ($users as $user) {
        if (strtolower($user['email']) == strtolower($v['email'])) {
            $emailTaken = true;
        }
    }

    if ($v['first'] == '' || $v['last'] == '' || $v['email'] == '') {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($v['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password != $confirm) {
        $error = 'Passwords do not match.';
    } elseif ($emailTaken) {
        $error = 'A user with this email already exists.';
    } else {
        $v['id']       = count($users) + 1;
        $v['password'] = password_hash($password, PASSWORD_DEFAULT);
        $v['role']     = 'client';
        $users[] = $v;
        save_users($users);
        header('Location: login.php?registered=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign up — Bloom Salon</title>
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
      <h1>Create account</h1>
      <?php if ($error): ?>
      <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
      <?php endif; ?>
      <div class="row">
        <div class="field">
          <label for="first">First name *</label>
          <input id="first" name="first" value="<?= h($v['first']) ?>" placeholder="Aigerim" pattern="[A-Za-zА-Яа-яЁё ]{2,}" required>
          <span class="error">At least 2 letters</span>
        </div>
        <div class="field">
          <label for="last">Last name *</label>
          <input id="last" name="last" value="<?= h($v['last']) ?>" placeholder="Sadykova" pattern="[A-Za-zА-Яа-яЁё ]{2,}" required>
          <span class="error">At least 2 letters</span>
        </div>
      </div>
      <div class="field">
        <label for="email">Email *</label>
        <input type="email" id="email" name="email" value="<?= h($v['email']) ?>" placeholder="you@email.com" required>
        <span class="error">Enter a valid email</span>
      </div>
      <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone" value="<?= h($v['phone']) ?>" placeholder="+7 777 123 45 67" pattern="\+?[0-9 ]{10,16}">
        <span class="error">Invalid phone format</span>
      </div>
      <div class="field">
        <label for="dob">Date of birth</label>
        <input type="date" id="dob" name="dob" value="<?= h($v['dob']) ?>" max="2014-12-31">
      </div>
      <div class="field">
        <label for="password">Password *</label>
        <input type="password" id="password" name="password" placeholder="••••••••" minlength="6" required>
        <span class="error">Password must be at least 6 characters</span>
      </div>
      <div class="field">
        <label for="confirm">Confirm password *</label>
        <input type="password" id="confirm" name="confirm" placeholder="••••••••" minlength="6" required>
        <span class="error">Passwords must match</span>
      </div>
      <button class="btn">Sign up</button>
      <p class="bottom">Already have an account? <a href="login.php">Log in</a></p>
    </form>
  </main>
</body>
</html>
