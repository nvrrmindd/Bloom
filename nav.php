<?php
// Меню: набор пунктов зависит from того, вошёл ли пользователь
$me  = current_user();
$cur = basename($_SERVER['SCRIPT_NAME']);
// у страниц деталей и подтверждения подсвечиваем родительский пункт меню
if ($cur == 'details.php') {
    $cur = 'services.php';
}
if ($cur == 'confirm.php') {
    $cur = 'booking.php';
}

$items = [
    'index.php'    => 'Home',
    'services.php' => 'Services',
    'masters.php'  => 'Stylists',
    'booking.php'  => 'Booking',
    'about.php'    => 'About',
];
if ($me) {
    $items['profile.php'] = 'Profile';
    if ($me['role'] == 'admin') {
        $items['admin.php'] = 'Admin';
    }
    $items['logout.php'] = 'Log out';
} else {
    $items['login.php'] = 'Log in';
    $items['register.php'] = 'Sign up';
}
?>
<nav>
        <ul>
<?php foreach ($items as $file => $label): ?>
        <li><a href="<?= h($file) ?>"<?= $file == $cur ? ' class="active"' : '' ?>><?= h($label) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
