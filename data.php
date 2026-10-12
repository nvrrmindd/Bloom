<?php
// Минимальное хранилище записей: data/bookings.json (создаётся при первом запуске)

const DATA_FILE   = __DIR__ . '/data/bookings.json';
const DEMO_CLIENT = 'Айгерим С.';   // демо-клиент: профиль без авторизации
const SERVICES    = ['Женская стрижка', 'Окрашивание', 'Маникюр', 'Чистка лица'];
const MASTERS     = ['Анна Ли', 'Дана Ким', 'Мадина Ахметова', 'Алия Нурлан'];
const SLOTS       = ['10:00', '12:00', '14:00', '16:00', '18:00'];
const STATUSES    = ['pending' => 'Ожидает', 'confirmed' => 'Подтверждена', 'cancelled' => 'Отменена'];

function h($s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
}

function load_bookings(): array
{
    if (!is_file(DATA_FILE)) {
        save_bookings([
            ['id' => 10211, 'client' => 'Айгерим С.', 'service' => 'Чистка лица',     'master' => 'Алия Нурлан',     'date' => '2026-09-18', 'time' => '16:00', 'phone' => '', 'note' => '', 'status' => 'cancelled'],
            ['id' => 10398, 'client' => 'Айгерим С.', 'service' => 'Маникюр',         'master' => 'Мадина Ахметова', 'date' => '2026-10-02', 'time' => '12:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
            ['id' => 10451, 'client' => 'Тимур А.',   'service' => 'Мужская стрижка', 'master' => 'Ерлан Сеитов',    'date' => '2026-10-15', 'time' => '10:00', 'phone' => '', 'note' => '', 'status' => 'confirmed'],
            ['id' => 10452, 'client' => 'Айгерим С.', 'service' => 'Женская стрижка', 'master' => 'Анна Ли',         'date' => '2026-10-15', 'time' => '14:00', 'phone' => '', 'note' => '', 'status' => 'pending'],
        ]);
    }
    return json_decode((string) file_get_contents(DATA_FILE), true) ?: [];
}

function save_bookings(array $rows): void
{
    if (!is_dir(dirname(DATA_FILE))) {
        mkdir(dirname(DATA_FILE), 0775, true);
    }
    file_put_contents(DATA_FILE, json_encode(array_values($rows), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT), LOCK_EX);
}
