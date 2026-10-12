<?php
require 'data.php';

$services = load_services();

// поиск по названию услуги или имени мастера
$q = '';
if (isset($_GET['q'])) {
    $q = trim($_GET['q']);
}
if ($q != '') {
    $found = [];
    foreach ($services as $s) {
        if (stripos($s['name'], $q) !== false || stripos($s['master'], $q) !== false) {
            $found[] = $s;
        }
    }
    $services = $found;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services — Bloom Salon</title>
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

  <section class="page-banner">
    <h1>Our services</h1>
  </section>

  <main class="section">
    <div class="container">
      <form class="search-bar" action="services.php">
        <input type="search" name="q" value="<?= h($q) ?>" placeholder="Search a service or stylist...">
        <button class="btn">Search</button>
      </form>

      <!-- фильтр по категориям на чистом CSS (radio + :checked) -->
      <input type="radio" name="cat" id="f-all" class="filter-radio" checked>
      <input type="radio" name="cat" id="f-hair" class="filter-radio">
      <input type="radio" name="cat" id="f-nails" class="filter-radio">
      <input type="radio" name="cat" id="f-face" class="filter-radio">
      <div class="filters">
        <label for="f-all">All</label>
        <label for="f-hair">Hair</label>
        <label for="f-nails">Nails</label>
        <label for="f-face">Face</label>
      </div>

      <?php if (!$services): ?>
      <p class="section-title">Nothing found for «<?= h($q) ?>»</p>
      <?php endif; ?>

      <div class="grid">
<?php foreach ($services as $s): ?>
        <article class="card <?= $s['cat'] ?>">
          <div class="card-img" style="background-image:url(<?= $s['img'] ?>)"></div>
          <div class="card-body">
            <span class="tag"><?= $CATEGORIES[$s['cat']] ?></span>
            <h3><?= h($s['name']) ?></h3>
            <p class="meta"><?= h($s['master']) ?> · <?= $s['min'] ?> min</p>
            <p class="stars"><?= str_repeat('★', $s['rating']) . str_repeat('☆', 5 - $s['rating']) ?></p>
            <p class="price">from <?= $s['price'] ?> ₸</p>
            <a href="details.php" class="btn btn-small">Details</a>
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
        <li><a href="about.php">About</a></li>
        <li><a href="about.php#contact">Contacts</a></li>
        <li><a href="#">Privacy</a></li>
        <li><a href="#">Terms</a></li>
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
