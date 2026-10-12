<?php
require 'data.php';

require_admin();

$users    = load_users();
$bookings = load_bookings();
$services = load_services();
$error    = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // добавить или сохранить услугу
    if ($action == 'add' || $action == 'update') {
        $name   = trim($_POST['name']);
        $master = trim($_POST['master']);
        $cat    = $_POST['cat'];
        $price  = $_POST['price'];

        // нельзя добавить вторую услугу с таким же названием
        $duplicate = false;
        foreach ($services as $i => $s) {
            if (strtolower($s['name']) == strtolower($name) && ($action == 'add' || $i != $_POST['i'])) {
                $duplicate = true;
            }
        }

        if ($name == '' || $master == '' || $cat == '' || $price <= 0) {
            $error = 'Please fill in all fields.';
        } elseif ($duplicate) {
            $error = 'A service with this name already exists.';
        } elseif ($action == 'add') {
            $services[] = ['cat' => $cat, 'name' => $name, 'master' => $master, 'min' => 60, 'rating' => 5, 'price' => $price, 'img' => 'images/salon.png'];
        } else {
            $i = $_POST['i'];
            $services[$i]['cat']    = $cat;
            $services[$i]['name']   = $name;
            $services[$i]['master'] = $master;
            $services[$i]['price']  = $price;
        }
    }

    // удалить услугу
    if ($action == 'delete') {
        unset($services[$_POST['i']]);
    }

    // сменить статус записи
    if ($action == 'status') {
        foreach ($bookings as $i => $b) {
            if ($b['id'] == $_POST['id']) {
                $bookings[$i]['status'] = $_POST['status'];
            }
        }
        save_bookings($bookings);
        header('Location: admin.php#bookings');
        exit;
    }

    if ($error == '') {
        save_services($services);
        header('Location: admin.php#services');
        exit;
    }
}

// какую услугу редактируем (admin.php?edit=номер) — её данные подставляются в форму
$edit = null;
if (isset($_GET['edit']) && isset($services[$_GET['edit']])) {
    $edit = $services[$_GET['edit']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin panel — Bloom Salon</title>
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

  <div class="admin">
    <aside class="sidebar">
      <a href="#dashboard">Dashboard</a>
      <a href="#users">Users</a>
      <a href="#services">Services</a>
      <a href="#bookings">Bookings</a>
      <a href="logout.php">Log out</a>
    </aside>

    <main class="admin-main">
      <section id="dashboard">
        <h2>Dashboard</h2>
        <div class="stats">
          <div class="stat"><b><?= count($users) ?></b>Users</div>
          <div class="stat"><b><?= count($bookings) ?></b>Bookings</div>
          <div class="stat"><b><?= count($services) ?></b>Services</div>
          <div class="stat"><b><?= count($MASTERS) ?></b>Stylists</div>
        </div>
      </section>

      <section id="users">
        <h2>Users</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr></thead>
            <tbody>
<?php foreach ($users as $u): ?>
              <tr><td><?= $u['id'] ?></td><td><?= h(full_name($u)) ?></td><td><?= h($u['email']) ?></td><td><?= $u['role'] ?></td></tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="services">
        <h2>Services</h2>
<?php if ($error != ''): ?>
        <p class="error" style="display:block;margin-bottom:14px"><?= h($error) ?></p>
<?php endif; ?>

        <!-- одна форма и для добавления, и для редактирования -->
        <form class="inline-form" action="admin.php" method="post">
<?php if ($edit): ?>
          <input type="hidden" name="i" value="<?= h($_GET['edit']) ?>">
<?php endif; ?>
          <input name="name" placeholder="Name" value="<?= $edit ? h($edit['name']) : '' ?>" required>
          <select name="cat" required>
            <option value="">Category</option>
<?php foreach ($CATEGORIES as $key => $label): ?>
            <option value="<?= $key ?>" <?= $edit && $edit['cat'] == $key ? 'selected' : '' ?>><?= $label ?></option>
<?php endforeach; ?>
          </select>
          <input type="number" name="price" placeholder="Price, ₸" min="1000" value="<?= $edit ? $edit['price'] : '' ?>" required>
          <input name="master" placeholder="Stylist" value="<?= $edit ? h($edit['master']) : '' ?>" required>
<?php if ($edit): ?>
          <button class="btn btn-small" name="action" value="update">Save</button>
<?php else: ?>
          <button class="btn btn-small" name="action" value="add">Add</button>
<?php endif; ?>
        </form>

        <div class="table-wrap">
          <table>
            <thead><tr><th>Service</th><th>Category</th><th>Price</th><th>Stylist</th><th></th></tr></thead>
            <tbody>
<?php foreach ($services as $i => $s): ?>
              <tr>
                <td><?= h($s['name']) ?></td>
                <td><?= $CATEGORIES[$s['cat']] ?></td>
                <td><?= $s['price'] ?> ₸</td>
                <td><?= h($s['master']) ?></td>
                <td>
                  <form action="admin.php" method="post" style="display:flex;gap:6px">
                    <input type="hidden" name="i" value="<?= $i ?>">
                    <a href="admin.php?edit=<?= $i ?>#services" class="btn btn-small">Edit</a>
                    <button class="btn btn-small btn-danger" name="action" value="delete">Delete</button>
                  </form>
                </td>
              </tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="bookings">
        <h2>Bookings</h2>
        <div class="table-wrap">
          <table>
            <thead><tr><th>№</th><th>Client</th><th>Service</th><th>Date</th><th>Status</th></tr></thead>
            <tbody>
<?php foreach (array_reverse($bookings) as $b): ?>
              <tr>
                <td>#<?= $b['id'] ?></td>
                <td><?= h($b['client']) ?></td>
                <td><?= h($b['service']) ?></td>
                <td><?= date('d.m.Y', strtotime($b['date'])) . ' ' . $b['time'] ?></td>
                <td>
                  <form action="admin.php" method="post" style="display:flex;gap:8px">
                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                    <select name="status">
<?php foreach ($STATUSES as $key => $label): ?>
                      <option value="<?= $key ?>" <?= $b['status'] == $key ? 'selected' : '' ?>><?= $label ?></option>
<?php endforeach; ?>
                    </select>
                    <button class="btn btn-small" name="action" value="status">OK</button>
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
