<?php
require __DIR__ . '/data.php';

$services = load_services();
$tags = CATEGORIES;

// поиск по названию услуги или имени мастера
$q = trim((string) ($_GET['q'] ?? ''));
if ($q !== '') {
    $services = array_filter($services, fn(array $s): bool =>
        mb_stripos($s[1], $q) !== false || mb_stripos($s[2], $q) !== false);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Услуги — Bloom Salon</title>
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

  <section class="page-banner">
    <h1 class="fade-up">Наши услуги</h1>
  </section>

  <main class="section">
    <div class="container">
      <form class="search-bar" action="services.php">
        <input type="search" name="q" value="<?= h($q) ?>" placeholder="Поиск услуги или мастера...">
        <button class="btn">Найти</button>
      </form>

      <!-- фильтр по категориям на чистом CSS (radio + :checked) -->
      <input type="radio" name="cat" id="f-all" class="filter-radio" checked>
      <input type="radio" name="cat" id="f-hair" class="filter-radio">
      <input type="radio" name="cat" id="f-nails" class="filter-radio">
      <input type="radio" name="cat" id="f-face" class="filter-radio">
      <div class="filters">
        <label for="f-all">Все</label>
        <label for="f-hair">Волосы</label>
        <label for="f-nails">Ногти</label>
        <label for="f-face">Лицо</label>
      </div>

      <?php if (!$services): ?>
      <p class="section-title">Ничего не найдено по запросу «<?= h($q) ?>»</p>
      <?php endif; ?>

      <div class="grid">
<?php foreach ($services as [$cat, $name, $master, $min, $rating, $price, $img]): ?>
        <article class="card <?= h($cat) ?>">
          <div class="card-img" style="background-image:url(<?= h($img) ?>)"></div>
          <div class="card-body">
            <span class="tag"><?= h($tags[$cat]) ?></span>
            <h3><?= h($name) ?></h3>
            <p class="meta"><?= h($master) ?> · <?= $min ?> мин</p>
            <p class="stars"><?= str_repeat('★', $rating) . str_repeat('☆', 5 - $rating) ?></p>
            <p class="price">от <?= number_format($price, 0, '', ' ') ?> ₸</p>
            <a href="details.php" class="btn btn-small">Подробнее</a>
          </div>
        </article>
<?php endforeach; ?>
      </div>
    </div>
  </main>

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
