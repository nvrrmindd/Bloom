<?php
// Минимальное хранилище и вход: data/bookings.json, data/users.json (создаются при первом запуске)

session_start();

const BOOKINGS_FILE = __DIR__ . '/data/bookings.json';
const USERS_FILE    = __DIR__ . '/data/users.json';
const SERVICES      = ['Женская стрижка', 'Окрашивание', 'Маникюр', 'Чистка лица'];
const MASTERS       = ['Анна Ли', 'Дана Ким', 'Мадина Ахметова', 'Алия Нурлан'];
const SLOTS         = ['10:00', '12:00', '14:00', '16:00', '18:00'];
const STATUSES      = ['pending' => 'Ожидает', 'confirmed' => 'Подтверждена', 'cancelled' => 'Отменена'];

function h($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

/* ---------- файлы ---------- */

function read_json(string $file, callable $seed): array
{
    if (!is_file($file)) {
        write_json($file, $seed());
    }
    return json_decode((string) file_get_contents($file), true) ?: [];
}

function write_json(string $file, array $rows): void
{
    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0775, true);
    }
    file_put_contents($file, json_encode(array_values($rows), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}

function load_bookings(): array
{
    return read_json(BOOKINGS_FILE, fn() => [
        ['id' => 10211, 'client' => 'Айгерим С.', 'service' => 'Чистка лица',     'master' => 'Алия Нурлан',     'date' => '2026-09-18', 'time' => '16:00', 'phone' => '', 'note' => '', 'status' => 'cancelled'],
        ['id' => 10398, 'client' => 'Айгерим С.', 'service' => 'Маникюр',         'master' => 'Мадина Ахметова', 'date' => '2026-10-02', 'time' => '12:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
        ['id' => 10451, 'client' => 'Тимур А.',   'service' => 'Мужская стрижка', 'master' => 'Ерлан Сеитов',    'date' => '2026-10-15', 'time' => '10:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
        ['id' => 10452, 'client' => 'Айгерим С.', 'service' => 'Женская стрижка', 'master' => 'Анна Ли',         'date' => '2026-10-15', 'time' => '14:00', 'phone' => '', 'note' => '', 'status' => 'pending'],
    ]);
}

function save_bookings(array $rows): void
{
    write_json(BOOKINGS_FILE, $rows);
}

function load_users(): array
{
    // демо-аккаунты, пароль у всех: bloom123
    return read_json(USERS_FILE, function (): array {
        $hash = password_hash('bloom123', PASSWORD_DEFAULT);
        return [
            ['id' => 1, 'first' => 'Айгерим', 'last' => 'Садыкова', 'email' => 'aigerim@email.com', 'phone' => '+7 777 555 11 22', 'dob' => '2002-04-12', 'password' => $hash, 'role' => 'client'],
            ['id' => 2, 'first' => 'Тимур',   'last' => 'Ахметов',  'email' => 'timur@email.com',   'phone' => '+7 777 555 33 44', 'dob' => '',           'password' => $hash, 'role' => 'client'],
            ['id' => 3, 'first' => 'Admin',   'last' => '',         'email' => 'admin@bloom.kz',    'phone' => '',                 'dob' => '',           'password' => $hash, 'role' => 'admin'],
        ];
    });
}

/* ---------- вход ---------- */

function current_user(): ?array
{
    if (!isset($_SESSION['uid'])) {
        return null;
    }
    foreach (load_users() as $u) {
        if ($u['id'] === $_SESSION['uid']) {
            return $u;
        }
    }
    return null;
}

function full_name(array $u): string
{
    return trim($u['first'] . ' ' . $u['last']);
}

/** «Айгерим Садыкова» → «Айгерим С.» — так клиент подписан в записях. */
function client_name(array $u): string
{
    return $u['last'] === '' ? $u['first'] : $u['first'] . ' ' . mb_substr($u['last'], 0, 1) . '.';
}

function require_login(): array
{
    $u = current_user();
    if ($u === null) {
        $self = basename($_SERVER['SCRIPT_NAME']) . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
        header('Location: login.php?next=' . urlencode($self));
        exit;
    }
    return $u;
}

function require_admin(): array
{
    $u = require_login();
    if ($u['role'] !== 'admin') {
        http_response_code(403);
        exit('Доступ запрещён');
    }
    return $u;
}

/* ---------- CSRF для POST-форм ---------- */

function csrf_field(): string
{
    $_SESSION['csrf'] ??= bin2hex(random_bytes(16));
    return '<input type="hidden" name="csrf" value="' . h($_SESSION['csrf']) . '">';
}

function csrf_ok(): bool
{
    return isset($_SESSION['csrf'], $_POST['csrf']) && hash_equals($_SESSION['csrf'], (string) $_POST['csrf']);
}
