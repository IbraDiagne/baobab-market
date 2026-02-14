<?php

require_once(__DIR__ . "/../config/database.php");

$ip = $_SERVER['REMOTE_ADDR'];
$today = date("Y-m-d");

$stmt = $pdo->prepare("
    INSERT IGNORE INTO visitors (ip_address, visit_date)
    VALUES (?, ?)
");

$stmt->execute([$ip, $today]);
