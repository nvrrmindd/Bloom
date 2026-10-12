<?php
require 'data.php';

$_SESSION = [];
session_destroy();

header('Location: login.php');
