<?php
session_start();
require '../db/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request");
}

$user_id = $_SESSION['user_id'];
$login_id = (int) $_GET['id'];

// ✅ Get the session ID of that login entry
$stmt = $pdo->prepare("SELECT session_id FROM login_history WHERE id = ? AND user_id = ?");
$stmt->execute([$login_id, $user_id]);
$login = $stmt->fetch(PDO::FETCH_ASSOC);

if ($login) {
    $sessionId = $login['session_id'];

    // ✅ Mark as logged out
    $update = $pdo->prepare("UPDATE login_history SET logout_time = NOW() WHERE id = ?");
    $update->execute([$login_id]);

    // ✅ Optionally, destroy session file (if using PHP file sessions)
    if (file_exists(session_save_path() . "/sess_$sessionId")) {
        @unlink(session_save_path() . "/sess_$sessionId");
    }
}

header("Location: security.php");
exit();
