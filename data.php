<?php
// Общие данные и функции для всех страниц.
// Данные хранятся в JSON-файлах в папке data/ (создаются при первом запуске).

session_start();

$CATEGORIES = ['hair' => 'Hair', 'nails' => 'Nails', 'face' => 'Face'];
$MASTERS    = ['Anna Lee', 'Dana Kim', 'Yerlan Seitov', 'Madina Akhmetova', 'Aliya Nurlan'];
$SLOTS      = ['10:00', '12:00', '14:00', '16:00', '18:00'];
$STATUSES   = ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'];

// защита от HTML в тексте, который ввёл пользователь
function h($text)
{
    return htmlspecialchars($text);
}

// прочитать файл; если его нет — создать с данными по умолчанию
function read_json($file, $default)
{
    if (!file_exists($file)) {
        write_json($file, $default);
    }
    return json_decode(file_get_contents($file), true);
}

function write_json($file, $data)
{
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data');
    }
    file_put_contents($file, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

/* ---------- услуги ---------- */

function load_services()
{
    return read_json(__DIR__ . '/data/services.json', [
        ['cat' => 'hair',  'name' => "Women's haircut",  'master' => 'Anna Lee',         'min' => 60,  'rating' => 5, 'price' => 8000,  'img' => 'images/styling.jpg'],
        ['cat' => 'hair',  'name' => 'Coloring',         'master' => 'Anna Lee',         'min' => 180, 'rating' => 5, 'price' => 25000, 'img' => 'images/hair.png'],
        ['cat' => 'hair',  'name' => 'Styling & curls',  'master' => 'Dana Kim',         'min' => 45,  'rating' => 4, 'price' => 6000,  'img' => 'images/blowdry.jpg'],
        ['cat' => 'hair',  'name' => "Men's haircut",    'master' => 'Yerlan Seitov',    'min' => 40,  'rating' => 5, 'price' => 5000,  'img' => 'images/barber2.jpg'],
        ['cat' => 'nails', 'name' => 'Manicure',         'master' => 'Madina Akhmetova', 'min' => 60,  'rating' => 5, 'price' => 7000,  'img' => 'images/manicure.jpg'],
        ['cat' => 'nails', 'name' => 'Pedicure',         'master' => 'Madina Akhmetova', 'min' => 75,  'rating' => 4, 'price' => 9000,  'img' => 'images/hands.jpg'],
        ['cat' => 'face',  'name' => 'Facial cleansing', 'master' => 'Aliya Nurlan',     'min' => 60,  'rating' => 5, 'price' => 12000, 'img' => 'images/facial.jpg'],
        ['cat' => 'face',  'name' => 'Makeup',           'master' => 'Aliya Nurlan',     'min' => 50,  'rating' => 4, 'price' => 10000, 'img' => 'images/makeup.jpg'],
    ]);
}

function save_services($services)
{
    write_json(__DIR__ . '/data/services.json', $services);
}

/* ---------- записи ---------- */

function load_bookings()
{
    return read_json(__DIR__ . '/data/bookings.json', [
        ['id' => 10211, 'client' => 'Aigerim S.', 'service' => 'Facial cleansing', 'master' => 'Aliya Nurlan',     'date' => '2026-09-18', 'time' => '16:00', 'phone' => '', 'note' => '', 'status' => 'cancelled'],
        ['id' => 10398, 'client' => 'Aigerim S.', 'service' => 'Manicure',         'master' => 'Madina Akhmetova', 'date' => '2026-10-02', 'time' => '12:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
        ['id' => 10451, 'client' => 'Timur A.',   'service' => "Men's haircut",    'master' => 'Yerlan Seitov',    'date' => '2026-10-15', 'time' => '10:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
        ['id' => 10452, 'client' => 'Aigerim S.', 'service' => "Women's haircut",  'master' => 'Anna Lee',         'date' => '2026-10-15', 'time' => '14:00', 'phone' => '', 'note' => '', 'status' => 'pending'],
    ]);
}

function save_bookings($bookings)
{
    write_json(__DIR__ . '/data/bookings.json', $bookings);
}

/* ---------- пользователи ---------- */

// демо-аккаунты: клиенты — bloom123, админ — admin123 (пароли хранятся в виде хеша)
function load_users()
{
    $client = '$2y$12$JBV1GMrEtPbyuhJpkuX8cOPQ1eDMc9ATbgUcvyPi67Ryb.DmQT39e';
    $admin  = '$2y$12$uTJiqo1N0M1ghYjMf4igT.NY6aQyT7ZwKi0r8JNa.vkIqiqTS6I9u';
    return read_json(__DIR__ . '/data/users.json', [
        ['id' => 1, 'first' => 'Aigerim', 'last' => 'Sadykova', 'email' => 'aigerim@email.com', 'phone' => '+7 777 555 11 22', 'dob' => '2002-04-12', 'password' => $client, 'role' => 'client'],
        ['id' => 2, 'first' => 'Timur',   'last' => 'Akhmetov', 'email' => 'timur@email.com',   'phone' => '+7 777 555 33 44', 'dob' => '',           'password' => $client, 'role' => 'client'],
        ['id' => 3, 'first' => 'Admin',   'last' => '',         'email' => 'admin@bloom.kz',    'phone' => '',                 'dob' => '',           'password' => $admin,  'role' => 'admin'],
    ]);
}

function save_users($users)
{
    write_json(__DIR__ . '/data/users.json', $users);
}

/* ---------- вход ---------- */

// пользователь, который сейчас вошёл, или null
function current_user()
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    foreach (load_users() as $user) {
        if ($user['id'] == $_SESSION['user_id']) {
            return $user;
        }
    }
    return null;
}

function full_name($user)
{
    return trim($user['first'] . ' ' . $user['last']);
}

// "Aigerim Sadykova" -> "Aigerim S." — так клиент подписан в записях
function client_name($user)
{
    if ($user['last'] == '') {
        return $user['first'];
    }
    return $user['first'] . ' ' . mb_substr($user['last'], 0, 1) . '.';
}

// страница только для вошедших
function require_login()
{
    $user = current_user();
    if ($user == null) {
        header('Location: login.php');
        exit;
    }
    return $user;
}

// страница только для админа
function require_admin()
{
    $user = require_login();
    if ($user['role'] != 'admin') {
        echo 'Access denied';
        exit;
    }
    return $user;
}
