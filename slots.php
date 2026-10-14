<?php
// занятое время мастера на дату: slots.php?master=...&date=YYYY-MM-DD
require 'data.php';

header('Content-Type: application/json');
$master = isset($_GET['master']) ? $_GET['master'] : '';
$date   = isset($_GET['date']) ? $_GET['date'] : '';
echo json_encode(busy_slots(load_bookings(), $master, $date));
