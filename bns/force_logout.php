<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$log_id = (int) $_GET['id'];

// ✅ Mark session as logged out
$stmt = $pdo->prepare("UPDATE login_history SET logout_time = NOW() WHERE id = ? AND user_id = ?");
$stmt->execute([$log_id, $user_id]);

// (Optional) Invalidate session ID if you store them somewhere
// $stmt = $pdo->prepare("DELETE FROM active_sessions WHERE session_id = ? AND user_id = ?");
// $stmt->execute([$session_id, $user_id]);

header("Location: security.php?logout=success");
exit();
