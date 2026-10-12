<?php
// Меню: набор пунктов зависит от того, вошёл ли пользователь
$me  = current_user();
$cur = basename($_SERVER['SCRIPT_NAME']);
$cur = ['details.php' => 'services.php', 'confirm.php' => 'booking.php'][$cur] ?? $cur;

$items = [
    'index.php'    => 'Главная',
    'services.php' => 'Услуги',
    'masters.php'  => 'Мастера',
    'booking.php'  => 'Запись',
    'about.php'    => 'О нас',
];
if ($me) {
    $items['profile.php'] = 'Профиль';
    if ($me['role'] === 'admin') {
        $items['admin.php'] = 'Админ';
    }
    $items['logout.php'] = 'Выйти';
} else {
    $items['login.php'] = 'Войти';
    $items['register.php'] = 'Регистрация';
}
?>
<nav>
        <ul>
<?php foreach ($items as $file => $label): ?>
        <li><a href="<?= h($file) ?>"<?= $file === $cur ? ' class="active"' : '' ?>><?= h($label) ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
