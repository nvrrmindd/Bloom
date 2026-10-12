<?php
require __DIR__ . '/data.php';

$_SESSION = [];
session_destroy();

header('Location: login.php');
