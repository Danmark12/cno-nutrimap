<?php
require '../db/config.php';
session_start();

$userId   = $_SESSION['user_id'] ?? null;
$userType = $_SESSION['user_type'] ?? '';

if ($userId && $userType === 'CNO') {
    $pdo->query("UPDATE reports SET prev_status = status WHERE prev_status IS NULL");
}

echo "ok";
